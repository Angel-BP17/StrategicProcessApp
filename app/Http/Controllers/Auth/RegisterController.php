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

        // Crear usuario en base a la estructura de la tabla
        User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'full_name'  => $request->first_name ." ". $request->last_name,
            'dni'        => 'TEMP-' . rand(10000, 99999), // valor temporal
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role' => json_encode(["planner"]),
        ]);

        return redirect()->route('welcome')->with('success', '¡Registro exitoso!');
    }
}
