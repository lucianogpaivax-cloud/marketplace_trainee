<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_order_item'); // PK

            // FK para o pedido
            $table->unsignedBigInteger('id_order');
            $table->foreign('id_order')
                  ->references('id_order')->on('orders')
                  ->onDelete('cascade');

            // FK para o produto
            $table->unsignedBigInteger('id_product');
            $table->foreign('id_product')
                  ->references('id_product')->on('products')
                  ->onDelete('cascade');

            // FK para o seller
            $table->unsignedBigInteger('id_seller');
            $table->foreign('id_seller')
                  ->references('id_seller')->on('sellers')
                  ->onDelete('cascade');

            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};