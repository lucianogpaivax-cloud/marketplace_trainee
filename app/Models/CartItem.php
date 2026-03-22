<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $primaryKey = 'id_cart_items';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_cart',
        'product_id',
        'quantity'
    ];

    // 🔗 Relacionamento com carrinho
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id_cart', 'id_cart');
    }

    // 📦 Relacionamento com produto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}