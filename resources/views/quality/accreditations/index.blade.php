@extends('layouts.app')

@section('title', 'Procesos de Acreditación')

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad</p>
                <h1 class="text-3xl font-semibold">Procesos de Acreditación</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('quality.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                   &larr; Volver al Panel
                </a>
                {{-- Botón Crear --}}
                <a href="{{ route('quality.accreditations.create') }}"
                   class="inline-flex items-center gap-2 bg-sky-500/90 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition">
                   Registrar Nueva
                </a>
            </div>
        </div>

        {{-- "Caja" de la Tabla --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Acreditaciones Registradas</div>

            {{-- Tabla --}}
            <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                {{-- Cabecera Tabla --}}
                <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Entidad Acreditadora</th>
                        <th class="px-6 py-3 text-left font-semibold">Resultado</th>
                        <th class="px-6 py-3 text-left font-semibold">Fecha Obtención</th>
                        <th class="px-6 py-3 text-left font-semibold">Fecha Expiración</th>
                        <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                    </tr>
                </thead>
                {{-- Cuerpo Tabla --}}
                <tbody class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                    @forelse ($accreditations as $accreditation)
                        <tr class="hover:bg-slate-900/60 transition">
                            <td class="px-6 py-4 text-slate-100">{{ $accreditation->entity }}</td>
                            <td class="px-6 py-4">{{ $accreditation->result }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($accreditation->accreditation_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                {{ $accreditation->expiration_date ? \Carbon\Carbon::parse($accreditation->expiration_date)->format('d/m/Y') : 'N/A' }}
                            </td>
                            {{-- Acciones (con estilo nuevo) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('quality.accreditations.edit', $accreditation) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition">Editar</a>
                                    <form action="{{ route('quality.accreditations.destroy', $accreditation) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-rose-600/90 text-white font-semibold hover:bg-rose-500 transition"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta acreditación?');">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-slate-500">No hay acreditaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> {{-- Fin "Caja" de la Tabla --}}

    </div> {{-- Fin contenedor principal --}}
@endsection