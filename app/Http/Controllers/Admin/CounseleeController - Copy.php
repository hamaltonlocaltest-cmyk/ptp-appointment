<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CounseleeRegistered;
use App\Models\Counselee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CounseleeController extends Controller
{
    public function index(Request $request)
    {
        $query = Counselee::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name',  'like', "%$s%")
                  ->orWhere('email',      'like', "%$s%")
                  ->orWhere('phone',      'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $counselees = $query->get(); // ← get() not paginate()

        $counts = [
            'total'    => Counselee::count(),
            'active'   => Counselee::where('status', 'active')->count(),
            'inactive' => Counselee::where('status', 'inactive')->count(),
        ];

        return view('admin.counselees.index', compact('counselees', 'counts'));
    }

    public function create()
    {
        return view('admin.counselees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:counselees,email',
            'phone'      => 'required|string|max:20',
            'birthdate'  => 'required|date',
            'gender'     => 'required|in:male,female,other',
            'status'     => 'required|in:active,inactive',
            'password'   => 'required|min:8|confirmed',
        ]);

        $plainPassword = $request->password;

        $counselee = Counselee::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'birthdate'  => $request->birthdate,
            'gender'     => $request->gender,
            'status'     => $request->status,
            'password'   => Hash::make($plainPassword),
        ]);

        try {
            Mail::to($counselee->email)->send(
                new CounseleeRegistered($counselee->full_name, $counselee->email, $plainPassword)
            );
        } catch (\Exception $e) {
            Log::error('Counselee mail failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.counselees.index')
            ->with('success', "Counselee {$counselee->full_name} created. Credentials sent to {$counselee->email}.");
    }

    public function show(Counselee $counselee)
    {
        return view('admin.counselees.show', compact('counselee'));
    }

    public function edit(Counselee $counselee)
    {
        return view('admin.counselees.edit', compact('counselee'));
    }

    public function update(Request $request, Counselee $counselee)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:counselees,email,' . $counselee->id,
            'phone'      => 'required|string|max:20',
            'birthdate'  => 'required|date',
            'gender'     => 'required|in:male,female,other',
            'status'     => 'required|in:active,inactive',
            'password'   => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['first_name', 'last_name', 'email', 'phone', 'birthdate', 'gender', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $counselee->update($data);

        return redirect()->route('admin.counselees.index')
            ->with('success', "Counselee {$counselee->full_name} updated successfully.");
    }

    public function toggleStatus(Counselee $counselee)
    {
        $newStatus = $counselee->status === 'active' ? 'inactive' : 'active';
        $counselee->update(['status' => $newStatus]);

        return back()->with('success', "{$counselee->full_name} status changed to " . ucfirst($newStatus) . ".");
    }

    public function destroy(Counselee $counselee)
    {
        $name = $counselee->full_name;
        $counselee->delete();

        return redirect()->route('admin.counselees.index')
            ->with('success', "Counselee {$name} deleted successfully.");
    }
}
