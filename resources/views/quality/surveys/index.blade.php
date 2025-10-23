@extends('layouts.app')

@section('title', 'Gestión de Encuestas')

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad</p>
                <h1 class="text-3xl font-semibold">Gestión de Encuestas</h1>
            </div>
            <div class="flex flex-wrap items-end gap-3"> {{-- items-end --}}
                <a href="{{ route('quality.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                   &larr; Volver al Panel
                </a>
                {{-- Botón Crear --}}
                <a href="{{ route('quality.surveys.create') }}"
                   class="inline-flex items-center gap-2 bg-sky-500/90 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition">
                   Crear Nueva Encuesta
                </a>
            </div>
        </div>

        {{-- "Caja" de la Tabla --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">

            {{-- Cabecera de la Caja (CON CAMPO DE BÚSQUEDA) --}}
            <div class="flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-800/70 gap-4">
                <h2 class="font-semibold text-lg text-slate-200 flex-shrink-0">Encuestas Creadas</h2>
                {{-- Input de búsqueda --}}
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <label for="surveySearch" class="sr-only">Buscar encuestas</label>
                    <input type="text" id="surveySearch" name="search"
                           class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                           placeholder="Buscar por título, dirigido a o estado..."> {{-- Placeholder ajustado --}}
                </div>
            </div>
            {{-- FIN CABECERA CON BÚSQUEDA --}}


            {{-- Tabla --}}
            <div class="overflow-x-auto"> {{-- Añadido overflow-x --}}
                <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                    {{-- Cabecera Tabla --}}
                    <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Título</th>
                            <th class="px-6 py-3 text-left font-semibold">Dirigido a</th>
                            <th class="px-6 py-3 text-left font-semibold">Estado</th>
                            <th class="px-6 py-3 text-left font-semibold">Fecha Creación</th>
                            <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    {{-- Cuerpo Tabla (AÑADIDO ID) --}}
                    <tbody id="surveysTableBody" class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                        @forelse ($surveys as $survey)
                            <tr class="hover:bg-slate-900/60 transition">
                                <td class="px-6 py-4 text-slate-100">{{ $survey->title }}</td>
                                <td class="px-6 py-4">{{ ucfirst($survey->target_type) }}</td>
                                <td class="px-6 py-4">
                                    {{-- Badge de estado (adaptado) --}}
                                    <span @class([
                                        'px-3 py-1 inline-flex text-xs font-semibold rounded-full border',
                                        'bg-yellow-500/15 text-yellow-300 border-yellow-400/30' => $survey->status == 'draft', // Borrador
                                        'bg-emerald-500/15 text-emerald-300 border-emerald-400/30' => $survey->status == 'active', // Activa
                                        'bg-slate-500/20 text-slate-200 border-slate-400/30' => $survey->status == 'closed', // Cerrada
                                    ])>
                                        {{ ucfirst($survey->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $survey->created_at->format('d/m/Y') }}</td>
                                {{-- Acciones --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3"> {{-- Reducido espacio --}}
                                        <a href="{{ route('quality.surveys.assign.show', $survey) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-500/90 text-white font-semibold hover:bg-blue-400 transition text-xs">Asignar</a>
                                        <a href="{{ route('quality.surveys.design', $survey) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-sky-400/90 text-slate-900 font-semibold hover:bg-sky-300 transition text-xs">Diseñar</a>
                                        <a href="{{ route('quality.surveys.edit', $survey) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition text-xs">Editar</a>
                                        <form action="{{ route('quality.surveys.destroy', $survey) }}" method="POST" class="m-0 p-0"> 
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
                                <td colspan="5" class="px-6 py-6 text-center text-slate-500">No hay encuestas creadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> {{-- Fin overflow-x --}}

        </div> {{-- Fin "Caja" de la Tabla --}}
    </div> {{-- Fin contenedor principal --}}

{{-- INICIO: SCRIPT PARA BÚSQUEDA EN VIVO (Adaptado para Encuestas) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('surveySearch'); // ID del input
        const tableBody = document.getElementById('surveysTableBody'); // ID del tbody
        // Guarda las filas originales
        const originalRowsHTML = tableBody.innerHTML;

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();

            if (searchTerm === '') {
                tableBody.innerHTML = originalRowsHTML; // Restaura si está vacío
                return;
            }

            let found = false;
            // Limpiamos el tbody antes de añadir filas filtradas
            tableBody.innerHTML = '';

            // Convertimos las filas ORIGINALES a nodos HTML para poder filtrarlas
            const parser = new DOMParser();
            const originalDoc = parser.parseFromString(`<table><tbody>${originalRowsHTML}</tbody></table>`, 'text/html');
            const originalRows = originalDoc.querySelectorAll('tbody tr');

            originalRows.forEach(row => {
                if (row.querySelector('td[colspan]')) { // Ignora fila "No hay..."
                    return;
                }

                // Celdas a buscar: Título (0), Dirigido a (1), Estado (2)
                const title = row.cells[0]?.textContent.toLowerCase() || '';
                const target = row.cells[1]?.textContent.toLowerCase() || '';
                const status = row.cells[2]?.querySelector('span')?.textContent.toLowerCase() || ''; // Buscamos estado en el badge

                // Comprueba si alguna celda relevante contiene el término
                if (title.includes(searchTerm) || target.includes(searchTerm) || status.includes(searchTerm)) {
                    // Si coincide, clonamos la fila original y la añadimos al tbody visible
                    tableBody.appendChild(row.cloneNode(true));
                    found = true;
                }
            });

             // Si después de filtrar no se encontró nada, añadimos la fila "No resultados"
             if (!found) {
                const noResultRow = document.createElement('tr');
                noResultRow.innerHTML = `<td colspan="5" class="px-6 py-6 text-center text-slate-500">No se encontraron encuestas que coincidan con "${this.value}".</td>`;
                tableBody.appendChild(noResultRow);
             }
        });
    });
</script>
{{-- FIN: SCRIPT --}}

@endsection