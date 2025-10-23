@extends('layouts.app')

@section('title', 'Diseñar Encuesta')

{{-- @section('header') ... @endsection --}} {{-- Quitamos si layout maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Encuestas</p>
             <h1 class="text-3xl font-semibold">Diseñando: {{ Str::limit($survey->title, 50) }}</h1>
             <p class="text-slate-400 mt-1">{{ $survey->description }}</p>
         </div>

        {{-- Botón Volver --}}
        <div class="mb-6">
            <a href="{{ route('quality.surveys.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                &larr; Volver al Listado
            </a>
        </div>

        {{-- "Caja" Principal --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">

            {{-- SECCIÓN 1: Añadir Nueva Pregunta --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Añadir Nueva Pregunta</div>
            <form action="{{ route('quality.surveys.questions.store', $survey) }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Campo: Texto Pregunta --}}
                    <div class="md:col-span-2">
                        <label for="question_text" class="block text-sm font-semibold text-slate-300 mb-1">Texto de la Pregunta</label>
                        <textarea name="question_text" id="question_text" rows="3" required
                                  class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">{{ old('question_text') }}</textarea>
                        @error('question_text') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo Respuesta --}}
                    <div>
                        <label for="question_type" class="block text-sm font-semibold text-slate-300 mb-1">Tipo de Respuesta</label>
                        <select name="question_type" id="question_type" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Texto Abierto</option>
                            <option value="option" {{ old('question_type') == 'option' ? 'selected' : '' }}>Opción Múltiple</option>
                            <option value="rating_1_5" {{ old('question_type') == 'rating_1_5' ? 'selected' : '' }}>Calificación (1-5)</option>
                        </select>
                        @error('question_type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror

                        {{-- Contenedor Opciones (estilo adaptado) --}}
                        <div id="optionsContainer" class="mt-4 space-y-1 hidden">
                            <label for="options_text" class="block text-xs font-semibold text-slate-400">Opciones (una por línea)</label>
                            <textarea name="options_text" id="options_text" rows="3"
                                      class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                                      placeholder="Opción 1&#10;Opción 2..."></textarea>
                            @error('options_text') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Espacio vacío para alinear botón --}}
                    <div></div>

                </div>
                {{-- Botón Añadir Pregunta --}}
                <div class="mt-4 text-right">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Añadir Pregunta
                    </button>
                </div>
            </form>

            {{-- SECCIÓN 2: Preguntas Existentes --}}
            <div class="p-6 font-semibold text-lg border-y border-slate-800/70 text-slate-200">Preguntas de la Encuesta</div>
            <div class="p-6 space-y-4">
                @forelse ($survey->questions->sortBy('order') as $question)
                    <div class="bg-slate-900/70 p-4 rounded-lg border border-slate-800/70">
                        {{-- Texto y Tipo --}}
                        <div class="flex justify-between items-start">
                             <div>
                                 <p class="text-slate-100 font-medium">{{ $loop->iteration }}. {{ $question->question_text }}</p>
                                 <small class="text-slate-400 italic">Tipo: {{ $question->question_type }}</small>
                             </div>
                             {{-- Botones Editar/Eliminar --}}
                             <div class="flex items-center space-x-3 text-xs flex-shrink-0">
                                <a href="{{ route('quality.surveys.questions.edit', ['survey' => $survey, 'question' => $question]) }}"
                                   class="inline-flex items-center px-2 py-1 rounded bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition">Editar</a>
                                <form action="{{ route('quality.surveys.questions.destroy', ['survey' => $survey, 'question' => $question]) }}" method="POST" class="m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 rounded bg-rose-600/90 text-white font-semibold hover:bg-rose-500 transition"
                                            onclick="return confirm('¿Eliminar esta pregunta?')">Eliminar</button>
                                </form>
                            </div>
                        </div>
                        {{-- Opciones (si existen) --}}
                        @if ($question->question_type == 'option' && $question->options->isNotEmpty())
                            <ul class="list-disc list-inside ml-4 mt-2 text-slate-300 text-sm space-y-1">
                                @foreach ($question->options->sortBy('order') as $option)
                                    <li>{{ $option->option_text }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @empty
                    <p class="text-slate-500 text-center py-4">Aún no has añadido preguntas a esta encuesta.</p>
                @endforelse
            </div>

        </div> {{-- Fin "Caja" Principal --}}
    </div> {{-- Fin contenedor principal --}}

{{-- INICIO: SCRIPT PARA OPCIONES (Sin cambios) --}}
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