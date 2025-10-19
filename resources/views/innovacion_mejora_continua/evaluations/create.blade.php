@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-4">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li><a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-blue-600">Innovación y Mejora Continua</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="hover:text-blue-600">Iniciativas</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="hover:text-blue-600">{{ Str::limit($initiative->title, 30) }}</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800 font-medium">Nueva Evaluación</li>
        </ol>
    </nav>

    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Nueva Evaluación</h1>
        <p class="text-gray-600">Evalúa las mejoras aplicadas de la iniciativa: <strong>{{ $initiative->title }}</strong></p>
    </div>

    <!-- Información de la Iniciativa -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mb-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-blue-800 font-semibold mb-1">{{ $initiative->title }}</h3>
                <p class="text-blue-700 text-sm">{{ Str::limit($initiative->summary, 150) }}</p>
                <div class="flex items-center gap-4 mt-2 text-xs text-blue-600">
                    <span class="font-medium">Estado: {{ ucfirst($initiative->status) }}</span>
                    <span>•</span>
                    <span>Evaluaciones anteriores: {{ $initiative->evaluations->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('innovacion-mejora-continua.initiatives.evaluations.store', $initiative) }}" method="POST">
            @csrf

            <!-- Fecha de Evaluación -->
            <div class="mb-6">
                <label for="evaluation_date" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha de Evaluación <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="evaluation_date" 
                       name="evaluation_date" 
                       value="{{ old('evaluation_date', date('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('evaluation_date') border-red-500 @enderror"
                       required>
                @error('evaluation_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Puntuación -->
            <div class="mb-6">
                <label for="score" class="block text-sm font-semibold text-gray-700 mb-2">
                    Puntuación <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-4">
                    <input type="number" 
                           id="score" 
                           name="score" 
                           min="0" 
                           max="10" 
                           step="0.1"
                           value="{{ old('score') }}"
                           class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('score') border-red-500 @enderror"
                           placeholder="0.0"
                           required>
                    <span class="text-gray-600">/ 10</span>
                    <div class="flex-1">
                        <input type="range" 
                               id="score-range" 
                               min="0" 
                               max="10" 
                               step="0.1" 
                               value="{{ old('score', 5) }}"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                               oninput="document.getElementById('score').value = this.value">
                    </div>
                </div>
                @error('score')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">Evalúa la efectividad de las mejoras aplicadas en una escala del 0 al 10</p>
            </div>

            <!-- Resumen de la Evaluación -->
            <div class="mb-6">
                <label for="summary" class="block text-sm font-semibold text-gray-700 mb-2">
                    Resumen de la Evaluación <span class="text-red-500">*</span>
                </label>
                <textarea id="summary" 
                          name="summary" 
                          rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('summary') border-red-500 @enderror"
                          placeholder="Describe los resultados obtenidos, impacto real vs estimado, viabilidad, costo, eficacia de la metodología aplicada..."
                          required>{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Incluye detalles sobre viabilidad, costo, eficacia y criterios evaluados</p>
            </div>

            <!-- Documento de Reporte (opcional) -->
            <div class="mb-6">
                <label for="report_document_version_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Documento de Reporte (Opcional)
                </label>
                <select id="report_document_version_id" 
                        name="report_document_version_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('report_document_version_id') border-red-500 @enderror">
                    <option value="">Sin documento adjunto</option>
                    {{-- Aquí irían los documentos disponibles si existen --}}
                </select>
                @error('report_document_version_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Adjunta un documento con el reporte detallado de la evaluación</p>
            </div>

            <!-- Criterios de Evaluación (Informativo) -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Criterios de Evaluación Sugeridos
                </h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>Viabilidad:</strong> Factibilidad técnica y operativa de la implementación</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>Impacto:</strong> Comparación entre impacto estimado vs impacto real obtenido</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>Costo:</strong> Análisis costo-beneficio de la iniciativa</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>Eficacia:</strong> Grado de cumplimiento de objetivos establecidos</span>
                    </li>
                </ul>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Registrar Evaluación
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Sincronizar input number con range
    document.getElementById('score').addEventListener('input', function() {
        document.getElementById('score-range').value = this.value;
    });
</script>
@endsection
