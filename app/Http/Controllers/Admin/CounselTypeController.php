<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CounselType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CounselTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = CounselType::orderBy('sort_order')->orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',         'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $counselTypes = $query->get(); // ← get() not paginate()

        $counts = [
            'total'    => CounselType::count(),
            'active'   => CounselType::where('status', 'active')->count(),
            'inactive' => CounselType::where('status', 'inactive')->count(),
        ];

        return view('admin.masters.counsel-types.index', compact('counselTypes', 'counts'));
    }

    public function create()
    {
        $icons = $this->availableIcons();
        $colors = $this->availableColors();
        return view('admin.masters.counsel-types.create', compact('icons', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:counsel_types,name',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:60',
            'color'       => 'nullable|string|max:20',
            'status'      => 'required|in:active,inactive',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        CounselType::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'icon'        => $request->icon ?? 'fas fa-comments',
            'color'       => $request->color ?? '#1a237e',
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.masters.counsel-types.index')
            ->with('success', "Counsel type \"{$request->name}\" created successfully.");
    }

    public function edit(CounselType $counselType)
    {
        $icons  = $this->availableIcons();
        $colors = $this->availableColors();
        return view('admin.masters.counsel-types.edit', compact('counselType', 'icons', 'colors'));
    }

    public function update(Request $request, CounselType $counselType)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:counsel_types,name,' . $counselType->id,
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:60',
            'color'       => 'nullable|string|max:20',
            'status'      => 'required|in:active,inactive',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $counselType->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'icon'        => $request->icon ?? 'fas fa-comments',
            'color'       => $request->color ?? '#1a237e',
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.masters.counsel-types.index')
            ->with('success', "Counsel type \"{$counselType->name}\" updated successfully.");
    }

    public function toggleStatus(CounselType $counselType)
    {
        $newStatus = $counselType->status === 'active' ? 'inactive' : 'active';
        $counselType->update(['status' => $newStatus]);

        return back()->with('success', "\"{$counselType->name}\" is now " . ucfirst($newStatus) . ".");
    }

    public function destroy(CounselType $counselType)
    {
        $name = $counselType->name;
        $counselType->delete();

        return redirect()->route('admin.masters.counsel-types.index')
            ->with('success', "Counsel type \"{$name}\" deleted successfully.");
    }

    // Available icons list
    private function availableIcons(): array
    {
        return [
            'fas fa-child'           => 'Child',
            'fas fa-heart'           => 'Pre-Marital',
            'fas fa-graduation-cap'  => 'Study',
            'fas fa-briefcase'       => 'Work',
            'fas fa-users'           => 'Family',
            'fas fa-brain'           => 'Mental Health',
            'fas fa-hand-holding-heart' => 'Grief',
            'fas fa-comments'        => 'General',
            'fas fa-pills'           => 'Substance',
            'fas fa-angry'           => 'Anger',
            'fas fa-sad-tear'        => 'Depression',
            'fas fa-user-friends'    => 'Relationship',
            'fas fa-home'            => 'Domestic',
            'fas fa-dollar-sign'     => 'Financial',
            'fas fa-running'         => 'Lifestyle',
            'fas fa-star'            => 'Career',
            'fas fa-leaf'            => 'Wellness',
            'fas fa-shield-alt'      => 'Trauma',
            'fas fa-cross'           => 'Spiritual',
            'fas fa-puzzle-piece'    => 'Behavioral',
        ];
    }

    // Available colors
    private function availableColors(): array
    {
        return [
            '#1a237e' => 'Navy Blue',
            '#1b5e20' => 'Dark Green',
            '#4a148c' => 'Purple',
            '#b71c1c' => 'Dark Red',
            '#e65100' => 'Orange',
            '#f57f17' => 'Amber',
            '#006064' => 'Teal',
            '#1565c0' => 'Blue',
            '#880e4f' => 'Pink',
            '#33691e' => 'Light Green',
            '#37474f' => 'Blue Grey',
            '#4e342e' => 'Brown',
        ];
    }
}
