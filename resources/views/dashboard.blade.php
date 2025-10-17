{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    {{-- Si NO hay plan, botón para crear --}}
    @if (!$plan)
        <div class="rounded-2xl bg-white shadow p-8 text-center">
            <p class="text-zinc-600 mb-4">Aún no existe un plan estratégico.</p>
            @can('plan.manage')
                <a href="{{ route('planning.plans.create') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl bg-zinc-900 text-white text-sm">
                    Crear plan
                </a>
            @endcan
        </div>
    @else
        <section class="mb-6">
            <div class="rounded-2xl shadow bg-white p-5">
                <div class="flex items-baseline justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Último plan: {{ $plan->title }}</h2>
                        <p class="text-sm text-zinc-500 mt-1">{{ $plan->description }}</p>
                        <p class="text-xs mt-2">Periodo: {{ $plan->start_date }} → {{ $plan->end_date }} | Estado:
                            {{ $plan->status }}</p>
                    </div>
                    <a class="text-sm underline" href="{{ route('planning.plans.show', $plan) }}">ver plan</a>
                </div>
            </div>
        </section>

        {{-- Objetivos del plan (últimos 5) o botón para crear si no hay --}}
        <section>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Últimos 5 objetivos del plan</h3>
                @can('objective.manage')
                    <a href="{{ route('planning.objectives.create', $plan) }}"
                        class="text-sm px-3 py-2 rounded-lg bg-zinc-900 text-white">Crear objetivo</a>
                @endcan
            </div>

            @if ($objectives->isEmpty())
                <div class="rounded-2xl bg-white shadow p-8 text-center">
                    <p class="text-zinc-600 mb-4">Este plan aún no tiene objetivos.</p>
                    @can('objective.manage')
                        <a href="{{ route('planning.objectives.create', $plan) }}"
                            class="inline-flex items-center px-4 py-2 rounded-xl bg-zinc-900 text-white text-sm">
                            Crear objetivos
                        </a>
                    @endcan
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach ($objectives as $o)
                        <div class="rounded-2xl shadow bg-white p-5">
                            <div class="flex items-baseline justify-between">
                                <a class="font-medium hover:underline"
                                    href="{{ route('planning.objectives.show', [$o->plan_id, $o->id]) }}">{{ $o->title }}</a>
                                <a class="text-xs underline"
                                    href="{{ route('planning.objectives.show', [$o->plan_id, $o->id]) }}">detalles</a>
                            </div>
                            <p class="text-sm text-zinc-500 mt-1 line-clamp-2">{{ $o->description }}</p>

                            {{-- Mini-dashboard de KPIs (última medición) --}}
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                @foreach ($o->kpis as $kpi)
                                    @php $last = $kpi->measurements->first(); @endphp
                                    <div class="border rounded-xl p-3">
                                        <div class="text-xs text-zinc-500">{{ $kpi->name }}</div>
                                        <div class="text-2xl font-semibold">
                                            {{ $last->value ?? '—' }} <span
                                                class="text-xs font-normal text-zinc-500">{{ $kpi->unit }}</span>
                                        </div>
                                        <div class="text-[11px] text-zinc-500">meta: {{ $kpi->target_value }} |
                                            {{ $kpi->frequency }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
@endsection
