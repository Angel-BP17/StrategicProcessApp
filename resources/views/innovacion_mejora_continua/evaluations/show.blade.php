@extends('layouts.app')
@section('title', 'Evaluación de mejoras')
@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8 text-slate-100">
        <nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500 flex flex-wrap items-center gap-2">
            <a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-slate-200 transition">Innovación</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="hover:text-slate-200 transition">Iniciativas</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="hover:text-slate-200 transition">{{ Str::limit($initiative->title, 30) }}</a>
            <span class="text-slate-600">/</span>
            <span class="text-slate-300">Evaluación</span>
        </nav>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h1 class="text-3xl font-semibold text-white">Detalle de evaluación</h1>
                            <p class="text-sm text-slate-400">Evaluación de la iniciativa <span class="text-slate-200">{{ $initiative->title }}</span></p>
                        </div>
                        <div class="flex flex-wrap gap-3 text-xs">
                            <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.edit', [$initiative, $evaluation]) }}"
                                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 px-4 py-2 font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Editar</a>
                            <form action="{{ route('innovacion-mejora-continua.initiatives.evaluations.destroy', [$initiative, $evaluation]) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar esta evaluación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-2xl border border-rose-500/40 px-4 py-2 font-semibold text-rose-200 transition hover:border-rose-400 hover:text-rose-100">Eliminar</button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-amber-400/30 bg-amber-500/10 p-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-200">Puntuación obtenida</p>
                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <span class="text-4xl font-semibold text-white">{{ number_format($evaluation->score, 1) }}</span>
                            <span class="text-sm text-amber-200">/ 10</span>
                            <div class="h-2 flex-1 rounded-full bg-slate-800/60">
                                <div class="h-2 rounded-full bg-gradient-to-r from-amber-400 to-orange-500"
                                    style="width: {{ ($evaluation->score / 10) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4 text-sm text-slate-300">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Resumen</p>
                            <div class="mt-2 rounded-2xl border border-slate-800/60 bg-slate-900/60 p-4 whitespace-pre-line">{{ $evaluation->summary }}</div>
                        </div>
                        @if ($evaluation->report_document_version_id)
                            <div class="rounded-2xl border border-sky-400/30 bg-sky-500/10 p-4 text-sky-100">
                                Documento asociado: <span class="font-semibold">#{{ $evaluation->report_document_version_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/40 backdrop-blur">
                    <h3 class="text-lg font-semibold text-white mb-4">Información general</h3>
                    <div class="space-y-4 text-sm text-slate-300">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Evaluador</p>
                            <p class="mt-1">{{ $evaluation->evaluator->name }}</p>
                            <p class="text-xs text-slate-500">{{ $evaluation->evaluator->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fecha de evaluación</p>
                            <p class="mt-1">{{ $evaluation->evaluation_date->format('d/m/Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $evaluation->evaluation_date->diffForHumans() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Creado</p>
                            <p class="mt-1">{{ $evaluation->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/40 backdrop-blur">
                    <h3 class="text-lg font-semibold text-white mb-4">Contexto de la iniciativa</h3>
                    <div class="space-y-3 text-sm text-slate-300">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Responsable</p>
                            <p class="mt-1">{{ $initiative->responsibleUser->name ?? 'Sin asignar' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estado actual</p>
                            <p class="mt-1">{{ ucfirst($initiative->status) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total de evaluaciones</p>
                            <p class="mt-1">{{ $initiative->evaluations->count() }}</p>
                        </div>
                        @if ($initiative->evaluations->count() > 1)
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Promedio histórico</p>
                                <p class="mt-1">{{ number_format($initiative->evaluations->avg('score'), 2) }} / 10</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-4 shadow-xl shadow-slate-950/40">
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700/70 px-4 py-2 text-xs font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Ver iniciativa</a>
                        <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">Nueva evaluación</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
