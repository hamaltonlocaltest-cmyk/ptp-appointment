<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $query = State::with('country')->orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%$s%");
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $states = $query->get();

        $counts = [
            'total'    => State::count(),
            'active'   => State::where('status', 'active')->count(),
            'inactive' => State::where('status', 'inactive')->count(),
        ];

        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('admin.masters.states.index', compact('states', 'counts', 'countries'));
    }

    public function create()
    {
        $countries = Country::active()->orderBy('name')->get(['id', 'name']);
        return view('admin.masters.states.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
            'name'       => 'required|string|max:150',
            'code'       => 'nullable|string|max:10',
            'status'     => 'required|in:active,inactive',
        ]);

        State::create($request->only(['country_id', 'name', 'code', 'status']));

        return redirect()->route('admin.masters.states.index')
            ->with('success', "State \"{$request->name}\" created successfully.");
    }

    public function edit(State $state)
    {
        $countries = Country::active()->orderBy('name')->get(['id', 'name']);
        return view('admin.masters.states.edit', compact('state', 'countries'));
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
            'name'       => 'required|string|max:150',
            'code'       => 'nullable|string|max:10',
            'status'     => 'required|in:active,inactive',
        ]);

        $state->update($request->only(['country_id', 'name', 'code', 'status']));

        return redirect()->route('admin.masters.states.index')
            ->with('success', "State \"{$state->name}\" updated successfully.");
    }

    public function toggleStatus(State $state)
    {
        $newStatus = $state->status === 'active' ? 'inactive' : 'active';
        $state->update(['status' => $newStatus]);

        return back()->with('success', "\"{$state->name}\" is now " . ucfirst($newStatus) . ".");
    }

    public function destroy(State $state)
    {
        $name = $state->name;
        $state->delete();

        return redirect()->route('admin.masters.states.index')
            ->with('success', "State \"{$name}\" deleted successfully.");
    }
}
