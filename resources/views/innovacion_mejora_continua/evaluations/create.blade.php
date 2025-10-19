@extends('layouts.app')
@section('title', 'Nueva evaluación')
@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8 text-slate-100">
        <nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500 flex flex-wrap items-center gap-2">
            <a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-slate-200 transition">Innovación</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="hover:text-slate-200 transition">Iniciativas</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="hover:text-slate-200 transition">{{ Str::limit($initiative->title, 30) }}</a>
            <span class="text-slate-600">/</span>
            <span class="text-slate-300">Nueva evaluación</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-white">Registrar evaluación</h1>
            <p class="text-sm text-slate-400">Evalúa la efectividad de la iniciativa <span class="text-slate-200">{{ $initiative->title }}</span>.</p>
        </div>

        <div class="mb-6 rounded-3xl border border-sky-500/30 bg-sky-500/10 p-5 text-sm text-sky-100">
            <h2 class="text-base font-semibold text-white">{{ $initiative->title }}</h2>
            <p class="mt-2 text-sky-100/90">{{ Str::limit($initiative->summary, 150) }}</p>
            <div class="mt-3 flex flex-wrap gap-4 text-xs text-sky-200">
                <span>Estado: {{ ucfirst($initiative->status) }}</span>
                <span>•</span>
                <span>Evaluaciones previas: {{ $initiative->evaluations->count() }}</span>
            </div>
        </div>

        <form action="{{ route('innovacion-mejora-continua.initiatives.evaluations.store', $initiative) }}" method="POST"
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-8 shadow-2xl shadow-slate-950/40 backdrop-blur space-y-8">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="evaluation_date" class="text-sm font-semibold text-slate-300">Fecha de evaluación <span class="text-rose-400">*</span></label>
                    <input type="date" id="evaluation_date" name="evaluation_date" value="{{ old('evaluation_date', date('Y-m-d')) }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40 @error('evaluation_date') border-rose-500/70 @enderror" />
                    @error('evaluation_date')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="score" class="text-sm font-semibold text-slate-300">Puntuación <span class="text-rose-400">*</span></label>
                    <div class="mt-2 flex flex-wrap items-center gap-4">
                        <input type="number" id="score" name="score" min="0" max="10" step="0.1" value="{{ old('score') }}" required
                            class="w-28 rounded-2xl border border-slate-800/60 bg-slate-900/60 px-3 py-2 text-slate-100 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40 @error('score') border-rose-500/70 @enderror" />
                        <span class="text-sm text-slate-400">/10</span>
                        <input type="range" id="score-range" min="0" max="10" step="0.1" value="{{ old('score', 5) }}"
                            class="flex-1 h-2 rounded-lg bg-slate-800/70 accent-emerald-400"
                            oninput="document.getElementById('score').value = this.value" />
                    </div>
                    @error('score')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">Evalúa la efectividad real de la mejora aplicada.</p>
                </div>
                <div class="md:col-span-2">
                    <label for="summary" class="text-sm font-semibold text-slate-300">Resumen de la evaluación <span class="text-rose-400">*</span></label>
                    <textarea id="summary" name="summary" rows="6" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40 @error('summary') border-rose-500/70 @enderror"
                        placeholder="Describe impacto real vs. esperado, aprendizajes y próximos pasos">{{ old('summary') }}</textarea>
                    @error('summary')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="report_document_version_id" class="text-sm font-semibold text-slate-300">Documento de reporte (opcional)</label>
                    <select id="report_document_version_id" name="report_document_version_id"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-fuchsia-400 focus:ring-2 focus:ring-fuchsia-500/40 @error('report_document_version_id') border-rose-500/70 @enderror">
                        <option value="">Sin documento adjunto</option>
                    </select>
                    @error('report_document_version_id')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">Adjunta evidencia o reportes complementarios si están disponibles.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800/60 bg-slate-950/60 p-5 text-xs text-slate-400">
                <h3 class="text-sm font-semibold text-slate-200 mb-3">Criterios sugeridos</h3>
                <ul class="space-y-2">
                    <li><span class="font-semibold text-slate-300">Viabilidad:</span> factibilidad técnica y operativa.</li>
                    <li><span class="font-semibold text-slate-300">Impacto:</span> comparación entre impacto estimado y real.</li>
                    <li><span class="font-semibold text-slate-300">Costo:</span> balance costo-beneficio.</li>
                    <li><span class="font-semibold text-slate-300">Eficacia:</span> cumplimiento de objetivos planteados.</li>
                </ul>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-4 border-t border-slate-800/60">
                <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Registrar evaluación
                </button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('score').addEventListener('input', function () {
            document.getElementById('score-range').value = this.value;
        });
    </script>
@endsection
