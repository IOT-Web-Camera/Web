@extends('layouts.app')
@section('title', $camera->label)

@push('styles')
    <style>
        .video-container iframe {
            width: 100%;
            aspect-ratio: 16 / 9;
            border: none;
            border-radius: 6px;
            display: block;
            background: #111;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #EF4444;
            display: inline-block;
            margin-right: 6px;
            transition: background 0.3s;
        }
        .control-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .control-group-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--sodium-muted);
            margin-bottom: 0.5rem;
        }
        .cmd-button {
            border: 1px solid var(--sodium-border);
            background: #fff;
            border-radius: 6px;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'DM Sans', sans-serif;
            color: var(--sodium-text);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .cmd-button:hover {
            border-color: #9CA3AF;
            background: var(--sodium-bg);
        }
        .cmd-button.is-active-cmd {
            border-color: var(--sodium-blue);
            background: var(--sodium-blue-light);
            color: var(--sodium-blue);
        }
        .cmd-button.is-danger-cmd {
            border-color: #FCA5A5;
            color: #DC2626;
        }
        .cmd-button.is-danger-cmd:hover {
            background: #FEF2F2;
            border-color: #DC2626;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid var(--sodium-border);
            font-size: 0.875rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .key { color: var(--sodium-muted); }
        .info-row .val { font-weight: 500; }
        .feedback-toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: #111827;
            color: #fff;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s;
            pointer-events: none;
            z-index: 999;
        }
        .feedback-toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper">

        {{-- Retour + titre --}}
        <div class="mb-5">
            <a href="{{ route('dashboard') }}" class="button is-light is-small mb-3">
                <span class="icon"><i class="fas fa-arrow-left"></i></span>
                <span>Dashboard</span>
            </a>
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <h1 class="title" style="margin-bottom: 0.25rem;">{{ $camera->label }}</h1>
                    <p style="font-size: 0.8rem; color: var(--sodium-muted);">
                        <span class="mono">{{ $camera->name }}</span>
                    </p>
                </div>
                <span style="display: flex; align-items: center; font-size: 0.8rem; font-weight: 500;">
                <span id="status-dot" class="status-dot animate-pulse"></span>
                <span id="status-text">Connexion...</span>
            </span>
            </div>
        </div>

        <div class="columns">
            {{-- Colonne vidéo --}}
            <div class="column is-8">
                <div class="box p-2 mb-4">
                    <iframe
                        src="http://{{ $serverIp }}:8889/{{ $camera->name }}/"
                        allowfullscreen>
                    </iframe>
                </div>

                {{-- Infos flux --}}
                <div class="box">
                    <p class="control-group-label">Informations du flux</p>
                    <div class="info-row">
                        <span class="key">Protocole</span>
                        <span class="val">WebRTC (Low Latency)</span>
                    </div>
                    <div class="info-row">
                        <span class="key">Source</span>
                        <span class="val mono">{{ $serverIp }}:8889</span>
                    </div>
                    <div class="info-row">
                        <span class="key">Path</span>
                        <span class="val mono">{{ $camera->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="key">Statut</span>
                        <span class="val">
                        <span class="tag {{ $camera->is_active ? 'is-success' : 'is-danger' }}">
                            {{ $camera->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </span>
                    </div>
                </div>
            </div>

            {{-- Colonne contrôles --}}
            <div class="column is-4">
                <div class="box">
                    <p class="control-group-label" style="margin-bottom: 1.25rem;">
                        <i class="fas fa-gamepad" style="margin-right: 4px;"></i> Contrôle
                    </p>

                    <div class="control-section">

                        {{-- Projecteur --}}
                        <div>
                            <p class="control-group-label">Projecteur</p>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="cmd-button" onclick="sendOrder('led', {state: 'ON'})">
                                    <i class="fas fa-lightbulb"></i> ON
                                </button>
                                <button class="cmd-button" onclick="sendOrder('led', {state: 'OFF'})">
                                    OFF
                                </button>
                            </div>
                        </div>

                        {{-- Orientation --}}
                        <div>
                            <p class="control-group-label">Orientation</p>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="cmd-button" onclick="sendOrder('move', {direction: 'left'})">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="cmd-button" onclick="sendOrder('move', {direction: 'center'})">
                                    Centrer
                                </button>
                                <button class="cmd-button" onclick="sendOrder('move', {direction: 'right'})">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Système --}}
                        <div>
                            <p class="control-group-label">Système</p>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button class="cmd-button" onclick="location.reload()">
                                    <i class="fas fa-sync"></i> Rafraîchir
                                </button>
                                <button class="cmd-button is-danger-cmd"
                                        onclick="if(confirm('Redémarrer la caméra ?')) sendOrder('reboot', {})">
                                    <i class="fas fa-power-off"></i> Reboot
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Toast feedback --}}
    <div id="toast" class="feedback-toast"></div>
@endsection
@push('scripts')
    <script>
        async function sendOrder(action, payload = {}) {
            const url = `/dashboard/cameras/{{ $camera->name }}/cmd/${action}`;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                showToast(res.ok ? '✓ Ordre envoyé' : '✗ Erreur serveur');
            } catch (e) {
                showToast('✗ Impossible de contacter le serveur');
            }
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 2500);
        }

        // Statut de connexion (ping bridge)
        async function checkBridgeStatus() {
            try {
                const res = await fetch('/api/bridge/status', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const dot = document.getElementById('status-dot');
                const txt = document.getElementById('status-text');

                if (res.ok) {
                    dot.style.background = '#10B981';
                    txt.textContent = 'Connecté';
                } else {
                    dot.style.background = '#EF4444';
                    txt.textContent = 'Déconnecté';
                }
            } catch {
                const dot = document.getElementById('status-dot');
                const txt = document.getElementById('status-text');
                dot.style.background = '#EF4444';
                txt.textContent = 'Déconnecté';
            }
        }

        checkBridgeStatus();
        setInterval(checkBridgeStatus, 5000);
    </script>
@endpush
