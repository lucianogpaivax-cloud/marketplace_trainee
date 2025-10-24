<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_category';
    public $timestamps = false; // Não há created_at/updated_at padrão

    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * Uma categoria pode ter vários produtos
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'id_category', 'id_category');
    }
}
