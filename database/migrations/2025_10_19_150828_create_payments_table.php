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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payment'); // PK

            // FK para o pedido
            $table->unsignedBigInteger('id_order');
            $table->foreign('id_order')
                  ->references('id_order')->on('orders')
                  ->onDelete('cascade');

            $table->enum('metodo', ['pix', 'cartão', 'boleto', 'simulado']);
            $table->decimal('valor_pago', 10, 2);
            $table->timestamp('data_pagamento')->nullable();
            $table->enum('status', ['pendente', 'confirmado', 'recusado'])->default('pendente');

            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
