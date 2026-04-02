@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')

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
        <div class="columns is-multiline">
            @foreach($activeCameras as $cam)
                <div class="column is-4">
                    <div class="box camera-card p-0" style="overflow: hidden;">
                        {{-- Header carte --}}
                        <div class="is-flex is-justify-content-space-between is-align-items-center" style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--sodium-border);">
                        <span style="font-weight: 500; font-size: 0.875rem;">
                            <span class="live-dot animate-pulse"></span>
                            {{ $cam->label }}
                        </span>
                            <span class="tag is-info">
                            <span class="mono">{{ $cam->name }}</span>
                        </span>
                        </div>

                        {{-- Vidéo --}}
                        <div class="video-container" style="border-radius: 0; position: relative;">
                            <video
                                id="cam-{{ $cam->name }}"
                                src="http://{{ config('app.mediamtx_host') }}:8889/{{ $cam->name }}/index.m3u8"
                                autoplay playsinline muted controls
                                style="width:100%; height:100%; background:black;">
                            </video>
                            <canvas id="freeze-{{ $cam->name }}"
                                    style="width:100%; height:100%; display:none; position:absolute; top:0; left:0;">
                            </canvas>
                        </div>

                        {{-- Footer carte --}}
                        <div style="padding: 0.75rem 1rem;">
                            <a href="{{ route('cameras.show', $cam->name) }}" class="button is-light is-small is-fullwidth">
                                <span class="icon"><i class="fas fa-sliders"></i></span>
                                <span>Contrôler</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        @foreach($activeCameras as $cam)
        (function() {
            const videoEl = document.getElementById("cam-{{ $cam->name }}");
            const canvas  = document.getElementById("freeze-{{ $cam->name }}");
            const ctx     = canvas.getContext('2d');
            let lastFrameCaptured = false;

            // Capture la dernière frame
            function captureLastFrame() {
                if(videoEl.readyState >= 2 && videoEl.videoWidth > 0) {
                    canvas.width  = videoEl.videoWidth;
                    canvas.height = videoEl.videoHeight;
                    ctx.drawImage(videoEl, 0, 0);
                    lastFrameCaptured = true;
                }
            }

            // Vérifie toutes les 1s si le flux est actif
            setInterval(() => {
                if(videoEl.readyState === 0 || videoEl.paused || videoEl.ended) {
                    // Flux perdu → afficher dernière frame
                    if(lastFrameCaptured) {
                        videoEl.style.display = 'none';
                        canvas.style.display  = 'block';
                    }
                } else {
                    // Flux actif → afficher vidéo
                    videoEl.style.display = 'block';
                    canvas.style.display  = 'none';
                    captureLastFrame();
                }
            }, 1000);

            // Capture initiale
            videoEl.addEventListener('play', captureLastFrame);
        })();
        @endforeach
    </script>
@endpush
