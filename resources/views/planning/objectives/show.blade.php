@extends('layouts.app')
@section('title', 'Objetivo')
@section('content')
    <a class="text-sm underline" href="{{ route('planning.plans.show', ['plan' => $objective->plan_id]) }}">← Volver al
        plan</a>

    <div class="container mx-auto p-4">
        @include('planning._nav')

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">Objetivo: {{ $objective->title }}</h1>
            @can('objective.manage')
                <a href="{{ route('planning.objectives.edit', ['plan' => $objective->plan_id, 'objective' => $objective->id]) }}"
                    class="px-3 py-2 bg-gray-800 text-white rounded">Editar objetivo</a>
            @endcan
        </div>

        <div class="rounded-2xl bg-white shadow p-5 mb-6">
            <p class="mb-2 text-gray-700">{{ $objective->description }}</p>
            <p class="text-xs mt-2">Meta: {{ $objective->goal_value }} | Plan: {{ $objective->plan->title }}</p>
        </div>

        <div class="flex items-center justify-between">
            <h2 class="font-semibold mb-2">KPIs del objetivo</h2>
            @can('objective.manage')
                <a href="{{ route('planning.kpis.create', ['plan' => $objective->plan_id, 'objective' => $objective->id]) }}"
                    class="inline-block mb-3 px-3 py-1 bg-blue-600 text-white rounded">Nuevo KPI</a>
            @endcan
        </div>

        @if ($objective->kpis->isEmpty())
            <div class="rounded-2xl bg-white shadow p-8 text-center">
                <p class="text-zinc-600 mb-2">Aún no hay KPIs para este objetivo.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($objective->kpis as $kpi)
                    @php
                        $measures = $kpi->measurements->sortBy('measured_at');
                        $labels = $measures
                            ->pluck('measured_at')
                            ->map(fn($d) => \Illuminate\Support\Str::substr($d, 0, 10));
                        $values = $measures->pluck('value');
                    @endphp

                    <div class="rounded-2xl bg-white shadow p-5">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium">{{ $kpi->name }}</div>
                            <div class="flex items-center gap-2">
                                <a class="text-xs underline"
                                    href="{{ route('planning.kpis.show', ['plan' => $objective->plan_id, 'objective' => $objective->id, 'kpi' => $kpi->id]) }}">
                                    Ver
                                </a>
                                @can('objective.manage')
                                    <a class="text-xs underline"
                                        href="{{ route('planning.kpis.edit', ['plan' => $objective->plan_id, 'objective' => $objective->id, 'kpi' => $kpi->id]) }}">
                                        Editar
                                    </a>
                                    <form method="POST"
                                        action="{{ route('planning.kpis.destroy', ['plan' => $objective->plan_id, 'objective' => $objective->id, 'kpi' => $kpi->id]) }}"
                                        onsubmit="return confirm('¿Eliminar KPI?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-600 underline">Eliminar</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        {{-- Mini dashboard del KPI --}}
                        <x-kpi-chart :kpi="$kpi" :labels="$labels" :values="$values" :compact="true"
                            :height="160" />

                        <div class="mt-2 text-xs text-zinc-500">
                            Meta: {{ $kpi->target_value ?? '—' }} {{ $kpi->unit }}
                            · Frecuencia: {{ $kpi->frequency ?? '—' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
