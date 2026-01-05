<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Customer;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Listar todos os clientes
    public function listarClientes()
    {
        $clientes = Customer::with('user')->get();
        return response()->json($clientes);
    }

    // Listar todos os vendedores
    public function listarVendedores()
    {
        $vendedores = Seller::with('user')->get();
        return response()->json($vendedores);
    }

    // Atualizar dados de um cliente (customer)
public function atualizarCustomer(Request $request, $id_customer)
{
    $customer = Customer::where('id_customer', $id_customer)->first();

    if (!$customer) {
        return response()->json(['message' => 'Cliente não encontrado'], 404);
    }

    $user = User::where('id_user', $customer->id_user)
                ->where('role', 'cliente')
                ->first();

    if (!$user) {
        return response()->json(['message' => 'Usuário do cliente não encontrado'], 404);
    }

    // UPDATE users SET name = 'Teste', email = 'teste' WHERE id_user = 18 

    $user->update($request->only(['name', 'email']));

    $customer->update($request->only(['cpf', 'endereco']));

    return response()->json(['message' => 'Cliente atualizado com sucesso']);
}

    // Mostra um cliente especifico
    public function showCustomer($id_customer)
    {
        //SELECT * FROM customers WHERE id_customer = 3 LIMIT 1
        $clientes = Customer::with('user')->where('id_customer', $id_customer)->first();

        return response()->json($clientes);
    }

    // Excluir um vendedor
    public function excluirVendedor($id_seller)
{
    $seller = Seller::where('id_seller', $id_seller)->first();

    if (!$seller) {
        return response()->json(['message' => 'Vendedor não encontrado'], 404);
    }

    User::where('id_user', $seller->id_user)->delete();
    $seller->delete();

    return response()->json(['message' => 'Vendedor excluído com sucesso']);

}
}
