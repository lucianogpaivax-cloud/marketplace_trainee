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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id('id_payout'); // PK

            // FK para o item do pedido
            $table->unsignedBigInteger('id_order_item');
            $table->foreign('id_order_item')
                  ->references('id_order_item')->on('order_items')
                  ->onDelete('cascade');

            // FK para o seller
            $table->unsignedBigInteger('id_seller');
            $table->foreign('id_seller')
                  ->references('id_seller')->on('sellers')
                  ->onDelete('cascade');

            $table->decimal('valor_seller', 10, 2);
            $table->decimal('valor_comissao_marketplace', 10, 2);
            $table->enum('status', ['pendente', 'pago'])->default('pendente');
            $table->timestamp('data_repasse')->nullable();

            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
