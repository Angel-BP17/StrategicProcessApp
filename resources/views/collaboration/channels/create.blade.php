{{-- resources/views/collab/channels/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nuevo canal')

@section('content')
    @php
        // Si el controlador no inyectó $users, hacemos un fallback (máx. 500)
        if (!isset($users)) {
            $users = \App\Models\User::query()
                ->select('id', 'name', 'email')
                ->orderByRaw('COALESCE(name, email)')
                ->limit(500)
                ->get();
        }

        // Prefill de miembros cuando se crea un nuevo equipo
        $defaultMembers = [['user_id' => auth()->id(), 'role' => 'admin']];
        $oldMembers = old('team_members', $defaultMembers);
    @endphp

    <div class="space-y-8 text-slate-100">
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-white">Crear canal</h1>
                <p class="text-slate-400 mt-2 text-sm">Solo admins pueden crear canales. Si eliges crear un nuevo equipo,
                    podrás definir sus miembros y roles.</p>
            </div>
            <a href="{{ route('collab.index') }}" class="text-sm underline">← Volver</a>
        </header>

        <form method="POST" action="{{ route('collab.channels.store') }}"
            class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 space-y-6">
            @csrf

            {{-- Selector modo de equipo --}}
            <div class="grid md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="text-sm text-slate-300">Modo de equipo</span>
                    <select name="team_mode" id="team_mode"
                        class="mt-1 w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-2 text-sm">
                        <option value="existing" {{ old('team_mode', 'existing') === 'existing' ? 'selected' : '' }}>Usar
                            equipo
                            existente</option>
                        <option value="new" {{ old('team_mode') === 'new' ? 'selected' : '' }}>Crear nuevo equipo
                        </option>
                    </select>
                    @error('team_mode')
                        <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                    @enderror
                </label>

                <label class="block" id="wrap_team_existing">
                    <span class="text-sm text-slate-300">Equipo existente</span>
                    <select name="team_id"
                        class="mt-1 w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-2 text-sm">
                        <option value="">— Selecciona —</option>
                        @foreach ($teams as $t)
                            <option value="{{ $t->id }}" @selected(old('team_id') == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    @error('team_id')
                        <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                    @enderror
                </label>
            </div>

            {{-- Datos nuevo equipo --}}
            <div id="wrap_team_new" class="{{ old('team_mode', 'existing') === 'new' ? '' : 'hidden' }}">
                <h3 class="text-white font-semibold mt-2">Datos del nuevo equipo</h3>
                <div class="grid md:grid-cols-2 gap-4 mt-2">
                    <label class="block">
                        <span class="text-sm text-slate-300">Nombre del equipo</span>
                        <input name="team_name" value="{{ old('team_name') }}"
                            class="mt-1 w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-2 text-sm">
                        @error('team_name')
                            <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm text-slate-300">Descripción del equipo</span>
                        <textarea name="team_description" rows="2"
                            class="mt-1 w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-2 text-sm">{{ old('team_description') }}</textarea>
                        @error('team_description')
                            <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </label>
                </div>

                {{-- Miembros + roles del equipo (solo cuando se crea un equipo nuevo) --}}
                <div class="mt-6">
                    <h4 class="text-white font-semibold">Miembros del equipo</h4>
                    <p class="text-slate-400 text-xs mb-2">Agrega usuarios y define su rol (admin, moderador o miembro). El
                        creador se incluye por defecto como admin.</p>

                    <div class="overflow-x-auto border border-slate-800/70 rounded-xl">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-900/60 text-slate-300">
                                <tr>
                                    <th class="px-3 py-2 text-left">Usuario</th>
                                    <th class="px-3 py-2 text-left">Rol</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody id="teamMembersBody" class="divide-y divide-slate-800/70">
                                @foreach ($oldMembers as $idx => $m)
                                    <tr class="bg-slate-950/40">
                                        <td class="px-3 py-2">
                                            <select name="team_members[{{ $idx }}][user_id]"
                                                class="w-full bg-slate-950/60 border border-slate-800/60 rounded p-2">
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}" @selected((int) ($m['user_id'] ?? 0) === (int) $u->id)>
                                                        {{ $u->full_name }} — {{ $u->email }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <select name="team_members[{{ $idx }}][role]"
                                                class="w-full bg-slate-950/60 border border-slate-800/60 rounded p-2">
                                                <option value="admin" @selected(($m['role'] ?? '') === 'admin')>admin</option>
                                                <option value="moderator" @selected(($m['role'] ?? '') === 'moderator')>moderador</option>
                                                <option value="member" @selected(($m['role'] ?? '') === 'member')>miembro</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{-- No permitir borrar la fila del creador si coincide --}}
                                            @php $isCreator = (int)($m['user_id'] ?? 0) === (int)auth()->id(); @endphp
                                            <button type="button" class="text-rose-300 hover:underline remove-row"
                                                {{ $isCreator ? 'disabled' : '' }}>
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" id="addMemberBtn"
                            class="inline-flex items-center gap-2 bg-slate-800/70 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-slate-700/70">
                            Añadir miembro
                        </button>
                    </div>

                    @error('team_members')
                        <p class="text-xs text-rose-300 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="border-slate-800/60">

            {{-- Datos del canal --}}
            <div class="grid md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="text-sm text-slate-300">Nombre del canal</span>
                    <input name="name" value="{{ old('name') }}"
                        class="mt-1 w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-2 text-sm" required>
                    @error('name')
                        <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                    @enderror
                </label>

                <label class="block">
                    <span class="text-sm text-slate-300">Tipo</span>
                    <select name="channel_type"
                        class="mt-1 w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-2 text-sm">
                        <option value="">—</option>
                        <option value="public" @selected(old('channel_type') === 'public')>Público</option>
                        <option value="private" @selected(old('channel_type') === 'private')>Privado</option>
                    </select>
                    @error('channel_type')
                        <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                    @enderror
                </label>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Plan relacionado</label>
                <select name="related_plan_id"
                    class="w-full bg-slate-950/60 border border-slate-800/60 rounded-xl p-3 text-sm outline-none focus:border-sky-500/60">
                    <option value="">— Sin plan —</option>
                    @foreach ($plans as $p)
                        <option value="{{ $p->id }}" @selected((string) old('related_plan_id') === (string) $p->id)>
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
                @error('related_plan_id')
                    <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 text-white px-4 py-2 rounded-xl font-semibold shadow-lg shadow-sky-500/40 hover:shadow-indigo-500/40 transition-transform hover:-translate-y-0.5">
                    Crear canal
                </button>
            </div>
        </form>
    </div>

    {{-- Template de fila para los miembros (clonado por JS) --}}
    <template id="memberRowTpl">
        <tr class="bg-slate-950/40">
            <td class="px-3 py-2">
                <select class="w-full bg-slate-950/60 border border-slate-800/60 rounded p-2 member-user">
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->full_name }} — {{ $u->email }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="px-3 py-2">
                <select class="w-full bg-slate-950/60 border border-slate-800/60 rounded p-2 member-role">
                    <option value="admin">admin</option>
                    <option value="moderator">moderador</option>
                    <option value="member" selected>miembro</option>
                </select>
            </td>
            <td class="px-3 py-2 text-right">
                <button type="button" class="text-rose-300 hover:underline remove-row">Eliminar</button>
            </td>
        </tr>
    </template>

    <script>
        (function() {
            const selMode = document.getElementById('team_mode');
            const wrapNew = document.getElementById('wrap_team_new');
            const wrapExisting = document.getElementById('wrap_team_existing');
            const body = document.getElementById('teamMembersBody');
            const addBtn = document.getElementById('addMemberBtn');
            const tpl = document.getElementById('memberRowTpl');

            function toggleMode() {
                if (selMode.value === 'new') {
                    wrapNew.classList.remove('hidden');
                    wrapExisting.classList.add('hidden');
                } else {
                    wrapNew.classList.add('hidden');
                    wrapExisting.classList.remove('hidden');
                }
            }
            selMode.addEventListener('change', toggleMode);

            // Al añadir, clonamos template y fijamos names con el siguiente índice
            function nextIndex() {
                const rows = body.querySelectorAll('tr');
                let max = -1;
                rows.forEach((tr) => {
                    const selUser = tr.querySelector('select[name^="team_members["]');
                    if (!selUser) return;
                    const m = selUser.name.match(/^team_members\[(\d+)]/);
                    if (m) max = Math.max(max, parseInt(m[1], 10));
                });
                return max + 1;
            }

            addBtn?.addEventListener('click', () => {
                const idx = nextIndex();
                const node = tpl.content.firstElementChild.cloneNode(true);

                // set names
                node.querySelector('.member-user').setAttribute('name', `team_members[${idx}][user_id]`);
                node.querySelector('.member-role').setAttribute('name', `team_members[${idx}][role]`);

                body.appendChild(node);
            });

            body?.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-row')) {
                    const tr = e.target.closest('tr');
                    if (!tr) return;
                    tr.remove();
                    // no renumeramos para no romper old inputs, el backend debe leer por claves existentes
                }
            });
        })();
    </script>
@endsection
