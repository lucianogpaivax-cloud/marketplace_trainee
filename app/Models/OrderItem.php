<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $primaryKey = 'id_order_item';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_order',
        'id_product',
        'id_seller',
        'quantidade',
        'preco_unitario',
        'subtotal'
    ];

    // RELACIONAMENTOS

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }
}