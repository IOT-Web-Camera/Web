@extends('layouts.app')

@section('content')
    <div class="events-content">

        <div class="page-header mb-5">
            <h1 class="title is-3">
                <i class="fa-solid fa-clock-rotate-left"></i>
                &nbsp;Événements & Statistiques
            </h1>
            <p class="subtitle">Historique des données remontées par vos caméras</p>
        </div>

        @if($cameras->isEmpty())
            <div class="box has-text-centered" style="padding: 3rem;">
                <i class="fa-solid fa-video-slash fa-2x mb-3" style="color: var(--sv-text-muted);"></i>
                <p style="font-weight: 500;">Aucune caméra disponible</p>
            </div>
        @else
            {{-- Onglets caméras --}}
            <div class="tabs mb-0" style="overflow-x: auto; overflow-y: hidden; flex-wrap: nowrap; -webkit-overflow-scrolling: touch;">
                <ul style="flex-wrap: nowrap; min-width: max-content;">
                    @foreach($cameras as $i => $camera)
                        <li class="camera-tab {{ $i === 0 ? 'is-active' : '' }}"
                            data-target="camera-{{ $camera->id }}">
                            <a style="white-space: nowrap;">
                                <span class="icon"><i class="fa-solid fa-video"></i></span>
                                <span>{{ $camera->label }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Panels --}}
            @foreach($cameras as $i => $camera)
                <div id="camera-{{ $camera->id }}"
                     class="camera-panel box"
                     style="{{ $i !== 0 ? 'display:none;' : '' }} border-radius: 0 8px 8px 8px;">

                    {{-- Boutons de plage --}}
                    <div class="is-flex is-justify-content-flex-end mb-4" style="gap: 0.5rem; flex-wrap: wrap;">
                        @foreach(['1h' => '1h', '6h' => '6h', '24h' => '24h', '7d' => '7j', 'all' => 'Tout'] as $val => $label)
                            <button class="button is-small range-btn {{ $val === 'all' ? 'is-primary' : '' }}"
                                    data-range="{{ $val }}"
                                    data-cam="{{ $camera->id }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Graphiques responsive --}}
                    <div class="chart-wrapper mb-4">
                        <div id="chart-temp-{{ $camera->id }}" style="height: 220px;"></div>
                    </div>
                    <div class="chart-wrapper mb-4">
                        <div id="chart-battery-{{ $camera->id }}" style="height: 220px;"></div>
                    </div>
                    <div class="chart-wrapper mb-5">
                        <div id="chart-signal-{{ $camera->id }}" style="height: 220px;"></div>
                    </div>

                    {{-- Timeline --}}
                    <h3 class="title is-6 mb-3" style="color: var(--sv-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">
                        Timeline des événements
                    </h3>

                    <div style="max-height: 280px; overflow-y: auto; border: 1px solid var(--sv-border); border-radius: 8px;">
                        @forelse($camera->events as $event)
                            <div style="display: flex; gap: 1rem; padding: 0.6rem 1rem; border-bottom: 1px solid var(--sv-border); font-size: 0.8rem; align-items: center;">
                                <span style="color: var(--sv-text-muted); white-space: nowrap; font-family: monospace;">
                                    {{ $event->created_at->format('H:i:s') }}
                                </span>
                                <span class="tag is-info" style="flex-shrink: 0;">{{ $event->type }}</span>
                                <code style="font-size: 0.75rem; color: var(--sv-text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ json_encode($event->payload) }}
                                </code>
                            </div>
                        @empty
                            <div style="padding: 2rem; text-align: center; color: var(--sv-text-muted); font-size: 0.875rem;">
                                Aucun événement enregistré
                            </div>
                        @endforelse
                    </div>

                </div>
            @endforeach
        @endif

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- Gestion des onglets caméras ---
            const tabs = document.querySelectorAll('.camera-tab');
            const panels = document.querySelectorAll('.camera-panel');

            if (tabs.length > 0) {
                panels.forEach(p => p.style.display = 'none');
                panels[0].style.display = 'block';
                tabs[0].classList.add('is-primary');
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.target;

                    panels.forEach(p => p.style.display = 'none');
                    tabs.forEach(t => t.classList.remove('is-primary'));

                    document.getElementById(target).style.display = 'block';
                    tab.classList.add('is-primary');
                });
            });

            // --- Fonction pour générer les graphes ---
            function renderChartsForCamera(cameraId, events) {

                const timestamps = events.map(e => new Date(e.created_at).getTime());
                const temps      = events.map(e => e.payload.temperature ?? null);
                const batteries  = events.map(e => e.payload.battery ?? null);
                const signals    = events.map(e => e.payload.signal ?? null);

                // Température
                const chartTemp = new ApexCharts(
                    document.querySelector("#chart-temp-" + cameraId),
                    {
                        chart: {
                            type: 'line',
                            height: 250,
                            zoom: { enabled: true },
                            toolbar: { show: true }
                        },
                        xaxis: {
                            type: 'datetime',
                            labels: { datetimeUTC: false }
                        },
                        series: [
                            { name: 'Température', data: temps.map((v, i) => [timestamps[i], v]) }
                        ],
                        colors: ['#f97316']
                    }
                );
                chartTemp.render();

                // Batterie
                const chartBattery = new ApexCharts(
                    document.querySelector("#chart-battery-" + cameraId),
                    {
                        chart: {
                            type: 'line',
                            height: 250,
                            zoom: { enabled: true },
                            toolbar: { show: true }
                        },
                        xaxis: {
                            type: 'datetime',
                            labels: { datetimeUTC: false }
                        },
                        series: [
                            { name: 'Batterie', data: batteries.map((v, i) => [timestamps[i], v]) }
                        ],
                        colors: ['#10b981']
                    }
                );
                chartBattery.render();

                // Signal
                const chartSignal = new ApexCharts(
                    document.querySelector("#chart-signal-" + cameraId),
                    {
                        chart: {
                            type: 'line',
                            height: 250,
                            zoom: { enabled: true },
                            toolbar: { show: true }
                        },
                        xaxis: {
                            type: 'datetime',
                            labels: { datetimeUTC: false }
                        },
                        series: [
                            { name: 'Signal', data: signals.map((v, i) => [timestamps[i], v]) }
                        ],
                        colors: ['#3b82f6']
                    }
                );
                chartSignal.render();

                // --- Boutons de plage : pour l’instant, juste "reset zoom" ---
                const rangeButtons = document.querySelectorAll(`#camera-${cameraId} .range-btn`);

                rangeButtons.forEach(btn => {
                    btn.addEventListener('click', () => {

                        // Remettre l’axe X en auto (équivalent d’un reset de zoom)
                        chartTemp.updateOptions({ xaxis: { min: undefined, max: undefined } });
                        chartBattery.updateOptions({ xaxis: { min: undefined, max: undefined } });
                        chartSignal.updateOptions({ xaxis: { min: undefined, max: undefined } });

                        rangeButtons.forEach(b => b.classList.remove('is-primary'));
                        btn.classList.add('is-primary');
                    });
                });
            }

            // --- Appel pour chaque caméra ---
            @foreach($cameras as $camera)
            renderChartsForCamera({{ $camera->id }}, @json($camera->events));
            @endforeach

            document.querySelectorAll('.camera-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.camera-tab').forEach(t => t.classList.remove('is-active'));
                    document.querySelectorAll('.camera-panel').forEach(p => p.style.display = 'none');
                    tab.classList.add('is-active');
                    document.getElementById(tab.dataset.target).style.display = 'block';
                });
            });

        });
    </script>
@endpush
