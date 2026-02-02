<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Listar todos os produtos ativos (rota pública)
     */
    public function index()
    {
        $products = Product::with(['seller', 'category'])
            ->where('status', 'ativo')
            ->paginate(12);

        return response()->json($products, 200);
    }

    /**
     * Mostrar um produto específico
     */
    public function show($id)
    {
        $product = Product::with(['seller', 'category'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado.'], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * Listar produtos do seller autenticado
     */
    public function myProducts()
    {
        $seller = Seller::where('id_user', Auth::id())->first();

        if (!$seller) {
            return response()->json(['message' => 'Perfil de seller não encontrado.'], 404);
        }

        $products = Product::with('category')
            ->where('id_seller', $seller->id_seller)
            ->get();

        return response()->json($products, 200);
    }

    /**
     * Criar um novo produto
     */
    public function store(Request $request)
    {
        $seller = Seller::where('id_user', Auth::id())->first();

        if (!$seller) {
            return response()->json(['message' => 'Apenas seller podem cadastrar produtos.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'id_category' => 'required|exists:categories,id_category',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'imagem' => 'nullable|string|max:255',
            'status' => 'in:ativo,inativo'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::create(array_merge(
            $validator->validated(),
            [
                'id_seller' => $seller->id_seller,
                'data_criacao' => now(),
                'status' => $request->status ?? 'ativo',
            ]
        ));

        return response()->json(['message' => 'Produto criado com sucesso!', 'product' => $product], 201);
    }

    /**
     * Atualizar produto (somente o seller dono pode)
     */
    public function update(Request $request, $id)
    {
        $seller = Seller::where('id_user', Auth::id())->first();

        if (!$seller) {
            return response()->json(['message' => 'Perfil de seller não encontrado.'], 404);
        }

        $product = Product::where('id_product', $id)
            ->where('id_seller', $seller->id_seller)
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado ou não pertence a este seller.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_category' => 'sometimes|exists:categories,id_category',
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'sometimes|numeric|min:0',
            'imagem' => 'nullable|string|max:255',
            'status' => 'in:ativo,inativo'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product->update($validator->validated());

        return response()->json(['message' => 'Produto atualizado com sucesso!', 'product' => $product], 200);
    }

    /**
     * Excluir produto (somente o dono)
     */
    public function destroy($id)
    {
        $seller = Seller::where('id_user', Auth::id())->first();

        if (!$seller) {
            return response()->json(['message' => 'Perfil de seller não encontrado.'], 404);
        }

        $product = Product::where('id_product', $id)
            ->where('id_seller', $seller->id_seller)
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado ou não pertence a este seller.'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produto excluído com sucesso.'], 200);
    }
}
