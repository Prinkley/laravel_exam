<?php

namespace App\Http\Controllers\Api;

use App\Models\ArchivedThing;
use App\Models\Thing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArchiveApiController extends Controller
{
    // Получить архив (только для администраторов или владельцев)
    public function index()
    {
        $archived = ArchivedThing::with('master', 'lastUser')->get();
        return response()->json([
            'success' => true,
            'data' => $archived
        ]);
    }

    // Восстановить вещь из архива
    public function restore(Request $request, ArchivedThing $archived)
    {
        if ($archived->is_restored) {
            return response()->json([
                'success' => false,
                'message' => 'Вещь уже была восстановлена'
            ], 400);
        }

        // Создаем новую вещь с данными из архива
        $newThing = Thing::create([
            'name' => $archived->name,
            'description' => $archived->description,
            'warranty' => $archived->warranty,
            'master_id' => auth()->id(), // Новый хозяин - пользователь, который восстановил
        ]);

        // Помечаем как восстановленную
        $archived->update([
            'is_restored' => true,
            'restored_by_id' => auth()->id(),
            'restored_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вещь восстановлена успешно',
            'data' => $newThing
        ], 201);
    }

    // Получить детали архивированной вещи
    public function show(ArchivedThing $archived)
    {
        $archived->load('master', 'lastUser');
        return response()->json([
            'success' => true,
            'data' => $archived
        ]);
    }
}
