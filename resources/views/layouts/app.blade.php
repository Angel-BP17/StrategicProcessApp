<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel')</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx', 'resources/js/charts.js'])
</head>

<body class="bg-zinc-50 text-zinc-900">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <div class="flex justify-end">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-gray-700 underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                @endauth
            </div>
        @endif
    </header>
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Contenido --}}
        <main class="flex-1">
            <header class="sticky top-0 z-40 bg-white/70 backdrop-blur border-b">
                <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3">
                    <h1 class="text-base font-medium">@yield('title', 'Panel')</h1>
                    <div class="ml-auto text-sm text-zinc-600">Hola, {{ Auth::user()->name }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-[#201A2F] border border-[#848282]/30 rounded-lg hover:border-[#26BBFF] transition-all duration-200">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </header>

            <section class="max-w-7xl mx-auto px-4 py-6">
                @yield('content')
            </section>
        </main>
    </div>

    @if (session('ok'))
        <div class="fixed bottom-4 right-4 bg-emerald-600 text-white px-4 py-2 rounded">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded">{{ session('error') }}</div>
    @endif
    @stack('chartjs')
    @stack('scripts')
</body>

</html>
