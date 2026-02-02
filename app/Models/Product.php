<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id_product';
    public $timestamps = false; // usamos data_criacao manualmente

    protected $fillable = [
        'id_seller',
        'id_category',
        'nome',
        'descricao',
        'preco',
        'imagem',
        'status',
        'data_criacao',
    ];

    /**
     * Relação: produto pertence a um seller
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'id_seller', 'id_seller');
    }

    /**
     * Relação: produto pertence a uma categoria
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }
}

