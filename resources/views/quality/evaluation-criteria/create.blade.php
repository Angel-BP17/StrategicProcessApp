@extends('layouts.app')

@section('title', 'Crear Nuevo Criterio de Evaluación')

{{-- @section('header') ... @endsection --}} {{-- Quitamos si layout maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Evaluación de Estándares</p>
             <h1 class="text-3xl font-semibold">Crear Nuevo Criterio de Evaluación</h1>
         </div>

        {{-- "Caja" del Formulario --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos del Nuevo Criterio</div>

            {{-- Formulario --}}
            <form action="{{ route('quality.evaluation-criteria.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Campo: Nombre del Criterio --}}
                    <div class="md:col-span-2">
                        <label for="criterion_name" class="block text-sm font-semibold text-slate-300 mb-1">Nombre del Criterio</label>
                        <input type="text" name="criterion_name" id="criterion_name" value="{{ old('criterion_name') }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                               placeholder="Ej: Claridad en la explicación">
                        @error('criterion_name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo de Respuesta --}}
                    <div>
                        <label for="response_type" class="block text-sm font-semibold text-slate-300 mb-1">Tipo de Respuesta Esperada</label>
                        <select name="response_type" id="response_type" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="">Seleccione...</option>
                            <option value="numeric" {{ old('response_type') == 'numeric' ? 'selected' : '' }}>Numérica (Ej: Calificación 1-5)</option>
                            <option value="text" {{ old('response_type') == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                            <option value="option" {{ old('response_type') == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                        </select>
                        @error('response_type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror

                        {{-- INICIO: CONTENEDOR PARA OPCIONES (OCULTO) --}}
                        <div id="criterionOptionsContainer" class="mt-4 space-y-1 hidden"> {{-- Reducido space-y --}}
                            <label for="options_text" class="block text-xs font-semibold text-slate-400">Opciones (una por línea)</label> {{-- Label más pequeño --}}
                            <textarea name="options_text" id="options_text" rows="3" {{-- Menos filas --}}
                                      class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                                      placeholder="Opción 1&#10;Opción 2..."></textarea>
                            @error('options_text') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        {{-- FIN: CONTENEDOR PARA OPCIONES --}}
                    </div>

                    {{-- Campo: Estado --}}
                    <div>
                        <label for="state" class="block text-sm font-semibold text-slate-300 mb-1">Estado</label>
                        <select name="state" id="state" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="active" {{ old('state', 'active') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('state') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('state') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Categoría (Opcional) --}}
                    <div>
                        <label for="category" class="block text-sm font-semibold text-slate-300 mb-1">Categoría (Opcional)</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}"
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                               placeholder="Ej: Pedagogía, Contenido">
                        @error('category') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Peso Porcentual (Opcional) --}}
                    <div>
                        <label for="percentage_weight" class="block text-sm font-semibold text-slate-300 mb-1">Peso (%) (Opcional)</label>
                        <input type="number" name="percentage_weight" id="percentage_weight" value="{{ old('percentage_weight') }}" step="0.01" min="0" max="100"
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                               placeholder="Ej: 20.5">
                        @error('percentage_weight') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.evaluation-criteria.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Guardar Criterio
                    </button>
                </div>
            </form>

        </div> {{-- Fin "Caja" del Formulario --}}
    </div> {{-- Fin contenedor principal --}}

{{-- INICIO: SCRIPT PARA OPCIONES DE CRITERIO (Sin cambios) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const responseTypeSelect = document.getElementById('response_type');
        const optionsContainer = document.getElementById('criterionOptionsContainer');
        const optionsTextarea = document.getElementById('options_text');

        if (responseTypeSelect && optionsContainer && optionsTextarea) {
            function toggleCriterionOptions() {
                if (responseTypeSelect.value === 'option') {
                    optionsContainer.classList.remove('hidden');
                    optionsTextarea.required = true;
                } else {
                    optionsContainer.classList.add('hidden');
                    optionsTextarea.required = false;
                    optionsTextarea.value = '';
                }
            }
            responseTypeSelect.addEventListener('change', toggleCriterionOptions);
            toggleCriterionOptions();
        } else {
            console.error('Error JS: No se encontraron elementos para opciones de criterio.');
        }
    });
</script>
{{-- FIN: SCRIPT --}}

@endsection