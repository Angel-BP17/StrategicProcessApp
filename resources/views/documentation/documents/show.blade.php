@extends('layouts.app')

@section('title', 'Detalle del documento')

@section('content')
    <div class="container mx-auto px-0 sm:px-4">
        @include('documentation._nav')

        @php
            $allowedLabel = collect($allowedExtensions ?? [])
                ->map(fn($ext) => strtoupper($ext))
                ->implode(', ');
            $maxUploadMb = number_format(($versionUploadMaxSize ?? 25600) / 1024, 1);
            $latestVersion = $document->latestVersion;
            $evidenceEntries = $document->versions
                ->flatMap(function ($version) {
                    return $version->evidences->map(function ($evidence) use ($version) {
                        return ['version' => $version, 'evidence' => $evidence];
                    });
                })
                ->sortByDesc(fn($entry) => $entry['evidence']->created_at)
                ->values();
        @endphp

        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Gestión documental</p>
                <h1 class="text-3xl font-semibold text-slate-100 flex items-center gap-3">
                    {{ $document->title }}
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-semibold {{ [
                            'draft' => 'bg-amber-500/15 text-amber-200 border-amber-400/30',
                            'active' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/30',
                            'archived' => 'bg-slate-700/40 text-slate-300 border-slate-500/40',
                        ][$document->status] ?? 'bg-slate-800/60 text-slate-200 border-slate-700' }}">
                        {{ ucfirst(__($document->status)) }}
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Detalle general del documento y su trazabilidad.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('documents.index') }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">
                    ← Volver al listado
                </a>

                @if ($latestVersion)
                    <a href="{{ route('documents.versions.download', [$document, $latestVersion]) }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-sky-600/80 px-4 py-2 text-xs sm:text-sm font-semibold text-white border border-sky-500/70 shadow shadow-sky-900/40 hover:bg-sky-500 transition">
                        Descargar versión vigente
                    </a>
                @endif

                @if ($canManageDocuments)
                    <a href="{{ route('documents.edit', $document) }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-slate-800/80 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-100 border border-slate-700 hover:border-sky-400 hover:text-sky-200 transition">
                        Editar metadata
                    </a>
                    @if ($document->versions_count === 0)
                        <form method="POST" action="{{ route('documents.destroy', $document) }}"
                            onsubmit="return confirm('¿Deseas eliminar este documento? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-2xl bg-rose-600/80 px-4 py-2 text-xs sm:text-sm font-semibold text-white border border-rose-500/60 shadow shadow-rose-900/40 hover:bg-rose-500 transition">
                                Eliminar
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        @if ($errors->any())
            <div
                class="mb-6 rounded-2xl border border-rose-500/40 bg-rose-500/10 px-5 py-4 text-sm text-rose-200 shadow-lg shadow-rose-950/30">
                <p class="font-semibold">Por favor corrige los siguientes campos:</p>
                <ul class="mt-2 space-y-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <section
                class="lg:col-span-2 rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-6 shadow-xl shadow-slate-950/40">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">Información general</h2>
                <dl class="grid gap-4 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="text-slate-400 uppercase tracking-[0.2em] text-[11px]">Categoría</dt>
                        <dd class="mt-1 text-slate-100 font-semibold">{{ ucfirst(__($document->category)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 uppercase tracking-[0.2em] text-[11px]">Versión actual</dt>
                        <dd class="mt-1 text-slate-100 font-semibold">
                            @if (isset($document->versions->sortByDesc('version_number')->first()->version_number))
                                v{{ $document->versions->sortByDesc('version_number')->first()->version_number }}
                            @else
                                <span class="text-slate-400">Sin versiones</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 uppercase tracking-[0.2em] text-[11px]">Responsable</dt>
                        <dd class="mt-1 text-slate-100 font-semibold">{{ $document->creator->full_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 uppercase tracking-[0.2em] text-[11px]">Creado</dt>
                        <dd class="mt-1 text-slate-100 font-semibold">
                            {{ $document->created_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 uppercase tracking-[0.2em] text-[11px]">Actualizado</dt>
                        <dd class="mt-1 text-slate-100 font-semibold">
                            {{ $document->updated_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 uppercase tracking-[0.2em] text-[11px]">Uso asociado</dt>
                        <dd class="mt-1 text-slate-100 font-semibold">
                            @if ($document->entity_type)
                                {{ class_basename($document->entity_type) }} #{{ $document->entity_id }}
                            @else
                                <span class="text-slate-400">No vinculado</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <h3 class="text-base font-semibold text-slate-100">Descripción</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300 whitespace-pre-line">
                        {{ $document->description ? $document->description : 'Aún no se ha registrado una descripción para este documento.' }}
                    </p>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-6 shadow-xl shadow-slate-950/40">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">Estado de control</h2>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li class="flex items-center gap-2">
                        <span class="size-2 rounded-full bg-emerald-400"></span>
                        {{ $document->versions_count }} versiones registradas
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="size-2 rounded-full bg-sky-400"></span>
                        Última actualización: {{ $document->updated_at?->diffForHumans() ?? '—' }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="size-2 rounded-full bg-violet-400"></span>
                        {{ $document->evidences_count }} evidencias asociadas
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="size-2 rounded-full bg-amber-400 mt-1"></span>
                        <span>Recuerda subir nuevas versiones para mantener la trazabilidad completa.</span>
                    </li>
                </ul>
            </section>
        </div>

        <section
            class="mt-8 rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-6 shadow-xl shadow-slate-950/40">
            <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-100">Gestión de versiones</h2>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Trazabilidad completa</p>
                </div>
                <div class="text-xs text-slate-400">
                    Formatos aceptados: {{ $allowedLabel ?: 'todos' }} · Máx: {{ $maxUploadMb }} MB
                </div>
            </div>

            @if ($canManageDocuments)
                <form method="POST" action="{{ route('documents.versions.store', $document) }}"
                    enctype="multipart/form-data"
                    class="mb-6 rounded-2xl border border-dashed border-slate-700/60 bg-slate-900/60 px-5 py-5">
                    @csrf
                    <div class="grid gap-4 lg:grid-cols-[2fr,1fr]">
                        <div>
                            <label class="text-sm font-semibold text-slate-300">Subir nueva versión</label>
                            <input type="file" name="file"
                                @if ($allowedLabel) accept="{{ collect($allowedExtensions ?? [])->map(fn($ext) => '.' . strtolower($ext))->implode(',') }}" @endif
                                class="mt-2 block w-full rounded-2xl border border-slate-700/70 bg-slate-950/70 px-4 py-3 text-sm text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40"
                                required />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-300">Notas de la versión (opcional)</label>
                            <textarea name="notes" rows="3"
                                class="mt-2 w-full rounded-2xl border border-slate-700/70 bg-slate-950/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                            Registrar versión
                        </button>
                    </div>
                </form>
            @endif

            @if ($document->versions->isEmpty())
                <div
                    class="rounded-2xl border border-dashed border-slate-700/60 bg-slate-900/60 px-5 py-8 text-center text-slate-400">
                    Aún no se han registrado archivos físicos. @if ($canManageDocuments)
                        Sube el primer archivo para iniciar la trazabilidad completa.
                    @endif
                </div>
            @else
                <div class="overflow-hidden rounded-2xl border border-slate-800/80 bg-slate-950/60">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-900/80 text-slate-300 uppercase tracking-wide text-[11px] sm:text-xs">
                                <th class="px-4 py-3 text-left font-semibold">Versión</th>
                                <th class="px-4 py-3 text-left font-semibold">Archivo</th>
                                <th class="px-4 py-3 text-left font-semibold">Tamaño</th>
                                <th class="px-4 py-3 text-left font-semibold">Subido por</th>
                                <th class="px-4 py-3 text-left font-semibold">Fecha</th>
                                <th class="px-4 py-3 text-left font-semibold">Notas</th>
                                <th class="px-4 py-3 text-left font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/70">
                            @foreach ($document->versions as $version)
                                <tr class="hover:bg-slate-900/60 transition">
                                    <td class="px-4 py-4 text-slate-100 font-semibold">v{{ $version->version_number }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $version->file_name }}</td>
                                    <td class="px-4 py-4 text-slate-300">
                                        {{ $version->file_size ? \Illuminate\Support\Number::fileSize($version->file_size) : '—' }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-300">{{ $version->uploader->full_name ?? '—' }}</td>
                                    <td class="px-4 py-4 text-slate-300">
                                        {{ $version->uploaded_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="px-4 py-4 text-slate-400">{{ $version->notes ?? '—' }}</td>
                                    <td class="px-4 py-4">
                                        <a href="{{ route('documents.versions.download', [$document, $version]) }}"
                                            class="inline-flex items-center gap-1 rounded-full border border-slate-700/70 px-3 py-1 text-[11px] font-semibold text-sky-300 hover:border-sky-400 hover:text-sky-200 transition">
                                            Descargar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
