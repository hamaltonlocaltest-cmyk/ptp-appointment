<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CounselorRegistered;
use App\Models\Counselor;
use App\Models\CounselorAvailability;
use App\Models\CounselType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CounselorController extends Controller
{
    // -----------------------------------------------------------------------
    // Index
    // -----------------------------------------------------------------------
    public function index(Request $request)
    {
        $query = Counselor::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name',     'like', "%$s%")
                  ->orWhere('last_name',      'like', "%$s%")
                  ->orWhere('email',          'like', "%$s%")
                  ->orWhere('specialization', 'like', "%$s%")
                  ->orWhere('phone',          'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $counselors = $query->get();

        $counts = [
            'total'    => Counselor::count(),
            'active'   => Counselor::where('status', 'active')->count(),
            'inactive' => Counselor::where('status', 'inactive')->count(),
            'pending'  => Counselor::where('status', 'pending')->count(),
        ];

        return view('admin.counselors.index', compact('counselors', 'counts'));
    }

    // -----------------------------------------------------------------------
    // Create
    // -----------------------------------------------------------------------
    public function create()
    {
        $counselTypes = CounselType::active()->ordered()->get();
        return view('admin.counselors.create', compact('counselTypes'));
    }

    // -----------------------------------------------------------------------
    // Store
    // -----------------------------------------------------------------------
    public function store(Request $request)
    {
        $this->validateCounselor($request);

        DB::beginTransaction();
        try {
            $plainPassword = $request->password;

            $counselor = Counselor::create([
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'address'          => $request->address,
                'specialization'   => $request->specialization,
                'experience_years' => $request->experience_years,
                'mode'             => $request->mode,
                'languages'        => $request->languages,
                'training_level'   => $request->training_level,
                'status'           => $request->status,
                'password'         => Hash::make($plainPassword),
            ]);

            $counselor->counselTypes()->sync($request->counsel_types ?? []);
            $this->syncAvailability($counselor, $request->availability ?? []);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin counselor store failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Could not save counselor. Please try again.']);
        }

        try {
            Mail::to($counselor->email)->send(
                new CounselorRegistered($counselor->full_name, $counselor->email, $plainPassword)
            );
        } catch (\Throwable $e) {
            Log::error('Counselor mail failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.counselors.index')
            ->with('success', "Counselor {$counselor->full_name} created. Credentials sent to {$counselor->email}.");
    }

    // -----------------------------------------------------------------------
    // Show
    // -----------------------------------------------------------------------
    public function show(Counselor $counselor)
    {
        $counselor->load('counselTypes', 'availabilities');
        return view('admin.counselors.show', compact('counselor'));
    }

    // -----------------------------------------------------------------------
    // Edit
    // -----------------------------------------------------------------------
    public function edit(Counselor $counselor)
    {
        $counselor->load('counselTypes', 'availabilities');
        $counselTypes = CounselType::active()->ordered()->get();
        return view('admin.counselors.edit', compact('counselor', 'counselTypes'));
    }

    // -----------------------------------------------------------------------
    // Update
    // -----------------------------------------------------------------------
    public function update(Request $request, Counselor $counselor)
    {
        $this->validateCounselor($request, $counselor->id);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'first_name', 'last_name', 'email', 'phone', 'address',
                'specialization', 'experience_years', 'mode', 'languages',
                'training_level', 'status',
            ]);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $counselor->update($data);

            $counselor->counselTypes()->sync($request->counsel_types ?? []);
            $this->syncAvailability($counselor, $request->availability ?? []);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin counselor update failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Could not update counselor. Please try again.']);
        }

        return redirect()->route('admin.counselors.index')
            ->with('success', "Counselor {$counselor->full_name} updated successfully.");
    }

    // -----------------------------------------------------------------------
    // Toggle Status
    // -----------------------------------------------------------------------
    public function toggleStatus(Counselor $counselor)
    {
        $newStatus = $counselor->status === 'active' ? 'inactive' : 'active';
        $counselor->update(['status' => $newStatus]);

        return back()->with('success', "{$counselor->full_name} status changed to " . ucfirst($newStatus) . ".");
    }

    // -----------------------------------------------------------------------
    // Destroy
    // -----------------------------------------------------------------------
    public function destroy(Counselor $counselor)
    {
        $name = $counselor->full_name;
        $counselor->delete();

        return redirect()->route('admin.counselors.index')
            ->with('success', "Counselor {$name} deleted successfully.");
    }

    // -----------------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------------
    private function validateCounselor(Request $request, ?int $counselorId = null): void
    {
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:counselors,email' . ($counselorId ? ",{$counselorId}" : ''),
            'phone'            => 'required|digits:10',
            'address'          => 'required|string|max:500',
            'specialization'   => 'required|string|max:150',
            'experience_years' => 'required|integer|min:0|max:60',
            'mode'             => 'required|in:Online,In person,Both',
            'languages'        => 'required|string|max:255',
            'training_level'   => 'required|in:Level 1,Level 2,Advanced,Certified,Other',
            'status'           => 'required|in:pending,active,inactive',
            'password'         => $counselorId ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',

            // Areas of Expertise (dynamic CounselType IDs)
            'counsel_types'    => 'nullable|array',
            'counsel_types.*'  => 'integer|exists:counsel_types,id',

            // Weekly availability
            'availability'                  => 'required|array|min:1',
            'availability.*.day'            => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'availability.*.start_time'     => 'required|date_format:H:i',
            'availability.*.end_time'       => 'required|date_format:H:i|after:availability.*.start_time',
        ], [
            'phone.digits'                 => 'Phone number must be exactly 10 digits.',
            'availability.required'        => 'Please add at least one available time slot.',
            'availability.min'             => 'Please add at least one available time slot.',
            'availability.*.end_time.after'=> 'End time must be later than start time.',
        ]);
    }

    private function syncAvailability(Counselor $counselor, array $rows): void
    {
        $counselor->availabilities()->delete();

        foreach ($rows as $row) {
            if (!empty($row['day']) && !empty($row['start_time']) && !empty($row['end_time'])) {
                if ($row['start_time'] < $row['end_time']) {
                    CounselorAvailability::create([
                        'counselor_id' => $counselor->id,
                        'day'          => $row['day'],
                        'start_time'   => $row['start_time'],
                        'end_time'     => $row['end_time'],
                    ]);
                }
            }
        }
    }
}