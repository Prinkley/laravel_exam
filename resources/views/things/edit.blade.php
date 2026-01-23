@extends('layouts.app')
@section('title', 'Редактировать вещь')
@section('content')
<h2 class="mb-4">Редактировать вещь: {{ $thing->name }}</h2>
<div class="row">
    <div class="col-md-6">
        <form action="{{ route('things.update', $thing) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Название *</label>
                <input type="text" name="name" class="form-control" value="{{ $thing->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control">{{ $thing->description }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Дата гарантии</label>
                <input type="date" name="warranty" class="form-control" value="{{ $thing->warranty?->format('Y-m-d') }}">
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
            <a href="{{ route('things.show', $thing) }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>
@endsection
