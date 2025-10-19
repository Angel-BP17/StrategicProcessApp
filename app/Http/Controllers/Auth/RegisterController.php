<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    // removed RegistersUsers trait

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        // Separar nombre completo en partes
        $parts = explode(' ', trim($request->name));
        $firstName = $parts[0] ?? '';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        // Crear usuario en base a la estructura de la tabla
        $user = User::create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'full_name'  => $request->name,
            'dni'        => 'TEMP-' . rand(10000, 99999), // valor temporal
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        return redirect()->route('welcome')->with('success', '¡Registro exitoso!');
    }
}
