<?php

namespace App\Http\Controllers\Api;

use App\Models\Thing;
use App\Models\ThingDescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThingApiController extends Controller
{
    // Получить все вещи
    public function index()
    {
        $things = Thing::with('master', 'descriptions')->get();
        return response()->json([
            'success' => true,
            'data' => $things
        ]);
    }

    // Создать вещь
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'warranty' => 'nullable|date',
        ]);

        $thing = Thing::create([
            ...$validated,
            'master_id' => auth()->id(),
        ]);

        // Задание 13: Создаем первое описание
        ThingDescription::create([
            'thing_id' => $thing->id,
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вещь создана успешно',
            'data' => $thing
        ], 201);
    }

    // Получить вещь
    public function show(Thing $thing)
    {
        $thing->load('master', 'descriptions', 'usages.place', 'usages.user');
        return response()->json([
            'success' => true,
            'data' => $thing
        ]);
    }

    // Обновить вещь
    public function update(Request $request, Thing $thing)
    {
        $this->authorize('update', $thing);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'warranty' => 'nullable|date',
        ]);

        $thing->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Вещь обновлена успешно',
            'data' => $thing
        ]);
    }

    // Удалить вещь
    public function destroy(Thing $thing)
    {
        $this->authorize('delete', $thing);

        $thing->usages()->delete();
        $thing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Вещь удалена успешно'
        ]);
    }

    // Задание 13: Добавить новое описание (не изменять существующее)
    public function addDescription(Request $request, Thing $thing)
    {
        $this->authorize('update', $thing);

        $validated = $request->validate([
            'description' => 'required|string',
        ]);

        // Деактивируем предыдущее активное описание
        $thing->descriptions()->where('is_active', true)->update(['is_active' => false]);

        // Создаем новое активное описание
        $description = ThingDescription::create([
            'thing_id' => $thing->id,
            'description' => $validated['description'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Описание добавлено успешно',
            'data' => $description
        ], 201);
    }

    // Получить все описания вещи
    public function getDescriptions(Thing $thing)
    {
        $descriptions = $thing->descriptions()->get();
        return response()->json([
            'success' => true,
            'data' => $descriptions
        ]);
    }

    // Выбрать активное описание
    public function setActiveDescription(Request $request, Thing $thing)
    {
        $this->authorize('update', $thing);

        $validated = $request->validate([
            'description_id' => 'required|exists:thing_descriptions,id',
        ]);

        $description = ThingDescription::findOrFail($validated['description_id']);

        if ($description->thing_id !== $thing->id) {
            return response()->json([
                'success' => false,
                'message' => 'Описание не принадлежит этой вещи'
            ], 403);
        }

        $thing->descriptions()->update(['is_active' => false]);
        $description->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Активное описание изменено',
            'data' => $description
        ]);
    }
}
