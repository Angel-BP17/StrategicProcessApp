@extends('layouts.app') {{-- Asegúrate que sea tu layout principal --}}

@section('title', 'Gestión de Auditorías') {{-- Añadimos título de página --}}

@section('content')
    {{-- Contenedor principal (estilo compañero) --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera (estilo compañero) --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad</p> {{-- Subtítulo --}}
                <h1 class="text-3xl font-semibold">Listado de Auditorías</h1> {{-- Título principal --}}
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('quality.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                   &larr; Volver al Panel
                </a>
                {{-- Botón Crear (adaptado) --}}
                <a href="{{ route('quality.audits.create') }}"
                   class="inline-flex items-center gap-2 bg-sky-500/90 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition">
                   {{-- Icono opcional: <svg>...</svg> --}}
                   Nueva Auditoría
                </a>
            </div>
        </div>

        {{-- "Caja" de la Tabla (estilo compañero) --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja (estilo compañero) --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Auditorías Planificadas</div>

            {{-- Tabla (adaptada) --}}
            <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                {{-- Cabecera Tabla (estilo compañero) --}}
                <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Área</th>
                        <th class="px-6 py-3 text-left font-semibold">Tipo</th>
                        <th class="px-6 py-3 text-left font-semibold">Estado</th>
                        <th class="px-6 py-3 text-left font-semibold">Fecha Inicio</th>
                        <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                    </tr>
                </thead>
                {{-- Cuerpo Tabla (estilo compañero) --}}
                <tbody class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                    @forelse ($audits as $audit)
                        <tr class="hover:bg-slate-900/60 transition">
                            <td class="px-6 py-4 text-slate-100">{{ $audit->area }}</td>
                            <td class="px-6 py-4">{{ $audit->type == 'internal' ? 'Interna' : 'Externa' }}</td>
                            <td class="px-6 py-4">
                                {{-- Badge de estado (adaptado) --}}
                                <span @class([
                                    'px-3 py-1 inline-flex text-xs font-semibold rounded-full border',
                                    'bg-sky-500/15 text-sky-300 border-sky-400/30' => $audit->state == 'planned',
                                    'bg-amber-500/15 text-amber-300 border-amber-400/30' => $audit->state == 'in_progress',
                                    'bg-emerald-500/15 text-emerald-300 border-emerald-400/30' => $audit->state == 'completed',
                                    'bg-rose-500/15 text-rose-300 border-rose-400/30' => $audit->state == 'cancelled',
                                    'bg-slate-500/20 text-slate-200 border-slate-400/30' => !in_array($audit->state, ['planned', 'in_progress', 'completed', 'cancelled']),
                                ])>
                                    {{ ucfirst($audit->state) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($audit->start_date)->format('d/m/Y') }}</td>
                            {{-- Acciones (estilo compañero) --}}
                            <td class="px-6 py-4">
                                {{-- Usamos flex y space-x para alinear --}}
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('quality.audits.show', $audit) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-sky-400/90 text-slate-900 font-semibold hover:bg-sky-300 transition text-xs">Ver</a>
                                    <a href="{{ route('quality.audits.edit', $audit) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition text-xs">Editar</a>
                                    {{-- Formulario Eliminar (NUEVO, usa SweetAlert) --}}
                                    <form action="{{ route('quality.audits.destroy', $audit) }}" method="POST" class="m-0 p-0"> 
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-rose-600/90 text-white font-semibold hover:bg-rose-500 transition text-xs"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta acreditación?');">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-slate-500">No hay auditorías registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> {{-- Fin "Caja" de la Tabla --}}

    </div> {{-- Fin contenedor principal --}}
@endsection