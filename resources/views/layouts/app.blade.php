<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel')</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx', 'resources/js/charts.js'])
</head>

<body class="bg-slate-950 text-slate-100 antialiased">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-xs sm:text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <div class="flex justify-end">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-slate-300 hover:text-sky-300 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-slate-300 hover:text-sky-300 transition">Login</a>
                    <a href="{{ route('register') }}"
                        class="ml-4 text-sm text-slate-300 hover:text-sky-300 transition">Register</a>
                @endauth
            </div>
        @endif
    </header>
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Contenido --}}
        <main class="flex-1 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
            <header class="sticky top-0 z-40 bg-slate-950/85 backdrop-blur border-b border-slate-800/60">
                <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3 text-sm">
                    <h1 class="text-base font-semibold text-slate-100">@yield('title', 'Panel')</h1>
                    @php $notis = auth()->user()->unreadNotifications()->latest()->take(10)->get(); @endphp
                    <div class="relative">
                        <!-- Botón toggle -->
                        <button id="notification-toggle" type="button"
                            class="inline-flex items-center gap-1 text-slate-100 hover:text-yellow-200 transition relative"
                            aria-haspopup="true" aria-expanded="false" aria-controls="notification-list">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                                width="24" aria-hidden="true">
                                <path fill="#FFFF55"
                                    d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z" />
                            </svg>
                            <span id="notification-count"
                                class="ml-0.5 inline-flex min-w-5 h-5 items-center justify-center rounded-full bg-yellow-500 text-slate-900 text-[11px] font-bold px-1">
                                {{ $notis->count() }}
                            </span>
                        </button>

                        <!-- Panel: oculto por defecto -->
                        <div id="notification-list"
                            class="absolute right-0 mt-2 w-72 bg-white text-slate-900 shadow-xl rounded-lg p-3 space-y-1 border border-slate-200 hidden"
                            role="menu" aria-labelledby="notification-toggle" tabindex="-1">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-slate-800">Notificaciones</h3>
                                <button type="button" id="notification-close"
                                    class="text-xs text-slate-500 hover:text-slate-700"
                                    aria-label="Cerrar notificaciones">Cerrar</button>
                            </div>

                            @forelse ($notis as $n)
                                <div class="text-sm py-1" role="menuitem" tabindex="-1">
                                    {{ $n->data['type'] === 'mention' ? 'Te mencionaron' : 'Nueva tarea' }} —
                                    {{ $n->created_at->diffForHumans() }}
                                </div>
                            @empty
                                <div class="text-sm py-2 text-slate-500" data-empty="true" role="menuitem"
                                    tabindex="-1">
                                    No tienes notificaciones.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="ml-auto text-xs sm:text-sm text-slate-300">Hola, {{ Auth::user()->first_name }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-sky-500/90 border border-sky-400/40 rounded-lg shadow-md shadow-sky-500/20 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300/70 transition-all duration-200">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </header>

            <section class="max-w-7xl mx-auto px-4 py-8">
                @yield('content')
            </section>
        </main>
    </div>

    @if (session('ok'))
        <div
            class="fixed bottom-4 right-4 bg-emerald-500/90 text-white px-4 py-2 rounded-lg shadow-lg shadow-emerald-500/40">
            {{ session('ok') }}</div>
    @endif
    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-rose-600/90 text-white px-4 py-2 rounded-lg shadow-lg shadow-rose-500/40">
            {{ session('error') }}</div>
    @endif
    @if (auth()->check())
        <script>
            window.userId = {{ auth()->id() }};
        </script>
        <script>
            (() => {
                const toggleBtn = document.getElementById('notification-toggle');
                const listEl = document.getElementById('notification-list');
                const countEl = document.getElementById('notification-count');
                const closeBtn = document.getElementById('notification-close');
                const maxItems = 10;
                const labels = {
                    mention: 'Te mencionaron',
                    task_assigned: 'Nueva tarea'
                };

                if (!toggleBtn || !listEl || !countEl) return;

                const isOpen = () => !listEl.classList.contains('hidden');
                const open = () => {
                    listEl.classList.remove('hidden');
                    toggleBtn.setAttribute('aria-expanded', 'true');
                    // focus al primer item
                    const firstItem = listEl.querySelector('[role="menuitem"]');
                    if (firstItem) firstItem.focus({
                        preventScroll: true
                    });
                };
                const close = () => {
                    listEl.classList.add('hidden');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                    toggleBtn.focus({
                        preventScroll: true
                    });
                };
                const toggle = () => (isOpen() ? close() : open());

                // Toggle con click
                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggle();
                });
                // Botón cerrar
                if (closeBtn) closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    close();
                });

                // Cerrar al hacer click fuera
                document.addEventListener('click', (e) => {
                    if (!isOpen()) return;
                    if (!listEl.contains(e.target) && !toggleBtn.contains(e.target)) close();
                });

                // Tecla Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && isOpen()) {
                        e.preventDefault();
                        close();
                    }
                });

                // Accesibilidad con teclado en el botón
                toggleBtn.addEventListener('keydown', (e) => {
                    if ((e.key === 'Enter' || e.key === ' ') && !isOpen()) {
                        e.preventDefault();
                        open();
                    }
                    if (e.key === 'ArrowDown' && !isOpen()) {
                        e.preventDefault();
                        open();
                    }
                });

                // Tiempo real
                if (window.Echo && window.userId) {
                    window.Echo.private(`user.${window.userId}`)
                        .notification((notification) => {
                            // actualizar contador
                            const current = parseInt(countEl.textContent || '0', 10) || 0;
                            countEl.textContent = current + 1;

                            // crear item
                            const item = document.createElement('div');
                            item.className = 'text-sm py-1';
                            item.setAttribute('role', 'menuitem');
                            item.setAttribute('tabindex', '-1');

                            const label = labels[notification.type] || 'Nueva notificación';
                            const time = new Intl.DateTimeFormat('es-PE', {
                                hour: '2-digit',
                                minute: '2-digit'
                            }).format(new Date());
                            item.textContent = `${label} — ${time}`;

                            // limpiar empty state
                            const emptyState = listEl.querySelector('[data-empty]');
                            if (emptyState) emptyState.remove();

                            // insertar arriba
                            listEl.prepend(item);

                            // truncar
                            const items = Array.from(listEl.querySelectorAll('[role="menuitem"]'));
                            if (items.length > maxItems) items.slice(maxItems).forEach(el => el.remove());
                        });
                }
            })();
        </script>
    @endif
    @stack('chartjs')
    @stack('scripts')


</body>

</html>
