@extends('layouts.app')
@section('title', 'Gestión de iniciativas')
@section('content')
    <div class="container mx-auto px-4 py-8 text-slate-100">
        <nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500 flex items-center gap-2">
            <a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-slate-200 transition">Innovación</a>
            <span class="text-slate-600">/</span>
            <span class="text-slate-300">Iniciativas</span>
        </nav>

        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-white">Gestión de iniciativas</h1>
                <p class="text-sm text-slate-400">Administra ideas, responsables, impactos esperados y recursos necesarios.</p>
            </div>
            <a href="{{ route('innovacion-mejora-continua.initiatives.create') }}"
                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva iniciativa
            </a>
        </div>

        <div class="mb-6 rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/40 backdrop-blur">
            <form method="GET" action="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Título, descripción..."
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">Estado</label>
                    <select name="status"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-2.5 text-sm text-slate-100 focus:border-fuchsia-400 focus:ring-2 focus:ring-fuchsia-500/40">
                        <option value="">Todos los estados</option>
                        @foreach (['propuesta', 'evaluada', 'aprobada', 'implementada', 'cerrada'] as $state)
                            <option value="{{ $state }}" @selected(request('status') == $state)>{{ ucfirst($state) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">Responsable</label>
                    <select name="responsible_user_id"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-2.5 text-sm text-slate-100 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40">
                        <option value="">Todos los usuarios</option>
                        @foreach (\App\Models\User::orderBy('full_name')->get() as $user)
                            <option value="{{ $user->id }}" @selected(request('responsible_user_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap items-end gap-2">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-slate-800/70 px-4 py-2.5 text-sm font-semibold text-slate-200 shadow-lg shadow-slate-950/30 transition hover:bg-slate-800/90">Filtrar</button>
                    @if (request()->hasAny(['search', 'status', 'responsible_user_id', 'responsible_team_id']))
                        <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700/70 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 px-5 py-4 text-sm text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-6">
            @forelse ($initiatives as $initiative)
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 shadow-xl shadow-slate-950/40 transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start gap-3">
                                    <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="text-lg font-semibold text-white hover:text-sky-300 transition">
                                        {{ $initiative->title }}
                                    </a>
                                    @php
                                        $statusPills = [
                                            'propuesta' => 'bg-amber-500/10 text-amber-200 border-amber-400/30',
                                            'evaluada' => 'bg-sky-500/10 text-sky-200 border-sky-400/30',
                                            'aprobada' => 'bg-emerald-500/10 text-emerald-200 border-emerald-400/30',
                                            'implementada' => 'bg-fuchsia-500/10 text-fuchsia-200 border-fuchsia-400/30',
                                            'cerrada' => 'bg-slate-500/20 text-slate-200 border-slate-400/30',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusPills[$initiative->status] ?? 'bg-slate-500/20 text-slate-200 border-slate-400/30' }}">
                                        {{ ucfirst($initiative->status) }}
                                    </span>
                                </div>
                                <p class="mt-3 text-sm text-slate-400">{{ Str::limit($initiative->summary, 200) }}</p>
                                <div class="mt-4 flex flex-wrap gap-4 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="size-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="font-semibold text-slate-300">Responsable:</span> {{ $initiative->responsibleUser->name ?? 'Sin asignar' }}
                                    </span>
                                    @if ($initiative->responsibleTeam)
                                        <span class="inline-flex items-center gap-2">
                                            <svg class="size-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span class="font-semibold text-slate-300">Equipo:</span> {{ $initiative->responsibleTeam->name }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="size-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="font-semibold text-slate-300">Inicio:</span> {{ $initiative->start_date ? $initiative->start_date->format('d/m/Y') : 'Sin fecha' }}
                                    </span>
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="size-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        <span class="font-semibold text-slate-300">Evaluaciones:</span> {{ $initiative->evaluations->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if ($initiative->estimated_impact)
                            <div class="mt-5 rounded-2xl border border-sky-500/30 bg-sky-500/10 px-4 py-3 text-sm text-sky-100">
                                <span class="font-semibold">Impacto estimado:</span> {{ $initiative->estimated_impact }}
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-800/60 bg-slate-950/60 px-6 py-3 text-xs text-slate-500">
                        <span>Creada {{ $initiative->created_at->diffForHumans() }}</span>
                        <div class="flex gap-3">
                            <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="inline-flex items-center gap-1 text-sky-300 hover:text-sky-100">
                                Ver detalles
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="{{ route('innovacion-mejora-continua.initiatives.edit', $initiative) }}" class="inline-flex items-center gap-1 text-slate-300 hover:text-slate-100">
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-12 text-center shadow-xl shadow-slate-950/40">
                    <svg class="mx-auto mb-4 size-16 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">No se encontraron iniciativas</h3>
                    <p class="text-sm text-slate-400 mb-6">
                        @if (request()->hasAny(['search', 'status', 'responsible_user_id']))
                            No hay iniciativas que coincidan con los filtros aplicados.
                        @else
                            Comienza creando tu primera iniciativa de innovación y mejora continua.
                        @endif
                    </p>
                    <div class="flex flex-wrap justify-center gap-3">
                        @if (request()->hasAny(['search', 'status', 'responsible_user_id']))
                            <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Limpiar filtros</a>
                        @endif
                        <a href="{{ route('innovacion-mejora-continua.initiatives.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Crear iniciativa
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($initiatives->hasPages())
            <div class="mt-8 text-slate-300">
                {{ $initiatives->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
