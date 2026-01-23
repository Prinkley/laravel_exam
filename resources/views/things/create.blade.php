@extends('layouts.app')
@section('title', 'Создать вещь')
@section('content')
<h2 class="mb-4">➕ Создать новую вещь</h2>
<div class="row">
    <div class="col-md-6">
        <form action="{{ route('things.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Название *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Дата гарантии</label>
                <input type="date" name="warranty" class="form-control @error('warranty') is-invalid @enderror" value="{{ old('warranty') }}">
                @error('warranty')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-success">Создать</button>
            <a href="{{ route('things.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>
@endsection
