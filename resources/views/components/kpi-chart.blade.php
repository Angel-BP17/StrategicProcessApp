@props([
    'canvasId' => 'kpiChart',
    'labels' => [], // array|Collection
    'values' => [], // array|Collection
    'title' => 'Serie',
    'type' => 'line',
])

@php
    use Illuminate\Support\Collection;

    $lbls = $labels instanceof Collection ? $labels->values()->all() : (is_array($labels) ? array_values($labels) : []);

    $vals = $values instanceof Collection ? $values->values()->all() : (is_array($values) ? array_values($values) : []);
@endphp

<canvas id="{{ $canvasId }}" height="120"></canvas>

<script>
    (() => {
        const initializeChart = () => {
            if (!window.Chart) {
                // Wait until the global Chart.js instance is registered by Vite's module loader.
                setTimeout(initializeChart, 50);
                return;
            }

            const el = document.getElementById(@json($canvasId));
            if (!el) return;

            const ctx = el.getContext('2d');

            const data = {
                labels: @json($lbls),
                datasets: [{
                    label: @json($title),
                    data: @json($vals),
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
                }]
            };

            new window.Chart(ctx, {
                type: @json($type),
                data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeChart, {
                once: true
            });
        } else {
            initializeChart();
        }
    })();
</script>
