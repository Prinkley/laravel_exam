@extends('layouts.app')
@section('title', 'Детали архивированной вещи')
@section('content')
<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">
            📦 {{ $archived->name }}
            @if($archived->is_restored)
                <span class="badge bg-success">✅ Восстановлена</span>
            @else
                <span class="badge bg-danger">🗑️ В архиве</span>
            @endif
        </h2>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Информация о вещи</h5>
                <p><strong>Название:</strong> {{ $archived->name }}</p>
                <p><strong>Описание:</strong> {{ $archived->description ?: '(не указано)' }}</p>
                @if($archived->warranty)
                    <p><strong>Гарантия до:</strong> {{ $archived->warranty->format('d.m.Y') }}</p>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">История</h5>
                <p><strong>Хозяин:</strong> {{ $archived->master_name }}</p>
                @if($archived->last_user_name)
                    <p><strong>Последний пользователь:</strong> {{ $archived->last_user_name }}</p>
                @endif
                @if($archived->place_name)
                    <p><strong>Место хранения:</strong> {{ $archived->place_name }}</p>
                @endif
                <p><strong>Удалена:</strong> {{ $archived->created_at->format('d.m.Y H:i') }}</p>
                @if($archived->is_restored)
                    <p><strong>Восстановлена:</strong> {{ $archived->restored_at->format('d.m.Y H:i') }}</p>
                @endif
            </div>
        </div>

        @if(!$archived->is_restored)
            <div class="alert alert-warning">
                ⚠️ Эта вещь может быть восстановлена. При восстановлении вы станете её новым хозяином.
            </div>
            <form action="{{ route('archive.restore', $archived) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">🔄 Восстановить вещь</button>
                <a href="{{ route('archive.index') }}" class="btn btn-secondary btn-lg">Назад</a>
            </form>
        @else
            <div class="alert alert-info">
                ✅ Эта вещь была восстановлена {{ $archived->restored_at->format('d.m.Y в H:i') }}
            </div>
            <a href="{{ route('archive.index') }}" class="btn btn-secondary">Назад</a>
        @endif
    </div>
</div>
@endsection
