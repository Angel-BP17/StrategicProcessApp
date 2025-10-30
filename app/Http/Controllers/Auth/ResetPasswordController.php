<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return response()->json([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    protected function reset(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ],
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status),
            ]);
        }

        return response()->json([
            'message' => __($status),
        ], 422);
    }
}
