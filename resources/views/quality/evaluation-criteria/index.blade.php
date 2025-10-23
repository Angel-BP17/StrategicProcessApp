@extends('layouts.app')

@section('title', 'Criterios de Evaluación')

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad</p>
                <h1 class="text-3xl font-semibold">Criterios de Evaluación</h1>
            </div>
            <div class="flex flex-wrap items-end gap-3"> {{-- items-end --}}
                <a href="{{ route('quality.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                   &larr; Volver al Panel
                </a>
                {{-- Botón Crear --}}
                <a href="{{ route('quality.evaluation-criteria.create') }}"
                   class="inline-flex items-center gap-2 bg-sky-500/90 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition">
                   Crear Nuevo Criterio
                </a>
            </div>
        </div>

        {{-- "Caja" de la Tabla --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">

            {{-- Cabecera de la Caja (CON CAMPO DE BÚSQUEDA) --}}
            <div class="flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-800/70 gap-4">
                <h2 class="font-semibold text-lg text-slate-200 flex-shrink-0">Listado de Criterios Definidos</h2>
                {{-- Input de búsqueda --}}
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <label for="criteriaSearch" class="sr-only">Buscar criterios</label>
                    <input type="text" id="criteriaSearch" name="search"
                           class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                           placeholder="Buscar por nombre o tipo..."> {{-- Placeholder ajustado --}}
                </div>
            </div>
            {{-- FIN CABECERA CON BÚSQUEDA --}}


            {{-- Tabla --}}
            <div class="overflow-x-auto"> {{-- Añadido overflow-x --}}
                <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                    {{-- Cabecera Tabla --}}
                    <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Nombre del Criterio</th>
                            <th class="px-6 py-3 text-left font-semibold">Tipo de Respuesta</th>
                            <th class="px-6 py-3 text-left font-semibold">Estado</th>
                            <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    {{-- Cuerpo Tabla (AÑADIDO ID) --}}
                    <tbody id="criteriaTableBody" class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                        @forelse ($criteria as $criterion)
                            <tr class="hover:bg-slate-900/60 transition">
                                <td class="px-6 py-4 text-slate-100 whitespace-normal">{{ $criterion->criterion_name }}</td>
                                <td class="px-6 py-4">{{ ucfirst($criterion->response_type) }}</td>
                                <td class="px-6 py-4">
                                    {{-- Badge de estado --}}
                                    <span @class([
                                        'px-3 py-1 inline-flex text-xs font-semibold rounded-full border',
                                        'bg-emerald-500/15 text-emerald-300 border-emerald-400/30' => $criterion->state == 'active',
                                        'bg-slate-500/20 text-slate-200 border-slate-400/30' => $criterion->state != 'active',
                                    ])>
                                        {{ ucfirst($criterion->state ?? 'Indefinido') }}
                                    </span>
                                </td>
                                {{-- Acciones --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('quality.evaluation-criteria.edit', $criterion) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition text-xs">Editar</a>
                                        <form action="{{ route('quality.evaluation-criteria.destroy', $criterion) }}" method="POST" class="m-0 p-0">
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
                                <td colspan="4" class="px-6 py-6 text-center text-slate-500">No hay criterios de evaluación definidos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> {{-- Fin overflow-x --}}

        </div> {{-- Fin "Caja" de la Tabla --}}
    </div> {{-- Fin contenedor principal --}}

{{-- INICIO: SCRIPT PARA BÚSQUEDA EN VIVO (Adaptado para Criterios) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('criteriaSearch'); // ID del input
        const tableBody = document.getElementById('criteriaTableBody'); // ID del tbody
        // Guarda las filas originales
        const originalRowsHTML = tableBody.innerHTML;

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();

            if (searchTerm === '') {
                tableBody.innerHTML = originalRowsHTML; // Restaura si está vacío
                return;
            }

            let found = false;
            Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                if (row.querySelector('td[colspan]')) { // Ignora fila "No hay..."
                    row.style.display = 'none';
                    return;
                }

                // Celdas a buscar: Nombre (0) y Tipo Respuesta (1)
                const name = row.cells[0]?.textContent.toLowerCase() || '';
                const type = row.cells[1]?.textContent.toLowerCase() || '';
                const state = row.cells[2]?.querySelector('span')?.textContent.toLowerCase() || ''; // Buscamos estado en el badge

                // Comprueba si alguna celda relevante contiene el término
                if (name.includes(searchTerm) || type.includes(searchTerm) || state.includes(searchTerm)) {
                    row.style.display = ''; // Muestra
                    found = true;
                } else {
                    row.style.display = 'none'; // Oculta
                }
            });
             // (Opcional: añadir mensaje "No se encontraron resultados")
        });
    });
</script>
{{-- FIN: SCRIPT --}}

@endsection