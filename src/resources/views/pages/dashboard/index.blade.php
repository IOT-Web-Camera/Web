@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
    <style>
        .video-container { position: relative; background: black; }
        .video-container canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: none; }
    </style>
@endpush

@section('content')
    <div class="page-wrapper">

        {{-- Header --}}
        <div class="page-header">
            <div class="is-flex is-justify-content-space-between is-align-items-center" style="flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 class="title">Dashboard</h1>
                    <p class="subtitle">Vue en direct de vos flux caméras</p>
                </div>
                <a href="{{ route('cameras.create') }}" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Ajouter une caméra</span>
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="columns mb-5">
            <div class="column is-4">
                <div class="box stat-card">
                    <div class="stat-number">{{ count($activeCameras) }}</div>
                    <div class="stat-label">Caméras en ligne</div>
                </div>
            </div>
            <div class="column is-4">
                <div class="box stat-card">
                    <div class="stat-number">{{ $totalUserCameras ?? 0 }}</div>
                    <div class="stat-label">Caméras totales</div>
                </div>
            </div>
            <div class="column is-4">
                <div class="box stat-card">
                    <div class="stat-number" style="color: #10B981;">
                        {{ ($totalUserCameras ?? 0) > 0 ? round((count($activeCameras) / $totalUserCameras) * 100) : 0 }}%
                    </div>
                    <div class="stat-label">Disponibilité</div>
                </div>
            </div>
        </div>

        {{-- Flux vidéo --}}
        <div id="camera-grid">
            <div class="columns is-multiline">
                @foreach($activeCameras as $cam)
                    <div class="column is-4">
                        <div class="box camera-card p-0" style="overflow: hidden;">

                            {{-- Header --}}
                            <div class="is-flex is-justify-content-space-between is-align-items-center"
                                 style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--sodium-border);">
                        <span style="font-weight: 500; font-size: 0.875rem;">
                            <span class="live-dot animate-pulse"></span>
                            {{ $cam->label }}
                        </span>
                                <span class="tag is-info">
                            <span class="mono">{{ $cam->name }}</span>
                        </span>
                            </div>

                            {{-- Iframe vidéo --}}
                            <div style="width: 100%; height: 250px; background: black;">
                                <iframe
                                    src="http://{{ $serverIp }}:8889/{{ $cam->name }}/"
                                    style="width: 100%; height: 100%; border: 0;"
                                    allowfullscreen>
                                </iframe>
                            </div>

                            {{-- Footer --}}
                            <div style="padding: 0.75rem 1rem;">
                                <a href="{{ route('cameras.show', $cam->name) }}"
                                   class="button is-light is-small is-fullwidth">
                                    <span class="icon"><i class="fas fa-sliders"></i></span>
                                    <span>Contrôler</span>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>




    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            async function checkActiveCameras() {
                try {
                    const res  = await fetch('/api/bridge/status');
                    const data = await res.json();
                    // Juste met à jour le compteur, ne touche pas aux iframes
                    const count = document.querySelector('.stat-number');
                    if (count) count.textContent = data.count ?? '—';
                } catch(e) {}
            }

            setInterval(checkActiveCameras, 10000);
        });
    </script>
@endpush
