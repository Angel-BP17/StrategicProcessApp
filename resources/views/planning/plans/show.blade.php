@extends('layouts.app')
@section('title', 'Plan')
@section('content')
    <div class="container mx-auto p-4">
        @include('planning._nav')

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">Plan: {{ $plan->title }}</h1>
            <a href="{{ route('planning.plans.edit', $plan) }}" class="px-3 py-2 bg-gray-800 text-white rounded">Editar</a>
        </div>

        <div class="mb-6 rounded-2xl bg-white shadow p-5">
            <p class="text-gray-600">
                {{ optional($plan->start_date)->format('Y-m-d') }} — {{ optional($plan->end_date)->format('Y-m-d') }}
            </p>
            <p class="mt-2">{{ $plan->description }}</p>
        </div>

        <div class="flex items-center justify-between">
            <h2 class="font-semibold mb-2">Objetivos estratégicos</h2>
            @can('objective.manage')
                <a href="{{ route('planning.objectives.create', $plan) }}"
                    class="inline-block mb-3 px-3 py-1 bg-blue-600 text-white rounded">Nuevo objetivo</a>
            @endcan
        </div>

        @if ($plan->objectives->isEmpty())
            <div class="rounded-2xl bg-white shadow p-8 text-center">
                <p class="text-zinc-600 mb-4">No hay objetivos registrados para este plan.</p>
                @can('objective.manage')
                    <a href="{{ route('planning.objectives.create', $plan) }}"
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-zinc-900 text-white text-sm">Crear objetivos</a>
                @endcan
            </div>
        @else
            <ul class="space-y-4">
                @foreach ($plan->objectives as $objective)
                    <li class="p-4 border rounded-2xl bg-white shadow-sm">
                        <div class="flex justify-between items-baseline">
                            <div>
                                <div class="font-semibold">{{ $objective->title }}</div>
                                <div class="text-sm text-gray-600">Meta: {{ $objective->goal_value }}</div>
                            </div>
                            <a class="text-blue-600 underline"
                                href="{{ route('planning.objectives.show', [$plan, $objective]) }}">Ver</a>
                        </div>

                        {{-- mini-dashboard de KPIs del objetivo --}}
                        @if ($objective->kpis->isNotEmpty())
                            <div class="grid md:grid-cols-2 gap-3 mt-4">
                                @foreach ($objective->kpis as $kpi)
                                    @php
                                        // Tomamos últimas 6 mediciones (controlador ya debe traerlas ordenadas desc)
                                        $measures = $kpi->measurements->sortBy('measured_at')->take(6);
                                        $labels = $measures
                                            ->pluck('measured_at')
                                            ->map(fn($d) => \Illuminate\Support\Str::substr($d, 0, 10));
                                        $values = $measures->pluck('value');
                                    @endphp
                                    <x-kpi-chart :kpi="$kpi" :labels="$labels" :values="$values" :compact="true"
                                        :height="110" />
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-zinc-500 mt-2">Este objetivo aún no tiene KPIs.</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
