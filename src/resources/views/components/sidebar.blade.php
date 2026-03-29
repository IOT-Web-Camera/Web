<aside class="menu sidebar">
    <p class="menu-label">Général</p>
    <ul class="menu-list">
        <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Dashboard</a></li>
    </ul>

    <p class="menu-label">Caméras</p>
    <ul class="menu-list">
        <li><a href="{{ route('cameras.index') }}" class="{{ request()->routeIs('cameras.*') ? 'is-active' : '' }}">Liste</a></li>
        <li><a href="{{ route('cameras.create') }}" class="{{ request()->routeIs('cameras.create') ? 'is-active' : '' }}">Ajouter</a></li>
    </ul>

    <p class="menu-label">Compte</p>
    <ul class="menu-list">
        <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">Profil</a></li>
    </ul>
</aside>

@push('styles')
    <style>
        .sidebar {
            width: 240px;
            position: fixed;
            top: 3.25rem; /* hauteur navbar */
            bottom: 0;
            left: 0;
            background-color: #f5f5f5;
            padding: 2rem 1rem;
            overflow-y: auto;
            z-index: 20; /* au-dessus du contenu principal */
        }

        .sidebar .menu-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4a4a4a;
            margin-top: 1.5rem;
        }

        .sidebar .menu-list li a {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            color: #363636;
            display: block;
            transition: background 0.2s;
        }

        .sidebar .menu-list li a:hover,
        .sidebar .menu-list li a.is-active {
            background-color: #10b981;
            color: white;
        }
    </style>
@endpush
