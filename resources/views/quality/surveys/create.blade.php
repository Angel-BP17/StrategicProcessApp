@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Crear Nueva Encuesta') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h1 class="text-2xl font-medium text-white mb-6">
                Datos de la Nueva Encuesta
            </h1>

            <form action="{{ route('quality.surveys.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Campo: Título (Reemplaza Descripción) --}}
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-300">Título de la Encuesta</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                               placeholder="Ej: Encuesta de Satisfacción General - Ciclo 2025-2">
                        @error('title') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Descripción (Opcional) --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-300">Descripción (Opcional)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Tipo de Objetivo (Target Type) --}}
                    <div>
                        <label for="target_type" class="block text-sm font-medium text-gray-300">Dirigido a:</label>
                        <select name="target_type" id="target_type" required
                                class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">Seleccione...</option>
                            <option value="students" {{ old('target_type') == 'students' ? 'selected' : '' }}>Estudiantes</option>
                            <option value="teachers" {{ old('target_type') == 'teachers' ? 'selected' : '' }}>Docentes</option>
                            <option value="graduates" {{ old('target_type') == 'graduates' ? 'selected' : '' }}>Egresados</option>
                            <option value="companies" {{ old('target_type') == 'companies' ? 'selected' : '' }}>Empresas</option>
                            <option value="general" {{ old('target_type') == 'general' ? 'selected' : '' }}>General</option>
                            {{-- Puedes añadir más opciones si las necesitas --}}
                        </select>
                        @error('target_type') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                     {{-- Campo: Estado Inicial --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300">Estado Inicial</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Borrador (Draft)</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activa (Active)</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Cerrada (Closed)</option>
                        </select>
                        @error('status') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('quality.surveys.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                        Crear Encuesta
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection