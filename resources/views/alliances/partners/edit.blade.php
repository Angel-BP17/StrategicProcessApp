@extends('layouts.app')
@section('title', 'Editar socio')
@section('content')
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 text-slate-100">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Relaciones estratégicas</p>
                <h1 class="text-3xl font-semibold">Editar socio</h1>
                <p class="text-sm text-slate-400">Actualiza la ficha del aliado para mantener la información al día.</p>
            </div>
            <a href="{{ route('alliances.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">← Volver</a>
        </div>

        <form action="{{ route('partners.update', $partner) }}" method="POST"
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-8 shadow-2xl shadow-slate-950/40 backdrop-blur text-slate-100 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="name" class="text-sm font-semibold text-slate-300">Nombre del socio</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40" />
                    @error('name')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="type" class="text-sm font-semibold text-slate-300">Tipo</label>
                    <select name="type" id="type" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40">
                        <option value="">-- Seleccione --</option>
                        @foreach (['Universidad', 'Empresa', 'Instituto', 'Fundación', 'Otro'] as $option)
                            <option value="{{ $option }}" @selected(old('type', $partner->type) == $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="contact" class="text-sm font-semibold text-slate-300">Correo o contacto</label>
                    <input type="text" name="contact" id="contact" value="{{ old('contact', $partner->contact['email'] ?? $partner->contact) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40" />
                    @error('contact')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="legal_representative" class="text-sm font-semibold text-slate-300">Representante legal</label>
                    <input type="text" name="legal_representative" id="legal_representative" value="{{ old('legal_representative', $partner->legal_representative) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/40" />
                    @error('legal_representative')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('alliances.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">Actualizar socio</button>
            </div>
        </form>
    </div>
@endsection
