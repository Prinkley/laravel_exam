<?php

namespace App\Http\Controllers;

use App\Models\Thing;
use App\Models\Place;
use App\Models\Usage;
use App\Models\User;
use App\Models\ArchivedThing;
use App\Models\ThingDescription;
use App\Jobs\SendThingDescriptionChangedEmail;
use App\Notifications\ThingDescriptionUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThingController extends Controller
{
    public function index()
    {
        $things = Thing::with('master', 'usages.place', 'usages.user')
            ->latest('id')
            ->paginate(15);
        return view('things.index', compact('things'));
    }

    public function myThings()
    {
        $things = Thing::where('master_id', auth()->id())
            ->with('usages.place', 'usages.user')
            ->latest('id')
            ->paginate(15);
        return view('things.my', compact('things'));
    }

    public function repairThings()
    {
        $things = Thing::whereHas('usages.place', function ($query) {
            $query->where('repair', true);
        })->with('master', 'usages.place', 'usages.user')
            ->latest('id')
            ->paginate(15);
        
        return view('things.repair', compact('things'));
    }

    public function workThings()
    {
        $things = Thing::whereHas('usages.place', function ($query) {
            $query->where('work', true);
        })->with('master', 'usages.place', 'usages.user')
            ->latest('id')
            ->paginate(15);
        
        return view('things.work', compact('things'));
    }

    public function usedThings()
    {
        $things = Thing::where('master_id', auth()->id())
            ->whereHas('usages', function ($query) {
                $query->where('user_id', '!=', auth()->id());
            })
            ->with('usages.place', 'usages.user')
            ->latest('id')
            ->paginate(15);
        
        return view('things.used', compact('things'));
    }

    public function create()
    {
        return view('things.create');
    }

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

        Cache::forget('things.all');
        Cache::forget('things.my.' . auth()->id());

        return redirect()->route('things.show', $thing)
            ->with('success', 'Вещь создана успешно');
    }

    public function show(Thing $thing)
    {
        $thing->load('master', 'usages.place', 'usages.user');
        return view('things.show', compact('thing'));
    }

    public function edit(Thing $thing)
    {
        $this->authorize('update', $thing);
        return view('things.edit', compact('thing'));
    }

    public function update(Request $request, Thing $thing)
    {
        $this->authorize('update', $thing);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'warranty' => 'nullable|date',
        ]);

        $oldDescription = $thing->description;
        $descriptionChanged = $oldDescription !== $validated['description'];

        $thing->update($validated);

        Cache::forget('things.all');
        Cache::forget('things.my.' . auth()->id());

        // Задание 17-18: Отправляем письмо в очередь при изменении описания
        if ($descriptionChanged) {
            SendThingDescriptionChangedEmail::dispatch($thing, $oldDescription);
            
            // Задание 19: Отправляем уведомление владельцу через Database и Email
            $thing->master->notify(new ThingDescriptionUpdated($thing, $oldDescription));
            
            // Уведомляем также пользователей, на которых назначена вещь
            $assignedUsers = $thing->usages()
                ->distinct()
                ->pluck('user_id')
                ->map(fn($id) => User::find($id))
                ->filter();
            
            foreach ($assignedUsers as $user) {
                $user->notify(new ThingDescriptionUpdated($thing, $oldDescription));
            }
        }

        return redirect()->route('things.show', $thing)
            ->with('success', 'Вещь обновлена успешно');
    }

    public function destroy(Thing $thing)
    {
        $this->authorize('delete', $thing);

        // Задание 15: Архивируем вещь перед удалением
        $lastUsage = $thing->usages()->latest()->first();
        
        ArchivedThing::create([
            'thing_id' => $thing->id,
            'name' => $thing->name,
            'description' => $thing->descriptions()->where('is_active', true)->first()?->description ?? $thing->description,
            'warranty' => $thing->warranty,
            'master_name' => $thing->master->name,
            'master_id' => $thing->master_id,
            'last_user_name' => $lastUsage?->user->name,
            'last_user_id' => $lastUsage?->user_id,
            'place_name' => $lastUsage?->place->name,
            'place_id' => $lastUsage?->place_id,
            'is_restored' => false,
        ]);

        $thing->usages()->delete();
        $thing->descriptions()->delete();
        $thing->delete();

        Cache::forget('things.all');
        Cache::forget('things.my.' . auth()->id());

        return redirect()->route('things.index')
            ->with('success', 'Вещь удалена и архивирована');
    }

    public function assign(Thing $thing)
    {
        $users = User::all();
        $places = Place::all();
        return view('things.assign', compact('thing', 'users', 'places'));
    }

    public function storeAssignment(Request $request, Thing $thing)
    {
        $this->authorize('assign', $thing);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'place_id' => 'required|exists:places,id',
            'amount' => 'required|integer|min:1',
            'dimension_id' => 'nullable|exists:dimensions,id',
        ]);

        Usage::create([
            'thing_id' => $thing->id,
            ...$validated,
        ]);

        Cache::forget('things.all');

        return redirect()->route('things.show', $thing)
            ->with('success', 'Thing assigned successfully');
    }

    // Задание 13: Добавление нового описания к вещи (не изменение)
    public function storeDescription(Request $request, Thing $thing)
    {
        $this->authorize('update', $thing);

        $validated = $request->validate([
            'description' => 'required|string|min:1',
        ]);

        // Просто создать новое описание (не деактивировать старые)
        ThingDescription::create([
            'thing_id' => $thing->id,
            'description' => $validated['description'],
            'is_active' => false,
        ]);

        return redirect()->route('things.show', $thing)
            ->with('success', 'Новое описание добавлено успешно');
    }

    // Задание 13: Выбор актуального описания из списка
    public function setActiveDescription(Request $request, Thing $thing)
    {
        $this->authorize('update', $thing);

        $validated = $request->validate([
            'description_id' => 'required|exists:thing_descriptions,id',
        ]);

        $description = ThingDescription::find($validated['description_id']);

        // Проверяем что описание принадлежит этой вещи
        if ($description->thing_id !== $thing->id) {
            abort(403);
        }

        // Деактивировать все описания
        $thing->descriptions()->update(['is_active' => false]);

        // Активировать выбранное
        $description->update(['is_active' => true]);

        // Обновляем основное поле description для совместимости
        $thing->update(['description' => $description->description]);

        // Отправляем уведомления
        SendThingDescriptionChangedEmail::dispatch($thing, null);
        $thing->master->notify(new ThingDescriptionUpdated($thing, null));

        // Уведомляем также пользователей, на которых назначена вещь
        $assignedUsers = $thing->usages()
            ->distinct()
            ->pluck('user_id')
            ->map(fn($id) => User::find($id))
            ->filter();
        
        foreach ($assignedUsers as $user) {
            $user->notify(new ThingDescriptionUpdated($thing, null));
        }

        return redirect()->route('things.show', $thing)
            ->with('success', 'Актуальное описание изменено');
    }
}
