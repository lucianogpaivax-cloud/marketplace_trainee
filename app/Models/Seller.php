<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $table = 'sellers';
    protected $primaryKey = 'id_seller';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nome_loja',
        'tipo_loja',
        'origem',
    ];

    // Cada vendedor pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}