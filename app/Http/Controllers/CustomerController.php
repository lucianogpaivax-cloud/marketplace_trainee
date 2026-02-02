<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
  public function getCustomer()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Não autenticado'], 401);
    }

    $customer = Customer::where('id_user', $user->id_user)->first();

    return response()->json([
        'user' => $user,
        'customer' => $customer
    ]);
}

    public function updateCustomer(Request $request)
{
    $user = Auth::user();
    $user = User::where('id_user', $user->id_user)->first();


    if (!$user) {
        return response()->json(['message' => 'Não autenticado'], 401);
    }

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id_user . ',id_user',
        'telefone' => 'nullable|string|max:20',
        'cpf' => 'nullable|string|max:20',
        'endereco' => 'nullable|string|max:255',
    ]);

    // Atualiza USER
    $user->update([
        'name' => $request->name ?? $user->name,
        'email' => $request->email ?? $user->email,
        'telefone' => $request->telefone ?? $user->telefone,
    ]);

    // Atualiza CUSTOMER
    $customer = Customer::where('id_user', $user->id_user)->first();

    if ($customer) {
        $customer->update([
            'cpf' => $request->cpf ?? $customer->cpf,
            'endereco' => $request->endereco ?? $customer->endereco,
        ]);
    }

    return response()->json([
        'message' => 'Perfil atualizado com sucesso!',
        'user' => $user,
        'customer' => $customer
    ]);
}
}