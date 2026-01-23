@extends('layouts.app')
@section('title', 'Редактировать место')
@section('content')
<h2 class="mb-4">✏️ Редактировать место: {{ $place->name }}</h2>
<div class="row">
    <div class="col-md-6">
        <form action="{{ route('places.update', $place) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Название *</label>
                <input type="text" name="name" class="form-control" value="{{ $place->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control">{{ $place->description }}</textarea>
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="repair" value="0">
                <input type="checkbox" name="repair" value="1" class="form-check-input" id="repair" {{ $place->repair ? 'checked' : '' }}>
                <label class="form-check-label" for="repair">Это место для ремонта?</label>
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="work" value="0">
                <input type="checkbox" name="work" value="1" class="form-check-input" id="work" {{ $place->work ? 'checked' : '' }}>
                <label class="form-check-label" for="work">Это место для работы?</label>
            </div>
            <button type="submit" class="btn btn-primary">💾 Обновить</button>
            <a href="{{ route('places.show', $place) }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>
@endsection
