<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['counselee', 'counselor'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%$s%")
                  ->orWhere('subject', 'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('filed_by')) {
            $query->where('filed_by', $request->filed_by);
        }

        $complaints = $query->get();

        $counts = [
            'total'     => Complaint::count(),
            'open'      => Complaint::where('status', 'open')->count(),
            'in_review' => Complaint::where('status', 'in_review')->count(),
            'resolved'  => Complaint::where('status', 'resolved')->count(),
            'closed'    => Complaint::where('status', 'closed')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'counts'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('counselee', 'counselor', 'appointment.counselType');

        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status'           => 'required|in:open,in_review,resolved,closed',
            'resolution_notes' => 'nullable|string|max:2000',
        ]);

        $complaint->update([
            'status'           => $validated['status'],
            'resolution_notes' => $validated['resolution_notes'] ?? $complaint->resolution_notes,
            'resolved_at'      => in_array($validated['status'], ['resolved', 'closed'])
                ? ($complaint->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', 'Complaint updated successfully.');
    }
}
