<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — Sodium Vision</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">

    @stack('styles')
</head>

<body style="background: var(--sodium-bg);">

<main class="section">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
