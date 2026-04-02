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
                            <video id="cam-{{ $cam->name }}"
                                   autoplay playsinline muted
                                   style="width:100%; height:100%; background:black; display:block;">
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

@push('scripts')
    <script>
        @foreach($activeCameras as $cam)
        (async () => {
            const videoEl   = document.getElementById("cam-{{ $cam->name }}");
            const canvas    = document.getElementById("freeze-{{ $cam->name }}");
            const ctx       = canvas.getContext('2d');
            let   lastFrame = false;

            // Capture la dernière frame visible dans le canvas
            function captureLastFrame() {
                if (videoEl.readyState >= 2 && videoEl.videoWidth > 0) {
                    canvas.width  = videoEl.videoWidth;
                    canvas.height = videoEl.videoHeight;
                    ctx.drawImage(videoEl, 0, 0);
                    lastFrame = true;
                }
            }

            // Capture toutes les 500ms
            setInterval(captureLastFrame, 500);

            async function connect() {
                const pc = new RTCPeerConnection({
                    iceServers: [],
                    iceTransportPolicy: 'all',
                    bundlePolicy: 'max-bundle',
                });

                pc.ontrack = event => {
                    videoEl.srcObject = event.streams[0];
                    // Montre la vidéo, cache le canvas
                    videoEl.style.display = 'block';
                    canvas.style.display  = 'none';
                };

                pc.onconnectionstatechange = () => {
                    const state = pc.connectionState;
                    console.log("WebRTC {{ $cam->name }} →", state);

                    if (state === 'failed' || state === 'disconnected') {
                        // Affiche la dernière frame gelée
                        if (lastFrame) {
                            videoEl.style.display = 'none';
                            canvas.style.display  = 'block';
                        }
                        // Reconnexion dans 3s
                        setTimeout(connect, 3000);
                    }
                };

                try {
                    const offer = await pc.createOffer();
                    await pc.setLocalDescription(offer);

                    const resp = await fetch(
                        `http://{{ config('app.mediamtx_host') }}:8889/{{ $cam->name }}/`,
                        {
                            method: 'POST',
                            body: offer.sdp,
                            headers: { 'Content-Type': 'application/sdp' }
                        }
                    );

                    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

                    const answerSDP = await resp.text();
                    await pc.setRemoteDescription({ type: 'answer', sdp: answerSDP });

                } catch (e) {
                    console.warn("Connexion {{ $cam->name }} échouée, retry dans 3s...", e);
                    if (lastFrame) {
                        videoEl.style.display = 'none';
                        canvas.style.display  = 'block';
                    }
                    setTimeout(connect, 3000);
                }
            }

            connect();
        })();
@endforeach
@endpush
