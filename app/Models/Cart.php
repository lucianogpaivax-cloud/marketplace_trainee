<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $primaryKey = 'id_cart';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
    'user_id' // ✅ CORRIGIDO
    ];

    // Relacionamento com usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // Relacionamento com itens do carrinho
    public function items()
    {
        return $this->hasMany(CartItem::class, 'id_cart', 'id_cart');
    }
}