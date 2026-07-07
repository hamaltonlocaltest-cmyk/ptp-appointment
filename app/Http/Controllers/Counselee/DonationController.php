<?php

namespace App\Http\Controllers\Counselee;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\InstamojoService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    public function __construct(
        private InstamojoService $instamojo,
        private NotificationService $notifications
    ) {
    }

    public function create()
    {
        $counselee = Auth::guard('counselee')->user();
        $layout    = $this->resolveLayout();

        return view('counselee.donations.create', compact('counselee', 'layout'));
    }

    public function store(Request $request)
    {
        $counselee = Auth::guard('counselee')->user();

        $rules = [
            'amount' => 'required|numeric|min:10|max:200000',
        ];

        if (!$counselee) {
            $rules['donor_name']  = 'required|string|max:150';
            $rules['donor_email'] = 'required|email|max:150';
        }

        $validated = $request->validate($rules);

        $donation = Donation::create([
            'counselee_id' => $counselee?->id,
            'donor_name'   => $counselee?->full_name ?? $validated['donor_name'],
            'donor_email'  => $counselee?->email ?? $validated['donor_email'],
            'amount'       => $validated['amount'],
            'currency'     => 'INR',
            'status'       => 'pending',
        ]);

        try {
            $paymentRequest = $this->instamojo->createPaymentRequest([
                'purpose'                 => 'Donation to P2P Counselling',
                'amount'                  => number_format((float) $validated['amount'], 2, '.', ''),
                'buyer_name'              => $donation->donor_name ?? 'Anonymous Donor',
                'email'                   => $donation->donor_email ?? 'donor@example.com',
                'redirect_url'            => route('counselee.donations.callback'),
                'send_email'              => false,
                'send_sms'                => false,
                'allow_repeated_payments' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('Instamojo payment request failed: ' . $e->getMessage());
            $donation->update(['status' => 'failed']);

            return back()->withErrors([
                'error' => 'We could not connect to the payment gateway right now. Please try again shortly.',
            ])->withInput();
        }

        $donation->update([
            'instamojo_payment_request_id' => $paymentRequest['id'],
        ]);

        return redirect()->away($paymentRequest['longurl']);
    }

    // Instamojo redirects the donor's browser back here after payment.
    public function callback(Request $request)
    {
        $paymentRequestId = $request->query('payment_request_id');
        $paymentId        = $request->query('payment_id');

        $donation = Donation::where('instamojo_payment_request_id', $paymentRequestId)->firstOrFail();

        // Skip re-verification if a webhook already settled this donation
        if ($donation->status === 'pending') {
            $this->verifyAndUpdate($donation, $paymentRequestId, $paymentId);
        }

        $layout = $this->resolveLayout();

        return view('counselee.donations.status', compact('donation', 'layout'));
    }

    // Server-to-server webhook — Instamojo POSTs payment updates here directly,
    // independent of whether the donor's browser makes it back to callback().
    public function webhook(Request $request)
    {
        $payload = $request->all();

        if (!$this->instamojo->verifyWebhookSignature($payload, $payload['mac'] ?? '')) {
            Log::warning('Instamojo webhook signature mismatch.', ['payload' => $payload]);
            return response('invalid signature', 400);
        }

        $donation = Donation::where('instamojo_payment_request_id', $payload['payment_request_id'] ?? null)->first();

        if ($donation && $donation->status === 'pending') {
            $this->verifyAndUpdate($donation, $payload['payment_request_id'] ?? null, $payload['payment_id'] ?? null);
        }

        return response('OK', 200);
    }

    // Re-fetches the payment request from Instamojo directly (never trusts
    // the redirect query string or webhook payload's status field alone).
    private function verifyAndUpdate(Donation $donation, ?string $paymentRequestId, ?string $paymentId): void
    {
        if (!$paymentRequestId) {
            return;
        }

        try {
            $paymentRequest = $this->instamojo->getPaymentRequest($paymentRequestId);
            $latest         = collect($paymentRequest['payments'] ?? [])->last();
            $verifiedStatus = $latest['status'] ?? null; // 'Credit' | 'Failed'

            if ($verifiedStatus === 'Credit') {
                $donation->update([
                    'status'            => 'completed',
                    'payment_reference' => $latest['payment_id'] ?? $paymentId,
                    'gateway_response'  => json_encode($paymentRequest),
                ]);

                $this->notifications->notifyDonationReceived($donation);
            } else {
                $donation->update([
                    'status'           => 'failed',
                    'gateway_response' => json_encode($paymentRequest),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Instamojo verification failed: ' . $e->getMessage());
        }
    }

    // Donations are reachable by any logged-in role (or a guest) — pick whichever
    // dashboard chrome (header/footer/side nav) matches who's currently signed in.
    private function resolveLayout(): string
    {
        if (Auth::guard('counselee')->check()) {
            return 'counselee.layouts.app';
        }

        if (Auth::guard('counselor')->check()) {
            return 'counselor.layouts.app';
        }

        if (Auth::guard('web')->check()) {
            return 'admin.layouts.app';
        }

        return 'layouts.guest';
    }
}
