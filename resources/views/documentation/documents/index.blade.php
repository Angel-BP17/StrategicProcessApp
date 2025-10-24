@extends('layouts.app')

@section('title', 'Documentación y evidencias')

@section('content')
    <div class="container mx-auto px-0 sm:px-4">
        @include('documentation._nav')

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Gestión documental</p>
                <h1 class="text-2xl font-semibold text-slate-100">Documentos registrados</h1>
                <p class="text-sm text-slate-400 mt-1">Administra la metadata de los documentos estratégicos y de apoyo.</p>
            </div>
            @if ($canManageDocuments)
                <a href="{{ route('documents.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 text-white font-semibold shadow-lg shadow-sky-500/30 hover:shadow-indigo-500/40 transition-transform hover:-translate-y-0.5">
                    <span class="hidden sm:inline">Nuevo documento</span>
                    <span class="sm:hidden">Crear</span>
                </a>
            @endif
        </div>

        <form action="{{ route('documents.index') }}" method="GET"
            class="mb-6 grid grid-cols-1 md:grid-cols-[2fr,1fr,1fr,auto] gap-3">
            <label class="relative block">
                <span class="sr-only">Buscar</span>
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </span>
                <input type="search" name="q" value="{{ $filters['q'] }}"
                    placeholder="Buscar por título o descripción"
                    class="w-full pl-10 pr-3 py-2 rounded-xl bg-slate-900/70 border border-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-400/70 focus:border-sky-400/70 transition text-sm" />
            </label>

            <label class="block">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Categoría</span>
                <select name="category"
                    class="mt-1 w-full px-3 py-2 rounded-xl bg-slate-900/70 border border-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-400/70 focus:border-sky-400/70 text-sm">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected($filters['category'] === $category)>
                            {{ ucfirst(__($category)) }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="block">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Estado</span>
                <select name="status"
                    class="mt-1 w-full px-3 py-2 rounded-xl bg-slate-900/70 border border-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-400/70 focus:border-sky-400/70 text-sm">
                    <option value="">Todos</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>
                            {{ ucfirst(__($status)) }}
                        </option>
                    @endforeach
                </select>
            </label>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full px-4 py-2 rounded-xl bg-sky-500/90 text-white font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition">
                    Filtrar
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-800/80 bg-slate-950/60 shadow-xl shadow-slate-900/50">
            <table class="w-full text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-900/80 text-slate-300 uppercase tracking-wide text-[11px] sm:text-xs">
                        <th class="px-4 py-3 text-left font-semibold">Título</th>
                        <th class="px-4 py-3 text-left font-semibold">Categoría</th>
                        <th class="px-4 py-3 text-left font-semibold">Estado</th>
                        <th class="px-4 py-3 text-left font-semibold">Versión</th>
                        <th class="px-4 py-3 text-left font-semibold">Responsable</th>
                        <th class="px-4 py-3 text-left font-semibold">Creado</th>
                        <th class="px-4 py-3 text-left font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @forelse ($documents as $document)
                        <tr class="hover:bg-slate-900/60 transition">
                            <td class="px-4 py-4 text-slate-100 font-medium">
                                <a href="{{ route('documents.show', $document) }}" class="hover:text-sky-300 transition">
                                    {{ $document->title }}
                                </a>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/60 text-slate-200 border border-slate-700 text-xs font-semibold">
                                    {{ ucfirst(__($document->category)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-amber-500/15 text-amber-200 border-amber-400/30',
                                        'active' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/30',
                                        'archived' => 'bg-slate-700/40 text-slate-300 border-slate-500/40',
                                    ];
                                    $statusColor =
                                        $statusColors[$document->status] ??
                                        'bg-slate-800/60 text-slate-200 border-slate-700';
                                @endphp
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst(__($document->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-300">
                                @if ($document->latestVersion)
                                    v{{ $document->latestVersion->version_number }}
                                @else
                                    <span class="text-slate-500">Sin versiones</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-slate-300">
                                {{ optional($document->creator)->name ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-slate-400">
                                {{ $document->created_at?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3 text-xs">
                                    <a href="{{ route('documents.show', $document) }}"
                                        class="text-sky-300 hover:text-sky-200 font-semibold">Ver</a>
                                    @if ($canManageDocuments)
                                        <a href="{{ route('documents.edit', $document) }}"
                                            class="text-indigo-300 hover:text-indigo-200 font-semibold">Editar</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">No hay documentos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    </div>
@endsection
