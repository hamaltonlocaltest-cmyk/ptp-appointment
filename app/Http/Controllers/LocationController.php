<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;

// Public, guard-agnostic endpoints for cascading Country -> State -> City
// selects, shared by registration, admin create/edit, and any future forms.
class LocationController extends Controller
{
    public function states(int $country)
    {
        $states = State::where('country_id', $country)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json(['states' => $states]);
    }

    public function cities(int $state)
    {
        $cities = City::where('state_id', $state)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json(['cities' => $cities]);
    }
}
