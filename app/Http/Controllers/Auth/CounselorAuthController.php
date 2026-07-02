<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CounselorRegistered;
use App\Models\Counselor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CounselorAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('counselor')->check()) {
            return redirect()->route('counselor.dashboard');
        }
        return view('auth.counselor.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('counselor')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('counselor.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::guard('counselor')->check()) {
            return redirect()->route('counselor.dashboard');
        }
        return view('auth.counselor.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:counselors,email',
            'phone'          => 'required|string|max:20',
            'specialization' => 'required|string|max:150',
            'password'       => 'required|min:8|confirmed',
        ]);

        $plainPassword = $request->password;

        $counselor = Counselor::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'specialization' => $request->specialization,
            'password'       => Hash::make($plainPassword),
            'status'         => 'pending',
        ]);

        // Send registration email
        try {
            Mail::to($counselor->email)->send(
                new CounselorRegistered(
                    $counselor->first_name . ' ' . $counselor->last_name,
                    $counselor->email,
                    $plainPassword
                )
            );
        } catch (\Exception $e) {
            Log::error('Counselor registration email failed: ' . $e->getMessage());
        }

        Auth::guard('counselor')->login($counselor);

        return redirect()->route('counselor.dashboard')
            ->with('message', 'Registration successful! A confirmation email has been sent to ' . $counselor->email);
    }

    public function logout(Request $request)
    {
        Auth::guard('counselor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('counselor.login')
            ->with('message', 'You have been logged out successfully.');
    }
}
