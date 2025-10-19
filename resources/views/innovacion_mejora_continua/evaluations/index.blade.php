@extends('layouts.app')
@section('title', 'Evaluaciones de mejoras')
@section('content')
    <div class="container mx-auto px-4 py-8 text-slate-100">
        <nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500 flex flex-wrap items-center gap-2">
            <a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-slate-200 transition">Innovación</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="hover:text-slate-200 transition">Iniciativas</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="hover:text-slate-200 transition">{{ Str::limit($initiative->title, 30) }}</a>
            <span class="text-slate-600">/</span>
            <span class="text-slate-300">Evaluaciones</span>
        </nav>

        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-white">Evaluaciones de mejoras</h1>
                <p class="text-sm text-slate-400">Seguimiento de la iniciativa <span class="text-slate-200">{{ $initiative->title }}</span>.</p>
            </div>
            <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}"
                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva evaluación
            </a>
        </div>

        <div class="mb-6 rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/40 backdrop-blur">
            <div class="grid gap-6 md:grid-cols-3">
                @php
                    $statusPills = [
                        'propuesta' => 'bg-amber-500/10 text-amber-200 border-amber-400/30',
                        'evaluada' => 'bg-sky-500/10 text-sky-200 border-sky-400/30',
                        'aprobada' => 'bg-emerald-500/10 text-emerald-200 border-emerald-400/30',
                        'implementada' => 'bg-fuchsia-500/10 text-fuchsia-200 border-fuchsia-400/30',
                        'cerrada' => 'bg-slate-500/20 text-slate-200 border-slate-400/30',
                    ];
                @endphp
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estado de la iniciativa</p>
                    <span class="mt-2 inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusPills[$initiative->status] ?? 'bg-slate-500/20 text-slate-200 border-slate-400/30' }}">
                        {{ ucfirst($initiative->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Responsable</p>
                    <p class="mt-2 text-sm text-slate-200">{{ $initiative->responsibleUser->name ?? 'Sin asignar' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total evaluaciones</p>
                    <p class="mt-2 text-3xl font-semibold text-sky-200">{{ $evaluations->total() }}</p>
                </div>
            </div>
        </div>

        @if ($evaluations->total() > 0)
            <div class="mb-6 grid gap-6 md:grid-cols-3">
                <div class="rounded-3xl border border-amber-400/30 bg-amber-500/10 p-6 text-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-200">Puntuación promedio</p>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-semibold">{{ number_format($evaluations->avg('score'), 2) }}</span>
                        <span class="text-sm text-amber-200">/ 10</span>
                    </div>
                </div>
                <div class="rounded-3xl border border-emerald-400/30 bg-emerald-500/10 p-6 text-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-200">Puntuación máxima</p>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-semibold">{{ number_format($evaluations->max('score'), 1) }}</span>
                        <span class="text-sm text-emerald-200">/ 10</span>
                    </div>
                </div>
                <div class="rounded-3xl border border-rose-400/30 bg-rose-500/10 p-6 text-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-rose-200">Puntuación mínima</p>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-semibold">{{ number_format($evaluations->min('score'), 1) }}</span>
                        <span class="text-sm text-rose-200">/ 10</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($evaluations as $evaluation)
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/40 transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-400">
                                <span class="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-500/10 px-3 py-1 text-amber-200">
                                    <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ number_format($evaluation->score, 1) }}/10
                                </span>
                                <span>{{ $evaluation->evaluation_date->format('d/m/Y') }}</span>
                                <span class="text-slate-500">{{ $evaluation->evaluation_date->diffForHumans() }}</span>
                                <span class="text-slate-500">Evaluado por <strong class="text-slate-300">{{ $evaluation->evaluator->name }}</strong></span>
                            </div>
                            <p class="mt-3 text-sm text-slate-300">{{ Str::limit($evaluation->summary, 200) }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.show', [$initiative, $evaluation]) }}"
                                class="rounded-full border border-sky-400/40 px-3 py-1 text-sky-200 hover:border-sky-300 hover:text-sky-100">Ver</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-12 text-center shadow-xl shadow-slate-950/40">
                    <svg class="mx-auto mb-4 size-16 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">No hay evaluaciones registradas</h3>
                    <p class="text-sm text-slate-400 mb-6">Esta iniciativa aún no ha sido evaluada.</p>
                    <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                        Crear primera evaluación
                    </a>
                </div>
            @endforelse
        </div>

        @if ($evaluations->hasPages())
            <div class="mt-8 text-slate-300">
                {{ $evaluations->links() }}
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 px-4 py-2 text-xs font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a la iniciativa
            </a>
        </div>
    </div>
@endsection
