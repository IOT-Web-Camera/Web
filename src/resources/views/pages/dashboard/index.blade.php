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
        @if(empty($activeCameras))
            <div class="box has-text-centered" style="padding: 4rem 2rem;">
                <span class="icon is-large mb-4" style="color: var(--sodium-muted);">
                    <i class="fas fa-video-slash fa-2x"></i>
                </span>
                <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucun flux actif</p>
                <p style="font-size: 0.875rem; color: var(--sodium-muted);">
                    Vérifiez que vos Raspberry Pi diffusent vers <code>{{ $serverIp }}</code>
                </p>
            </div>
        @else
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
                            <div class="video-container" style="border-radius: 0;">
                                <iframe src="http://{{ $serverIp }}:8889/{{ $cam->name }}/" allowfullscreen></iframe>
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
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        setTimeout(() => location.reload(), 60000);
    </script>
@endpush
