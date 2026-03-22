<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $user = Auth::user();

    if (!$user) {
    return response()->json([
        'message' => 'Não autenticado'
    ], 401);
}

         $request->validate([
        'product_id' => 'required|exists:products,id_product'
        ]);

        // pega ou cria carrinho
        $cart = Cart::firstOrCreate([
            'user_id' => $user->id_user
        ]);

        // verifica se já existe o produto no carrinho
        $item = CartItem::where('id_cart', $cart->id_cart)
            ->where('product_id', $request->product_id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'id_cart' => $cart->id_cart,
                'product_id' => $request->product_id,
                'quantity' => 1
            ]);
        }

        return response()->json([
            'message' => 'Produto adicionado ao carrinho'
        ]);
    }

    // ✅ método separado corretamente
    public function getCart()
    {
        $user = Auth::user();

    if (!$user) {
    return response()->json([
        'message' => 'Não autenticado'
    ], 401);
}

        $cart = Cart::with('items.product')
            ->where('user_id', $user->id_user)
            ->first();

        return response()->json($cart);
    }

    public function clear()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'message' => 'Usuário não autenticado'
        ], 401);
    }

    $cart = Cart::where('user_id', $user->id_user)->first();

    if (!$cart) {
        return response()->json([
            'message' => 'Carrinho vazio'
        ], 404);
    }

    CartItem::where('id_cart', $cart->id_cart)->delete();

    return response()->json([
        'message' => 'Carrinho limpo com sucesso'
    ]);
}

    public function removeItem($id)
{
    $user = Auth::user();

    $cart = Cart::where('user_id', $user->id_user)->first();

    $item = CartItem::where('id_cart', $cart->id_cart)
        ->where('id_cart_items', $id)
        ->first();

    if (!$item) {
        return response()->json(['message' => 'Item não encontrado'], 404);
    }

    $item->delete();

    return response()->json([
        'message' => 'Item removido'
    ]);
}
}