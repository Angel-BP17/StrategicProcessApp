@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Procesos de Acreditación') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            {{-- Título y botón --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-medium text-white">
                    Listado de Acreditaciones
                </h1>
                <a href="{{ route('quality.accreditations.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                    Registrar Nueva
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-smoky-black">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Entidad Acreditadora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Resultado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha Obtención</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha Expiración</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-dark-purple divide-y divide-gray-700">
                        @forelse ($accreditations as $accreditation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $accreditation->entity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $accreditation->result }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ \Carbon\Carbon::parse($accreditation->accreditation_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    {{ $accreditation->expiration_date ? \Carbon\Carbon::parse($accreditation->expiration_date)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('quality.accreditations.edit', $accreditation) }}" class="text-primary hover:text-opacity-75">
                                        Ver/Editar
                                    </a>
                                    
                                    {{-- 2. Formulario para Eliminar (esto es lo nuevo) --}}
                                    <form action="{{ route('quality.accreditations.destroy', $accreditation) }}" method="POST" class="inline-block ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700" 
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta acreditación? Esta acción no se puede deshacer.');">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    No hay acreditaciones registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection