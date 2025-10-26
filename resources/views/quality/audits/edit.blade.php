@extends('layouts.app')

@section('title', 'Editar Auditoría')

{{-- @section('header') ... @endsection --}}

@section('content')
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Auditorías</p>
            <h1 class="text-3xl font-semibold">Editando Auditoría: {{ Str::limit($audit->area, 40) }}</h1>
        </div>

        <div
            class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos de la Auditoría</div>

            <form action="{{ route('quality.audits.update', $audit) }}" method="POST" class="p-6">
                @csrf
                @method('PUT') {{-- Método PUT --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Campo: Área (Pre-llenado) --}}
                    <div>
                        <label for="area" class="block text-sm font-semibold text-slate-300 mb-1">Área a Auditar</label>
                        <input type="text" name="area" id="area" value="{{ old('area', $audit->area) }}" required
                            class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('area')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Tipo (Pre-seleccionado) --}}
                    <div>
                        <label for="type" class="block text-sm font-semibold text-slate-300 mb-1">Tipo de
                            Auditoría</label>
                        <select name="type" id="type" required
                            class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="internal" {{ old('type', $audit->type) == 'internal' ? 'selected' : '' }}>Interna
                            </option>
                            <option value="external" {{ old('type', $audit->type) == 'external' ? 'selected' : '' }}>Externa
                            </option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Objetivo (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="objective" class="block text-sm font-semibold text-slate-300 mb-1">Objetivo</label>
                        <input type="text" name="objective" id="objective"
                            value="{{ old('objective', $audit->objective) }}" required
                            class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('objective')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Alcance (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="audit_range" class="block text-sm font-semibold text-slate-300 mb-1">Alcance</label>
                        <textarea name="audit_range" id="audit_range" rows="3" required
                            class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">{{ old('range', $audit->range) }}</textarea>
                        @error('audit_range')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Fecha Inicio (Pre-llenado) --}}
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de
                            Inicio</label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', $audit->start_date) }}" required
                            class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('start_date')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Fecha Fin (Pre-llenado) --}}
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de Fin</label>
                        <input type="date" name="end_date" id="end_date"
                            value="{{ old('end_date', $audit->end_date) }}" required
                            class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('end_date')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Estado (NUEVO) --}}
                    <div>
                        <label for="state" class="block text-sm font-semibold text-slate-300 mb-1">Estado</label>
                        <select name="state" id="state" required
                            class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="planned" {{ old('state', $audit->state) == 'planned' ? 'selected' : '' }}>
                                Planificada</option>
                            <option value="in_progress"
                                {{ old('state', $audit->state) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                            <option value="completed" {{ old('state', $audit->state) == 'completed' ? 'selected' : '' }}>
                                Completada</option>
                            <option value="cancelled" {{ old('state', $audit->state) == 'cancelled' ? 'selected' : '' }}>
                                Cancelada</option>
                        </select>
                        @error('state')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.audits.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Actualizar Auditoría
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
