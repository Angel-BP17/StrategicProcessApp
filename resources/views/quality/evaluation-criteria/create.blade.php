@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Crear Nuevo Criterio de Evaluación') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h1 class="text-2xl font-medium text-white mb-6">
                Datos del Nuevo Criterio
            </h1>

            <form action="{{ route('quality.evaluation-criteria.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Campo: Nombre del Criterio --}}
                    <div class="md:col-span-2">
                        <label for="criterion_name" class="block text-sm font-medium text-gray-300">Nombre del Criterio</label>
                        <input type="text" name="criterion_name" id="criterion_name" value="{{ old('criterion_name') }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                               placeholder="Ej: Claridad en la explicación">
                        @error('criterion_name') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo de Respuesta --}}
                    <div>
                        <label for="response_type" class="block text-sm font-medium text-gray-300">Tipo de Respuesta Esperada</label>
                        <select name="response_type" id="response_type" required
                                class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">Seleccione...</option>
                            <option value="numeric" {{ old('response_type') == 'numeric' ? 'selected' : '' }}>Numérica (Ej: Calificación 1-5)</option>
                            <option value="text" {{ old('response_type') == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                            <option value="option" {{ old('response_type') == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                        </select>
                        @error('response_type') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror

                        {{-- INICIO: CONTENEDOR PARA OPCIONES (OCULTO) --}}
                        <div id="criterionOptionsContainer" class="mt-4 space-y-2 hidden">
                            <label for="options_text" class="block text-sm font-medium text-gray-300">Opciones de Respuesta (una por línea)</label>
                            <textarea name="options_text" id="options_text" rows="4"
                                    class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                    placeholder="Opción 1&#10;Opción 2&#10;Opción 3..."></textarea>
                            <small class="text-gray-400">Escribe cada opción en una nueva línea.</small>
                            {{-- Mostraremos errores de validación aquí --}}
                            @error('options_text') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- FIN: CONTENEDOR PARA OPCIONES --}}
                    </div>

                     {{-- Campo: Estado --}}
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-300">Estado</label>
                        <select name="state" id="state" required
                                class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="active" {{ old('state', 'active') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('state') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('state') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                     {{-- Campo: Categoría (Opcional) --}}
                     <div>
                        <label for="category" class="block text-sm font-medium text-gray-300">Categoría (Opcional)</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                               placeholder="Ej: Pedagogía, Contenido, Puntualidad">
                        @error('category') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Peso Porcentual (Opcional) --}}
                    <div>
                        <label for="percentage_weight" class="block text-sm font-medium text-gray-300">Peso (%) (Opcional)</label>
                        <input type="number" name="percentage_weight" id="percentage_weight" value="{{ old('percentage_weight') }}" step="0.01" min="0" max="100"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                               placeholder="Ej: 20.5">
                        @error('percentage_weight') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('quality.evaluation-criteria.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                        Guardar Criterio
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- INICIO: SCRIPT PARA OPCIONES DE CRITERIO --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const responseTypeSelect = document.getElementById('response_type');
        const optionsContainer = document.getElementById('criterionOptionsContainer');
        const optionsTextarea = document.getElementById('options_text');

        if (responseTypeSelect && optionsContainer && optionsTextarea) {
            function toggleCriterionOptions() {
                if (responseTypeSelect.value === 'option') {
                    optionsContainer.classList.remove('hidden');
                    optionsTextarea.required = true; // Hacer obligatorio si se muestra
                } else {
                    optionsContainer.classList.add('hidden');
                    optionsTextarea.required = false;
                    optionsTextarea.value = ''; // Limpiar al ocultar
                }
            }
            responseTypeSelect.addEventListener('change', toggleCriterionOptions);
            toggleCriterionOptions(); // Ejecutar al cargar
        } else {
            console.error('Error JS: No se encontraron elementos para opciones de criterio.');
        }
    });
</script>

@endsection