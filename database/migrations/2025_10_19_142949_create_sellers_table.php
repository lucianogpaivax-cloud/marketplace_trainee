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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id('id_seller');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('nome_loja');
            $table->string('tipo_loja')->nullable(); // ex: roupas, eletrônicos
            $table->enum('origem', ['nacional', 'internacional']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
