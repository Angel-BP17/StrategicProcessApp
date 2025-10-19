@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Editar Criterio de Evaluación') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h1 class="text-2xl font-medium text-white mb-6">
                Editando: {{ $criterion->criterion_name }}
            </h1>

            {{-- El formulario apunta a la ruta 'update' --}}
            <form action="{{ route('quality.evaluation-criteria.update', $criterion) }}" method="POST">
                @csrf
                @method('PUT') {{-- Usamos método PUT --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Campo: Nombre (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="criterion_name" class="block text-sm font-medium text-gray-300">Nombre del Criterio</label>
                        <input type="text" name="criterion_name" id="criterion_name" value="{{ old('criterion_name', $criterion->criterion_name) }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('criterion_name') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo Respuesta (Pre-seleccionado) --}}
                    <div>
                        <label for="response_type" class="block text-sm font-medium text-gray-300">Tipo de Respuesta Esperada</label>
                        <select name="response_type" id="response_type" required
                                class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">Seleccione...</option>
                            <option value="numeric" {{ old('response_type', $criterion->response_type) == 'numeric' ? 'selected' : '' }}>Numérica</option>
                            <option value="text" {{ old('response_type', $criterion->response_type) == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                            <option value="option" {{ old('response_type', $criterion->response_type) == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                        </select>
                        @error('response_type') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror

                        {{-- INICIO: CONTENEDOR PARA OPCIONES (OCULTO) --}}
                        <div id="criterionOptionsContainer" class="mt-4 space-y-2 hidden">
                            <label for="options_text" class="block text-sm font-medium text-gray-300">Opciones de Respuesta (una por línea)</label>
                            {{-- Pre-llenamos con las opciones existentes formateadas --}}
                            <textarea name="options_text" id="options_text" rows="4"
                                    class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                    placeholder="Opción 1&#10;Opción 2&#10;Opción 3...">{{ old('options_text', $optionsText ?? '') }}</textarea>
                            <small class="text-gray-400">Escribe cada opción en una nueva línea.</small>
                            @error('options_text') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- FIN: CONTENEDOR PARA OPCIONES --}}

                    </div>

                     {{-- Campo: Estado (Pre-seleccionado) --}}
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-300">Estado</label>
                        <select name="state" id="state" required
                                class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="active" {{ old('state', $criterion->state) == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('state', $criterion->state) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('state') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                     {{-- Campo: Categoría (Pre-llenado) --}}
                     <div>
                        <label for="category" class="block text-sm font-medium text-gray-300">Categoría (Opcional)</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $criterion->category) }}"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('category') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Peso (Pre-llenado) --}}
                    <div>
                        <label for="percentage_weight" class="block text-sm font-medium text-gray-300">Peso (%) (Opcional)</label>
                        <input type="number" name="percentage_weight" id="percentage_weight" value="{{ old('percentage_weight', $criterion->percentage_weight) }}" step="0.01" min="0" max="100"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('percentage_weight') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    {{-- Futuro: Si es 'option', mostrar un área para editar opciones --}}
                </div>

                {{-- Botones --}}
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('quality.evaluation-criteria.index') }}" class="text-red-500 hover:text-red-700">Cancelar</a>
                    <button type="submit" class="text-yellow-500 hover:text-yellow-700">Actualizar Criterio</button>
                </div>
            </form>

        </div>
    </div>
</div>

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