<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.svg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">

    @stack('styles')
</head>

<body>

@include('components.sidebar')

<div class="main-content" style="margin-left: 260px;">
    @include('components.navbar')

    <section class="section">
        <div class="container">
            @yield('content')
        </div>
    </section>
</div>

@stack('scripts')
</body>
</html>
