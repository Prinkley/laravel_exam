@extends('layouts.app')

@section('title', 'Добро пожаловать - Хранилище вещей')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h1 class="mb-4">📦 Добро пожаловать в Хранилище вещей</h1>
        <p class="lead">Организуйте свои вещи и поделитесь ими с друзьями</p>
        
        @guest
            <div class="alert alert-info">
                <h5>Начните работу!</h5>
                <p>Пожалуйста <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a>, чтобы использовать наш сервис.</p>
            </div>
        @endguest

        @auth
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Привет, {{ auth()->user()->name }}! 👋</h5>
                    <p class="card-text">Добро пожаловать в вашу систему управления хранилищем.</p>
                    <a href="{{ route('things.index') }}" class="btn btn-primary">Просмотреть все вещи</a>
                    <a href="{{ route('things.create') }}" class="btn btn-success">Создать новую вещь</a>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Ваши вещи</h6>
                            <h3>{{ auth()->user()->things()->count() }}</h3>
                            <a href="{{ route('things.my') }}" class="btn btn-sm btn-outline-primary">Просмотр</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Выданные вещи</h6>
                            <h3>{{ auth()->user()->usages()->count() }}</h3>
                            <a href="{{ route('things.used') }}" class="btn btn-sm btn-outline-primary">Просмотр</a>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Возможности</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li>✅ Создание и управление вещами</li>
                    <li>✅ Организация мест хранения</li>
                    <li>✅ Поделиться вещами с друзьями</li>
                    <li>✅ Отслеживание местоположения вещей</li>
                    <li>✅ Права доступа пользователей</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
