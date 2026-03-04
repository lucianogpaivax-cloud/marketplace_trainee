<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'created_at',
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

