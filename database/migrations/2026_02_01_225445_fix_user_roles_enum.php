<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Garante que os valores antigos ainda existam temporariamente
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('cliente','vendedor','customer','seller','admin') NOT NULL
        ");

        // 2. Converte valores antigos para os novos
        DB::table('users')->where('role', 'cliente')->update(['role' => 'customer']);
        DB::table('users')->where('role', 'vendedor')->update(['role' => 'seller']);

        // 3. Remove os valores antigos do ENUM
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('customer','seller','admin') NOT NULL
        ");
    }

    public function down(): void
    {
        // Rollback seguro
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('cliente','vendedor','admin') NOT NULL
        ");

        DB::table('users')->where('role', 'customer')->update(['role' => 'cliente']);
        DB::table('users')->where('role', 'seller')->update(['role' => 'vendedor']);
    }
};
