@extends('layouts.app')
@section('title', 'Создать место')
@section('content')
<h2 class="mb-4">➕ Создать новое место</h2>
<div class="row">
    <div class="col-md-6">
        <form action="{{ route('places.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Название *</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="repair" value="0">
                <input type="checkbox" name="repair" value="1" class="form-check-input" id="repair">
                <label class="form-check-label" for="repair">Это место для ремонта?</label>
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="work" value="0">
                <input type="checkbox" name="work" value="1" class="form-check-input" id="work">
                <label class="form-check-label" for="work">Это место для работы?</label>
            </div>
            <button type="submit" class="btn btn-success">Создать</button>
            <a href="{{ route('places.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>
@endsection
