<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Customer;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Listar todos os customers
    public function listarcustomers()
    {
        $customers = Customer::with('user')->get();
        return response()->json($customers);
    }

    // Listar todos os seller
    public function listarseller()
    {
        $seller = Seller::with('user')->get();
        return response()->json($seller);
    }

    // Atualizar dados de um customer (customer)
public function atualizarCustomer(Request $request, $id_customer)
{
    $customer = Customer::where('id_customer', $id_customer)->first();

    if (!$customer) {
        return response()->json(['message' => 'customer não encontrado'], 404);
    }

    $user = User::where('id_user', $customer->id_user)
                ->where('role', 'customer')
                ->first();

    if (!$user) {
        return response()->json(['message' => 'Usuário do customer não encontrado'], 404);
    }

    // UPDATE users SET name = 'Teste', email = 'teste' WHERE id_user = 18 

    $user->update($request->only(['name', 'email']));

    $customer->update($request->only(['cpf', 'endereco']));

    return response()->json(['message' => 'customer atualizado com sucesso']);
}

    // Mostra um customer especifico
    public function showCustomer($id_customer)
    {
        //SELECT * FROM customers WHERE id_customer = 3 LIMIT 1
        $customers = Customer::with('user')->where('id_customer', $id_customer)->first();

        return response()->json($customers);
    }

    // Atualizar dados de um seller (seller)
public function atualizarSeller(Request $request, $id_seller)
{
    $seller = Seller::where('id_seller', $id_seller)->first();

    if (!$seller) {
        return response()->json(['message' => 'Seller não encontrado'], 404);
    }

    $user = User::where('id_user', $seller->id_user)
                ->where('role', 'seller') // ou 'seller', conforme seu sistema
                ->first();

    if (!$user) {
        return response()->json(['message' => 'Usuário do seller não encontrado'], 404);
    }

    // Atualiza dados do usuário
    $user->update($request->only(['name', 'email']));

    // Atualiza dados do seller
    $seller->update($request->only([
        'nome_loja',
        'tipo_loja',
        'origem'
    ]));

    return response()->json(['message' => 'Seller atualizado com sucesso']);
}


    // Excluir um seller
    public function excluirseller($id_seller)
{
    $seller = Seller::where('id_seller', $id_seller)->first();

    if (!$seller) {
        return response()->json(['message' => 'seller não encontrado'], 404);
    }

    User::where('id_user', $seller->id_user)->delete();
    $seller->delete();

    return response()->json(['message' => 'seller excluído com sucesso']);

}
}
