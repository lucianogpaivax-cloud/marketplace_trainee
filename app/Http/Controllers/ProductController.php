<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Usuário não autenticado.'], 401);
    }

    if ($user->role === 'admin') {
        $product = Product::where('id_product', $id)->first();
    } else {
        if (!$user->seller) {
            return response()->json([
                'message' => 'Perfil de seller não encontrado.'
            ], 404);
        }

        $product = Product::where('id_product', $id)
            ->where('id_seller', $user->seller->id_seller)
            ->first();
    }

    if (!$product) {
        return response()->json([
            'message' => 'Produto não encontrado.'
        ], 404);
    }

    return response()->json($product);
    }

    /**
     * Mostrar um produto PUBLICO
     */
    public function publicShow($id)
    {
    $product = Product::where('id_product', $id)
        ->where('status', 'ativo')
        ->first();

    if (!$product) {
        return response()->json([
            'message' => 'Produto não encontrado.'
        ], 404);
    }

    return response()->json($product);
    }

    /**
     * Listar produtos do seller autenticado
     */
    public function myProducts()
    {
        $user = Auth::user();

        if (!$user || !$user->seller) {
            return response()->json(['message' => 'Perfil de seller não encontrado.'], 404);
        }

        $products = Product::with('category')
            ->where('id_seller', $user->seller->id_seller)
            ->get();

        return response()->json($products, 200);
    }

    /**
     * Criar um novo produto
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->seller) {
            return response()->json([
                'message' => 'Apenas sellers podem cadastrar produtos.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'id_category' => 'required|exists:categories,id_category',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'imagem' => 'nullable|string|max:255',
            'status' => 'nullable|in:ativo,inativo'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $product = Product::create([
                'id_seller' => $user->seller->id_seller,
                'id_category' => $request->id_category,
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'preco' => $request->preco,
                'imagem' => $request->imagem,
                'status' => $request->status ?? 'ativo',
                'created_at' => now(),
            ]);

            return response()->json([
                'message' => 'Produto criado com sucesso!',
                'product' => $product
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno ao criar produto.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar produto
     */
    public function update(Request $request, $id)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Usuário não autenticado.'], 401);
    }

    // Se for ADMIN → pode editar qualquer produto
    if ($user->role === 'admin') {
        $product = Product::where('id_product', $id)->first();
    } 
    // Se for SELLER → só pode editar os próprios
    else {
        if (!$user->seller) {
            return response()->json(['message' => 'Perfil de seller não encontrado.'], 404);
        }

        $product = Product::where('id_product', $id)
            ->where('id_seller', $user->seller->id_seller)
            ->first();
    }

    if (!$product) {
        return response()->json(['message' => 'Produto não encontrado.'], 404);
    }

    $validator = Validator::make($request->all(), [
        'id_category' => 'sometimes|exists:categories,id_category',
        'nome' => 'sometimes|string|max:255',
        'descricao' => 'nullable|string',
        'preco' => 'sometimes|numeric|min:0',
        'imagem' => 'nullable|string|max:255',
        'status' => 'nullable|in:ativo,inativo'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $product->update($validator->validated());

    return response()->json([
        'message' => 'Produto atualizado com sucesso!',
        'product' => $product
    ], 200);
}

    /**
     * Excluir produto
     */
    public function destroy($id)
    {
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Usuário não autenticado.'], 401);
    }

    if ($user->role === 'admin') {
        $product = Product::where('id_product', $id)->first();
    } else {
        if (!$user->seller) {
            return response()->json(['message' => 'Perfil de seller não encontrado.'], 404);
        }

        $product = Product::where('id_product', $id)
            ->where('id_seller', $user->seller->id_seller)
            ->first();
    }

    if (!$product) {
        return response()->json(['message' => 'Produto não encontrado.'], 404);
    }

    $product->delete();

    return response()->json([
        'message' => 'Produto excluído com sucesso.'
    ], 200);
    }

    public function adminIndex()
    {
    $products = Product::with(['seller', 'category'])
        ->paginate(15);

    return response()->json($products);
    }
}
