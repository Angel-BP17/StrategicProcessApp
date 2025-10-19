@extends('layouts.app') {{-- Asegúrate que 'layouts.app' sea tu plantilla principal --}}

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{-- Mostramos el título de la encuesta (limitado a 50 caracteres) --}}
    Diseñando Encuesta: {{ Str::limit($survey->title, 50) }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Caja principal con fondo oscuro --}}
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            {{-- Botón para volver al listado de encuestas --}}
            <div class="mb-6">
                <a href="{{ route('quality.surveys.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 transition ease-in-out duration-150">
                    &larr; Volver al Listado de Encuestas
                </a>
            </div>

            {{-- Título y descripción de la encuesta que se está diseñando --}}
            <h1 class="text-2xl font-medium text-white mb-2">
                {{ $survey->title }}
            </h1>
            <p class="text-gray-400 mb-6">{{ $survey->description ?? 'Añade o edita las preguntas para esta encuesta.' }}</p>

            {{-- Divisor visual --}}
            <hr class="border-gray-700 my-6">

            {{-- ====================================================== --}}
            {{-- SECCIÓN 1: Formulario para Añadir Nueva Pregunta       --}}
            {{-- ====================================================== --}}
            <h2 class="text-xl font-medium text-white mb-4">Añadir Nueva Pregunta</h2>
            {{-- El formulario apunta a la ruta para guardar la pregunta --}}
            <form action="{{ route('quality.surveys.questions.store', $survey) }}" method="POST" class="bg-night p-6 rounded-lg mb-8">
                @csrf
                {{-- No necesitamos campo oculto para survey_id, Laravel lo inyecta por la ruta --}}

                {{-- Campo: Texto de la Pregunta --}}
                <div class="mb-4">
                    <label for="question_text" class="block text-sm font-medium text-gray-300">Texto de la Pregunta</label>
                    <textarea name="question_text" id="question_text" rows="3" required
                              class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('question_text') }}</textarea>
                    {{-- Mostramos errores de validación si los hay --}}
                    @error('question_text') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Campo: Tipo de Respuesta --}}
                <div class="mb-4">
                    <label for="question_type" class="block text-sm font-medium text-gray-300">Tipo de Respuesta</label>
                    <select name="question_type" id="question_type" required
                            class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                        <option value="option" {{ old('question_type') == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                        <option value="rating_1_5" {{ old('question_type') == 'rating_1_5' ? 'selected' : '' }}>Calificación (1-5)</option>
                        {{-- Puedes añadir más tipos según la columna 'question_type' en tu tabla 'survey_questions' --}}
                    </select>
                    @error('question_type') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror

                    {{-- INICIO: CONTENEDOR PARA OPCIONES (OCULTO POR DEFECTO) --}}
                    <div id="optionsContainer" class="mt-4 space-y-2 hidden">
                        <label class="block text-sm font-medium text-gray-300">Opciones de Respuesta (una por línea)</label>
                        <textarea name="options_text" id="options_text" rows="4"
                                  class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                  placeholder="Opción 1&#10;Opción 2&#10;Opción 3..."></textarea>
                         <small class="text-gray-400">Escribe cada opción en una nueva línea.</small>
                         @error('options_text') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    {{-- FIN: CONTENEDOR PARA OPCIONES --}}

                </div> {{-- Cierre del div del campo 'Tipo de Respuesta' --}}

                {{-- Futuro: Aquí podríamos añadir dinámicamente campos para las opciones si el tipo es 'option' --}}
                {{-- <div id="optionsContainer" class="hidden"> ... </div> --}}

                {{-- Botón de envío --}}
                <div class="text-right">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75 transition ease-in-out duration-150">
                        Añadir Pregunta
                    </button>
                </div>
            </form>

            {{-- ====================================================== --}}
            {{-- SECCIÓN 2: Listado de Preguntas Existentes            --}}
            {{-- ====================================================== --}}
            <h2 class="text-xl font-medium text-white mb-4">Preguntas de la Encuesta</h2>
            <div class="bg-night p-4 rounded-lg space-y-4">
                {{-- Usamos la relación 'questions' cargada en el controlador --}}
                @forelse ($survey->questions->sortBy('order') as $question) {{-- Ordenamos por la columna 'order' --}}
                    <div class="border-b border-gray-700 pb-4">
                        {{-- Texto de la pregunta --}}
                        <p class="text-gray-200 font-medium">{{ $question->order ?? $loop->iteration }}. {{ $question->question_text }}</p>
                        {{-- Tipo de pregunta --}}
                        <small class="text-gray-400 italic">Tipo: {{ $question->question_type }}</small>

                        {{-- Mostramos las opciones si es de tipo 'option' y tiene opciones cargadas --}}
                        @if ($question->question_type == 'option' && $question->options->isNotEmpty())
                            <ul class="list-disc list-inside ml-4 mt-2 text-gray-300 text-sm space-y-1">
                                @foreach ($question->options->sortBy('order') as $option) {{-- Ordenamos opciones --}}
                                    <li>{{ $option->option_text }}</li>
                                @endforeach
                            </ul>
                            {{-- Futuro: Botón para añadir/editar opciones --}}
                        @endif

                        {{-- Futuro: Botones para Editar/Eliminar la pregunta --}}
                        <div class="mt-2 text-right text-xs">
                            <a href="{{ route('quality.surveys.questions.edit', ['survey' => $survey, 'question' => $question]) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a>
                            {{-- ASEGÚRATE QUE EL ACTION SEA ESTE --}}
                            <form action="{{ route('quality.surveys.questions.destroy', ['survey' => $survey, 'question' => $question]) }}" method="POST" class="inline-block ml-2">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('¿Eliminar esta pregunta?')">Eliminar</button>
                            </form>
                        </div>
                    </div>
                @empty
                    {{-- Mensaje si no hay preguntas --}}
                    <p class="text-gray-400 text-center py-4">Aún no has añadido preguntas a esta encuesta.</p>
                @endforelse
            </div>

        </div> {{-- Fin de la caja principal --}}
    </div> {{-- Fin del contenedor max-w-7xl --}}
</div> {{-- Fin del py-12 --}}

{{-- INICIO: SCRIPT PARA MOSTRAR/OCULTAR OPCIONES --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questionTypeSelect = document.getElementById('question_type');
        const optionsContainer = document.getElementById('optionsContainer');
        const optionsTextarea = document.getElementById('options_text');

        if (questionTypeSelect && optionsContainer && optionsTextarea) {
            function toggleOptions() {
                // USA COMILLAS SIMPLES REALES (')
                if (questionTypeSelect.value === 'option') { 
                    optionsContainer.classList.remove('hidden'); 
                    optionsTextarea.required = true;
                } else {
                    optionsContainer.classList.add('hidden'); 
                    optionsTextarea.required = false;
                    optionsTextarea.value = ''; 
                }
            }
            questionTypeSelect.addEventListener('change', toggleOptions);
            toggleOptions(); 
        } else {
            console.error('Error: IDs question_type, optionsContainer u options_text no encontrados.');
        }
    });
</script>

{{-- FIN: SCRIPT --}}

@endsection