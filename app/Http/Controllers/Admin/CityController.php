<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('state', 'country')->orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%$s%");
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cities = $query->get();

        $counts = [
            'total'    => City::count(),
            'active'   => City::where('status', 'active')->count(),
            'inactive' => City::where('status', 'inactive')->count(),
        ];

        $states = State::with('country')->active()->orderBy('name')->get(['id', 'name', 'country_id']);

        return view('admin.masters.cities.index', compact('cities', 'counts', 'states'));
    }

    public function create()
    {
        $countries = Country::active()->orderBy('name')->get(['id', 'name']);
        return view('admin.masters.cities.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
            'state_id'   => 'required|integer|exists:states,id',
            'name'       => 'required|string|max:150',
            'status'     => 'required|in:active,inactive',
        ]);

        City::create($request->only(['state_id', 'country_id', 'name', 'status']));

        return redirect()->route('admin.masters.cities.index')
            ->with('success', "City \"{$request->name}\" created successfully.");
    }

    public function edit(City $city)
    {
        $countries = Country::active()->orderBy('name')->get(['id', 'name']);
        $states = State::where('country_id', $city->country_id)->active()->orderBy('name')->get(['id', 'name']);
        return view('admin.masters.cities.edit', compact('city', 'countries', 'states'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
            'state_id'   => 'required|integer|exists:states,id',
            'name'       => 'required|string|max:150',
            'status'     => 'required|in:active,inactive',
        ]);

        $city->update($request->only(['state_id', 'country_id', 'name', 'status']));

        return redirect()->route('admin.masters.cities.index')
            ->with('success', "City \"{$city->name}\" updated successfully.");
    }

    public function toggleStatus(City $city)
    {
        $newStatus = $city->status === 'active' ? 'inactive' : 'active';
        $city->update(['status' => $newStatus]);

        return back()->with('success', "\"{$city->name}\" is now " . ucfirst($newStatus) . ".");
    }

    public function destroy(City $city)
    {
        $name = $city->name;
        $city->delete();

        return redirect()->route('admin.masters.cities.index')
            ->with('success', "City \"{$name}\" deleted successfully.");
    }
}
