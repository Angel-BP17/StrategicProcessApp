@extends('layouts.app')
@section('title', 'Nuevo KPI')
@section('content')
    <a class="text-sm underline" href="{{ route('planning.plans.show', [$plan, $objective]) }}">← Volver al plan</a>
    <h1 class="text-2xl font-semibold mt-3">Nuevo KPI para: {{ $objective->title }}</h1>

    <form class="space-y-4 mt-4" method="POST" action="{{ route('planning.kpis.store', [$plan, $objective]) }}">
        @csrf
        <input type="hidden" name="objective_id" value="{{ $objective->id }}">

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm">Nombre</label>
                <input name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-3 py-2" />
                @error('name')
                    <div class="text-xs text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="text-sm">Unidad</label>
                <input name="unit" value="{{ old('unit') }}" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
                <label class="text-sm">Meta (target)</label>
                <input type="number" step="any" name="target_value" value="{{ old('target_value') }}"
                    class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
                <label class="text-sm">Frecuencia</label>
                <input name="frequency" value="{{ old('frequency', 'Mensual') }}"
                    class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div class="md:col-span-2">
                <label class="text-sm">Descripción</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description') }}</textarea>
            </div>
        </div>

        <button class="px-4 py-2 rounded-xl bg-zinc-900 text-white text-sm">Guardar KPI</button>
    </form>
@endsection
