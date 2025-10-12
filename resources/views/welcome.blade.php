<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
            </style>
        @endif
    </head>
    <body class="bg-[#111115] min-h-screen">
    <!-- Navigation -->
    <nav class="border-b border-[#848282]/20 bg-[#201A2F]/50 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white">
                        <span class="text-[#26BBFF]">INCA</span>DEV
                    </h1>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-[#848282] text-sm">Hola, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-[#201A2F] border border-[#848282]/30 rounded-lg hover:border-[#26BBFF] transition-all duration-200">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-white hover:text-[#26BBFF] transition-colors duration-200">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-bold text-[#111115] bg-[#26BBFF] rounded-lg hover:bg-[#26BBFF]/90 transition-all duration-200 transform hover:scale-105">
                            Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#26BBFF]/10 via-transparent to-[#201A2F]/50"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-[#201A2F] border border-[#26BBFF]/30 rounded-full mb-8">
                    <span class="w-2 h-2 bg-[#26BBFF] rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm text-[#26BBFF] font-semibold">Bienvenido a tu aplicación</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-5xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                    Construye algo
                    <span class="text-[#26BBFF] block">increíble hoy</span>
                </h1>

                <!-- Description -->
                <p class="text-xl text-[#848282] max-w-2xl mx-auto mb-12 leading-relaxed">
                    Una plataforma moderna y elegante construida con Laravel. 
                    Comienza tu viaje con nosotros y descubre todas las posibilidades.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center relative z-10">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-[#26BBFF] text-white font-bold rounded-lg hover:bg-[#26BBFF]/90 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-[#26BBFF]/20 ring-1 ring-[#26BBFF]/20">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-[#26BBFF] text-white font-bold rounded-lg hover:bg-[#26BBFF]/90 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-[#26BBFF]/20 ring-1 ring-[#26BBFF]/20">
                            Comenzar Gratis
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-[#201A2F] text-white font-semibold rounded-lg border border-[#848282]/30 hover:border-[#26BBFF] transition-all duration-200 shadow-sm">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                ¿Por qué elegirnos?
            </h2>
            <p class="text-lg text-[#848282] max-w-2xl mx-auto">
                Características diseñadas para ayudarte a tener éxito
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-[#201A2F] border border-[#848282]/20 rounded-2xl p-8 hover:border-[#26BBFF]/50 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#26BBFF]/10 rounded-lg flex items-center justify-center mb-6 group-hover:bg-[#26BBFF]/20 transition-colors duration-300">
                    <svg class="w-6 h-6 text-[#26BBFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Rápido y Eficiente</h3>
                <p class="text-[#848282] leading-relaxed">
                    Optimizado para el mejor rendimiento y experiencia de usuario.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-[#201A2F] border border-[#848282]/20 rounded-2xl p-8 hover:border-[#26BBFF]/50 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#26BBFF]/10 rounded-lg flex items-center justify-center mb-6 group-hover:bg-[#26BBFF]/20 transition-colors duration-300">
                    <svg class="w-6 h-6 text-[#26BBFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Seguro y Confiable</h3>
                <p class="text-[#848282] leading-relaxed">
                    Tus datos están protegidos con las mejores prácticas de seguridad.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-[#201A2F] border border-[#848282]/20 rounded-2xl p-8 hover:border-[#26BBFF]/50 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#26BBFF]/10 rounded-lg flex items-center justify-center mb-6 group-hover:bg-[#26BBFF]/20 transition-colors duration-300">
                    <svg class="w-6 h-6 text-[#26BBFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Fácil de Usar</h3>
                <p class="text-[#848282] leading-relaxed">
                    Interfaz intuitiva diseñada pensando en la mejor experiencia.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-[#848282]/20 bg-[#201A2F]/50 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <p class="text-[#848282] text-sm">
                    © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Todos los derechos reservados.
                </p>
                <p class="text-[#848282] text-xs mt-2">
                    Hecho con <span class="text-[#26BBFF]">♥</span> usando Laravel
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
