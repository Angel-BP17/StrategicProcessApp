@extends('layouts.app')

@section('title', 'Gestión de Alianzas y Convenios')

@section('content')
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Relaciones estratégicas</p>
                <h1 class="text-3xl font-semibold">Gestión de Alianzas y Convenios</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('partners.create') }}"
                    class="inline-flex items-center gap-2 bg-emerald-500/90 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-emerald-500/30 hover:bg-emerald-400 transition">
                    Nuevo Socio
                </a>
                <a href="{{ route('agreements.create') }}"
                    class="inline-flex items-center gap-2 bg-sky-500/90 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition">
                    Nuevo Convenio
                </a>
            </div>
        </div>

        {{-- Tabla de Socios --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Socios Registrados</div>
            <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Nombre</th>
                        <th class="px-6 py-3 text-left font-semibold">Tipo</th>
                        <th class="px-6 py-3 text-left font-semibold">Contacto</th>
                        <th class="px-6 py-3 text-left font-semibold">Representante</th>
                        <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                    @forelse($partners as $partner)
                        <tr class="hover:bg-slate-900/60 transition">
                            <td class="px-6 py-4 text-slate-100">{{ $partner->name }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $partner->type }}</td>
                            <td class="px-6 py-4 text-slate-300">
                                @if(is_array($partner->contact))
                                    {{ $partner->contact['email'] ?? '' }}
                                @else
                                    {{ $partner->contact }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $partner->legal_representative }}</td>
                            <td class="px-6 py-4 flex flex-wrap gap-2">
                                <a href="{{ route('partners.edit', $partner->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition">Editar</a>
                                <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-rose-600/90 text-white font-semibold hover:bg-rose-500 transition"
                                        onclick="return confirm('¿Deseas eliminar este socio?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-slate-500">No hay socios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabla de Convenios --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl overflow-hidden">
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Convenios Registrados</div>
            <table class="min-w-full divide-y divide-slate-800/70 text-sm">
                <thead class="bg-slate-900/70 text-slate-400 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Socio</th>
                        <th class="px-6 py-3 text-left font-semibold">Convenio</th>
                        <th class="px-6 py-3 text-left font-semibold">Inicio</th>
                        <th class="px-6 py-3 text-left font-semibold">Fin</th>
                        <th class="px-6 py-3 text-left font-semibold">Estado</th>
                        <th class="px-6 py-3 text-left font-semibold">Firma Electrónica</th>
                        <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-transparent divide-y divide-slate-800/70 text-slate-300">
                    @forelse($agreements as $agreement)
                        <tr class="hover:bg-slate-900/60 transition">
                            <td class="px-6 py-4 text-slate-100">{{ $agreement->partner->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $agreement->title }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $agreement->start_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $agreement->end_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border
                                    @if($agreement->status === 'Activo') bg-emerald-500/15 text-emerald-300 border-emerald-400/30
                                    @elseif($agreement->status === 'Pendiente') bg-amber-500/15 text-amber-300 border-amber-400/30
                                    @else bg-slate-500/20 text-slate-200 border-slate-400/30 @endif">
                                    {{ $agreement->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $agreement->electronic_signature ? 'Sí' : 'No' }}</td>
                            <td class="px-6 py-4 flex flex-wrap gap-2">
                                <a href="{{ route('agreements.edit', $agreement->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 font-semibold hover:bg-amber-300 transition">Editar</a>
                                <form action="{{ route('agreements.destroy', $agreement->id) }}" method="POST"
                                    onsubmit="return confirm('¿Seguro que quieres eliminar este convenio?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-rose-600/90 text-white font-semibold hover:bg-rose-500 transition">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-slate-500">No hay convenios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
