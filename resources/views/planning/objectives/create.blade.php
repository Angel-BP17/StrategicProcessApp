@extends('layouts.app')
@section('title', 'Nuevo objetivo')
@section('content')
    <h1 class="text-2xl font-semibold mb-4">Nuevo objetivo para: {{ $plan->title }}</h1>
    <form class="space-y-4" method="POST" action="{{ route('planning.objectives.store', $plan) }}">
        @csrf
        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm">Título</label>
                <input name="title" class="w-full border rounded-lg px-3 py-2" />
                @error('title')
                    <div class="text-xs text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="text-sm">Meta (valor)</label>
                <input type="number" step="any" name="goal_value" class="w-full border rounded-lg px-3 py-2" />
            </div>

            <div>
                <label class="text-sm">Responsable</label>
                <select name="responsible_user_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">— Seleccionar —</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm">Peso (%)</label>
                <input type="number" step="1" min="0" max="100" name="weight"
                    class="w-full border rounded-lg px-3 py-2" />
            </div>

            <div class="md:col-span-2">
                <label class="text-sm">Descripción</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>
        </div>
        <button class="px-4 py-2 rounded-xl bg-zinc-900 text-white text-sm">Guardar</button>
    </form>
@endsection
