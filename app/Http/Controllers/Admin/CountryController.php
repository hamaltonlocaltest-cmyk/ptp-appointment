<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('code', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $countries = $query->get();

        $counts = [
            'total'    => Country::count(),
            'active'   => Country::where('status', 'active')->count(),
            'inactive' => Country::where('status', 'inactive')->count(),
        ];

        return view('admin.masters.countries.index', compact('countries', 'counts'));
    }

    public function create()
    {
        return view('admin.masters.countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100|unique:countries,name',
            'code'       => 'required|string|size:2|unique:countries,code',
            'phone_code' => 'nullable|string|max:10',
            'status'     => 'required|in:active,inactive',
        ]);

        Country::create([
            'name'       => $request->name,
            'code'       => strtoupper($request->code),
            'phone_code' => $request->phone_code,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.masters.countries.index')
            ->with('success', "Country \"{$request->name}\" created successfully.");
    }

    public function edit(Country $country)
    {
        return view('admin.masters.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name'       => 'required|string|max:100|unique:countries,name,' . $country->id,
            'code'       => 'required|string|size:2|unique:countries,code,' . $country->id,
            'phone_code' => 'nullable|string|max:10',
            'status'     => 'required|in:active,inactive',
        ]);

        $country->update([
            'name'       => $request->name,
            'code'       => strtoupper($request->code),
            'phone_code' => $request->phone_code,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.masters.countries.index')
            ->with('success', "Country \"{$country->name}\" updated successfully.");
    }

    public function toggleStatus(Country $country)
    {
        $newStatus = $country->status === 'active' ? 'inactive' : 'active';
        $country->update(['status' => $newStatus]);

        return back()->with('success', "\"{$country->name}\" is now " . ucfirst($newStatus) . ".");
    }

    public function destroy(Country $country)
    {
        $name = $country->name;
        $country->delete();

        return redirect()->route('admin.masters.countries.index')
            ->with('success', "Country \"{$name}\" deleted successfully.");
    }
}
