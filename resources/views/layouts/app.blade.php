<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Хранилище вещей')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin-top: 80px; }
        .navbar-brand { font-weight: bold; font-size: 1.3rem; }
        .card { margin-bottom: 20px; }
        .thing-item { padding: 15px; border: 1px solid #ddd; margin: 10px 0; border-radius: 5px; }
        .thing-master { background-color: #e8f5e9; }
        .thing-repair { background-color: #fff3e0; }
        .thing-work { background-color: #e3f2fd; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('welcome') }}">📦 Хранилище</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        {{-- Задание 21: Blade директива для выделения активной вкладки --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @activeRoute('things') active @endactiveRoute" href="#" id="thingsDropdown" role="button" data-bs-toggle="dropdown">
                                📋 Вещи
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item @activeRoute('things.index') active @endactiveRoute" href="{{ route('things.index') }}">Все вещи</a></li>
                                <li><a class="dropdown-item @activeRoute('things.my') active @endactiveRoute" href="{{ route('things.my') }}">Мои вещи</a></li>
                                <li><a class="dropdown-item @activeRoute('things.repair') active @endactiveRoute" href="{{ route('things.repair') }}">На ремонте</a></li>
                                <li><a class="dropdown-item @activeRoute('things.work') active @endactiveRoute" href="{{ route('things.work') }}">В работе</a></li>
                                <li><a class="dropdown-item @activeRoute('things.used') active @endactiveRoute" href="{{ route('things.used') }}">Выданные</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item @activeRoute('things.create') active @endactiveRoute" href="{{ route('things.create') }}">+ Новая вещь</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @activeRoute('places') active @endactiveRoute" href="{{ route('places.index') }}">📍 Места</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @activeRoute('archive') active @endactiveRoute" href="{{ route('archive.index') }}">🗂️ Архив</a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link btn btn-warning btn-sm ms-2 @activeRoute('places.create') active @endactiveRoute" href="{{ route('places.create') }}">⚙️ Администратор</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                👤 {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Выход</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong> Ошибка!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
