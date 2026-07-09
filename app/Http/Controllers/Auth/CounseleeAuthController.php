<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CounseleeRegistered;
use App\Models\Counselee;
use App\Models\CounseleeChild;
use App\Models\CounseleeMedicalHistory;
use App\Models\CounselType;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CounseleeAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('counselee')->check()) {
            return redirect()->route('counselee.dashboard');
        }
        return view('auth.counselee.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('counselee')->attempt($request->only('email', 'password'))) {
            if (Auth::guard('counselee')->user()->status === 'deleted') {
                Auth::guard('counselee')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'This account has been deleted. Please contact the administrator.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            return redirect()->route('counselee.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::guard('counselee')->check()) {
            return redirect()->route('counselee.dashboard');
        }

        $counselTypes = CounselType::active()->ordered()->get();

        [$countries, $states, $cities, $defaultCountry, $defaultState, $defaultCity] = $this->defaultLocationData();

        return view('auth.counselee.register', compact(
            'counselTypes', 'countries', 'states', 'cities',
            'defaultCountry', 'defaultState', 'defaultCity'
        ));
    }

    // Loads dropdown data + India/Telangana/Hyderabad defaults, shared by the register view.
    private function defaultLocationData(): array
    {
        $countries = Country::active()->orderBy('name')->get(['id', 'name']);

        $defaultCountry = Country::where('code', 'IN')->first();
        $defaultState   = $defaultCountry
            ? State::where('country_id', $defaultCountry->id)->where('name', 'Telangana')->first()
            : null;
        $defaultCity    = $defaultState
            ? City::where('state_id', $defaultState->id)->where('name', 'Hyderabad')->first()
            : null;

        $states = $defaultCountry
            ? State::where('country_id', $defaultCountry->id)->active()->orderBy('name')->get(['id', 'name'])
            : collect();
        $cities = $defaultState
            ? City::where('state_id', $defaultState->id)->active()->orderBy('name')->get(['id', 'name'])
            : collect();

        return [$countries, $states, $cities, $defaultCountry, $defaultState, $defaultCity];
    }

    public function register(Request $request)
    {
        // ------------------------------------------------------------------
        // Validation
        // ------------------------------------------------------------------
        $request->validate([
            // Step 1 - Personal Information
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:150|unique:counselees,email',
            'password'       => 'required|string|min:8|confirmed',
            'title'          => 'nullable|in:Mr,Miss,Mrs,Rev,Dr',
            'address'        => 'nullable|string|max:500',
            'telephone1'     => 'nullable|string|max:20',
            'telephone2'     => 'nullable|string|max:20',
            'age'            => 'nullable|integer|min:1|max:120',
            'birthdate'      => 'nullable|date|before:today',
            'gender'         => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'country_id'     => 'required|integer|exists:countries,id',
            'state_id'       => 'required|integer|exists:states,id',
            'city_id'        => 'required|integer|exists:cities,id',

            // Step 2 - Children
            'children'           => 'nullable|array',
            'children.*.name'    => 'nullable|string|max:100',
            'children.*.gender'  => 'nullable|in:Male,Female',
            'children.*.age'     => 'nullable|integer|min:0|max:30',

            // Step 2 - Medical History
            'medical_history'              => 'nullable|array',
            'medical_history.*.condition'  => 'nullable|string|max:200',
            'medical_history.*.details'    => 'nullable|string|max:500',

            // Step 2 - Counselling Areas (now references CounselType IDs)
            'counsel_types'   => 'nullable|array',
            'counsel_types.*' => 'integer|exists:counsel_types,id',

            // Step 3 - Referral / Previous Counselling
            'referral'                     => 'nullable|in:Self,Friend,Relative,Pastor,PtP Website',
            'previous_counselling'         => 'nullable|in:Yes,No',
            'previous_counselling_details' => 'nullable|string|max:1000',
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'email.required'      => 'Email address is required.',
            'email.email'         => 'Please enter a valid email address.',
            'email.unique'        => 'This email address is already registered.',
            'password.required'   => 'Password is required.',
            'password.min'        => 'Password must be at least 8 characters.',
            'password.confirmed'  => 'Passwords do not match.',
        ]);

        // ------------------------------------------------------------------
        // Save everything in a transaction
        // ------------------------------------------------------------------
        DB::beginTransaction();

        try {
            $plainPassword = $request->password;

            // 1. Create counselee account
            $counselee = Counselee::create([
                'title'                        => $request->title,
                'first_name'                   => $request->first_name,
                'last_name'                    => $request->last_name,
                'address'                      => $request->address,
                'telephone1'                   => $request->telephone1,
                'telephone2'                   => $request->telephone2,
                'email'                        => $request->email,
                'age'                          => $request->age,
                'birthdate'                    => $request->birthdate,
                'gender'                       => $request->gender,
                'marital_status'               => $request->marital_status,
                'country_id'                   => $request->country_id,
                'state_id'                     => $request->state_id,
                'city_id'                      => $request->city_id,
                'referral'                     => $request->referral,
                'previous_counselling'         => $request->previous_counselling,
                'previous_counselling_details' => $request->previous_counselling_details,
                'password'                     => Hash::make($plainPassword),
                'status'                       => 'active',
            ]);

            // 2. Save children (skip blank rows)
            if ($request->filled('children')) {
                foreach ($request->children as $child) {
                    if (!empty($child['name'])) {
                        CounseleeChild::create([
                            'counselee_id' => $counselee->id,
                            'name'         => $child['name'],
                            'gender'       => $child['gender'] ?? null,
                            'age'          => $child['age']    ?? null,
                        ]);
                    }
                }
            }

            // 3. Save medical history (skip blank rows)
            if ($request->filled('medical_history')) {
                foreach ($request->medical_history as $history) {
                    if (!empty($history['condition'])) {
                        CounseleeMedicalHistory::create([
                            'counselee_id' => $counselee->id,
                            'condition'    => $history['condition'],
                            'details'      => $history['details'] ?? null,
                        ]);
                    }
                }
            }

            // 4. Sync counselling areas via pivot table
            $counselee->counselTypes()->sync($request->counsel_types ?? []);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Counselee registration failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again later.']);
        }

        // ------------------------------------------------------------------
        // Send welcome email (non-fatal)
        // ------------------------------------------------------------------
        try {
            Mail::to($counselee->email)->send(
                new CounseleeRegistered(
                    $counselee->first_name . ' ' . $counselee->last_name,
                    $counselee->email,
                    $plainPassword
                )
            );
        } catch (\Throwable $e) {
            Log::error('Counselee welcome email failed: ' . $e->getMessage());
        }

        Auth::guard('counselee')->login($counselee);

        return redirect()->route('counselee.dashboard')
            ->with('message', 'Registration successful! A confirmation email has been sent to ' . $counselee->email);
    }

    public function logout(Request $request)
    {
        Auth::guard('counselee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('counselee.login')
            ->with('message', 'You have been logged out successfully.');
    }
}