<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Customer;
use App\Models\Seller;

class AuthController extends Controller
{
    // Exibe o formulário de login
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Busca o usuário
    $user = User::where('email', $request->email)->first();

    // Verifica se usuário existe e senha confere
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Email ou senha inválidos.'
        ], 401);
    }

    // Cria token API (Sanctum)
    $token = $user->createToken('auth_token')->plainTextToken;

    // Determina o redirecionamento com base na role
    $redirect = match ($user->role) {
        'cliente'  => '/dashboard-cliente',
        'vendedor' => '/dashboard-vendedor',
        'admin'    => '/dashboard-admin',
        default    => '/',
    };

    // Retorna JSON completo
    return response()->json([
        'message'  => 'Login realizado com sucesso',
        'user'     => $user,
        'role'     => $user->role,
        'redirect' => $redirect,
        'token'    => $token,
    ], 200);
}

    // Exibe formulário de cadastro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Registra um novo usuário
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:cliente,vendedor',
        ]);

        // Cria usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Se for cliente
        if ($user->role === 'cliente') {
            Customer::create([
                'id_user' => $user->id_user
            ]);
        }

        // Se for vendedor
        if ($user->role === 'vendedor') {
            Seller::create([
                'id_user' => $user->id_user,
                'tipo_loja' => $request->tipo_loja ?? null,
                'nome_loja' => $request->nomeLoja,
                'origem' => $request->nacionalidade ?? 'nacional'
                
            ]);
        }

        // 🔑 Gera o token Sanctum
    $token = $user->createToken('auth_token')->plainTextToken;

    // Retorna o usuário e o token
    return response()->json([
        'message' => 'Usuário registrado com sucesso',
        'user' => $user,
        'token' => $token,
    ], 201);

        // Redireciona conforme a role
        if ($user->role === 'cliente') {
            return redirect('/dashboard');
        } elseif ($user->role === 'vendedor') {
            return redirect('/dashboard-vendedor');
        }

        return redirect('/login');
    }
}
