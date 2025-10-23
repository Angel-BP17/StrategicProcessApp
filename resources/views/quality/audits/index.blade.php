@extends('layouts.app') 

@section('title', 'Gestión de Auditorías') 

@section('content')
    {{-- Contenedor principal (estilo compañero) --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera (estilo compañero) --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            {{-- LADO IZQUIERDO: Título y Subtítulo --}}
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad</p> 
                <h1 class="text-3xl font-semibold">Listado de Auditorías</h1> 
            </div>
            {{-- LADO DERECHO: Botones --}}
            <div class="flex flex-wrap items-end gap-3"> {{-- Añadido items-end para alinear --}}
                
                {{-- Botón Volver --}}
                <a href="{{ route('quality.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                   &larr; Volver al Panel
                </a>

                {{-- Botón Crear --}}
                <a href="{{ route('quality.audits.create') }}"
                   class="inline-flex items-center gap-2 bg-sky-500/90 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition">
                   Nueva Auditoría
                </a>
            </div>
        </div>

        {{-- "Caja" de la Tabla (estilo compañero) --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            
            {{-- Cabecera de la Caja (MODIFICADA CON FLEXBOX) --}}
            {{-- 1. Añadimos flex, justify-between, items-center --}}
            <div class="flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-800/70 gap-4">
                {{-- Título a la izquierda --}}
                <h2 class="font-semibold text-lg text-slate-200 flex-shrink-0">Auditorías Planificadas</h2>

                {{-- Input de búsqueda a la derecha --}}
                {{-- 2. Movimos el input aquí y ajustamos ancho --}}
                <div class="w-full md:w-1/2 lg:w-1/3"> {{-- Contenedor para controlar ancho --}}
                    <label for="auditSearch" class="sr-only">Buscar auditorías</label>
                    <input type="text" id="auditSearch" name="search"
                           class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                           placeholder="Buscar por área, tipo o estado...">
                </div>
            </div>
            {{-- FIN CABECERA MODIFICADA --}}

            {{-- Tabla (adaptada) --}}
            <div class="overflow-x-auto">
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
                    <tbody id="auditsTableBody" class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
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
            </div> 
        </div> {{-- Fin "Caja" de la Tabla --}}
    </div> {{-- Fin contenedor principal --}}

    {{-- INICIO: SCRIPT PARA BÚSQUEDA EN VIVO (Sin cambios) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('auditSearch');
            const tableBody = document.getElementById('auditsTableBody');
            const originalRowsHTML = tableBody.innerHTML;

            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase().trim();
                if (searchTerm === '') {
                    tableBody.innerHTML = originalRowsHTML;
                    return; 
                }

                let found = false;
                Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                    if (row.querySelector('td[colspan]')) {
                        row.style.display = 'none'; 
                        return;
                    }

                    const area = row.cells[0]?.textContent.toLowerCase() || '';
                    const type = row.cells[1]?.textContent.toLowerCase() || '';
                    const state = row.cells[2]?.querySelector('span')?.textContent.toLowerCase() || ''; // Buscamos dentro del span del badge

                    if (area.includes(searchTerm) || type.includes(searchTerm) || state.includes(searchTerm)) {
                        row.style.display = ''; 
                        found = true;
                    } else {
                        row.style.display = 'none'; 
                    }
                });
            });
        });
    </script>
    {{-- FIN: SCRIPT --}}
@endsection