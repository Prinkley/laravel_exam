@extends('layouts.app')
@section('title', $place->name)
@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2>{{ $place->name }}</h2>
    </div>
    <div class="col-md-4 text-end">
        @can('update', $place)
            <a href="{{ route('places.edit', $place) }}" class="btn btn-warning">Редактировать</a>
        @endcan
        @can('delete', $place)
            <form action="{{ route('places.destroy', $place) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
            </form>
        @endcan
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <p>{{ $place->description ?? 'Нет описания' }}</p>
        @if($place->repair || $place->work)
            <h6>Флаги:</h6>
            @if($place->repair) <span class="badge bg-warning">🔧 Ремонт</span> @endif
            @if($place->work) <span class="badge bg-info">⚙️ Работа</span> @endif
        @endif
    </div>
</div>

<h5>Вещи здесь</h5>
@forelse($place->usages as $usage)
    <div class="card mb-2">
        <div class="card-body p-2">
            <strong>{{ $usage->thing->name }}</strong> ({{ $usage->user->name }}) · {{ $usage->amount }} ед.
        </div>
    </div>
@empty
    <div class="alert alert-info">В этом месте нет вещей.</div>
@endforelse
@endsection
