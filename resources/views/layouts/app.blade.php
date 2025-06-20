<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        .modern-navbar {
            background: linear-gradient(90deg, #f44040 0%,#019b8d 100%);
        }
        .modern-navbar .navbar-brand, .modern-navbar .nav-link, .modern-navbar .dropdown-toggle {
            color: #fff !important;
        }
        .modern-navbar .nav-link.active, .modern-navbar .dropdown-menu a:hover {
            color: #4f8cff !important;
            background: #fff !important;
        }
        .modern-footer {
            background: #f8f9fa;
            border-top: 1px solid #e3e6ea;
            color: #6c757d;
            font-size: 0.97rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div id="app" class="flex-grow-1 d-flex flex-column">
        <nav class="navbar navbar-expand-md modern-navbar shadow-sm py-2">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ url('/') }}">
                    <i class="bi bi-ticket-perforated fs-3"></i>
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> {{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> {{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle fs-5"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow rounded-3 mt-2" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell fs-5"></i>
                                    @php $unread = Auth::user()->unreadNotifications()->count(); @endphp
                                    @if($unread > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread }}</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow rounded-3 mt-2 p-0" aria-labelledby="notificationDropdown" style="min-width: 320px; max-width: 350px;">
                                    <li class="dropdown-header bg-light fw-bold py-2 px-3 border-bottom">Notifications</li>
                                    @forelse(Auth::user()->unreadNotifications->take(10) as $notification)
                                        <li>
                                            <a href="{{ route('etricket.show', $notification->data['ticket_id'] ?? 0) }}" class="dropdown-item py-2 px-3 small">
                                                <div class="fw-semibold">{{ $notification->data['subject'] ?? 'Ticket' }}</div>
                                                <div class="text-muted small">{{ $notification->data['message'] ?? '' }}</div>
                                                <div class="text-end text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                                            </a>
                                        </li>
                                    @empty
                                        <li><span class="dropdown-item text-muted small">No new notifications.</span></li>
                                    @endforelse
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="dropdown-item text-center small text-primary" type="submit">
                                                <i class="bi bi-check2-all me-1"></i> Mark all as read
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4 flex-grow-1">
            @yield('content')
        </main>
    </div>
    <footer class="modern-footer py-3 mt-auto">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <span class="fw-semibold">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}</span> &mdash; All rights reserved.
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ url('/') }}" class="text-decoration-none text-secondary me-3"><i class="bi bi-house-door"></i> Home</a>
                <a href="#" class="text-decoration-none text-secondary me-3"><i class="bi bi-info-circle"></i> About</a>
                <a href="#" class="text-decoration-none text-secondary"><i class="bi bi-envelope"></i> Contact</a>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
