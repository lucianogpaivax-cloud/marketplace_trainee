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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');  // PK

            // FK para o cliente
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                  ->references('id_user')->on('users')
                  ->onDelete('cascade');

            $table->enum('status', ['pendente', 'pago', 'enviado', 'concluído', 'cancelado'])->default('pendente');
            $table->decimal('valor_total', 10, 2);
            $table->timestamp('data_pedido')->useCurrent(); // Data do pedido
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
