<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Customer;
use App\Models\Seller;

class UserController extends Controller
{
    // Registro via API
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,seller',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'customer') {
            Customer::create(['id_user' => $user->id_user]);
        }

        if ($user->role === 'seller') {
            Seller::create([
                'id_user' => $user->id_user,
                'tipo_loja' => $request->tipo_loja ?? null,
                'nacional_internacional' => $request->nacional_internacional ?? 'nacional'
            ]);
        }

        // Retorna JSON com dados do usuário
        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'user' => $user
        ], 201);
    }
}
