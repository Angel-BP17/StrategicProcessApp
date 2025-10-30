<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return response()->json([
            'message' => 'El registro se gestiona a través de la API.',
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'dni' => 'TEMP-' . rand(10000, 99999),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => ["planner", "admin"],
        ]);

        return response()->json([
            'message' => 'Registro exitoso.',
            'data' => $user,
        ], 201);
    }
}
