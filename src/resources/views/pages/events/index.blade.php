@extends('layouts.app')

@section('content')
    <div class="container is-fluid" style="margin-left:260px; padding-top:2rem;">

        <h1 class="title is-3">
            <i class="fa-solid fa-chart-line"></i>
            &nbsp;Événements & Statistiques
        </h1>

        <!-- Sélecteur de caméras -->
        <div class="buttons mb-5">
            @foreach($cameras as $camera)
                <button class="button camera-tab"
                        data-target="camera-{{ $camera->id }}">
                    <i class="fa-solid fa-video"></i>
                    &nbsp;{{ $camera->label }}
                </button>
            @endforeach
        </div>

        @foreach($cameras as $camera)
            <div id="camera-{{ $camera->id }}" class="camera-panel" style="display:none;">
                <div class="box mb-6">
                    <h2 class="title is-4">
                        <i class="fa-solid fa-video"></i>
                        &nbsp;{{ $camera->label }} ({{ $camera->name }})
                    </h2>

                    <!-- Boutons de plage -->
                    <div class="buttons is-right mb-3">
                        <button class="button is-small range-btn" data-range="1h">1h</button>
                        <button class="button is-small range-btn" data-range="6h">6h</button>
                        <button class="button is-small range-btn" data-range="24h">24h</button>
                        <button class="button is-small range-btn" data-range="7d">7j</button>
                        <button class="button is-small range-btn is-primary" data-range="all">Tout</button>
                    </div>

                    <!-- Graphiques -->
                    <div id="chart-temp-{{ $camera->id }}" style="height: 250px;"></div>
                    <div id="chart-battery-{{ $camera->id }}" style="height: 250px;" class="mt-5"></div>
                    <div id="chart-signal-{{ $camera->id }}" style="height: 250px;" class="mt-5"></div>

                    <!-- Timeline -->
                    <h3 class="title is-5 mt-5">Timeline des événements</h3>

                    <div class="timeline-box"
                         style="max-height: 250px; overflow-y: auto; padding-right: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <ul>
                            @foreach($camera->events as $event)
                                <li style="margin-bottom: 4px;">
                                    <strong>{{ $event->created_at->format('H:i:s') }}</strong>
                                    — {{ $event->type }}
                                    — <code>{{ json_encode($event->payload) }}</code>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        @endforeach

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

        });
    </script>
@endpush
