@extends('layouts.app')

@section('title', 'Crear Nueva Encuesta') {{-- Título de la página --}}

{{-- @section('header') ... @endsection --}} {{-- Quitamos si layout maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Encuestas</p>
             <h1 class="text-3xl font-semibold">Crear Nueva Encuesta</h1>
         </div>

        {{-- "Caja" del Formulario --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos de la Nueva Encuesta</div>

            {{-- Formulario --}}
            <form action="{{ route('quality.surveys.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Campo: Título --}}
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-slate-300 mb-1">Título de la Encuesta</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                               placeholder="Ej: Encuesta de Satisfacción General - Ciclo 2025-2">
                        @error('title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Descripción (Opcional) --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-300 mb-1">Descripción (Opcional)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo de Objetivo (Target Type) --}}
                    <div>
                        <label for="target_type" class="block text-sm font-semibold text-slate-300 mb-1">Dirigido a:</label>
                        <select name="target_type" id="target_type" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="">Seleccione...</option>
                            <option value="students" {{ old('target_type') == 'students' ? 'selected' : '' }}>Estudiantes</option>
                            <option value="teachers" {{ old('target_type') == 'teachers' ? 'selected' : '' }}>Docentes</option>
                            <option value="graduates" {{ old('target_type') == 'graduates' ? 'selected' : '' }}>Egresados</option>
                            <option value="companies" {{ old('target_type') == 'companies' ? 'selected' : '' }}>Empresas</option>
                            <option value="general" {{ old('target_type') == 'general' ? 'selected' : '' }}>General</option>
                            {{-- Puedes añadir más opciones si las necesitas --}}
                        </select>
                        @error('target_type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                     {{-- Campo: Estado Inicial --}}
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-300 mb-1">Estado Inicial</label>
                        <select name="status" id="status" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Borrador (Draft)</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activa (Active)</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Cerrada (Closed)</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.surveys.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Crear Encuesta
                    </button>
                </div>
            </form>

        </div> {{-- Fin "Caja" del Formulario --}}
    </div> {{-- Fin contenedor principal --}}
@endsection