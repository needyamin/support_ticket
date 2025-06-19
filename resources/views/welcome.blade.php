<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Ticket System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light text-dark d-flex flex-column min-vh-100">
    <div class="container d-flex flex-column justify-content-center align-items-center flex-grow-1 py-5">
        <div class="text-center mb-5">
            <i class="bi bi-ticket-perforated display-1 text-primary mb-3"></i>
            <h1 class="fw-bold mb-3">Welcome to E-Ticket Support System</h1>
            <p class="lead text-muted mb-4">Easily create, track, and manage your support tickets with a modern, user-friendly interface.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg px-4">
                            <i class="bi bi-person-plus me-2"></i> Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    <footer class="text-center text-muted py-3 small mt-auto">
        &copy; {{ date('Y') }} E-Ticket System. All rights reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
