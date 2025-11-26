<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    /**
     * Lista todos os vendedores (rota pública)
     */
    public function index()
    {
        $sellers = Seller::paginate(10);

        return response()->json($sellers, 200);
    }

    /**
     * Mostra informações de um vendedor específico (rota pública)
     */
    public function show($id)
    {
        $seller = Seller::find($id);

        if (!$seller) {
            return response()->json(['message' => 'Vendedor não encontrado.'], 404);
        }

        return response()->json($seller, 200);
    }

    /**
     * Retorna o perfil do vendedor autenticado
     */
    public function profile()
    {
        $seller = Seller::where('id_user', Auth::id())->first();

        if (!$seller) {
            return response()->json(['message' => 'Perfil de vendedor não encontrado.'], 404);
        }

        return response()->json($seller, 200);
    }

    /**
     * Cria um perfil de vendedor para o usuário autenticado
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_loja' => 'required|string|max:255',
            'origem' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Evita duplicar perfil de vendedor
        $existing = Seller::where('id_user', Auth::id())->first();
        if ($existing) {
            return response()->json(['message' => 'O usuário já possui um perfil de vendedor.'], 409);
        }

        $seller = Seller::create([
            'id_user' => Auth::id(),
            'tipo_loja' => $request->tipo_loja,
            'origem' => $request->origem,
        ]);

        return response()->json(['message' => 'Perfil de vendedor criado com sucesso!', 'seller' => $seller], 201);
    }

    /**
     * Atualiza o perfil do vendedor autenticado
     */
    public function update(Request $request)
    {
        $seller = Seller::where('id_user', Auth::id())->first();

        if (!$seller) {
            return response()->json(['message' => 'Perfil de vendedor não encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo_loja' => 'sometimes|string|max:255',
            'nacional_internacional' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $seller->update($validator->validated());

        return response()->json(['message' => 'Perfil atualizado com sucesso!', 'seller' => $seller], 200);
    }

    /**
     * Deleta o perfil do vendedor autenticado
     */
    public function destroy()
    {
        $seller = Seller::where('id_user', Auth::id())->first();

        if (!$seller) {
            return response()->json(['message' => 'Perfil de vendedor não encontrado.'], 404);
        }

        $seller->delete();

        return response()->json(['message' => 'Perfil de vendedor excluído com sucesso.'], 200);
    }
}