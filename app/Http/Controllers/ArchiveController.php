<?php

namespace App\Http\Controllers;

use App\Models\ArchivedThing;
use App\Models\Thing;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    // Просмотр архива
    public function index()
    {
        $archived = ArchivedThing::paginate(15);
        return view('archive.index', compact('archived'));
    }

    // Восстановить вещь
    public function restore(ArchivedThing $archived)
    {
        if ($archived->is_restored) {
            return redirect()->route('archive.index')
                ->with('error', 'Вещь уже была восстановлена');
        }

        // Создаем новую вещь
        $newThing = Thing::create([
            'name' => $archived->name,
            'description' => $archived->description,
            'warranty' => $archived->warranty,
            'master_id' => auth()->id(),
        ]);

        // Помечаем как восстановленную
        $archived->update([
            'is_restored' => true,
            'restored_by_id' => auth()->id(),
            'restored_at' => now(),
        ]);

        return redirect()->route('archive.index')
            ->with('success', 'Вещь "' . $archived->name . '" успешно восстановлена');
    }

    // Просмотр деталей архивированной вещи
    public function show(ArchivedThing $archived)
    {
        return view('archive.show', compact('archived'));
    }
}
