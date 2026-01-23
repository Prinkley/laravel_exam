@extends('layouts.app')
@section('title', $thing->name)
@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2>{{ $thing->name }}</h2>
        <p class="text-muted">Владелец: <strong>{{ $thing->master->name }}</strong></p>
    </div>
    <div class="col-md-4 text-end">
        @can('update', $thing)<a href="{{ route('things.edit', $thing) }}" class="btn btn-warning">Редактировать</a>@endcan
        @can('assign', $thing)<a href="{{ route('things.assign', $thing) }}" class="btn btn-primary">Выдать пользователю</a>@endcan
        @can('delete', $thing)
            <form action="{{ route('things.destroy', $thing) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
            </form>
        @endcan
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6>Описание</h6>
        <p>{{ $thing->description ?? 'Нет описания' }}</p>
        @if($thing->warranty)
            <h6 class="mt-3">Гарантия</h6>
            <p>{{ $thing->warranty->format('Y-m-d') }}</p>
        @endif
        @can('update', $thing)
            <button class="btn btn-sm btn-info mt-2" data-bs-toggle="modal" data-bs-target="#addDescriptionModal">
                ➕ Добавить описание
            </button>
        @endcan
    </div>
</div>

{{-- Модальное окно для добавления описания --}}
@can('update', $thing)
<div class="modal fade" id="addDescriptionModal" tabindex="-1" aria-labelledby="addDescriptionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDescriptionLabel">➕ Добавить новое описание</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('things.storeDescription', $thing) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Новое описание</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required placeholder="Введите новое описание..."></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Добавить описание</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

{{-- Задание 13: История описаний --}}
@php
    $thing->load('descriptions');
@endphp
@if($thing->descriptions->count() > 0)
    <div class="card mb-3">
        <div class="card-body">
            <h6>📝 История описаний</h6>
            @if($thing->descriptions->count() > 1)
                <ul class="list-group mb-3">
                    @foreach($thing->descriptions as $desc)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div style="flex-grow: 1;">
                                <p class="mb-0">{{ $desc->description ?: '(пусто)' }}</p>
                                <small class="text-muted">{{ $desc->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                            <div>
                                @if($desc->is_active)
                                    <span class="badge bg-success me-2">Текущее</span>
                                @else
                                    @can('update', $thing)
                                        <form action="{{ route('things.setActiveDescription', $thing) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="description_id" value="{{ $desc->id }}">
                                            <button type="submit" class="btn btn-xs btn-outline-secondary" title="Выбрать как актуальное">
                                                ✓ Выбрать
                                            </button>
                                        </form>
                                    @endcan
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">{{ $thing->descriptions->first()?->description ?? '(пусто)' }}</p>
                <small class="text-muted">Создано: {{ $thing->descriptions->first()?->created_at->format('d.m.Y H:i') }}</small>
            @endif
        </div>
    </div>
@endif

<h5>Выданные</h5>
@forelse($thing->usages as $usage)
    <div class="card mb-2">
        <div class="card-body p-2">
            <strong>{{ $usage->user->name }}</strong> · {{ $usage->amount }} {{ $usage->dimension?->abbreviation ?? 'ед.' }} · {{ $usage->place->name }}
        </div>
    </div>
@empty
    <div class="alert alert-info">Пока никому не выдана.</div>
@endforelse
@endsection
