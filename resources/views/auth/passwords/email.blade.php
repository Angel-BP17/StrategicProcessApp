<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-[#201A2F] rounded-2xl shadow p-6">
            <h1 class="text-xl font-semibold text-gray-900">¿Olvidaste tu contraseña?</h1>
            <p class="text-sm text-gray-600 mt-1">Te enviaremos un enlace para restablecerla.</p>

            @if (session('status'))
                <div class="mt-4 rounded-md bg-green-50 text-green-800 px-4 py-2 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-white mb-2">Correo electrónico</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-[#111115] border border-[#848282]/30 rounded-lg text-white placeholder-[#848282] focus:outline-none focus:border-[#26BBFF] focus:ring-2 focus:ring-[#26BBFF]/20 transition-all duration-200 @error('email') border-red-500 @enderror"
                        placeholder="tu@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-[#26BBFF] text-[#111115] font-bold py-3 rounded-lg hover:bg-[#26BBFF]/90 focus:outline-none focus:ring-4 focus:ring-[#26BBFF]/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                    Enviar enlace de restablecimiento
                </button>
            </form>

            <div class="mt-6 flex items-center justify-between text-sm">
                <a href="{{ route('login') }}"
                    class="text-sm text-[#26BBFF] hover:text-[#26BBFF]/80 transition-colors duration-200">Volver a
                    iniciar sesión</a>
                <a href="{{ route('register') }}"
                    class="text-sm text-[#26BBFF] hover:text-[#26BBFF]/80 transition-colors duration-200">Crear
                    cuenta</a>
            </div>
        </div>

        <p class="mt-4 text-center text-xs text-gray-500">
            Si no encuentras el correo, revisa tu carpeta de spam.
        </p>
    </div>
</body>

</html>
