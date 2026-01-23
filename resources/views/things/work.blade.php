@extends('layouts.app')
@section('title', 'В работе')
@section('content')
<h2 class="mb-4">Вещи в работе</h2>
@forelse($things as $thing)
    <div class="card mb-2 thing-work">
        <div class="card-body p-3">
            <h6>{{ $thing->name }}</h6>
            <small>Владелец: {{ $thing->master->name }}</small>
            <div class="float-end">
                <a href="{{ route('things.show', $thing) }}" class="btn btn-sm btn-info">Просмотр</a>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Вещей в работе нет.</div>
@endforelse
{{ $things->links('pagination::bootstrap-5') }}
@endsection
