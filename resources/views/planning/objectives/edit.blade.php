@extends('layouts.app')
@section('title', 'Editar objetivo')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">Editar objetivo</h1>
                <p class="text-sm text-slate-400">Plan: {{ $plan->title }}</p>
            </div>
            <a href="{{ route('planning.objectives.show', [$plan, $objective]) }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">←
                Ver objetivo</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-red-400 mb-1">Error</h3>
                        <ul class="text-sm text-red-300 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-8 shadow-2xl shadow-slate-950/40 backdrop-blur space-y-8"
            method="POST" action="{{ route('planning.objectives.update', [$plan, $objective]) }}">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Título</label>
                    <input name="title" value="{{ old('title', $objective->title ?? '') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                    @error('title')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Meta (valor)</label>
                    <input type="number" step="any" name="goal_value"
                        value="{{ old('goal_value', $objective->goal_value ?? '') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Responsable</label>
                    <select name="responsible_user_id"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">
                        <option value="">— Seleccionar —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected(old('responsible_user_id', $objective->responsible_user_id) == $u->id)>{{ $u->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Peso (%)</label>
                    <input type="number" step="1" min="0" max="100" name="weight"
                        value="{{ old('weight', $objective->weight ?? '') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Descripción</label>
                    <textarea name="description" rows="3"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('description', $objective->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('planning.objectives.show', [$plan, $objective]) }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">Actualizar
                    objetivo</button>
            </div>
        </form>
    </div>
    <script>
        (function() {
            const form = document.querySelector('form');
            if (!form) return;
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const fd = new FormData(form);
                const payload = Object.fromEntries(fd.entries());
                console.log('Enviando (payload):', payload);

                // CSRF token hidden input exists thanks to @csrf
                const token = document.querySelector('input[name="_token"]')?.value;
                try {
                    const res = await fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams(fd)
                    });
                    const text = await res.text();
                    console.log('Respuesta (status:', res.status + '):', text);
                    // si quieres seguir con la redirección original en caso de éxito:
                    if (res.redirected) {
                        window.location.href = res.url;
                        return;
                    }
                    // si respuesta JSON:
                    try {
                        console.log('JSON parse:', JSON.parse(text));
                    } catch (_) {}
                } catch (err) {
                    console.error('Error en fetch:', err);
                }
            }, {
                once: true
            });
        })();
    </script>
@endsection
