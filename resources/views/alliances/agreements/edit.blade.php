@extends('layouts.app')
@section('title', 'Editar convenio')
@section('content')
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 text-slate-100">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Relaciones estratégicas</p>
                <h1 class="text-3xl font-semibold">Editar convenio</h1>
                <p class="text-sm text-slate-400">Ajusta los detalles del acuerdo con tu aliado.</p>
            </div>
            <a href="{{ route('alliances.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">← Volver</a>
        </div>

        <form action="{{ route('agreements.update', $agreement->id) }}" method="POST"
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-8 shadow-2xl shadow-slate-950/40 backdrop-blur text-slate-100 space-y-8">
            @csrf
            @method('PUT')
            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Socio</label>
                    <select name="partner_id"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" required>
                        @foreach ($partners as $partner)
                            <option value="{{ $partner->id }}" @selected(old('partner_id', $agreement->partner_id) == $partner->id)>
                                {{ $partner->name }} ({{ $partner->type }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Título del convenio</label>
                    <input type="text" name="title" value="{{ old('title', $agreement->title) }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Fecha inicio</label>
                    <input type="date" name="start_date" value="{{ old('start_date', optional($agreement->start_date)->format('Y-m-d')) }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Fecha fin</label>
                    <input type="date" name="end_date" value="{{ old('end_date', optional($agreement->end_date)->format('Y-m-d')) }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Estado</label>
                    <input type="text" name="status" value="{{ old('status', $agreement->status) }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Fecha de renovación</label>
                    <input type="date" name="renewal_date" value="{{ old('renewal_date', optional($agreement->renewal_date)->format('Y-m-d')) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-300">
                        <input type="checkbox" name="electronic_signature" value="1"
                            class="size-4 rounded border-slate-700 bg-slate-900 text-sky-400 focus:ring-0"
                            {{ old('electronic_signature', $agreement->electronic_signature) ? 'checked' : '' }}>
                        Firma electrónica
                    </label>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('alliances.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">Actualizar convenio</button>
            </div>
        </form>
    </div>
@endsection
