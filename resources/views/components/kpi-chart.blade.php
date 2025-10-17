@props([
    'canvasId' => 'kpiChart',
    'labels' => [], // array|Collection de etiquetas (fechas, etc.)
    'values' => [], // array|Collection de valores
    'title' => 'Serie',
])

@php
    use Illuminate\Support\Collection;

    $lbls = $labels instanceof Collection ? $labels->values()->all() : (is_array($labels) ? array_values($labels) : []);

    $vals = $values instanceof Collection ? $values->values()->all() : (is_array($values) ? array_values($values) : []);
@endphp

<canvas id="{{ $canvasId }}" height="120"></canvas>

<script>
    (() => {
        const ctx = document.getElementById(@json($canvasId));
        if (!ctx) return;

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

        // Si usas Chart.js v4
        const chart = new Chart(ctx, {
            type: 'line',
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
    })();
</script>
