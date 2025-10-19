<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Mostrar formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Fazer login
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Redireciona conforme o tipo de usuário
        if ($user->role === 'admin') {
            return redirect('/dashboard-admin');
        } elseif ($user->role === 'vendedor') {
            return redirect('/dashboard-vendedor');
        }

        return redirect('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Credenciais inválidas.',
    ]);
}

    // Fazer logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Mostrar o painel cliente após login
    public function dashboard()
    {
        return view('dashboard');
    }

    // Mostrar o painel vendedor após login
    public function dashboardVendedor()
{
    return view('dashboard-vendedor');
}

    // Exibe o formulário de cadastro
    public function showRegisterForm()
{
    return view('auth.register');
}
    public function register(Request $request)
{
    // Validação dos campos recebidos do formulário
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:cliente,vendedor',
        'telefone' => 'nullable|string|max:20',
    ]);

    // Cria um novo usuário no banco
    $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => $request->role,
    'status' => 'ativo',
    'tipo_loja' => $request->tipo_loja,
    'escopo' => $request->escopo ?? 'nacional',
]);

    Auth::login($user);

    if ($user->role === 'admin') {
        return redirect('/dashboard-admin');
    } elseif ($user->role === 'vendedor') {
        return redirect('/dashboard-vendedor');
    } else {
        return redirect('/dashboard');
    }
}

}