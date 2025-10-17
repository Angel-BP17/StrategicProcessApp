{{-- resources/views/planning/plans/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Editar plan')
@section('content')
    <h1 class="text-2xl font-semibold mb-4">Editar plan</h1>
    <form class="space-y-4" method="POST" action="{{ route('planning.plans.update', $plan) }}">
        @csrf @method('PUT')
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm">Título</label>
                <input name="title" value="{{ old('title', $plan->title ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2" />
                @error('title')
                    <div class="text-xs text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="text-sm">Estado</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2">
                    @foreach (['Draft', 'Active', 'Closed'] as $st)
                        <option value="{{ $st }}" @selected(old('status', $plan->status ?? 'Draft') == $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm">Inicio</label>
                <input type="date" name="start_date" value="{{ old('start_date', $plan->start_date->format('Y-m-d')) }}"
                    class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
                <label class="text-sm">Fin</label>
                <input type="date" name="end_date" value="{{ old('end_date', $plan->end_date->format('Y-m-d')) }}"
                    class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div class="md:col-span-2">
                <label class="text-sm">Descripción</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description', $plan->description ?? '') }}</textarea>
            </div>
        </div>

        <button class="px-4 py-2 rounded-xl bg-zinc-900 text-white text-sm">Actualizar</button>
    </form>
@endsection
