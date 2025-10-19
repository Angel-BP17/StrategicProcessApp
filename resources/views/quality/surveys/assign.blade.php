@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    Asignar Encuesta: {{ Str::limit($survey->title, 40) }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            {{-- Botón para volver al listado --}}
            <div class="mb-6">
                <a href="{{ route('quality.surveys.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 transition ease-in-out duration-150">
                    &larr; Volver al Listado de Encuestas
                </a>
            </div>

            <h1 class="text-2xl font-medium text-white mb-2">
                Asignar Encuesta: {{ $survey->title }}
            </h1>
            <p class="text-gray-400 mb-1">Dirigido a: <span class="font-semibold">{{ ucfirst($survey->target_type) }}</span></p>
            <p class="text-gray-400 mb-6">Estado actual: <span class="font-semibold">{{ ucfirst($survey->status) }}</span></p>

            {{-- Formulario para guardar las asignaciones --}}
            {{-- La ruta 'quality.surveys.assign.store' la crearemos después --}}
            <form action="{{ route('quality.surveys.assign.store', $survey) }}" method="POST">
                @csrf

                <h2 class="text-xl font-medium text-white mb-4">Seleccionar Usuarios para Asignar</h2>

                @if($usersToAssign->isEmpty())
                    <div class="bg-night p-4 rounded-lg text-center text-gray-400">
                        No se encontraron usuarios del tipo "{{ ucfirst($survey->target_type) }}" para asignar.
                    </div>
                @else
                    <div class="overflow-x-auto bg-night rounded-lg border border-gray-700">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-smoky-black">
                                <tr>
                                    <th scope="col" class="w-10 px-6 py-3">
                                        {{-- Checkbox para seleccionar/deseleccionar todos --}}
                                        <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-600 text-primary focus:ring-primary">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre Completo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado Asignación</th>
                                </tr>
                            </thead>
                            <tbody class="bg-dark-purple divide-y divide-gray-700">
                                @foreach ($usersToAssign as $user)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                   {{-- Marcamos si ya está asignado --}}
                                                   {{ in_array($user->id, $assignedUserIds) ? 'checked' : '' }}
                                                   class="user-checkbox rounded border-gray-600 text-primary focus:ring-primary">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $user->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if(in_array($user->id, $assignedUserIds))
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-800 text-green-100">
                                                    Ya Asignado
                                                </span>
                                                {{-- Aquí podríamos mostrar el estado (pending/completed) si lo cargamos --}}
                                            @else
                                                <span class="text-gray-400">No Asignado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Botón de Guardar Asignaciones --}}
                    <div class="mt-6 text-right">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75 transition ease-in-out duration-150">
                            Guardar Asignaciones
                        </button>
                    </div>
                @endif {{-- Fin del if $usersToAssign->isEmpty() --}}

            </form>

        </div> {{-- Fin caja principal --}}
    </div> {{-- Fin max-w --}}
</div> {{-- Fin py-12 --}}

{{-- Script para el checkbox "Seleccionar Todos" --}}
<script>
    document.getElementById('selectAllCheckbox').addEventListener('change', function(event) {
        document.querySelectorAll('.user-checkbox').forEach(function(checkbox) {
            checkbox.checked = event.target.checked;
        });
    });
</script>

@endsection