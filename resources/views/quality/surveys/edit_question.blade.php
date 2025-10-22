@extends('layouts.app')

@section('title', 'Editar Pregunta')

{{-- @section('header') ... @endsection --}} {{-- Quitamos si layout maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Encuestas / Diseñar</p>
             <h1 class="text-3xl font-semibold">Editando Pregunta</h1>
             <p class="text-slate-400 mt-1">Encuesta: {{ Str::limit($survey->title, 50) }}</p>
         </div>

         {{-- Botón Volver --}}
         <div class="mb-6">
            <a href="{{ route('quality.surveys.design', $survey) }}"
               class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                &larr; Volver al Diseño de Encuesta
            </a>
        </div>

        {{-- "Caja" del Formulario --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos de la Pregunta</div>

            {{-- Formulario --}}
            <form action="{{ route('quality.surveys.questions.update', ['survey' => $survey, 'question' => $question]) }}" method="POST" class="p-6">
                @csrf
                @method('PUT') {{-- Método PUT --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Ajustado a 2 columnas --}}

                    {{-- Campo: Texto Pregunta (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="question_text" class="block text-sm font-semibold text-slate-300 mb-1">Texto de la Pregunta</label>
                        <textarea name="question_text" id="question_text" rows="3" required
                                  class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo Respuesta (Pre-seleccionado) --}}
                    <div>
                        <label for="question_type" class="block text-sm font-semibold text-slate-300 mb-1">Tipo de Respuesta</label>
                        <select name="question_type" id="question_type" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="text" {{ old('question_type', $question->question_type) == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                            <option value="option" {{ old('question_type', $question->question_type) == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                            <option value="rating_1_5" {{ old('question_type', $question->question_type) == 'rating_1_5' ? 'selected' : '' }}>Calificación (1-5)</option>
                        </select>
                        @error('question_type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror

                        {{-- Contenedor Opciones (estilo adaptado, pre-llenado) --}}
                        <div id="optionsContainer" class="mt-4 space-y-1 hidden">
                            <label for="options_text" class="block text-xs font-semibold text-slate-400">Opciones (una por línea)</label>
                            <textarea name="options_text" id="options_text" rows="3"
                                      class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                                      placeholder="Opción 1&#10;Opción 2...">{{ old('options_text', $optionsText) }}</textarea>
                            @error('options_text') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Espacio vacío para alinear botón --}}
                    <div></div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.surveys.design', $survey) }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Actualizar Pregunta
                    </button>
                </div>
            </form>

        </div> {{-- Fin "Caja" del Formulario --}}
    </div> {{-- Fin contenedor principal --}}

{{-- INICIO: SCRIPT PARA OPCIONES (Sin cambios, importante no limpiar value) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questionTypeSelect = document.getElementById('question_type');
        const optionsContainer = document.getElementById('optionsContainer');
        const optionsTextarea = document.getElementById('options_text');
        if (questionTypeSelect && optionsContainer && optionsTextarea) {
            function toggleOptions() {
                if (questionTypeSelect.value === 'option') {
                    optionsContainer.classList.remove('hidden');
                    optionsTextarea.required = true;
                } else {
                    optionsContainer.classList.add('hidden');
                    optionsTextarea.required = false;
                    // No limpiamos value aquí
                }
            }
            questionTypeSelect.addEventListener('change', toggleOptions);
            toggleOptions(); // Ejecutar al cargar
        } else {
            console.error('Error: IDs question_type, optionsContainer or options_text not found.');
        }
    });
</script>
{{-- FIN: SCRIPT --}}

@endsection