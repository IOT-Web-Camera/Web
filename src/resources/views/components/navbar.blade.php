<nav class="navbar is-fixed-top">

    {{-- Zone gauche --}}
    <div class="navbar-brand">
        {{-- Tu peux mettre un bouton menu plus tard --}}
    </div>

    <div class="navbar-menu is-active">

        <a href="/" class="navbar-start navbar-item has-text-centered">
            Sodium Vision
        </a>

        {{-- Zone droite --}}
        <div class="navbar-end">
            @guest
                <a href="{{ route('login') }}" class="navbar-item">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="navbar-item">
                    Inscription
                </a>
            @endguest

            @auth
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </a>

                    <div class="navbar-dropdown is-right">
                        <a href="{{ route('dashboard') }}" class="navbar-item">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="navbar-item">Profil</a>

                        <hr class="navbar-divider">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="navbar-item button is-white is-fullwidth">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

    </div>
</nav>
