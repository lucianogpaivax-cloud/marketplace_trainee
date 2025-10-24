<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $table = 'sellers';
    protected $primaryKey = 'id_seller';
    protected $fillable = ['id_user', 'tipo_loja', 'nacional_internacional'];
}