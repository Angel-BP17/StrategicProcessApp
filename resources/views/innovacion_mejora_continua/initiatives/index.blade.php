@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-4">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li><a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-blue-600">Innovación y Mejora Continua</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800 font-medium">Iniciativas</li>
        </ol>
    </nav>

    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestión de Iniciativas</h1>
            <p class="text-gray-600 mt-2">Sistema de gestión de iniciativas/ideas: título, descripción, autor, equipo, impacto esperado, recursos requeridos</p>
        </div>
        <a href="{{ route('innovacion-mejora-continua.initiatives.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Iniciativa
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Título, descripción..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos los estados</option>
                    <option value="propuesta" {{ request('status') == 'propuesta' ? 'selected' : '' }}>Propuesta</option>
                    <option value="evaluada" {{ request('status') == 'evaluada' ? 'selected' : '' }}>Evaluada</option>
                    <option value="aprobada" {{ request('status') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                    <option value="implementada" {{ request('status') == 'implementada' ? 'selected' : '' }}>Implementada</option>
                    <option value="cerrada" {{ request('status') == 'cerrada' ? 'selected' : '' }}>Cerrada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                <select name="responsible_user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos los usuarios</option>
                    @foreach(\App\Models\User::orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ request('responsible_user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Filtrar
                </button>
                @if(request()->hasAny(['search', 'status', 'responsible_user_id', 'responsible_team_id']))
                    <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Lista de Iniciativas -->
    <div class="grid gap-6">
        @forelse($initiatives as $initiative)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-xl font-semibold text-gray-800 flex-1">
                                <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" 
                                   class="hover:text-blue-600 transition-colors">
                                    {{ $initiative->title }}
                                </a>
                            </h3>
                            @php
                                $statusColors = [
                                    'propuesta' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'evaluada' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'aprobada' => 'bg-green-100 text-green-800 border-green-300',
                                    'implementada' => 'bg-purple-100 text-purple-800 border-purple-300',
                                    'cerrada' => 'bg-gray-100 text-gray-800 border-gray-300'
                                ];
                            @endphp
                            <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$initiative->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($initiative->status) }}
                            </span>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 leading-relaxed">{{ Str::limit($initiative->summary, 200) }}</p>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <strong class="text-gray-700 mr-1">Responsable:</strong>
                                {{ $initiative->responsibleUser->name ?? 'Sin asignar' }}
                            </span>
                            
                            @if($initiative->responsibleTeam)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <strong class="text-gray-700 mr-1">Equipo:</strong>
                                {{ $initiative->responsibleTeam->name }}
                            </span>
                            @endif
                            
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <strong class="text-gray-700 mr-1">Inicio:</strong>
                                {{ $initiative->start_date ? $initiative->start_date->format('d/m/Y') : 'Sin fecha' }}
                            </span>
                            
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <strong class="text-gray-700 mr-1">Evaluaciones:</strong>
                                {{ $initiative->evaluations->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($initiative->estimated_impact)
                <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-400 rounded">
                    <p class="text-sm text-blue-800">
                        <strong class="font-semibold">Impacto estimado:</strong> {{ $initiative->estimated_impact }}
                    </p>
                </div>
                @endif
            </div>
            
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <span class="text-xs text-gray-500">
                    Creada {{ $initiative->created_at->diffForHumans() }}
                </span>
                <div class="flex gap-3">
                    <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                        Ver detalles
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('innovacion-mejora-continua.initiatives.edit', $initiative) }}" 
                       class="text-gray-600 hover:text-gray-800 font-medium text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No se encontraron iniciativas</h3>
            <p class="text-gray-600 mb-6">
                @if(request()->hasAny(['search', 'status', 'responsible_user_id']))
                    No hay iniciativas que coincidan con los filtros aplicados.
                @else
                    Comienza creando tu primera iniciativa de innovación y mejora continua.
                @endif
            </p>
            @if(request()->hasAny(['search', 'status', 'responsible_user_id']))
                <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" 
                   class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg mr-2">
                    Limpiar filtros
                </a>
            @endif
            <a href="{{ route('innovacion-mejora-continua.initiatives.create') }}" 
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear iniciativa
            </a>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($initiatives->hasPages())
    <div class="mt-6">
        {{ $initiatives->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
