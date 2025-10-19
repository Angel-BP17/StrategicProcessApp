@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{-- Mostramos el nombre del área en el título --}}
    Detalle de Auditoría: {{ $audit->area }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            {{-- Botón para volver al listado --}}
            <div class="mb-6">
                <a href="{{ route('quality.audits.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                    &larr; Volver al Listado
                </a>
            </div>

            {{-- SECCIÓN 1: DETALLES PRINCIPALES --}}
            <h2 class="text-2xl font-medium text-white mb-4">Información General</h2>
            
            {{-- Grid con la info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-300">
                
                {{-- Columna 1 --}}
                <div>
                    <strong class="block text-gray-100">Área Auditada:</strong>
                    <span>{{ $audit->area }}</span>
                </div>
                <div>
                    <strong class="block text-gray-100">Tipo:</strong>
                    <span>{{ $audit->type == 'internal' ? 'Interna' : 'Externa' }}</span>
                </div>
                <div>
                    <strong class="block text-gray-100">Estado:</strong>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-800 text-green-100">
                        {{ $audit->state }}
                    </span>
                </div>

                {{-- Columna 2 --}}
                <div>
                    <strong class="block text-gray-100">Fecha de Inicio:</strong>
                    <span>{{ \Carbon\Carbon::parse($audit->start_date)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <strong class="block text-gray-100">Fecha de Fin:</strong>
                    <span>{{ \Carbon\Carbon::parse($audit->end_date)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <strong class="block text-gray-100">Responsable:</strong>
                    {{-- Usamos la relación 'responsible' que cargamos en el controlador --}}
                    <span>{{ $audit->responsible->full_name ?? 'No asignado' }}</span>
                </div>

                {{-- Columna 3 (ancho completo) --}}
                <div class="md:col-span-3">
                    <strong class="block text-gray-100">Objetivo:</strong>
                    <p class="font-light">{{ $audit->objective }}</p>
                </div>
                <div class="md:col-span-3">
                    <strong class="block text-gray-100">Alcance:</strong>
                    <p class="font-light">{{ $audit->range }}</p>
                </div>
            </div>

            {{-- Divisor --}}
            <hr class="border-gray-700 my-8">

            {{-- SECCIÓN 2: HALLAZGOS (Findings) --}}
            <h2 class="text-2xl font-medium text-white mb-4">Hallazgos</h2>

            {{-- Formulario para crear un nuevo hallazgo --}}
            <form action="{{ route('quality.audits.findings.store', $audit) }}" method="POST" class="bg-night p-6 rounded-lg mb-6">
                @csrf
                <h3 class="text-lg font-medium text-white mb-4">Registrar Nuevo Hallazgo</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Campo: Descripción --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-300">Descripción del Hallazgo</label>
                        <textarea name="description" id="description" rows="3" required
                                class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Clasificación --}}
                    <div>
                        <label for="classification" class="block text-sm font-medium text-gray-300">Clasificación</label>
                        <input type="text" name="classification" id="classification" value="{{ old('classification') }}" required
                            class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('classification') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Severidad --}}
                    <div>
                        <label for="severity" class="block text-sm font-medium text-gray-300">Severidad</label>
                        <select name="severity" id="severity" required
                                class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Baja</option>
                            <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Media</option>
                            <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>Alta</option>
                        </select>
                        @error('severity') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Fecha de Descubrimiento --}}
                    <div>
                        <label for="discovery_date" class="block text-sm font-medium text-gray-300">Fecha de Descubrimiento</label>
                        <input type="date" name="discovery_date" id="discovery_date" value="{{ old('discovery_date', date('Y-m-d')) }}" required
                            class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('discovery_date') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Evidencia (Opcional) --}}
                    <div>
                        <label for="evidence" class="block text-sm font-medium text-gray-300">Evidencia (Opcional)</label>
                        <input type="text" name="evidence" id="evidence" value="{{ old('evidence') }}"
                            class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                            placeholder="Ej: Documento X, foto, etc.">
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                        Guardar Hallazgo
                    </button>
                </div>
            </form>

            {{-- Tabla de Hallazgos Registrados --}}
            <h3 class="text-lg font-medium text-white mb-4">Hallazgos Registrados</h3>
            <div class="overflow-x-auto bg-night rounded-lg">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-smoky-black">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Descripción</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Severidad</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-dark-purple divide-y divide-gray-700">
                        {{-- 
                        Aquí usamos la relación 'findings' que cargamos en el AuditController 
                        ($audit->load('findings'))
                        --}}
                        @forelse ($audit->findings as $finding)
                            <tr>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-200">{{ $finding->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $finding->severity == 'high' ? 'bg-red-800 text-red-100' : ($finding->severity == 'medium' ? 'bg-yellow-800 text-yellow-100' : 'bg-blue-800 text-blue-100') }}">
                                        {{ $finding->severity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ \Carbon\Carbon::parse($finding->discovery_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-primary hover:text-opacity-75">Acciones</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    Aún no se han registrado hallazgos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- SECCIÓN 3: ACCIONES CORRECTIVAS --}}
            <h2 class="text-2xl font-medium text-white mt-8 mb-4">Acciones Correctivas</h2>
            <div class="overflow-x-auto bg-night rounded-lg">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-smoky-black">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hallazgo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acción Requerida</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Responsable</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha Límite</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-dark-purple divide-y divide-gray-700">

                        @forelse ($audit->findings as $finding)
                            @forelse ($finding->correctiveActions as $action)
                                <tr>
                                    @if ($loop->first)
                                    <td class="px-6 py-4 align-top text-sm text-gray-400 italic" rowspan="{{ $loop->count }}">
                                        {{ Str::limit($finding->description, 50) }}
                                    </td>
                                    @endif

                                    <td class="px-6 py-4 text-sm text-gray-200">{{ $action->description }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-200">{{ $action->responsible->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-200">{{ \Carbon\Carbon::parse($action->due_date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-800 text-yellow-100">
                                            {{ $action->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-400 italic">{{ Str::limit($finding->description, 50) }}</td>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay acciones correctivas para este hallazgo.
                                    </td>
                                </tr>
                            @endforelse
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aún no se han registrado hallazgos (y por lo tanto, no hay acciones).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Formulario para añadir una nueva acción correctiva --}}
            @if ($audit->findings->isNotEmpty())
            <form action="" method="POST" id="correctiveActionForm" class="bg-night p-6 rounded-lg mt-6">
                @csrf
                <h3 class="text-lg font-medium text-white mb-4">Registrar Nueva Acción Correctiva</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label for="finding_id" class="block text-sm font-medium text-gray-300">Asignar al Hallazgo:</label>
                        <select name="finding_id" id="finding_id" required
                                class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            @foreach ($audit->findings as $finding)
                                <option value="{{ $finding->id }}">{{ Str::limit($finding->description, 70) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-300">Descripción de la Acción</label>
                        <input type="text" name="description" id="description" required
                            class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-300">Responsable</label>
                        <select name="user_id" id="user_id" required
                                class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">Seleccione un usuario...</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-300">Fecha Límite</label>
                        <input type="date" name="due_date" id="due_date" value="{{ date('Y-m-d', strtotime('+1 week')) }}" required
                            class="mt-1 block w-full bg-dark-purple border-gray-700 text-white rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                        Guardar Acción
                    </button>
                </div>
            </form>

            {{-- Script para cambiar la URL del formulario dinámicamente --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const form = document.getElementById('correctiveActionForm');
                    const findingSelect = document.getElementById('finding_id');

                    function updateFormAction() {
                        const selectedFindingId = findingSelect.value;
                        const baseUrl = "{{ route('quality.findings.corrective-actions.store', ['finding' => ':id']) }}";
                        form.action = baseUrl.replace(':id', selectedFindingId);
                    }

                    updateFormAction();
                    findingSelect.addEventListener('change', updateFormAction);
                });
            </script>
            @endif
            
        </div>
    </div>
</div>
@endsection