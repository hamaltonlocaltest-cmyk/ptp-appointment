<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CounseleeRegistered;
use App\Models\Counselee;
use App\Models\CounseleeChild;
use App\Models\CounseleeMedicalHistory;
use App\Models\CounselType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CounseleeController extends Controller
{
    // -----------------------------------------------------------------------
    // Index
    // -----------------------------------------------------------------------
    public function index(Request $request)
    {
        $query = Counselee::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name',  'like', "%$s%")
                  ->orWhere('email',      'like', "%$s%")
                  ->orWhere('telephone1', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $counselees = $query->get();

        $counts = [
            'total'    => Counselee::count(),
            'active'   => Counselee::where('status', 'active')->count(),
            'inactive' => Counselee::where('status', 'inactive')->count(),
        ];

        return view('admin.counselees.index', compact('counselees', 'counts'));
    }

    // -----------------------------------------------------------------------
    // Create
    // -----------------------------------------------------------------------
    public function create()
    {
        $counselTypes = CounselType::active()->ordered()->get();
        return view('admin.counselees.create', compact('counselTypes'));
    }

    // -----------------------------------------------------------------------
    // Store
    // -----------------------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'title'          => 'nullable|in:Mr,Miss,Mrs,Rev,Dr',
            'email'          => 'required|email|unique:counselees,email',
            'telephone1'     => 'nullable|string|max:20',
            'telephone2'     => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'birthdate'      => 'required|date',
            'age'            => 'nullable|integer|min:1|max:120',
            'gender'         => 'required|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'status'         => 'required|in:active,inactive',
            'password'       => 'required|min:8|confirmed',
            'referral'                     => 'nullable|in:Self,Friend,Relative,Pastor,PtP Website',
            'previous_counselling'         => 'nullable|in:Yes,No',
            'previous_counselling_details' => 'nullable|string|max:1000',
            'children'              => 'nullable|array',
            'children.*.name'       => 'nullable|string|max:100',
            'children.*.gender'     => 'nullable|in:Male,Female',
            'children.*.age'        => 'nullable|integer|min:0|max:30',
            'medical_history'              => 'nullable|array',
            'medical_history.*.condition'  => 'nullable|string|max:200',
            'medical_history.*.details'    => 'nullable|string|max:500',
            // Counselling areas now reference CounselType IDs
            'counsel_types'   => 'nullable|array',
            'counsel_types.*' => 'integer|exists:counsel_types,id',
        ]);

        DB::beginTransaction();
        try {
            $plainPassword = $request->password;

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
                'referral'                     => $request->referral,
                'previous_counselling'         => $request->previous_counselling,
                'previous_counselling_details' => $request->previous_counselling_details,
                'status'                       => $request->status,
                'password'                     => Hash::make($plainPassword),
            ]);

            $this->syncChildren($counselee, $request->children ?? []);
            $this->syncMedical($counselee, $request->medical_history ?? []);

            // Sync counsel types via pivot table
            $counselee->counselTypes()->sync($request->counsel_types ?? []);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin counselee store failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Could not save counselee. Please try again.']);
        }

        try {
            Mail::to($counselee->email)->send(
                new CounseleeRegistered($counselee->full_name, $counselee->email, $plainPassword)
            );
        } catch (\Throwable $e) {
            Log::error('Counselee mail failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.counselees.index')
            ->with('success', "Counselee {$counselee->full_name} created. Credentials sent to {$counselee->email}.");
    }

    // -----------------------------------------------------------------------
    // Show
    // -----------------------------------------------------------------------
    public function show(Counselee $counselee)
    {
        $counselee->load('children', 'medicalHistories', 'counselTypes');
        return view('admin.counselees.show', compact('counselee'));
    }

    // -----------------------------------------------------------------------
    // Edit
    // -----------------------------------------------------------------------
    public function edit(Counselee $counselee)
    {
        $counselee->load('children', 'medicalHistories', 'counselTypes');
        $counselTypes = CounselType::active()->ordered()->get();
        return view('admin.counselees.edit', compact('counselee', 'counselTypes'));
    }

    // -----------------------------------------------------------------------
    // Update
    // -----------------------------------------------------------------------
    public function update(Request $request, Counselee $counselee)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'title'          => 'nullable|in:Mr,Miss,Mrs,Rev,Dr',
            'email'          => 'required|email|unique:counselees,email,' . $counselee->id,
            'telephone1'     => 'nullable|string|max:20',
            'telephone2'     => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'birthdate'      => 'required|date',
            'age'            => 'nullable|integer|min:1|max:120',
            'gender'         => 'required|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'status'         => 'required|in:active,inactive',
            'password'       => 'nullable|min:8|confirmed',
            'referral'                     => 'nullable|in:Self,Friend,Relative,Pastor,PtP Website',
            'previous_counselling'         => 'nullable|in:Yes,No',
            'previous_counselling_details' => 'nullable|string|max:1000',
            'children'              => 'nullable|array',
            'children.*.name'       => 'nullable|string|max:100',
            'children.*.gender'     => 'nullable|in:Male,Female',
            'children.*.age'        => 'nullable|integer|min:0|max:30',
            'medical_history'              => 'nullable|array',
            'medical_history.*.condition'  => 'nullable|string|max:200',
            'medical_history.*.details'    => 'nullable|string|max:500',
            'counsel_types'   => 'nullable|array',
            'counsel_types.*' => 'integer|exists:counsel_types,id',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'title', 'first_name', 'last_name', 'address',
                'telephone1', 'telephone2', 'email', 'age',
                'birthdate', 'gender', 'marital_status', 'status',
                'referral', 'previous_counselling', 'previous_counselling_details',
            ]);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $counselee->update($data);

            $this->syncChildren($counselee, $request->children ?? []);
            $this->syncMedical($counselee, $request->medical_history ?? []);

            // Sync counsel types via pivot table
            $counselee->counselTypes()->sync($request->counsel_types ?? []);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin counselee update failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Could not update counselee. Please try again.']);
        }

        return redirect()->route('admin.counselees.index')
            ->with('success', "Counselee {$counselee->full_name} updated successfully.");
    }

    // -----------------------------------------------------------------------
    // Toggle Status
    // -----------------------------------------------------------------------
    public function toggleStatus(Counselee $counselee)
    {
        $newStatus = $counselee->status === 'active' ? 'inactive' : 'active';
        $counselee->update(['status' => $newStatus]);

        return back()->with('success', "{$counselee->full_name} status changed to " . ucfirst($newStatus) . ".");
    }

    // -----------------------------------------------------------------------
    // Destroy
    // -----------------------------------------------------------------------
    public function destroy(Counselee $counselee)
    {
        $name = $counselee->full_name;
        $counselee->delete();

        return redirect()->route('admin.counselees.index')
            ->with('success', "Counselee {$name} deleted successfully.");
    }

    // -----------------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------------
    private function syncChildren(Counselee $counselee, array $rows): void
    {
        $counselee->children()->delete();
        foreach ($rows as $row) {
            if (!empty($row['name'])) {
                CounseleeChild::create([
                    'counselee_id' => $counselee->id,
                    'name'         => $row['name'],
                    'gender'       => $row['gender'] ?? null,
                    'age'          => $row['age']    ?? null,
                ]);
            }
        }
    }

    private function syncMedical(Counselee $counselee, array $rows): void
    {
        $counselee->medicalHistories()->delete();
        foreach ($rows as $row) {
            if (!empty($row['condition'])) {
                CounseleeMedicalHistory::create([
                    'counselee_id' => $counselee->id,
                    'condition'    => $row['condition'],
                    'details'      => $row['details'] ?? null,
                ]);
            }
        }
    }
}