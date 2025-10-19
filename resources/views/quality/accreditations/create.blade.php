@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Registrar Nueva Acreditación') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h1 class="text-2xl font-medium text-white mb-6">
                Datos de la Acreditación
            </h1>

            <form action="{{ route('quality.accreditations.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Campo: Entidad --}}
                    <div class="md:col-span-2">
                        <label for="entity" class="block text-sm font-medium text-gray-300">Entidad Acreditadora</label>
                        <input type="text" name="entity" id="entity" value="{{ old('entity') }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('entity') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Resultado --}}
                    <div class="md:col-span-2">
                        <label for="result" class="block text-sm font-medium text-gray-300">Resultado (Ej. "Aprobado", "Certificación ISO 9001")</label>
                        <input type="text" name="result" id="result" value="{{ old('result') }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('result') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Fecha de Acreditación --}}
                    <div>
                        <label for="accreditation_date" class="block text-sm font-medium text-gray-300">Fecha de Obtención</label>
                        <input type="date" name="accreditation_date" id="accreditation_date" value="{{ old('accreditation_date') }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('accreditation_date') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Fecha de Expiración --}}
                    <div>
                        <label for="expiration_date" class="block text-sm font-medium text-gray-300">Fecha de Expiración (Opcional)</label>
                        <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('expiration_date') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('quality.accreditations.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                        Guardar Acreditación
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection