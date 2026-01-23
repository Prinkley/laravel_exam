@extends('layouts.app')
@section('title', 'Архив удаленных вещей')
@section('content')
<h2 class="mb-4">📦 Архив удаленных вещей</h2>

@forelse($archived as $item)
    <div class="card mb-2" style="{{ $item->is_restored ? 'opacity: 0.7; background-color: #f0f0f0;' : '' }}">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-1">
                        {{ $item->name }}
                        @if($item->is_restored)
                            <span class="badge bg-success"> Восстановлена</span>
                        @else
                            <span class="badge bg-danger">🗑️ В архиве</span>
                        @endif
                    </h6>
                    <small class="text-muted">
                        Хозяин: <strong>{{ $item->master_name }}</strong><br>
                        @if($item->last_user_name)
                            Последний пользователь: <strong>{{ $item->last_user_name }}</strong><br>
                        @endif
                        @if($item->place_name)
                            Место: <strong>{{ $item->place_name }}</strong>
                        @endif
                    </small>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('archive.show', $item) }}" class="btn btn-sm btn-info">Просмотр</a>
                    @if(!$item->is_restored)
                        <form action="{{ route('archive.restore', $item) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success"> Восстановить</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Архив пуст.</div>
@endforelse

{{ $archived->links('pagination::bootstrap-5') }}
@endsection
