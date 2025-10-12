<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#111115] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Bienvenido</h1>
            <p class="text-[#848282]">Inicia sesión en tu cuenta</p>
        </div>

        <!-- Card Container -->
        <div class="bg-[#201A2F] rounded-2xl shadow-2xl p-8 border border-[#26BBFF]/10">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-400 mb-1">Error al iniciar sesión</h3>
                            <ul class="text-sm text-red-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-[#26BBFF]/10 border border-[#26BBFF]/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-[#26BBFF] mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-[#26BBFF]">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-white mb-2">
                        Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus 
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200 @error('email') border-red-500 @enderror"
                        placeholder="tu@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-white mb-2">
                        Contraseña
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200 @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            class="w-4 h-4 bg-[#111115] border-[#848282]/30 rounded text-[#26BBFF] focus:ring-[#26BBFF] focus:ring-offset-0"
                        >
                        <label for="remember" class="ml-2 text-sm text-[#848282]">
                            Recordarme
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-[#26BBFF] hover:text-[#26BBFF]/80 transition-colors duration-200">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-[#26BBFF] text-[#111115] font-bold py-3 rounded-lg hover:bg-[#26BBFF]/90 focus:outline-none focus:ring-4 focus:ring-[#26BBFF]/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                >
                    Iniciar Sesión
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-[#848282]">
                    ¿No tienes una cuenta? 
                    <a href="{{ route('register') }}" class="text-[#26BBFF] hover:text-[#26BBFF]/80 font-semibold transition-colors duration-200">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="{{ route('welcome') }}" class="text-sm text-[#848282] hover:text-white transition-colors duration-200">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>

