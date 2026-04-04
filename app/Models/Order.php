<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders'; 

    protected $primaryKey = 'id_order';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'status',
        'valor_total',
        'data_pedido',
        'address',
        'city',
        'state',
        'payment_method',
        'payment_status'
    ];

    // RELACIONAMENTOS

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'id_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}