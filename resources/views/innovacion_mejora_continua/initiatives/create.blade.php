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
            <li class="text-gray-800 font-medium">Nueva Iniciativa</li>
        </ol>
    </nav>

    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Crear Nueva Iniciativa</h1>
        <p class="text-gray-600">Registra una nueva iniciativa de innovación, implementación de nuevas metodologías o mejora continua</p>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('innovacion-mejora-continua.initiatives.store') }}" method="POST">
            @csrf

            <!-- Plan ID -->
            <div class="mb-6">
                <label for="plan_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    ID del Plan <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="plan_id" 
                       name="plan_id" 
                       value="{{ old('plan_id') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('plan_id') border-red-500 @enderror"
                       placeholder="Ej: PLAN-2025-001"
                       required>
                @error('plan_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Título -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Título de la Iniciativa <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       placeholder="Ingrese un título descriptivo para la iniciativa"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Resumen -->
            <div class="mb-6">
                <label for="summary" class="block text-sm font-semibold text-gray-700 mb-2">
                    Resumen <span class="text-red-500">*</span>
                </label>
                <textarea id="summary" 
                          name="summary" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('summary') border-red-500 @enderror"
                          placeholder="Descripción breve de la iniciativa (máximo 500 caracteres)"
                          required>{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Describe brevemente el propósito y alcance de la iniciativa</p>
            </div>

            <!-- Descripción Completa -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Descripción Completa
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Detalla los objetivos, metodología, recursos necesarios y resultados esperados">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid de 2 columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Usuario Responsable -->
                <div>
                    <label for="responsible_user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Usuario Responsable
                    </label>
                    <select id="responsible_user_id" 
                            name="responsible_user_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('responsible_user_id') border-red-500 @enderror">
                        <option value="">Seleccionar usuario</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('responsible_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_user_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Equipo Responsable -->
                <div>
                    <label for="responsible_team_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Equipo Responsable
                    </label>
                    <select id="responsible_team_id" 
                            name="responsible_team_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('responsible_team_id') border-red-500 @enderror">
                        <option value="">Seleccionar equipo</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('responsible_team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_team_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid de 2 columnas para fechas y estado -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Fecha de Inicio -->
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Fecha de Inicio
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Fin -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Fecha de Fin
                    </label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ old('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Estado -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select id="status" 
                        name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                        required>
                    <option value="propuesta" {{ old('status') == 'propuesta' ? 'selected' : '' }}>Propuesta</option>
                    <option value="evaluada" {{ old('status') == 'evaluada' ? 'selected' : '' }}>Evaluada</option>
                    <option value="aprobada" {{ old('status') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                    <option value="implementada" {{ old('status') == 'implementada' ? 'selected' : '' }}>Implementada</option>
                    <option value="cerrada" {{ old('status') == 'cerrada' ? 'selected' : '' }}>Cerrada</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Workflow: propuesta → evaluada → aprobada → implementada → cerrada</p>
            </div>

            <!-- Impacto Estimado -->
            <div class="mb-6">
                <label for="estimated_impact" class="block text-sm font-semibold text-gray-700 mb-2">
                    Impacto Estimado
                </label>
                <input type="text" 
                       id="estimated_impact" 
                       name="estimated_impact" 
                       value="{{ old('estimated_impact') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('estimated_impact') border-red-500 @enderror"
                       placeholder="Ej: Reducción del 20% en tiempos de proceso, mejora en satisfacción del cliente">
                @error('estimated_impact')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Describe el impacto esperado de implementar esta iniciativa</p>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Crear Iniciativa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
