<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use App\Models\CounselorLeave;
use Illuminate\Http\Request;

class CounselorLeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = CounselorLeave::with('counselor')->orderByDesc('start_date');

        if ($request->filled('counselor_id')) {
            $query->where('counselor_id', $request->counselor_id);
        }

        $leaves = $query->get();

        $counselors = Counselor::where('status', '!=', 'deleted')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        return view('admin.counselor-leaves.index', compact('leaves', 'counselors'));
    }

    public function create()
    {
        $counselors = Counselor::where('status', '!=', 'deleted')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        return view('admin.counselor-leaves.create', compact('counselors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'counselor_id' => 'required|integer|exists:counselors,id',
            'start_date'   => 'required|date|after_or_equal:today',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'reason'       => 'nullable|string|max:255',
        ]);

        $counselor = Counselor::findOrFail($validated['counselor_id']);

        CounselorLeave::create([
            'counselor_id' => $validated['counselor_id'],
            'start_date'   => $validated['start_date'],
            'end_date'     => $validated['end_date'],
            'reason'       => $validated['reason'] ?? null,
            'created_by'   => 'admin',
        ]);

        return redirect()->route('admin.counselor-leaves.index')
            ->with('success', "Leave applied for {$counselor->full_name}.");
    }

    public function destroy(CounselorLeave $counselorLeave)
    {
        $counselorLeave->delete();

        return back()->with('success', 'Leave record removed.');
    }
}
