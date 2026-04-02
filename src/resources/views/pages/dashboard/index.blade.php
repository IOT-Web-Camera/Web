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
        <div class="columns is-multiline">
            @foreach($activeCameras as $cam)
                <div class="column is-4">
                    <div class="box camera-card p-0" style="overflow: hidden;">
                        {{-- Header carte --}}
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

                        {{-- Vidéo --}}
                        <div class="video-container">
                            <video id="cam-{{ $cam->name }}" autoplay muted playsinline style="width:100%; height:100%;"></video>
                            <canvas id="freeze-{{ $cam->name }}"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            @foreach($activeCameras as $cam)
            (function() {
                const video = document.getElementById("cam-{{ $cam->name }}");
                const canvas = document.getElementById("freeze-{{ $cam->name }}");
                const ctx = canvas.getContext('2d');
                let lastFrameCaptured = false;

                function captureFrame() {
                    if(video.videoWidth > 0 && video.videoHeight > 0) {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        lastFrameCaptured = true;
                    }
                }

                function showLastFrame() {
                    if(lastFrameCaptured) {
                        video.style.display = 'none';
                        canvas.style.display = 'block';
                    }
                }

                function showVideo() {
                    canvas.style.display = 'none';
                    video.style.display = 'block';
                }

                function initHls() {
                    if(Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource("http://{{ $serverIp }}:8878/{{ $cam->name }}/index.m3u8");
                        hls.attachMedia(video);

                        hls.on(Hls.Events.MANIFEST_PARSED, () => {
                            video.play().catch(()=>{});
                        });

                        hls.on(Hls.Events.ERROR, (event, data) => {
                            console.warn("HLS error for {{ $cam->name }}:", data);
                            showLastFrame();
                        });
                    } else if(video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = "http://{{ $serverIp }}:8878/{{ $cam->name }}/index.m3u8";
                    }
                }

                // Capture régulière toutes les 500ms
                setInterval(() => {
                    if(!video.paused && !video.ended && video.readyState >= 2) {
                        showVideo();
                        captureFrame();
                    } else {
                        showLastFrame();
                    }
                }, 500);

                video.addEventListener('play', captureFrame);
                initHls();
            })();
            @endforeach
        });
    </script>
@endpush
