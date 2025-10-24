@php($document = $document ?? null)
@php($withFileUpload = $withFileUpload ?? false)
@php($allowedExtensions = config('documentation.allowed_extensions', []))
@php($maxFileSize = config('documentation.max_upload_size_kb', 25600))
@php($acceptAttribute = collect($allowedExtensions)->map(fn($ext) => '.' . strtolower($ext))->implode(','))
<form method="POST" action="{{ $action }}" @if ($withFileUpload) enctype="multipart/form-data" @endif
    class="rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-8 shadow-2xl shadow-slate-950/40 backdrop-blur">
    @csrf
    @isset($method)
        @if (strtoupper($method) !== 'POST')
            @method($method)
        @endif
    @endisset

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="lg:col-span-2">
            <label class="text-sm font-semibold text-slate-300">Título</label>
            <input name="title" value="{{ old('title', $document->title ?? '') }}" required
                class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
        </div>

        <div class="lg:col-span-2">
            <label class="text-sm font-semibold text-slate-300">Descripción (opcional)</label>
            <textarea name="description" rows="4"
                class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('description', $document->description ?? '') }}</textarea>
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-300">Categoría</label>
            <select name="category" required
                class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $document->category ?? 'administrative') === $category)>
                        {{ ucfirst(__($category)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-300">Estado</label>
            <select name="status" required
                class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $document->status ?? 'draft') === $status)>
                        {{ ucfirst(__($status)) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @if ($withFileUpload)
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <label class="text-sm font-semibold text-slate-300">Archivo inicial</label>
                <input type="file" name="file"
                    @if ($acceptAttribute) accept="{{ $acceptAttribute }}" @endif
                    class="mt-2 block w-full rounded-2xl border border-dashed border-slate-700/70 bg-slate-900/60 px-4 py-3 text-sm text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                <p class="mt-2 text-xs text-slate-400">Formatos permitidos:
                    {{ collect($allowedExtensions)->map(fn($ext) => strtoupper($ext))->implode(', ') ?: 'cualquier archivo' }}.
                    Tamaño máximo: {{ number_format($maxFileSize / 1024, 1) }} MB.</p>
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm font-semibold text-slate-300">Notas de la versión (opcional)</label>
                <textarea name="version_notes" rows="3"
                    class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('version_notes') }}</textarea>
            </div>
        </div>
    @endif

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('documents.index') }}"
            class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">
            ← Cancelar
        </a>
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
            {{ $submitLabel ?? 'Guardar' }}
        </button>
    </div>
</form>
