<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlaceController extends Controller
{
    public function index()
    {
        $places = Cache::remember('places.all', 3600, function () {
            return Place::with('usages')->paginate(15);
        });
        return view('places.index', compact('places'));
    }

    public function create()
    {
        return view('places.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'repair' => 'nullable|boolean',
            'work' => 'nullable|boolean',
        ]);

        $place = Place::create($validated);
        Cache::forget('places.all');

        return redirect()->route('places.show', $place)
            ->with('success', 'Место создано');
    }

    public function show(Place $place)
    {
        $place->load('usages.thing', 'usages.user');
        return view('places.show', compact('place'));
    }

    public function edit(Place $place)
    {
        $this->authorize('update', $place);
        return view('places.edit', compact('place'));
    }

    public function update(Request $request, Place $place)
    {
        $this->authorize('update', $place);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'repair' => 'nullable|boolean',
            'work' => 'nullable|boolean',
        ]);

        $place->update($validated);
        Cache::forget('places.all');

        return redirect()->route('places.show', $place)
            ->with('success', 'Место обновлено');
    }

    public function destroy(Place $place)
    {
        $this->authorize('delete', $place);

        $place->usages()->delete();
        $place->delete();
        Cache::forget('places.all');

        return redirect()->route('places.index')
            ->with('success', 'Место удалено');
    }
}
