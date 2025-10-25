@extends('layouts.app')

@section('title', 'Gestión de Alianzas y Convenios')

@section('content')
    @php
        // Cálculos rápidos (puedes moverlos al controlador si prefieres)
        $partnersCount = \App\Models\Partner::count();
        $agreementsCount = \App\Models\Agreement::count();
        $agreementsActiveCount = \App\Models\Agreement::where('status', 'Activo')->count();
    @endphp

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Relaciones estratégicas</p>
                <h1 class="text-3xl font-semibold">Gestión de Alianzas y Convenios</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('partners.create') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-sky-500 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 transition">
                    Nuevo Socio
                </a>
                <a href="{{ route('agreements.create') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/30 hover:shadow-indigo-500/40 transition-transform hover:-translate-y-0.5">
                    Nuevo Convenio
                </a>
            </div>
        </div>

        {{-- Contadores --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-5 shadow-xl shadow-slate-900/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Socios</h3>
                    <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-4-4h-1M7 20H2v-2a4 4 0 014-4h1m10-5a4 4 0 11-8 0 4 4 0 018 0M9 9a4 4 0 018 0" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-white">{{ $partnersCount }}</p>
                <p class="text-xs text-slate-400 mt-1">Total de organizaciones asociadas</p>
            </div>

            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-5 shadow-xl shadow-slate-900/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Convenios</h3>
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h10a2 2 0 012 2v7l-3-2-3 2-3-2-3 2V9a2 2 0 012-2z" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-white">{{ $agreementsCount }}</p>
                <p class="text-xs text-slate-400 mt-1">Total de convenios registrados</p>
            </div>

            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-5 shadow-xl shadow-slate-900/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Convenios activos</h3>
                    <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v6l3 2 3-2 3 2 3-2V8a6 6 0 00-6-6z" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-white">{{ $agreementsActiveCount }}</p>
                <p class="text-xs text-slate-400 mt-1">Con estado “Activo”</p>
            </div>
        </section>

        {{-- Tabla de Socios --}}
        <div
            class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
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
                                @if (is_array($partner->contact))
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
                                <span
                                    class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border
                          @if ($agreement->status === 'Activo') bg-emerald-500/15 text-emerald-300 border-emerald-400/30
                          @elseif ($agreement->status === 'Pendiente')
                              bg-amber-500/15 text-amber-300 border-amber-400/30
                          @else
                              bg-slate-500/20 text-slate-200 border-slate-400/30 @endif">
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
                            <td colspan="7" class="px-6 py-6 text-center text-slate-500">No hay convenios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
