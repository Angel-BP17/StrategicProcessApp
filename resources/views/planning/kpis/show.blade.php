@extends('layouts.app')
@section('title', 'KPI')
@section('content')
    <a class="text-sm underline"
        href="{{ route('planning.objectives.show', ['plan' => $objective->plan, 'objective' => $objective]) }}">←
        Volver al
        objetivo</a>

    <div class="rounded-2xl bg-white shadow p-5 mt-3">
        <div class="flex items-baseline justify-between">
            <div>
                <h1 class="text-2xl font-semibold">{{ $kpi->name }}</h1>
                <p class="text-sm text-zinc-500">{{ $kpi->description }}</p>
                <p class="text-xs mt-2">
                    Unidad: {{ $kpi->unit ?? '—' }} |
                    Meta: {{ $kpi->target_value ?? '—' }} |
                    Frecuencia: {{ $kpi->frequency ?? '—' }}
                </p>
            </div>
            <div class="flex gap-2">
                @can('objective.manage')
                    <a class="text-xs underline"
                        href="{{ route('planning.kpis.edit', [$plan->id, $objective->id, $kpi->id]) }}">Editar</a>
                    <form method="POST" action="{{ route('planning.kpis.destroy', [$plan->id, $objective->id, $kpi->id]) }}"
                        onsubmit="return confirm('¿Eliminar KPI?');">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-600 underline">Eliminar</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    {{-- Medir (crear medición) --}}
    @can('objective.manage')
        <div class="rounded-2xl bg-white shadow p-5 mt-6">
            <h2 class="text-lg font-semibold mb-3">Registrar medición</h2>
            <form class="grid md:grid-cols-4 gap-4" method="POST"
                action="{{ route('planning.kpis.measurements.store', [$plan->id, $objective->id, $kpi->id]) }}">
                @csrf
                <input type="hidden" name="kpi_id" value="{{ $kpi->id }}">
                <div>
                    <label class="text-sm">Fecha de medición</label>
                    <input type="date" name="measured_at" value="{{ old('measured_at', now()->toDateString()) }}"
                        class="w-full border rounded-lg px-3 py-2" />
                    @error('measured_at')
                        <div class="text-xs text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="text-sm">Valor</label>
                    <input type="number" step="any" name="value" value="{{ old('value') }}"
                        class="w-full border rounded-lg px-3 py-2" />
                    @error('value')
                        <div class="text-xs text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="text-sm">Fuente</label>
                    <input name="source" value="{{ old('source') }}" class="w-full border rounded-lg px-3 py-2" />
                </div>
                <div class="flex items-end">
                    <button class="px-4 py-2 rounded-xl bg-zinc-900 text-white text-sm">Guardar medición</button>
                </div>
            </form>
        </div>
    @endcan

    {{-- Listado de mediciones --}}
    <div class="rounded-2xl bg-white shadow p-5 mt-6">
        <h2 class="text-lg font-semibold mb-3">Historial de mediciones</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2 pr-4">Fecha</th>
                        <th class="py-2 pr-4">Valor</th>
                        <th class="py-2 pr-4">Fuente</th>
                        <th class="py-2 pr-4">Registrado por</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kpi->measurements as $m)
                        <tr class="border-b">
                            <td class="py-2 pr-4">{{ \Illuminate\Support\Carbon::parse($m->measured_at)->format('Y-m-d') }}
                            </td>
                            <td class="py-2 pr-4">{{ $m->value }}</td>
                            <td class="py-2 pr-4">{{ $m->source }}</td>
                            <td class="py-2 pr-4">#{{ $m->recorded_by_user_id }}</td>
                            <td class="py-2 text-right">
                                @can('objective.manage')
                                    <form method="POST"
                                        action="{{ route('planning.kpis.measurements.destroy', [$plan->id, $objective->id, $kpi->id, $m->id]) }}"
                                        onsubmit="return confirm('¿Eliminar medición?');">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-600 underline">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-3 text-zinc-500" colspan="5">Aún no hay mediciones.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
