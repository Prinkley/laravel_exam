@extends('layouts.app')
@section('title', 'Выданные вещи')
@section('content')
<h2 class="mb-4">Выданные вещи</h2>
<div class="alert alert-info">Вещи, которые вы выдали другим пользователям</div>
@forelse($things as $thing)
    <div class="card mb-2">
        <div class="card-body p-3">
            <h6>{{ $thing->name }}</h6>
            <small class="text-muted">
                @foreach($thing->usages as $usage)
                    Выдана {{ $usage->user->name }} · {{ $usage->amount }} ед.
                @endforeach
            </small>
            <div class="float-end">
                <a href="{{ route('things.show', $thing) }}" class="btn btn-sm btn-info">Просмотр</a>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Вы ещё ничего не выдавали.</div>
@endforelse
{{ $things->links('pagination::bootstrap-5') }}
@endsection
