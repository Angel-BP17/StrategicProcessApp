<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-[#201A2F] rounded-2xl shadow p-6">
            <h1 class="text-xl font-semibold text-gray-900">Restablecer contraseña</h1>
            <p class="text-sm text-gray-600 mt-1">Ingresa tu nueva contraseña para tu cuenta.</p>

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf

                {{-- Token de restablecimiento --}}
                <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">

                {{-- Email (usualmente se pasa como query o en el form) --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input id="email" name="email" type="email"
                        value="{{ old('email', $email ?? request('email')) }}" required autofocus
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmar nueva contraseña
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-indigo-600 text-white py-2.5 text-sm font-medium hover:bg-indigo-700">
                    Guardar nueva contraseña
                </button>
            </form>

            <div class="mt-6 flex items-center justify-between text-sm">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Volver a iniciar sesión</a>
                <a href="{{ route('password.request') }}" class="text-gray-600 hover:underline">Solicitar otro
                    enlace</a>
            </div>
        </div>

        <p class="mt-4 text-center text-xs text-gray-500">
            Asegúrate de que tu contraseña cumpla con la política de seguridad.
        </p>
    </div>
</body>

</html>
