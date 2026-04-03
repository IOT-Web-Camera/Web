<aside class="menu sidebar">
    <p class="menu-label">Général</p>
    <ul class="menu-list">
        <li>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
        </li>
    </ul>

    <p class="menu-label">Caméras</p>
    <ul class="menu-list">
        <li>
            <a href="{{ route('cameras.index') }}"
               class="{{ request()->routeIs('cameras.index') ? 'is-active' : '' }}">
                <i class="fa-solid fa-video"></i> Liste
            </a>
        </li>
        <li>
            <a href="{{ route('cameras.create') }}"
               class="{{ request()->routeIs('cameras.create') ? 'is-active' : '' }}">
                <i class="fa-solid fa-plus"></i> Ajouter
            </a>
        </li>
    </ul>

    <p class="menu-label">Historique</p>
    <ul class="menu-list">
        <li>
            <a href="/events">
                <i class="fa-solid fa-clock-rotate-left"></i> Événements
            </a>
        </li>
    </ul>

    <p class="menu-label">Compte</p>
    <ul class="menu-list">
        <li>
            <a href="{{ route('profile.edit') }}"
               class="{{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
                <i class="fa-solid fa-user"></i> Profil
            </a>
        </li>
    </ul>
</aside>

@push('styles')
    <style>
        .sidebar {
            width: 240px;
            background-color: #f5f5f5;
            padding: 2rem 1rem;
            overflow-y: auto;
        }

        /* Desktop → sidebar prend de la place */
        @media (min-width: 769px) {
            .layout {
                display: flex;
            }

            .sidebar {
                position: relative;
                height: calc(100vh - 3.25rem);
            }

            .main-content {
                margin-left: 0;
                flex: 1;
            }
        }

        /* Mobile → sidebar overlay */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 3.25rem;
                bottom: 0;
                left: 0;
                transform: translateX(-100%);
                z-index: 100;
                transition: transform 0.3s ease;
            }

            .sidebar.is-open {
                transform: translateX(0);
            }
        }
    </style>
@endpush
