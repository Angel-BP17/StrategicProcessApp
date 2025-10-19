@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Editar Acreditación') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h1 class="text-2xl font-medium text-white mb-6">
                Editando: {{ $accreditation->entity }}
            </h1>

            {{-- CAMBIO 1: La acción apunta a 'update' y pasamos el ID --}}
            <form action="{{ route('quality.accreditations.update', $accreditation) }}" method="POST">
                @csrf
                @method('PUT') {{-- CAMBIO 2: Le decimos a Laravel que esto es un PUT --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- CAMBIO 3: 'value' se rellena con los datos existentes --}}
                    <div class="md:col-span-2">
                        <label for="entity" class="block text-sm font-medium text-gray-300">Entidad Acreditadora</label>
                        <input type="text" name="entity" id="entity" value="{{ old('entity', $accreditation->entity) }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('entity') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="result" class="block text-sm font-medium text-gray-300">Resultado</label>
                        <input type="text" name="result" id="result" value="{{ old('result', $accreditation->result) }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('result') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="accreditation_date" class="block text-sm font-medium text-gray-300">Fecha de Obtención</label>
                        <input type="date" name="accreditation_date" id="accreditation_date" value="{{ old('accreditation_date', $accreditation->accreditation_date) }}" required
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('accreditation_date') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="expiration_date" class="block text-sm font-medium text-gray-300">Fecha de Expiración (Opcional)</label>
                        <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', $accreditation->expiration_date) }}"
                               class="mt-1 block w-full bg-night border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('expiration_date') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('quality.accreditations.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                        Actualizar Acreditación
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection