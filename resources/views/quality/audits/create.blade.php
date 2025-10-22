@extends('layouts.app')

@section('title', 'Planificar Nueva Auditoría') {{-- Título de página --}}

{{-- @section('header') --}} {{-- Quitamos el header default si el layout ya lo maneja --}}
{{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Planificar Nueva Auditoría') }}
    </h2> --}}
{{-- @endsection --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8"> {{-- Simplificado para formularios --}}
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Auditorías</p>
            <h1 class="text-3xl font-semibold">Planificar Nueva Auditoría</h1>
        </div>

        {{-- "Caja" del Formulario --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos de la Auditoría</div>

            {{-- Formulario --}}
            <form action="{{ route('quality.audits.store') }}" method="POST" class="p-6"> {{-- Añadimos padding al form --}}
                @csrf
                {{-- Grid para los campos (usamos clases de Tailwind para responsividad) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Campo: Área --}}
                    <div>
                        {{-- Estilo de Label adaptado --}}
                        <label for="area" class="block text-sm font-semibold text-slate-300 mb-1">Área a Auditar</label>
                        {{-- Estilo de Input adaptado --}}
                        <input type="text" name="area" id="area" value="{{ old('area') }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('area')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Tipo --}}
                    <div>
                        <label for="type" class="block text-sm font-semibold text-slate-300 mb-1">Tipo de Auditoría</label>
                        <select name="type" id="type" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="internal" {{ old('type') == 'internal' ? 'selected' : '' }}>Interna</option>
                            <option value="external" {{ old('type') == 'external' ? 'selected' : '' }}>Externa</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Objetivo --}}
                    <div class="md:col-span-2">
                        <label for="objective" class="block text-sm font-semibold text-slate-300 mb-1">Objetivo</label>
                        <input type="text" name="objective" id="objective" value="{{ old('objective') }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('objective')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Alcance (Range) --}}
                    <div class="md:col-span-2">
                        <label for="range" class="block text-sm font-semibold text-slate-300 mb-1">Alcance</label>
                        {{-- Estilo de Textarea adaptado --}}
                        <textarea name="range" id="range" rows="3" required
                                  class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">{{ old('range') }}</textarea>
                        @error('range')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Fecha de Inicio --}}
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de Inicio</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('start_date')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Fecha de Fin --}}
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de Fin</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('end_date')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Botones de Acción (alineados a la derecha, estilo adaptado) --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.audits.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Guardar Auditoría
                    </button>
                </div>
            </form>

        </div> {{-- Fin "Caja" del Formulario --}}
    </div> {{-- Fin contenedor principal --}}
@endsection