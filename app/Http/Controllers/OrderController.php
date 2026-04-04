<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();

        // VALIDAÇÃO
        $request->validate([
        'address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'payment_method' => 'required|string|in:pix,credit_card'
    ]);

        return DB::transaction(function () use ($user, $request) {

            $cart = Cart::where('user_id', $user->id_user)->first();

            if (!$cart) {
                return response()->json(['message' => 'Carrinho vazio'], 400);
            }

            $items = CartItem::where('id_cart', $cart->id_cart)
                ->with('product')
                ->get();

            if ($items->isEmpty()) {
                return response()->json(['message' => 'Carrinho vazio'], 400);
            }

            // CRIA PEDIDO COM ENDEREÇO + PAGAMENTO
            $order = Order::create([
                'id_user' => $user->id_user,
                'status' => 'pendente',
                'valor_total' => 0,
                'data_pedido' => now(),

            // ENDEREÇO
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,

            // PAGAMENTO
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending'
            ]);

            $total = 0;

            foreach ($items as $item) {

    if (!$item->product) {
        continue;
    }

    // VERIFICA ESTOQUE
    if ($item->product->quantidade < $item->quantity) {
        return response()->json([
            'message' => "Estoque insuficiente para {$item->product->nome}"
        ], 400);
    }

    $subtotal = $item->quantity * $item->product->preco;

    OrderItem::create([
        'id_order' => $order->id_order,
        'id_product' => $item->product->id_product,
        'id_seller' => $item->product->id_seller,
        'quantidade' => $item->quantity,
        'preco_unitario' => $item->product->preco,
        'subtotal' => $subtotal
    ]);

    // 📉 DIMINUI ESTOQUE
    $item->product->decrement('quantidade', $item->quantity);

    $total += $subtotal;
            }

            $order->update([
                'valor_total' => $total
            ]);

            CartItem::where('id_cart', $cart->id_cart)->delete();

            return response()->json([
                'message' => 'Pedido criado com sucesso',
                'order' => $order
            ]);
        });
    }
    
    public function index()
    {
    $user = Auth::user();

    $orders = Order::where('id_user', $user->id_user)
        ->with(['items.product']) // traz os itens + produto
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($orders);
    }

    public function show($id)
    {
    $user = Auth::user();

    $order = Order::where('id_order', $id)
        ->where('id_user', $user->id_user)
        ->with(['items.product'])
        ->first();

    if (!$order) {
        return response()->json(['message' => 'Pedido não encontrado'], 404);
    }

    return response()->json($order);
    }
}