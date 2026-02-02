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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product');  // PK

            // FK para o seller
            $table->unsignedBigInteger('id_seller');
            $table->foreign('id_seller')
                  ->references('id_seller')->on('sellers')
                  ->onDelete('cascade');

            // FK para a categoria
            $table->unsignedBigInteger('id_category');
            $table->foreign('id_category')
                  ->references('id_category')->on('categories')
                  ->onDelete('cascade');

            $table->string('nome');              // Nome do produto
            $table->text('descricao')->nullable(); // Descrição opcional
            $table->decimal('preco', 10, 2);    // Preço
            $table->string('imagem')->nullable(); // URL ou caminho da imagem
            $table->enum('status', ['ativo', 'inativo'])->default('ativo'); // Status
            $table->timestamps();               // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
