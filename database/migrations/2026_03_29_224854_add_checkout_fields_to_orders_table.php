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
    Schema::table('orders', function (Blueprint $table) {

        // ENDEREÇO
        $table->string('address')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();

        // PAGAMENTO
        $table->string('payment_method')->nullable();
        $table->string('payment_status')->default('pending');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {

        $table->dropColumn([
            'address',
            'city',
            'state',
            'payment_method',
            'payment_status'
        ]);

    });
}
    
};
