@extends('layouts.app')
@section('title', 'Места')
@section('content')
<h2 class="mb-4">📍 Места хранения</h2>
@auth
    @if(auth()->user()->isAdmin())
        <a href="{{ route('places.create') }}" class="btn btn-success mb-3">+ Создать место</a>
    @endif
@endauth

@forelse($places as $place)
    <div class="card mb-2">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-1">{{ $place->name }}</h6>
                    <small class="text-muted">{{ $place->description }}</small>
                    @if($place->repair || $place->work)
                        <br><small class="badge" style="background-color: #ff9800;">
                            @if($place->repair) 🔧 Ремонт @endif
                            @if($place->work) ⚙️ Работа @endif
                        </small>
                    @endif
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('places.show', $place) }}" class="btn btn-sm btn-info">Просмотр</a>
                    @can('update', $place)
                        <a href="{{ route('places.edit', $place) }}" class="btn btn-sm btn-warning">Редактировать</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Места не найдены.</div>
@endforelse
{{ $places->links('pagination::bootstrap-5') }}
@endsection
