@extends('layouts.app')
@section('title', 'Все вещи')
@section('content')
<h2 class="mb-4">Все вещи</h2>
<a href="{{ route('things.create') }}" class="btn btn-success mb-3">+ Создать вещь</a>

@forelse($things as $thing)
    @if(auth()->check() && auth()->id() === $thing->master_id)
        <div class="card mb-2 border-success bg-light">
    @else
        <div class="card mb-2">
    @endif
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-1">
                        {{ $thing->name }}
                        @if(auth()->check() && auth()->id() === $thing->master_id)
                            <span class="badge bg-success">Ваша вещь</span>
                        @endif
                        @if(auth()->check())
                            @php
                                $isAssigned = $thing->usages()->where('user_id', auth()->id())->exists();
                            @endphp
                            @if($isAssigned)
                                <span class="badge bg-info">Вам назначена</span>
                            @endif
                        @endif
                    </h6>
                    <small class="text-muted">Владелец: <strong>{{ $thing->master->name }}</strong></small>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('things.show', $thing) }}" class="btn btn-sm btn-info">Просмотр</a>
                    @can('update', $thing)<a href="{{ route('things.edit', $thing) }}" class="btn btn-sm btn-warning">Редактировать</a>@endcan
                    @can('assign', $thing)<a href="{{ route('things.assign', $thing) }}" class="btn btn-sm btn-primary">Выдать</a>@endcan
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Вещи не найдены.</div>
@endforelse
{{ $things->links('pagination::bootstrap-5') }}
@endsection

