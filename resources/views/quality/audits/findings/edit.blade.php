@extends('layouts.app')

@section('title', 'Editar Hallazgo')

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Auditorías / Hallazgos</p>
             <h1 class="text-3xl font-semibold">Editando Hallazgo</h1>
             {{-- Podríamos mostrar el ID o parte de la descripción aquí --}}
         </div>

        {{-- "Caja" del Formulario --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos del Hallazgo</div>

            {{-- Formulario --}}
            <form action="{{ route('quality.findings.update', $finding) }}" method="POST" class="p-6">
                @csrf
                @method('PUT') {{-- Método PUT --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    {{-- Campo: Descripción (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-300 mb-1">Descripción del Hallazgo</label>
                        <textarea name="description" id="description" rows="3" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">{{ old('description', $finding->description) }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    {{-- Campo: Clasificación (Pre-llenado) --}}
                    <div>
                        <label for="classification" class="block text-sm font-semibold text-slate-300 mb-1">Clasificación</label>
                        <input type="text" name="classification" id="classification" value="{{ old('classification', $finding->classification) }}" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('classification') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    {{-- Campo: Severidad (Pre-seleccionado) --}}
                    <div>
                        <label for="severity" class="block text-sm font-semibold text-slate-300 mb-1">Severidad</label>
                        <select name="severity" id="severity" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="low" {{ old('severity', $finding->severity) == 'low' ? 'selected' : '' }}>Baja</option>
                            <option value="medium" {{ old('severity', $finding->severity) == 'medium' ? 'selected' : '' }}>Media</option>
                            <option value="high" {{ old('severity', $finding->severity) == 'high' ? 'selected' : '' }}>Alta</option>
                        </select>
                        @error('severity') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    {{-- Campo: Fecha Desc. (Pre-llenado) --}}
                    <div>
                        <label for="discovery_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de Descubrimiento</label>
                        <input type="date" name="discovery_date" id="discovery_date" value="{{ old('discovery_date', $finding->discovery_date instanceof \Carbon\Carbon ? $finding->discovery_date->format('Y-m-d') : $finding->discovery_date) }}" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('discovery_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                     {{-- Campo: Evidencia (Pre-llenado) --}}
                    <div>
                        <label for="evidence" class="block text-sm font-semibold text-slate-300 mb-1">Evidencia (Opcional)</label>
                        <input type="text" name="evidence" id="evidence" value="{{ old('evidence', $finding->evidence) }}" class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm" placeholder="Ej: Documento X, foto, etc.">
                         @error('evidence') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    {{-- Botón Cancelar (vuelve al show de la auditoría) --}}
                    <a href="{{ route('quality.audits.show', $finding->audit_id) }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Actualizar Hallazgo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection