<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — Sodium Vision</title>

    <!-- Bulma -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">

    <!-- Ton CSS global -->
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>

<body style="min-height: 100vh; display: flex; flex-direction: column;">

@include('components.navbar')

<div style="display: flex; flex: 1;">
    @auth
        @include('components.sidebar')
    @endauth

    <main class="section" style="flex: 1; min-height: 100vh; padding-left: 1rem; padding-right: 1rem;">
        <div class="container">
            @yield('content')
        </div>
    </main>
</div>

@include('components.footer')

@stack('scripts')
</body>
</html>
