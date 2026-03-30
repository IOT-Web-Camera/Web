<nav class="navbar is-fixed-top has-shadow sv-navbar" role="navigation" aria-label="main navigation">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{ url('/') }}">
                <strong class="sv-brand">Sodium Vision</strong>
            </a>

            <!-- burger -->
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="mainNavbar">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="mainNavbar" class="navbar-menu">
            <div class="navbar-start">
                @auth
                    <a href="/dashboard" class="navbar-item">Dashboard</a>
                @endauth
            </div>

            <div class="navbar-end">
                @guest
                    <div class="navbar-item sv-auth-buttons">
                        <div class="buttons">
                            <a href="{{ route('register') }}" class="button">Inscription</a>
                            <a href="{{ route('login') }}" class="button">Connexion</a>
                        </div>
                    </div>
                @endguest

                @auth
                        <div class="navbar-item has-dropdown is-hoverable is-align-items-center is-arrowless sv-user-dropdown">
                            <a class="navbar-link sv-user-link" aria-haspopup="true" aria-expanded="false" role="button">
                                <span class="sv-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </a>

                        <div class="navbar-dropdown is-right">
                            <a href="{{ route('dashboard') }}" class="navbar-item">
                                <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                                &nbsp;Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="navbar-item">
                                <span class="icon"><i class="fa-solid fa-user"></i></span>
                                &nbsp;Profil
                            </a>
                            <hr class="navbar-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="button is-white is-fullwidth navbar-item">
                                    <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                                    &nbsp;Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const burgers = Array.from(document.querySelectorAll('.navbar-burger'));

            burgers.forEach(burger => {
                burger.addEventListener('click', () => {
                    const target = document.getElementById(burger.dataset.target);

                    burger.classList.toggle('is-active');
                    target.classList.toggle('is-active');
                });
            });
        });
    </script>
@endpush
