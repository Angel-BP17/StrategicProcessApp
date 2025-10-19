@extends('layouts.app')

@section('title', 'Gestión de Alianzas y Convenios')

@section('content')
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Gestión de Alianzas y Convenios</h1>

        {{-- Botones de acción --}}
        <div class="flex justify-end mb-4 space-x-2">
            <a href="{{ route('partners.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Nuevo Socio</a>
            <a href="{{ route('agreements.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nuevo Convenio</a>
        </div>

        {{-- Tabla de Socios --}}
        <div class="bg-white shadow-md rounded-lg mb-8 overflow-hidden">
            <div class="p-4 font-semibold text-lg border-b">Socios Registrados</div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left">Nombre</th>
                        <th class="px-6 py-3 text-left">Tipo</th>
                        <th class="px-6 py-3 text-left">Contacto</th>
                        <th class="px-6 py-3 text-left">Representante</th>
                        <th class="px-6 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($partners as $partner)
                        <tr>
                            <td class="px-6 py-4">{{ $partner->name }}</td>
                            <td class="px-6 py-4">{{ $partner->type }}</td>
                            <td class="px-6 py-4">
                                @if(is_array($partner->contact))
                                    {{ $partner->contact['email'] ?? '' }}
                                @else
                                    {{ $partner->contact }}
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $partner->legal_representative }}</td>
                            <td class="px-6 py-4 space-x-1">
                                <a href="{{ route('partners.edit', $partner->id) }}"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Editar</a>
                                <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                                        onclick="return confirm('¿Deseas eliminar este socio?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-zinc-500">No hay socios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabla de Convenios --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 font-semibold text-lg border-b">Convenios Registrados</div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left">Socio</th>
                        <th class="px-6 py-3 text-left">Convenio</th>
                        <th class="px-6 py-3 text-left">Inicio</th>
                        <th class="px-6 py-3 text-left">Fin</th>
                        <th class="px-6 py-3 text-left">Estado</th>
                        <th class="px-6 py-3 text-left">Firma Electrónica</th>
                        <th class="px-6 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($agreements as $agreement)
                        <tr>
                            <td class="px-6 py-4">{{ $agreement->partner->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $agreement->title }}</td>
                            <td class="px-6 py-4">{{ $agreement->start_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $agreement->end_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full 
                                    @if($agreement->status === 'Activo') bg-green-100 text-green-800
                                    @elseif($agreement->status === 'Pendiente') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $agreement->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $agreement->electronic_signature ? 'Sí' : 'No' }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('agreements.edit', $agreement->id) }}"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Editar</a>
                                    <form action="{{ route('agreements.destroy', $agreement->id) }}" method="POST"
          onsubmit="return confirm('¿Seguro que quieres eliminar este convenio?');">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
            Eliminar
        </button>
    </form>

                                    
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-zinc-500">No hay convenios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
