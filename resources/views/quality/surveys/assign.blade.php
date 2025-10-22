@extends('layouts.app')

@section('title', 'Asignar Encuesta')

{{-- @section('header') ... @endsection --}} {{-- Quitamos si layout maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera --}}
        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Encuestas</p>
             <h1 class="text-3xl font-semibold">Asignar Encuesta: {{ Str::limit($survey->title, 40) }}</h1>
             {{-- Información adicional de la encuesta --}}
             <div class="mt-2 text-sm text-slate-400 space-x-4">
                 <span>Dirigido a: <span class="font-medium text-slate-300">{{ ucfirst($survey->target_type) }}</span></span>
                 <span>Estado:
                    <span @class([
                        'px-2 py-0.5 inline-flex text-xs font-semibold rounded-full border',
                        'bg-yellow-500/15 text-yellow-300 border-yellow-400/30' => $survey->status == 'draft',
                        'bg-emerald-500/15 text-emerald-300 border-emerald-400/30' => $survey->status == 'active',
                        'bg-slate-500/20 text-slate-200 border-slate-400/30' => $survey->status == 'closed',
                    ])>
                        {{ ucfirst($survey->status) }}
                    </span>
                 </span>
             </div>
         </div>

         {{-- Botón Volver --}}
         <div class="mb-6">
            <a href="{{ route('quality.surveys.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                &larr; Volver al Listado
            </a>
        </div>

        {{-- "Caja" Principal --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Seleccionar Usuarios para Asignar</div>

            {{-- Formulario que envuelve la tabla --}}
            <form action="{{ route('quality.surveys.assign.store', $survey) }}" method="POST">
                @csrf
                <div class="p-6"> {{-- Padding para el contenido dentro de la caja --}}

                    @if($usersToAssign->isEmpty())
                        <div class="bg-slate-900/70 p-4 rounded-lg text-center text-slate-500 border border-slate-800/70">
                            No se encontraron usuarios del tipo "{{ ucfirst($survey->target_type) }}" para asignar.
                        </div>
                    @else
                        {{-- Tabla de Usuarios --}}
                        <div class="overflow-x-auto border border-slate-800/70 rounded-lg">
                            <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                                {{-- Cabecera Tabla --}}
                                <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                                    <tr>
                                        <th class="w-10 px-6 py-3">
                                            {{-- Checkbox "Seleccionar Todos" con estilo adaptado --}}
                                            <input type="checkbox" id="selectAllCheckbox"
                                                   class="rounded border-slate-600 bg-slate-700 text-sky-500 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50">
                                        </th>
                                        <th class="px-6 py-3 text-left font-semibold">Nombre Completo</th>
                                        <th class="px-6 py-3 text-left font-semibold">Email</th>
                                        <th class="px-6 py-3 text-left font-semibold">Estado Asignación</th>
                                    </tr>
                                </thead>
                                {{-- Cuerpo Tabla --}}
                                <tbody class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                                    @foreach ($usersToAssign as $user)
                                        <tr class="hover:bg-slate-900/60 transition">
                                            <td class="px-6 py-4">
                                                {{-- Checkbox individual con estilo adaptado --}}
                                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                       {{ in_array($user->id, $assignedUserIds) ? 'checked' : '' }}
                                                       class="user-checkbox rounded border-slate-600 bg-slate-700 text-sky-500 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50">
                                            </td>
                                            <td class="px-6 py-4 text-slate-100">{{ $user->full_name }}</td>
                                            <td class="px-6 py-4">{{ $user->email }}</td>
                                            <td class="px-6 py-4">
                                                @if(in_array($user->id, $assignedUserIds))
                                                    {{-- Badge "Ya Asignado" adaptado --}}
                                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border bg-emerald-500/15 text-emerald-300 border-emerald-400/30">
                                                        Asignado
                                                    </span>
                                                @else
                                                    <span class="text-slate-500">No Asignado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Botón de Guardar Asignaciones --}}
                        <div class="mt-6 flex justify-end"> {{-- Alineado a la derecha --}}
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                                Guardar Asignaciones
                            </button>
                        </div>
                    @endif {{-- Fin if $usersToAssign->isEmpty() --}}
                </div> {{-- Fin p-6 --}}
            </form>

        </div> {{-- Fin "Caja" Principal --}}
    </div> {{-- Fin contenedor principal --}}

{{-- Script para "Seleccionar Todos" (sin cambios) --}}
<script>
    document.getElementById('selectAllCheckbox')?.addEventListener('change', function(event) {
        document.querySelectorAll('.user-checkbox').forEach(function(checkbox) {
            checkbox.checked = event.target.checked;
        });
    });
    // Añadimos '?' por si la tabla está vacía y el checkbox no existe
</script>

@endsection