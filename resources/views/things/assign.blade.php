@extends('layouts.app')
@section('title', 'Выдать: ' . $thing->name)
@section('content')
<h2 class="mb-4">Выдать: {{ $thing->name }}</h2>
<div class="row">
    <div class="col-md-6">
        <form action="{{ route('things.storeAssignment', $thing) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Пользователь *</label>
                <select name="user_id" class="form-control" required>
                    <option>-- Выберите пользователя --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Место *</label>
                <select name="place_id" class="form-control" required>
                    <option>-- Выберите место --</option>
                    @foreach($places as $place)
                        <option value="{{ $place->id }}">{{ $place->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Количество *</label>
                <input type="number" name="amount" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Единица</label>
                <select name="dimension_id" class="form-control">
                    <option value="">-- Без единицы --</option>
                    @foreach(\App\Models\Dimension::all() as $dim)
                        <option value="{{ $dim->id }}">{{ $dim->name }} ({{ $dim->abbreviation }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Выдать</button>
            <a href="{{ route('things.show', $thing) }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>
@endsection
