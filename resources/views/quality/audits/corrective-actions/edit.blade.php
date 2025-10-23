@extends('layouts.app')

@section('title', 'Editar Acción Correctiva')

@section('content')
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        <div class="mb-8">
             <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Gestión de Calidad / Auditorías / Acciones Correctivas</p>
             <h1 class="text-3xl font-semibold">Editando Acción Correctiva</h1>
             <p class="text-slate-400 mt-1">Para Hallazgo: {{ Str::limit($finding->description, 60) }}</p> {{-- Mostramos contexto --}}
         </div>

         {{-- Botón Volver al Detalle de Auditoría --}}
         <div class="mb-6">
            <a href="{{ route('quality.audits.show', $finding->audit_id) }}"
               class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                &larr; Volver a la Auditoría
            </a>
        </div>

        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Datos de la Acción Correctiva</div>

            <form action="{{ route('quality.corrective-actions.update', $action) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4"> {{-- 3 columnas --}}

                    {{-- Campo: Descripción (Pre-llenado) --}}
                    <div class="md:col-span-3"> {{-- Ocupa todo el ancho --}}
                        <label for="description" class="block text-sm font-semibold text-slate-300 mb-1">Descripción de la Acción</label>
                        <input type="text" name="description" id="description" value="{{ old('description', $action->description) }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Responsable (Pre-seleccionado) --}}
                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-slate-300 mb-1">Responsable</label>
                        <select name="user_id" id="user_id" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="">Seleccione...</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $action->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Fecha Límite (Pre-llenado) --}}
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha Límite</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $action->due_date ? \Carbon\Carbon::parse($action->due_date)->format('Y-m-d') : '') }}" required
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('due_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo: Estado (Pre-seleccionado) --}}
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-300 mb-1">Estado</label>
                        <select name="status" id="status" required
                                class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                            <option value="pending" {{ old('status', $action->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="in_progress" {{ old('status', $action->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                            <option value="completed" {{ old('status', $action->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelled" {{ old('status', $action->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campos Opcionales: Fechas de Compromiso y Completado --}}
                    <div>
                        <label for="engagement_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha Compromiso (Opc)</label>
                        <input type="date" name="engagement_date" id="engagement_date" value="{{ old('engagement_date', $action->engagement_date ? \Carbon\Carbon::parse($action->engagement_date)->format('Y-m-d') : '') }}"
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('engagement_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                     <div>
                        <label for="completion_date" class="block text-sm font-semibold text-slate-300 mb-1">Fecha Completado (Opc)</label>
                        <input type="date" name="completion_date" id="completion_date" value="{{ old('completion_date', $action->completion_date ? \Carbon\Carbon::parse($action->completion_date)->format('Y-m-d') : '') }}"
                               class="block w-full bg-slate-800/60 border border-slate-700 text-slate-100 rounded-lg shadow-sm py-2 px-3 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 text-sm">
                        @error('completion_date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quality.audits.show', $finding->audit_id) }}" class="inline-flex items-center px-4 py-2 bg-slate-600/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500/90 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-500/90 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-400/90 transition shadow-md shadow-sky-500/30">
                        Actualizar Acción
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection