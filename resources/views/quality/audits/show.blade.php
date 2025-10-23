@extends('layouts.app')

@section('title', 'Detalle de Auditoría')

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera Simplificada para Detalle --}}
        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Auditorías</p>
            <h1 class="text-3xl font-semibold">Auditoría: {{ $audit->area }}</h1>
        </div>

        {{-- Botón Volver (estilo adaptado) --}}
        <div class="mb-6">
            <a href="{{ route('quality.audits.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                &larr; Volver al Listado
            </a>
        </div>

        {{-- "Caja" Principal para todo el contenido --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">

            {{-- SECCIÓN 1: DETALLES PRINCIPALES --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Información General</div>
            <div class="p-6">
                {{-- ... (Contenido de Información General - sin cambios) ... --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 text-sm text-slate-300">
                    <div>
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Área Auditada:</strong>
                        <span class="text-slate-100">{{ $audit->area }}</span>
                    </div>
                    <div>
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Tipo:</strong>
                        <span class="text-slate-100">{{ $audit->type == 'internal' ? 'Interna' : 'Externa' }}</span>
                    </div>
                    <div>
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Estado:</strong>
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
                    </div>
                    <div>
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Fecha de Inicio:</strong>
                        <span class="text-slate-100">{{ \Carbon\Carbon::parse($audit->start_date)->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Fecha de Fin:</strong>
                        <span class="text-slate-100">{{ \Carbon\Carbon::parse($audit->end_date)->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Responsable:</strong>
                        <span class="text-slate-100">{{ $audit->responsible->full_name ?? 'No asignado' }}</span>
                    </div>
                    <div class="md:col-span-3">
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Objetivo:</strong>
                        <p class="text-slate-100">{{ $audit->objective }}</p>
                    </div>
                    <div class="md:col-span-3">
                        <strong class="block text-xs uppercase text-slate-400 mb-1">Alcance:</strong>
                        <p class="text-slate-100">{{ $audit->range }}</p>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: HALLAZGOS (Findings) --}}
            {{-- Cabecera con Búsqueda --}}
            <div id="findingsSection" class="flex flex-col md:flex-row justify-between items-center p-6 border-y border-slate-800/70 text-slate-200 gap-4">
                <h2 class="font-semibold text-lg text-slate-200 flex-shrink-0">Hallazgos</h2>
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <label for="findingsSearch" class="sr-only">Buscar hallazgos</label>
                    <input type="text" id="findingsSearch"
                           class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                           placeholder="Buscar en hallazgos...">
                </div>
            </div>

            {{-- Contenido de Hallazgos --}}
            <div class="p-6 space-y-6">
                {{-- Formulario para crear hallazgo (sin cambios) --}}
                <form action="{{ route('quality.audits.findings.store', $audit) }}" method="POST" class="bg-slate-900/70 p-6 rounded-xl border border-slate-800/70">
                    @csrf
                    <h3 class="text-lg font-semibold text-slate-100 mb-4">Registrar Nuevo Hallazgo</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        {{-- ... (Campos del formulario: description, classification, severity, etc. - sin cambios) ... --}}
                         <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-slate-300 mb-1">Descripción del Hallazgo</label>
                            <textarea name="description" id="description" rows="3" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="classification" class="block text-sm font-semibold text-slate-300 mb-1">Clasificación</label>
                            <input type="text" name="classification" id="classification" value="{{ old('classification') }}" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            @error('classification') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="severity" class="block text-sm font-semibold text-slate-300 mb-1">Severidad</label>
                            <select name="severity" id="severity" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                                <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Baja</option>
                                <option value="medium" {{ old('severity', 'medium') == 'medium' ? 'selected' : '' }}>Media</option>
                                <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>Alta</option>
                            </select>
                            @error('severity') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="discovery_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha de Descubrimiento</label>
                            <input type="date" name="discovery_date" id="discovery_date" value="{{ old('discovery_date', date('Y-m-d')) }}" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            @error('discovery_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="evidence" class="block text-sm font-semibold text-slate-300 mb-1">Evidencia (Opcional)</label>
                            <input type="text" name="evidence" id="evidence" value="{{ old('evidence') }}" class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm" placeholder="Ej: Documento X, foto, etc.">
                        </div>
                        <div class="md:col-span-2 text-right mt-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                                Guardar Hallazgo
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Tabla de Hallazgos Registrados --}}
                <div class="mt-6">
                    <div class="overflow-x-auto border border-slate-800/70 rounded-lg">
                        <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                            <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold">Descripción</th>
                                    <th class="px-6 py-3 text-left font-semibold">Severidad</th>
                                    <th class="px-6 py-3 text-left font-semibold">Fecha Desc.</th>
                                    <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                                </tr>
                            </thead>
                            {{-- AÑADIDO ID al tbody --}}
                            <tbody id="findingsTableBody" class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                                @forelse ($audit->findings as $finding)
                                    <tr class="hover:bg-slate-900/60 transition">
                                        <td class="px-6 py-4 text-slate-100 whitespace-normal">{{ $finding->description }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-3 py-1 inline-flex text-xs font-semibold rounded-full border',
                                                'bg-rose-500/15 text-rose-300 border-rose-400/30' => $finding->severity == 'high',
                                                'bg-amber-500/15 text-amber-300 border-amber-400/30' => $finding->severity == 'medium',
                                                'bg-sky-500/15 text-sky-300 border-sky-400/30' => $finding->severity == 'low',
                                            ])>
                                                {{ ucfirst($finding->severity) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $finding->discovery_date ? \Carbon\Carbon::parse($finding->discovery_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-4 text-xs">
                                                <a href="#correctiveActionsSection"
                                                   class="inline-flex items-center px-2 py-1 rounded bg-emerald-400/90 text-slate-900 font-semibold hover:bg-emerald-300 transition"
                                                   title="Ver/Añadir Acciones Correctivas">Acciones</a>

                                                <a href="{{ route('quality.findings.edit', $finding) }}"
                                                   class="inline-flex items-center px-2 py-1 rounded bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition"
                                                   title="Editar Hallazgo">Editar</a>
                                                <form action="{{ route('quality.findings.destroy', $finding) }}" method="POST" class="m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 rounded bg-rose-600/90 text-white font-semibold hover:bg-rose-500 transition"
                                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta acreditación?');">
                                                        Eliminar
                                                    </button>
                                                </form>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="no-results-row"> {{-- Fila especial --}}
                                        <td colspan="4" class="px-6 py-6 text-center text-slate-500">Aún no se han registrado hallazgos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 3: ACCIONES CORRECTIVAS --}}
            {{-- Cabecera con Búsqueda --}}
            <div id="correctiveActionsSection" class="flex flex-col md:flex-row justify-between items-center p-6 border-y border-slate-800/70 text-slate-200 gap-4">
                <h2 class="font-semibold text-lg text-slate-200 flex-shrink-0">Acciones Correctivas</h2>
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <label for="actionsSearch" class="sr-only">Buscar acciones</label>
                    <input type="text" id="actionsSearch"
                           class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm"
                           placeholder="Buscar en acciones...">
                </div>
            </div>

            {{-- Contenido de Acciones Correctivas --}}
            <div class="p-6 space-y-6">
                {{-- Formulario para añadir acción (sin cambios) --}}
                @if ($audit->findings->isNotEmpty())
                <form action="" method="POST" id="correctiveActionForm" class="bg-slate-900/70 p-6 rounded-xl border border-slate-800/70 mt-6">
                   @csrf
                   <h3 class="text-lg font-semibold text-slate-100 mb-4">Registrar Nueva Acción Correctiva</h3>
                   <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                       {{-- ... (Campos del formulario: finding_id, description, user_id, etc. - sin cambios) ... --}}
                        <div>
                            <label for="finding_id" class="block text-sm font-semibold text-slate-300 mb-1">Asignar al Hallazgo:</label>
                            <select name="finding_id" id="finding_id" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                                @foreach ($audit->findings as $finding)
                                    <option value="{{ $finding->id }}">{{ Str::limit($finding->description, 70) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="action_description" class="block text-sm font-semibold text-slate-300 mb-1">Descripción de la Acción</label>
                            <input type="text" name="description" id="action_description" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="user_id" class="block text-sm font-semibold text-slate-300 mb-1">Responsable</label>
                            <select name="user_id" id="user_id" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                                <option value="">Seleccione...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha Límite</label>
                            <input type="date" name="due_date" id="due_date" value="{{ date('Y-m-d', strtotime('+1 week')) }}" required class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                             @error('due_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-3 text-right mt-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                                Guardar Acción
                            </button>
                        </div>
                   </div>
                </form>
                @endif

                {{-- Tabla de Acciones Correctivas --}}
                <div class="overflow-x-auto border border-slate-800/70 rounded-lg">
                    <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                        <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Hallazgo Asoc.</th>
                                <th class="px-6 py-3 text-left font-semibold">Acción Requerida</th>
                                <th class="px-6 py-3 text-left font-semibold">Responsable</th>
                                <th class="px-6 py-3 text-left font-semibold">Fecha Límite</th>
                                <th class="px-6 py-3 text-left font-semibold">Estado</th>
                                <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        {{-- AÑADIDO ID al tbody --}}
                        <tbody id="actionsTableBody" class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                            @php $hasActions = false; @endphp
                            @forelse ($audit->findings as $finding)
                                @forelse ($finding->correctiveActions as $action)
                                    @php $hasActions = true; @endphp
                                    <tr class="hover:bg-slate-900/60 transition">
                                        @if ($loop->first)
                                        <td class="px-6 py-4 align-top text-slate-400 italic" rowspan="{{ $loop->count }}">
                                            {{ Str::limit($finding->description, 40) }}
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 text-slate-100 whitespace-normal">{{ $action->description }}</td>
                                        <td class="px-6 py-4 text-slate-100">{{ $action->responsible->full_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $action->due_date ? \Carbon\Carbon::parse($action->due_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-3 py-1 inline-flex text-xs font-semibold rounded-full border',
                                                'bg-amber-500/15 text-amber-300 border-amber-400/30' => $action->status == 'pending',
                                                'bg-blue-500/15 text-blue-300 border-blue-400/30' => $action->status == 'in_progress',
                                                'bg-emerald-500/15 text-emerald-300 border-emerald-400/30' => $action->status == 'completed',
                                                'bg-rose-500/15 text-rose-300 border-rose-400/30' => $action->status == 'cancelled',
                                                'bg-slate-500/20 text-slate-200 border-slate-400/30' => !in_array($action->status, ['pending', 'in_progress', 'completed', 'cancelled']),
                                            ])>
                                                {{ ucfirst($action->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3 text-xs">
                                                <a href="{{ route('quality.corrective-actions.edit', $action) }}"
                                                   class="inline-flex items-center px-2 py-1 rounded bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition"
                                                   title="Editar Acción">Editar</a>
                                                <form action="{{ route('quality.corrective-actions.destroy', $action) }}" method="POST" class="m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 rounded bg-rose-600/90 text-white font-semibold hover:bg-rose-500 transition"
                                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta acreditación?');">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            @empty
                            @endforelse
                            @if (!$hasActions)
                                <tr class="no-results-row"> {{-- Fila especial --}}
                                    <td colspan="6" class="px-6 py-6 text-center text-slate-500">
                                        No hay acciones correctivas registradas para ningún hallazgo.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Script para formularios y BÚSQUEDA EN VIVO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Script para formulario de Acciones Correctivas (ya existente) ---
            const form = document.getElementById('correctiveActionForm');
            const findingSelect = document.getElementById('finding_id');
            if (form && findingSelect) {
                function updateFormAction() {
                    const selectedFindingId = findingSelect.value;
                    const baseUrl = "{{ route('quality.findings.corrective-actions.store', ['finding' => ':id']) }}";
                    form.action = baseUrl.replace(':id', selectedFindingId);
                }
                updateFormAction();
                findingSelect.addEventListener('change', updateFormAction);
            }

            // --- INICIO: SCRIPT PARA BÚSQUEDA EN VIVO ---

            // Función genérica de búsqueda para tablas
            function setupLiveSearch(inputId, tableBodyId, searchCellsIndices, colspan) {
                const searchInput = document.getElementById(inputId);
                const tableBody = document.getElementById(tableBodyId);
                if (!searchInput || !tableBody) return; // Salir si los elementos no existen

                const originalRowsHTML = tableBody.innerHTML; // Guardar estado original

                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase().trim();

                    if (searchTerm === '') {
                        tableBody.innerHTML = originalRowsHTML; // Restaurar
                        return;
                    }

                    let found = false;
                    tableBody.innerHTML = ''; // Limpiar para filtrar

                    const parser = new DOMParser();
                    const originalDoc = parser.parseFromString(`<table><tbody>${originalRowsHTML}</tbody></table>`, 'text/html');
                    const originalRows = originalDoc.querySelectorAll('tbody tr');

                    originalRows.forEach(row => {
                        if (row.querySelector('td[colspan]')) {
                            return; // Ignorar fila "No hay..."
                        }

                        let match = false;
                        // Recorrer los índices de celdas especificados para buscar
                        for (const index of searchCellsIndices) {
                            // Buscar texto en celda o dentro de un span (para badges)
                            const cellText = (row.cells[index]?.querySelector('span')?.textContent || row.cells[index]?.textContent || '').toLowerCase();
                            if (cellText.includes(searchTerm)) {
                                match = true;
                                break; // Salir del bucle de celdas si ya hay coincidencia
                            }
                        }

                        if (match) {
                            tableBody.appendChild(row.cloneNode(true));
                            found = true;
                        }
                    });

                    if (!found) {
                        const noResultRow = document.createElement('tr');
                        noResultRow.className = 'no-results-row';
                        noResultRow.innerHTML = `<td colspan="${colspan}" class="px-6 py-6 text-center text-slate-500">No se encontraron registros que coincidan con "${this.value}".</td>`;
                        tableBody.appendChild(noResultRow);
                    }
                });
            }

            // Configurar búsqueda para Hallazgos
            // Buscar en: Descripción (0), Severidad (1)
            setupLiveSearch('findingsSearch', 'findingsTableBody', [0, 1], 4);

            // Configurar búsqueda para Acciones Correctivas
            // Buscar en: Acción Requerida (1), Responsable (2), Estado (4)
            // (Saltamos la celda 0 'Hallazgo Asoc.' por la complejidad del rowspan)
            setupLiveSearch('actionsSearch', 'actionsTableBody', [1, 2, 4], 6);

        });
    </script>
@endsection