<?php

namespace App\Http\Controllers;

use App\Models\Usage;
use Illuminate\Http\Request;

class UsageController extends Controller
{
    public function index()
    {
        $usages = Usage::with('thing', 'place', 'user')->paginate(15);
        return view('usages.index', compact('usages'));
    }

    public function show(Usage $usage)
    {
        $usage->load('thing', 'place', 'user', 'dimension');
        return view('usages.show', compact('usage'));
    }

    public function edit(Usage $usage)
    {
        $this->authorize('update', $usage);
        return view('usages.edit', compact('usage'));
    }

    public function update(Request $request, Usage $usage)
    {
        $this->authorize('update', $usage);

        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'dimension_id' => 'nullable|exists:dimensions,id',
        ]);

        $usage->update($validated);

        return redirect()->route('usages.show', $usage)
            ->with('success', 'Usage updated successfully');
    }

    public function destroy(Usage $usage)
    {
        $this->authorize('delete', $usage);
        $usage->delete();

        return redirect()->route('things.show', $usage->thing_id)
            ->with('success', 'Usage removed successfully');
    }
}
