<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\CounselorLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $counselor = Auth::guard('counselor')->user();

        $upcoming = CounselorLeave::where('counselor_id', $counselor->id)
            ->upcoming()
            ->orderBy('start_date')
            ->get();

        $past = CounselorLeave::where('counselor_id', $counselor->id)
            ->where('end_date', '<', now()->toDateString())
            ->orderByDesc('start_date')
            ->take(20)
            ->get();

        return view('counselor.leaves.index', compact('upcoming', 'past'));
    }

    public function create()
    {
        return view('counselor.leaves.create');
    }

    public function store(Request $request)
    {
        $counselor = Auth::guard('counselor')->user();

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:255',
        ]);

        CounselorLeave::create([
            'counselor_id' => $counselor->id,
            'start_date'   => $validated['start_date'],
            'end_date'     => $validated['end_date'],
            'reason'       => $validated['reason'] ?? null,
            'created_by'   => 'counselor',
        ]);

        return redirect()->route('counselor.leaves.index')
            ->with('success', 'Leave added. You will not be shown as available for booking on these dates.');
    }

    public function destroy(CounselorLeave $leave)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($leave->counselor_id !== $counselor->id) {
            abort(403);
        }

        $leave->delete();

        return back()->with('success', 'Leave cancelled.');
    }
}
