@extends('layouts.app')
@section('title', 'Мои вещи')
@section('content')
<h2 class="mb-4">Мои вещи</h2>
<div class="alert alert-info">Это ваши вещи</div>
@forelse($things as $thing)
    <div class="card mb-2">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-1">{{ $thing->name }}</h6>
                    <small class="text-muted">{{ Str::limit($thing->description, 60) }}</small>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('things.show', $thing) }}" class="btn btn-sm btn-info">Просмотр</a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">У вас пока нет вещей. <a href="{{ route('things.create') }}">Создайте одну</a></div>
@endforelse
{{ $things->links('pagination::bootstrap-5') }}
@endsection
