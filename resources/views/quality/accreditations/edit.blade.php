@extends('layouts.app')

@section('title', 'Editar Acreditación')

{{-- @section('header') ... @endsection --}} {{-- Quitamos el header si el layout lo maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Acreditaciones</p>
             <h1 class="text-3xl font-semibold">Editando: {{ Str::limit($accreditation->entity, 40) }}</h1> {{-- Usamos el nombre en el título --}}
         </div>

        {{-- "Caja" del Formulario --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos de la Acreditación</div>

            {{-- Formulario --}}
            <form action="{{ route('quality.accreditations.update', $accreditation) }}" method="POST" class="p-6">
                @csrf
                @method('PUT') {{-- Método PUT para actualizar --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Campo: Entidad (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="entity" class="block text-sm font-semibold text-slate-300 mb-1">Entidad Acreditadora</label>
                        <input type="text" name="entity" id="entity" value="{{ old('entity', $accreditation->entity) }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('entity') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Resultado (Pre-llenado) --}}
                    <div class="md:col-span-2">
                        <label for="result" class="block text-sm font-semibold text-slate-300 mb-1">Resultado</label>
                        <input type="text" name="result" id="result" value="{{ old('result', $accreditation->result) }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('result') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Fecha de Acreditación (Pre-llenado) --}}
                    <div>
                        <label for="accreditation_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de Obtención</label>
                        <input type="date" name="accreditation_date" id="accreditation_date" value="{{ old('accreditation_date', $accreditation->accreditation_date) }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('accreditation_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Fecha de Expiración (Pre-llenado) --}}
                    <div>
                        <label for="expiration_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de Expiración (Opcional)</label>
                        <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', $accreditation->expiration_date) }}"
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('expiration_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.accreditations.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    {{-- Cambiamos texto del botón --}}
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Actualizar Acreditación
                    </button>
                </div>
            </form>

        </div> {{-- Fin "Caja" del Formulario --}}
    </div> {{-- Fin contenedor principal --}}
@endsection