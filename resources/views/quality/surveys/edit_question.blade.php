@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    Editando Pregunta (Encuesta: {{ Str::limit($survey->title, 30) }})
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            {{-- Back button --}}
            <div class="mb-6">
                <a href="{{ route('quality.surveys.design', $survey) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 transition ease-in-out duration-150">
                    &larr; Volver al Diseño de Encuesta
                </a>
            </div>

            <h1 class="text-2xl font-medium text-white mb-6">
                Editar Pregunta
            </h1>

            {{-- Form points to the update route --}}
            <form action="{{ route('quality.surveys.questions.update', ['survey' => $survey, 'question' => $question]) }}" method="POST" class="bg-night p-6 rounded-lg mb-8">
                @csrf
                @method('PUT') {{-- Use PUT method for update --}}

                {{-- Question Text --}}
                <div class="mb-4">
                    <label for="question_text" class="block text-sm font-medium text-gray-300">Texto de la Pregunta</label>
                    {{-- Pre-fill with existing data, fallback to old input if validation fails --}}
                    <textarea name="question_text" id="question_text" rows="3" required
                              class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Question Type --}}
                <div class="mb-4">
                    <label for="question_type" class="block text-sm font-medium text-gray-300">Tipo de Respuesta</label>
                    <select name="question_type" id="question_type" required
                            class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        {{-- Pre-select the current type --}}
                        <option value="text" {{ old('question_type', $question->question_type) == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                        <option value="option" {{ old('question_type', $question->question_type) == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                        <option value="rating_1_5" {{ old('question_type', $question->question_type) == 'rating_1_5' ? 'selected' : '' }}>Calificación (1-5)</option>
                    </select>
                    @error('question_type') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror

                    {{-- Options Container (same as create view) --}}
                    <div id="optionsContainer" class="mt-4 space-y-2 hidden">
                        <label class="block text-sm font-medium text-gray-300">Opciones de Respuesta (una por línea)</label>
                        {{-- Pre-fill with existing options (formatted in controller) --}}
                        <textarea name="options_text" id="options_text" rows="4"
                                  class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                  placeholder="Opción 1&#10;Opción 2&#10;Opción 3...">{{ old('options_text', $optionsText) }}</textarea>
                         <small class="text-gray-400">Escribe cada opción en una nueva línea.</small>
                         @error('options_text') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-right">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75 transition ease-in-out duration-150">
                        Actualizar Pregunta
                    </button>
                </div>
            </form>

        </div> {{-- End main box --}}
    </div> {{-- End max-w --}}
</div> {{-- End py --}}

{{-- Include the same JavaScript as the design view --}}
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
                    // Don't clear value on edit form: optionsTextarea.value = '';
                }
            }
            questionTypeSelect.addEventListener('change', toggleOptions);
            toggleOptions(); // Run on page load to show options if pre-selected
        } else {
            console.error('Error: IDs question_type, optionsContainer or options_text not found.');
        }
    });
</script>

@endsection