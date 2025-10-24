{{-- resources/views/collaboration.blade.php --}}
@extends('layouts.app')

@section('title', 'Colaboración y Comunicación Digital')

@section('content')
    @php
        /** @var \App\Models\Collaboration\Channel|null $channel */
        $channelId = $channel->id ?? null;
        $channelName = $channel->name ?? '—';
        $channelMeta =
            optional($channel)->topic ??
            (optional($channel)->description ?? 'Selecciona o crea un canal para empezar a colaborar.');

        $messages = $channel?->messages ?? collect();
        $tasks = $channel?->tasks ?? collect();

        $files = collect();
        if ($messages->count() > 0) {
            $files = \App\Models\Documentation\DocumentVersion::query()
                ->where('linked_type', 'message')
                ->whereIn('linked_id', $messages->pluck('id'))
                ->orderByDesc('uploaded_at')
                ->limit(6)
                ->get();
        }

        $userId = auth()->id();
        $allChannels = \App\Models\Collaboration\Channel::with('team')->orderBy('name')->get();

        $ownedChannels = $allChannels
            ->filter(fn($c) => (int) ($c->created_by_user_id ?? 0) === (int) $userId)
            ->values();

        $memberChannels = $allChannels
            ->filter(fn($c) => $c->isMember((int) $userId) && (int) ($c->created_by_user_id ?? 0) !== (int) $userId)
            ->values();
    @endphp

    <div class="space-y-8 text-slate-100">

        {{-- Encabezado --}}
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-white">Colaboración y Comunicación Digital</h1>
                <p class="text-slate-400 mt-2 text-sm max-w-2xl">
                    {{ $channelMeta }}
                </p>
            </div>

            @php
                $raw = auth()->user()->role ?? (auth()->user()->roles ?? []);
                $roles = is_array($raw) ? $raw : [$raw];
                $isAdmin = in_array('admin', $roles, true);
            @endphp

            @if ($isAdmin)
                <a href="{{ route('collab.channels.create') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-sky-500 text-white px-4 py-2 rounded-xl font-semibold shadow-lg hover:-translate-y-0.5 transition">
                    Nuevo canal
                </a>
            @endif

            @if ($channelId)
                <a href="#nuevo-mensaje"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/40 hover:shadow-indigo-500/40 transition-transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Mensaje
                </a>
            @endif
        </header>
        {{-- Canales del usuario --}}
        <section class="grid gap-6 md:grid-cols-2">
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6">
                <h2 class="text-lg font-semibold text-white">Canales creados por mí</h2>
                @if ($ownedChannels->isEmpty())
                    <p class="text-sm text-slate-400 mt-2">Aún no has creado canales.</p>
                @else
                    <ul class="mt-4 space-y-2">
                        @foreach ($ownedChannels as $c)
                            <li>
                                <a href="{{ route('collab.index', ['channel' => $c->id]) }}"
                                    class="flex flex-col gap-1 rounded-xl border border-slate-800/60 bg-slate-950/40 px-4 py-3 transition hover:border-emerald-500/40 hover:shadow-emerald-500/20">
                                    <span class="text-sm font-semibold text-white"># {{ $c->name }}</span>
                                    <span
                                        class="text-xs text-slate-400">{{ $c->team->name ?? 'Sin equipo asignado' }}</span>
                                    <span
                                        class="text-xs text-slate-500">{{ collect(optional($c->team)->members ?? [])->count() }}
                                        integrantes</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6">
                <h2 class="text-lg font-semibold text-white">Canales donde participo</h2>
                @if ($memberChannels->isEmpty())
                    <p class="text-sm text-slate-400 mt-2">Todavía no te has unido a ningún canal.</p>
                @else
                    <ul class="mt-4 space-y-2">
                        @foreach ($memberChannels as $c)
                            <li>
                                <a href="{{ route('collab.index', ['channel' => $c->id]) }}"
                                    class="flex flex-col gap-1 rounded-xl border border-slate-800/60 bg-slate-950/40 px-4 py-3 transition hover:border-sky-500/40 hover:shadow-sky-500/20">
                                    <span class="text-sm font-semibold text-white"># {{ $c->name }}</span>
                                    <span
                                        class="text-xs text-slate-400">{{ $c->team->name ?? 'Sin equipo asignado' }}</span>
                                    <span
                                        class="text-xs text-slate-500">{{ collect(optional($c->team)->members ?? [])->count() }}
                                        integrantes</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        {{-- Mensajería / Anuncios --}}
        <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white"># {{ $channelName }}</h2>
                @if ($channelId)
                    <form method="GET" action="{{ route('collab.search') }}" class="flex items-center gap-2">
                        <input type="hidden" name="channel_id" value="{{ $channelId }}">
                        <input name="q" placeholder="Buscar en el canal…"
                            class="bg-slate-950/60 border border-slate-800/60 rounded px-3 py-1.5 text-sm outline-none focus:border-sky-500/60">
                        <button class="text-sm text-sky-300 hover:text-sky-200 transition font-medium">Ver todos</button>
                    </form>
                @endif
            </div>

            <div id="chat-log" class="space-y-4">
                @forelse ($messages as $m)
                    <div
                        class="border border-slate-800/70 rounded-xl p-4 bg-slate-950/40 hover:border-sky-500/40 hover:shadow-sky-500/20 transition">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-white">
                                {{ $m->user->full_name ?? 'Usuario' }}
                                <span class="text-xs text-slate-500 font-normal">—
                                    {{ $m->created_at?->diffForHumans() }}</span>
                                @if ($m->pinned)
                                    <span class="ml-1 text-[10px] px-1 bg-yellow-300/20 text-yellow-200 rounded">PIN</span>
                                @endif
                            </h3>
                            <div class="flex items-center gap-3 text-xs">
                                <form method="POST" action="{{ route('collab.messages.pin', $m->id) }}">
                                    @csrf <button class="text-amber-300 hover:underline">Fijar</button>
                                </form>
                                <form method="POST" action="{{ route('collab.messages.destroy', $m->id) }}"
                                    onsubmit="return confirm('¿Eliminar mensaje?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-300 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </div>
                        <p class="text-sm text-slate-300 mt-1 whitespace-pre-wrap">{{ $m->content }}</p>

                        @if ($m->replies?->count())
                            <div class="mt-3 space-y-2 border-l border-slate-800/60 pl-3">
                                @foreach ($m->replies as $r)
                                    <div class="text-sm">
                                        <span class="text-slate-300 font-medium">{{ $r->user->name ?? 'Usuario' }}</span>
                                        <span class="text-slate-500 text-xs">—
                                            {{ $r->created_at?->diffForHumans() }}</span>
                                        <div class="text-slate-300">{{ $r->content }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-sm text-slate-400">Todavía no hay mensajes en este canal.</div>
                @endforelse
            </div>

            @if ($channelId)
                <form id="nuevo-mensaje" class="mt-6 border-t border-slate-800/70 pt-4" method="POST"
                    action="{{ route('collab.messages.store', $channelId) }}" enctype="multipart/form-data">
                    @csrf
                    <label class="block text-sm text-slate-300 mb-1">Escribe un mensaje</label>
                    <textarea name="content" rows="3" id="message-input"
                        class="w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-3 text-sm outline-none focus:border-sky-500/60"
                        placeholder="Usa @nombre para mencionar a alguien…"></textarea>
                    <div class="mt-3 flex items-center justify-between">
                        <input type="file" name="files[]" multiple
                            class="text-xs text-slate-300 file:mr-3 file:px-3 file:py-1.5 file:rounded file:border-0 file:bg-slate-800/80 file:text-slate-200 hover:file:bg-slate-700/80">
                        <button
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/40 hover:shadow-indigo-500/40 transition-transform hover:-translate-y-0.5">
                            Enviar
                        </button>
                    </div>
                </form>
            @endif
        </section>

        {{-- Documentos compartidos --}}
        <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Documentos compartidos</h2>
                @if ($channelId)
                    <a href="#nuevo-mensaje" class="text-sm text-sky-300 hover:text-sky-200 transition font-medium">Subir
                        documento</a>
                @endif
            </div>

            @if ($files->isEmpty())
                <p class="text-sm text-slate-400">Aún no se han compartido archivos en este canal.</p>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($files as $f)
                        @php $url = \Illuminate\Support\Facades\Storage::disk('public')->url($f->storage_path); @endphp
                        <div
                            class="border border-slate-800/70 rounded-xl p-4 bg-slate-950/40 hover:border-sky-500/40 hover:shadow-sky-500/20 transition">
                            <h3 class="font-medium text-white flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-300" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                                </svg>
                                {{ $f->file_name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Tamaño: {{ number_format(($f->file_size ?? 0) / 1024, 0) }} KB ·
                                {{ \Illuminate\Support\Str::upper($f->mime_type ?? '') }}
                            </p>
                            <div class="mt-2">
                                <a href="{{ $url }}" target="_blank"
                                    class="text-sm text-sky-300 hover:text-sky-200 hover:underline">Ver / Descargar</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Calendario colaborativo = próximas tareas --}}
        <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Calendario colaborativo</h2>
                @if ($channelId)
                    <form method="POST" action="{{ route('collab.tasks.store', $channelId) }}"
                        class="flex items-center gap-2">
                        @csrf
                        <input name="title" placeholder="Nueva tarea…"
                            class="bg-slate-950/60 border border-slate-800/60 rounded px-3 py-1.5 text-sm outline-none focus:border-emerald-500/60">
                        <input name="due_date" type="date"
                            class="bg-slate-950/60 border border-slate-800/60 rounded px-3 py-1.5 text-sm outline-none focus:border-emerald-500/60">
                        <button
                            class="text-sm text-emerald-300 hover:text-emerald-200 transition font-medium">Agregar</button>
                    </form>
                @endif
            </div>

            @if ($tasks->isEmpty())
                <p class="text-sm text-slate-400">No hay tareas en este canal.</p>
            @else
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach ($tasks as $t)
                        <div
                            class="border border-slate-800/70 rounded-xl p-4 bg-slate-950/40 hover:border-emerald-500/40 hover:shadow-emerald-500/20 transition">
                            <h3 class="font-medium text-white flex items-center justify-between">
                                <span
                                    class="{{ $t->status === 'done' ? 'line-through text-slate-400' : '' }}">{{ $t->title }}</span>
                                <form method="POST" action="{{ route('collab.tasks.toggle', $t->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button
                                        class="text-xs {{ $t->status === 'done' ? 'text-emerald-300' : 'text-slate-300' }} hover:underline">
                                        {{ $t->status === 'done' ? 'Reabrir' : 'Completar' }}
                                    </button>
                                </form>
                            </h3>
                            <p class="text-sm text-slate-400 mt-1">
                                @if ($t->due_date)
                                    Vence: {{ $t->due_date->format('Y-m-d') }}
                                @else
                                    Sin fecha
                                @endif
                            </p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-xs text-slate-500">Estado: {{ $t->status }}</span>
                                <form method="POST" action="{{ route('collab.tasks.destroy', $t->id) }}"
                                    onsubmit="return confirm('¿Eliminar tarea?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-rose-300 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    @if ($channelId)
        @push('scripts')
            <script>
                (() => {
                    // Regex unificada con unicode: @Angel, @Bustamante, @Angel_Bustamante, @Angel-Bustamante, @Angel.Bustamante
                    const atNameToken = /@([\p{L}]+(?:[._-][\p{L}]+)*)/gu;
                    const MENTION_LIMIT = 10;

                    // Selectores (el textarea ahora tiene id="message-input")
                    const input = document.querySelector('#message-input') ||
                        document.querySelector('form#nuevo-mensaje textarea[name="content"]');
                    const counter = document.querySelector('#mention-counter');
                    const preview = document.querySelector('#message-preview');

                    const escapeHTML = (str) => String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');

                    function extractMentionTokens(text) {
                        if (!text) return [];
                        // ⚠️ IMPORTANTE: resetear lastIndex por la bandera 'g'
                        atNameToken.lastIndex = 0;
                        const tokens = [];
                        let match;
                        while ((match = atNameToken.exec(text)) !== null) {
                            tokens.push(match[1]);
                        }
                        const norm = tokens.map(t => t.trim().toLowerCase()).filter(Boolean);
                        return norm.filter((t, i) => norm.indexOf(t) === i); // únicos preservando orden
                    }

                    function highlightMentions(text) {
                        if (!text) return '';
                        // Primero escapamos TODO para evitar XSS
                        const safe = escapeHTML(text);
                        // Usamos una NUEVA regex para no compartir estado (o reseteamos lastIndex)
                        const rx = /@([\p{L}]+(?:[._-][\p{L}]+)*)/gu;
                        return safe.replace(rx, (_, tok) => {
                            return `<span class="mention bg-yellow-300/20 text-yellow-200 px-1 rounded">@${tok}</span>`;
                        });
                    }

                    function updateMentionUI() {
                        const text = input?.value ?? '';
                        const tokens = extractMentionTokens(text);

                        console.debug('[mentions] tokens:', tokens);

                        if (tokens.length > MENTION_LIMIT) {
                            console.warn(`[mentions] Se excede el límite de ${MENTION_LIMIT}; el backend truncará.`);
                        }
                        if (counter) {
                            counter.textContent = `${Math.min(tokens.length, MENTION_LIMIT)} / ${MENTION_LIMIT}`;
                        }
                        if (preview) {
                            preview.innerHTML = highlightMentions(text);
                        }
                    }

                    if (input) {
                        ['input', 'change', 'keyup', 'paste'].forEach(evt => input.addEventListener(evt, updateMentionUI));
                        updateMentionUI(); // inicial
                    } else {
                        console.warn(
                            '[mentions] No se encontró el textarea de mensaje (#message-input o form#nuevo-mensaje textarea[name="content"]).'
                        );
                    }
                })();
            </script>
        @endpush
    @endif
@endsection
