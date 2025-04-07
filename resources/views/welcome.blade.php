<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>

        <!-- Bootstrap 5 CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light text-dark d-flex flex-column justify-content-center align-items-center min-vh-100 p-3 p-lg-5">

        <header class="w-100 mb-4" style="max-width: 960px;">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-center gap-2">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="btn btn-outline-dark btn-sm"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="btn btn-outline-secondary btn-sm"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="btn btn-outline-dark btn-sm"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        @if (Route::has('login'))
            <div style="height: 58px;" class="d-none d-lg-block"></div>
        @endif

        <!-- Optional Bootstrap 5 JS (for dropdowns, modals, etc.) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
