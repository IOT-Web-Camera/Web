<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — Sodium Vision</title>

    <!-- Bulma -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.svg') }}">

    <meta name="color-scheme" content="light only">
    <style>
        :root { color-scheme: light only; }

        /* Forcer Bulma en light même si prefers-color-scheme: dark */
        html {
            background-color: #fff !important;
            color: #363636 !important;
        }
    </style>

    <!-- Ton CSS global -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>


<body style="min-height: 100vh; display: flex; flex-direction: column;">

<div class="layout">

    @auth
        @include('components.sidebar')
    @endauth


<main class="main-content" style="flex:1; min-height:100vh; padding:2rem">
    @include('components.navbar')

    <section class="section">
        <div class="container">
            @yield('content')
        </div>
    </section>
</main>

</div>

@include('components.footer')

@stack('scripts')

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        const isOpen  = sidebar.classList.toggle('is-open');
        overlay.style.display = isOpen ? 'block' : 'none';
    }
</script>

</body>


</html>

