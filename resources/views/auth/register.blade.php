<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body class="bg-[#111115] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Crear Cuenta</h1>
            <p class="text-[#848282]">Únete a nuestra comunidad</p>
        </div>

        <!-- Card Container -->
        <div class="bg-[#201A2F] rounded-2xl shadow-2xl p-8 border border-[#26BBFF]/10">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-400 mb-1">Por favor corrige los siguientes
                                errores:</h3>
                            <ul class="text-sm text-red-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Firstame Field -->
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-white mb-2">
                        Nombre
                    </label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                        autofocus
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200 @error('name') border-red-500 @enderror"
                        placeholder="Juan">
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Laststname Field -->
                <div>
                    <label for="last_name" class="block text-sm font-semibold text-white mb-2">
                        Apellido
                    </label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                        autofocus
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200 @error('name') border-red-500 @enderror"
                        placeholder="Pérez">
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-white mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200 @error('email') border-red-500 @enderror"
                        placeholder="tu@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-white mb-2">
                        Contraseña
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200 @error('password') border-red-500 @enderror"
                        placeholder="Mínimo 8 caracteres">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-[#848282]">Debe tener al menos 8 caracteres</p>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-white mb-2">
                        Confirmar Contraseña
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200"
                        placeholder="Repite tu contraseña">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-[#26BBFF] text-[#111115] font-bold py-3 rounded-lg hover:bg-[#26BBFF]/90 focus:outline-none focus:ring-4 focus:ring-[#26BBFF]/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] mt-6">
                    Crear Cuenta
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-[#848282]">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}"
                        class="text-[#26BBFF] hover:text-[#26BBFF]/80 font-semibold transition-colors duration-200">
                        Inicia sesión
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="{{ route('welcome') }}"
                class="text-sm text-[#848282] hover:text-white transition-colors duration-200">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>

</html>
