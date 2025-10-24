<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Customer;
use App\Models\Seller;

class ApiAuthController extends Controller
{   
    // Login via API
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas.'
            ], 401);
        }

        // Criar token de API (Sanctum ou Passport)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // Logout via API
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // remove todos os tokens

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ]);
    }
}
