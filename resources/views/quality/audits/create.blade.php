@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Planificar Nueva Auditoría') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Usamos bg-dark-purple de tu app.css --}}
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h1 class="text-2xl font-medium text-white mb-6">
                Datos de la Nueva Auditoría
            </h1>

            {{-- 
              Este formulario enviará los datos al método 'store' 
              del controlador usando el nombre de la ruta 'quality.audits.store'
            --}}
            <form action="{{ route('quality.audits.store') }}" method="POST">
                @csrf {{-- Token de seguridad de Laravel --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Campo: Área --}}
                    <div>
                        <label for="area" class="block text-sm font-medium text-gray-300">Área a Auditar</label>
                        <input type="text" name="area" id="area" value="{{ old('area') }}" 
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('area')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Tipo --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-300">Tipo de Auditoría</label>
                        <select name="type" id="type" 
                                class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="internal" {{ old('type') == 'internal' ? 'selected' : '' }}>Interna</option>
                            <option value="external" {{ old('type') == 'external' ? 'selected' : '' }}>Externa</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Objetivo --}}
                    <div class="md:col-span-2">
                        <label for="objective" class="block text-sm font-medium text-gray-300">Objetivo</label>
                        <input type="text" name="objective" id="objective" value="{{ old('objective') }}"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('objective')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Alcance (Range) --}}
                    <div class="md:col-span-2">
                        <label for="range" class="block text-sm font-medium text-gray-300">Alcance</label>
                        <textarea name="range" id="range" rows="3" 
                                  class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('range') }}</textarea>
                        @error('range')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Fecha de Inicio --}}
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-300">Fecha de Inicio</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('start_date')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Fecha de Fin --}}
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-300">Fecha de Fin</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('end_date')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('quality.audits.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                        Guardar Auditoría
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection