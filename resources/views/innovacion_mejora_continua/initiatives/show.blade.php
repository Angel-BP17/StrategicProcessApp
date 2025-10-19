@extends('layouts.app')
@section('title', $initiative->title)
@section('content')
    <div class="container mx-auto px-4 py-8 text-slate-100">
        <nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500 flex flex-wrap items-center gap-2">
            <a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-slate-200 transition">Innovación</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="hover:text-slate-200 transition">Iniciativas</a>
            <span class="text-slate-600">/</span>
            <span class="text-slate-300">{{ Str::limit($initiative->title, 50) }}</span>
        </nav>

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

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-semibold text-white mb-2">{{ $initiative->title }}</h1>
                            @php
                                $statusPills = [
                                    'propuesta' => 'bg-amber-500/10 text-amber-200 border-amber-400/30',
                                    'evaluada' => 'bg-sky-500/10 text-sky-200 border-sky-400/30',
                                    'aprobada' => 'bg-emerald-500/10 text-emerald-200 border-emerald-400/30',
                                    'implementada' => 'bg-fuchsia-500/10 text-fuchsia-200 border-fuchsia-400/30',
                                    'cerrada' => 'bg-slate-500/20 text-slate-200 border-slate-400/30',
                                ];
                            @endphp
                            <div class="flex flex-wrap items-center gap-3 text-xs">
                                <span class="inline-flex items-center rounded-full border px-3 py-1 font-semibold {{ $statusPills[$initiative->status] ?? 'bg-slate-500/20 text-slate-200 border-slate-400/30' }}">
                                    {{ ucfirst($initiative->status) }}
                                </span>
                                <span class="text-slate-400">ID:</span>
                                <span class="font-mono text-slate-200">{{ $initiative->plan_id }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('innovacion-mejora-continua.initiatives.edit', $initiative) }}"
                                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 px-4 py-2 text-xs font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>
                            <form action="{{ route('innovacion-mejora-continua.initiatives.destroy', $initiative) }}" method="POST" onsubmit="return confirm('¿Eliminar esta iniciativa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-2xl border border-rose-500/40 px-4 py-2 text-xs font-semibold text-rose-200 transition hover:border-rose-400 hover:text-rose-100">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-6 space-y-6 text-sm leading-relaxed text-slate-300">
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Resumen</h3>
                            <p class="mt-2 text-slate-200">{{ $initiative->summary }}</p>
                        </div>
                        @if ($initiative->description)
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Descripción completa</h3>
                                <div class="mt-2 rounded-2xl border border-slate-800/60 bg-slate-900/60 p-4 text-slate-200 whitespace-pre-line">{{ $initiative->description }}</div>
                            </div>
                        @endif
                        @if ($initiative->estimated_impact)
                            <div class="rounded-2xl border border-sky-500/30 bg-sky-500/10 p-4 text-sky-100">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-sky-200">Impacto estimado</h3>
                                <p class="mt-2">{{ $initiative->estimated_impact }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <h2 class="text-2xl font-semibold text-white">Evaluaciones de mejoras aplicadas</h2>
                        <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-5 py-2 text-xs font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nueva evaluación
                        </a>
                    </div>

                    <div class="mt-6 space-y-4">
                        @forelse ($initiative->evaluations as $evaluation)
                            <div class="rounded-2xl border border-slate-800/60 bg-slate-950/60 p-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-slate-400">
                                            <span class="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-500/10 px-3 py-1 text-amber-200">
                                                <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                {{ number_format($evaluation->score, 1) }}/10
                                            </span>
                                            <span>{{ $evaluation->evaluation_date->format('d/m/Y') }}</span>
                                            <span class="text-slate-500">Evaluado por <strong class="text-slate-300">{{ $evaluation->evaluator->name }}</strong></span>
                                        </div>
                                        <p class="mt-3 text-sm text-slate-300">{{ $evaluation->summary }}</p>
                                    </div>
                                    <div class="flex gap-3 text-xs font-semibold">
                                        <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.show', [$initiative, $evaluation]) }}"
                                            class="rounded-full border border-sky-400/40 px-3 py-1 text-sky-200 hover:border-sky-300 hover:text-sky-100">Ver</a>
                                        <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.edit', [$initiative, $evaluation]) }}"
                                            class="rounded-full border border-emerald-400/40 px-3 py-1 text-emerald-200 hover:border-emerald-300 hover:text-emerald-100">Editar</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-slate-800/60 bg-slate-950/60 p-10 text-center text-sm text-slate-400">
                                Aún no hay evaluaciones registradas para esta iniciativa.
                                <div class="mt-4">
                                    <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}"
                                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                                        Crear primera evaluación
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
                    <h3 class="text-lg font-semibold text-white mb-4">Información</h3>
                    <div class="space-y-4 text-sm text-slate-300">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Usuario responsable</p>
                            <p class="mt-1">{{ $initiative->responsibleUser->name ?? 'Sin asignar' }}</p>
                        </div>
                        @if ($initiative->responsibleTeam)
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Equipo responsable</p>
                                <p class="mt-1">{{ $initiative->responsibleTeam->name }}</p>
                            </div>
                        @endif
                        @if ($initiative->start_date)
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fecha de inicio</p>
                                <p class="mt-1">{{ $initiative->start_date->format('d/m/Y') }}</p>
                            </div>
                        @endif
                        @if ($initiative->end_date)
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fecha de fin</p>
                                <p class="mt-1">{{ $initiative->end_date->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
                    <h3 class="text-lg font-semibold text-white mb-4">Estadísticas</h3>
                    <div class="space-y-4 text-sm text-slate-300">
                        <div class="flex items-center justify-between">
                            <span>Total de evaluaciones</span>
                            <span class="text-xl font-semibold text-sky-200">{{ $initiative->evaluations->count() }}</span>
                        </div>
                        @if ($initiative->evaluations->count() > 0)
                            <div class="flex items-center justify-between">
                                <span>Puntuación promedio</span>
                                <span class="flex items-center gap-2 text-xl font-semibold text-amber-200">
                                    {{ number_format($initiative->evaluations->avg('score'), 1) }}
                                    <span class="text-xs text-slate-500">/10</span>
                                </span>
                            </div>
                            <div class="rounded-2xl border border-slate-800/60 bg-slate-900/60 p-4 text-xs text-slate-400">
                                <p class="font-semibold text-slate-200">Última evaluación</p>
                                <p>{{ $initiative->evaluations->first()->evaluation_date->format('d/m/Y') }}</p>
                                <p>{{ $initiative->evaluations->first()->evaluation_date->diffForHumans() }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-4 text-xs text-slate-500 shadow-xl shadow-slate-950/40">
                    <p><span class="font-semibold text-slate-300">Creada:</span> {{ $initiative->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold text-slate-300">Actualizada:</span> {{ $initiative->updated_at->format('d/m/Y H:i') }}</p>
                    <p class="mt-1 text-slate-600">{{ $initiative->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
