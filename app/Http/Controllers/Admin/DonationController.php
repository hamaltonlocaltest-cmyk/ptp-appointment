<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function index(Request $request)
    {
        $query = Donation::with('counselee')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $donations = $query->get();

        $counts = [
            'total'          => Donation::count(),
            'completed'      => Donation::where('status', 'completed')->count(),
            'pending'        => Donation::where('status', 'pending')->count(),
            'failed'         => Donation::where('status', 'failed')->count(),
            'total_received' => Donation::completed()->sum('amount'),
        ];

        return view('admin.donations.index', compact('donations', 'counts'));
    }

    public function show(Donation $donation)
    {
        $donation->load('counselee');

        return view('admin.donations.show', compact('donation'));
    }

    // Manual/offline confirmation fallback (e.g. bank transfer, or Instamojo
    // callback/webhook missed) — lets an admin mark a pending donation paid.
    public function markCompleted(Donation $donation)
    {
        if ($donation->status === 'completed') {
            return back()->withErrors(['error' => 'This donation is already marked completed.']);
        }

        $donation->update([
            'status'             => 'completed',
            'payment_reference'  => $donation->payment_reference ?: 'MANUAL-' . now()->format('YmdHis'),
        ]);

        $this->notifications->notifyDonationReceived($donation);

        return back()->with('success', 'Donation marked as completed.');
    }
}
