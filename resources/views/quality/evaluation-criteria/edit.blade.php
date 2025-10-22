@extends('layouts.app')

@section('title', 'Editar Criterio de Evaluación')

{{-- @section('header') ... @endsection --}} {{-- Quitamos si layout maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Evaluación de Estándares</p>
             <h1 class="text-3xl font-semibold">Editando: {{ Str::limit($criterion->criterion_name, 40) }}</h1>
         </div>

        {{-- "Caja" del Formulario --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos del Criterio</div>

            {{-- Formulario --}}
            <form action="{{ route('quality.evaluation-criteria.update', $criterion) }}" method="POST" class="p-6">
                @csrf
                @method('PUT') {{-- Método PUT --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Campo: Nombre (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="criterion_name" class="block text-sm font-semibold text-slate-300 mb-1">Nombre del Criterio</label>
                        <input type="text" name="criterion_name" id="criterion_name" value="{{ old('criterion_name', $criterion->criterion_name) }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('criterion_name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo Respuesta (Pre-seleccionado) --}}
                    <div>
                        <label for="response_type" class="block text-sm font-semibold text-slate-300 mb-1">Tipo de Respuesta Esperada</label>
                        <select name="response_type" id="response_type" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="">Seleccione...</option>
                            <option value="numeric" {{ old('response_type', $criterion->response_type) == 'numeric' ? 'selected' : '' }}>Numérica</option>
                            <option value="text" {{ old('response_type', $criterion->response_type) == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                            <option value="option" {{ old('response_type', $criterion->response_type) == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                        </select>
                        @error('response_type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror

                        {{-- INICIO: CONTENEDOR PARA OPCIONES (OCULTO) --}}
                        <div id="criterionOptionsContainer" class="mt-4 space-y-1 hidden">
                            <label for="options_text" class="block text-xs font-semibold text-slate-400">Opciones (una por línea)</label>
                            <textarea name="options_text" id="options_text" rows="3"
                                      class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                                      placeholder="Opción 1&#10;Opción 2...">{{ old('options_text', $optionsText ?? '') }}</textarea> {{-- Pre-llenado --}}
                            @error('options_text') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        {{-- FIN: CONTENEDOR PARA OPCIONES --}}
                    </div>

                    {{-- Campo: Estado (Pre-seleccionado) --}}
                    <div>
                        <label for="state" class="block text-sm font-semibold text-slate-300 mb-1">Estado</label>
                        <select name="state" id="state" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="active" {{ old('state', $criterion->state) == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('state', $criterion->state) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('state') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Categoría (Pre-llenado) --}}
                    <div>
                        <label for="category" class="block text-sm font-semibold text-slate-300 mb-1">Categoría (Opcional)</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $criterion->category) }}"
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('category') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Peso (Pre-llenado) --}}
                    <div>
                        <label for="percentage_weight" class="block text-sm font-semibold text-slate-300 mb-1">Peso (%) (Opcional)</label>
                        <input type="number" name="percentage_weight" id="percentage_weight" value="{{ old('percentage_weight', $criterion->percentage_weight) }}" step="0.01" min="0" max="100"
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('percentage_weight') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.evaluation-criteria.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    {{-- Cambiamos texto del botón --}}
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Actualizar Criterio
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
                    // No limpiamos el valor en editar: optionsTextarea.value = '';
                }
            }
            responseTypeSelect.addEventListener('change', toggleCriterionOptions);
            toggleCriterionOptions(); // Ejecutar al cargar para mostrar si ya es 'option'
        } else {
            console.error('Error JS: No se encontraron elementos para opciones de criterio.');
        }
    });
</script>
{{-- FIN: SCRIPT --}}

@endsection