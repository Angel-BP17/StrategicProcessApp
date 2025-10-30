@extends('layouts.app')
@section('title', 'Nueva iniciativa')
@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8 text-slate-100">
        <nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500 flex flex-wrap items-center gap-2">
            <a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-slate-200 transition">Innovación</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}"
                class="hover:text-slate-200 transition">Iniciativas</a>
            <span class="text-slate-600">/</span>
            <span class="text-slate-300">Nueva</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-white">Crear nueva iniciativa</h1>
            <p class="text-sm text-slate-400">Registra propuestas o mejoras que impulsen la transformación institucional.
            </p>
        </div>

        <form action="{{ route('innovacion-mejora-continua.initiatives.store') }}" method="POST"
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-8 shadow-2xl shadow-slate-950/40 backdrop-blur space-y-8">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="plan_id" class="text-sm font-semibold text-slate-300">ID del plan <span
                            class="text-rose-400">*</span></label>
                    <select name="plan_id"
                        class="w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-3 text-sm outline-none focus:border-sky-500/60">
                        <option value="">— Sin plan —</option>
                        @foreach ($plans as $p)
                            <option value="{{ $p->id }}" @selected((string) old('plan_id') === (string) $p->id)>
                                {{ $p->title }}
                                @if ($p->start_date || $p->end_date)
                                    ({{ optional($p->start_date)->format('Y-m-d') }} —
                                    {{ optional($p->end_date)->format('Y-m-d') }})
                                @endif
                                @if ($p->status)
                                    — {{ $p->status }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('plan_id')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="title" class="text-sm font-semibold text-slate-300">Título de la iniciativa <span
                            class="text-rose-400">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40 @error('title') border-rose-500/70 @enderror"
                        placeholder="Ingrese un título descriptivo" />
                    @error('title')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="summary" class="text-sm font-semibold text-slate-300">Resumen <span
                            class="text-rose-400">*</span></label>
                    <textarea id="summary" name="summary" rows="4" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40 @error('summary') border-rose-500/70 @enderror"
                        placeholder="Descripción breve de la iniciativa (máximo 500 caracteres)">{{ old('summary') }}</textarea>
                    @error('summary')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">Describe brevemente el propósito y alcance de la iniciativa.</p>
                </div>
                <div>
                    <label for="responsible_user_id" class="text-sm font-semibold text-slate-300">Usuario
                        responsable</label>
                    <select id="responsible_user_id" name="responsible_user_id"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40 @error('responsible_user_id') border-rose-500/70 @enderror">
                        <option value="">Seleccionar usuario</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('responsible_user_id') == $user->id)>{{ $user->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_user_id')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="responsible_team_id" class="text-sm font-semibold text-slate-300">Equipo responsable</label>
                    <select id="responsible_team_id" name="responsible_team_id"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40 @error('responsible_team_id') border-rose-500/70 @enderror">
                        <option value="">Seleccionar equipo</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('responsible_team_id') == $team->id)>{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('responsible_team_id')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="start_date" class="text-sm font-semibold text-slate-300">Fecha de inicio</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40 @error('start_date') border-rose-500/70 @enderror" />
                    @error('start_date')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="text-sm font-semibold text-slate-300">Fecha de fin</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40 @error('end_date') border-rose-500/70 @enderror" />
                    @error('end_date')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="text-sm font-semibold text-slate-300">Estado <span
                            class="text-rose-400">*</span></label>
                    <select id="status" name="status" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-fuchsia-400 focus:ring-2 focus:ring-fuchsia-500/40 @error('status') border-rose-500/70 @enderror">
                        @foreach (['propuesta', 'evaluada', 'aprobada', 'implementada', 'cerrada'] as $state)
                            <option value="{{ $state }}" @selected(old('status') == $state)>{{ ucfirst($state) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">Workflow sugerido: propuesta → evaluada → aprobada → implementada
                        → cerrada.</p>
                </div>
                <div class="md:col-span-2">
                    <label for="estimated_impact" class="text-sm font-semibold text-slate-300">Impacto estimado</label>
                    <input type="text" id="estimated_impact" name="estimated_impact"
                        value="{{ old('estimated_impact') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40 @error('estimated_impact') border-rose-500/70 @enderror"
                        placeholder="Ej: Reducción del 20% en tiempos de proceso" />
                    @error('estimated_impact')
                        <p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">Describe el impacto esperado de implementar esta iniciativa.</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-6 border-t border-slate-800/60">
                <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Crear iniciativa
                </button>
            </div>
        </form>
    </div>
@endsection
