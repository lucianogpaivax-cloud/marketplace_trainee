<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
    $table->id('id_cart_items');

    // 🔗 relação com carrinho
    $table->unsignedBigInteger('id_cart');

    // 🔗 relação com produto
    $table->unsignedBigInteger('product_id');

    $table->integer('quantity')->default(1);

    $table->timestamps();

    // ✅ FK correta (nome certo + coluna certa)
    $table->foreign('id_cart')
          ->references('id_cart') // 👈 CORRIGIDO
          ->on('carts')
          ->onDelete('cascade');

    // FK product
    $table->foreign('product_id')
          ->references('id_product')
          ->on('products')
          ->onDelete('cascade');

    // evitar duplicidade
    $table->unique(['id_cart', 'product_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
