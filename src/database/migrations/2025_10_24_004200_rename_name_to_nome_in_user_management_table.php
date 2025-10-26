<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameNameToNomeInUserManagementTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Só executa se a tabela existir, a coluna `name` existir e `nome` não existir
        if (Schema::hasTable('user_management') && Schema::hasColumn('user_management', 'name') && ! Schema::hasColumn('user_management', 'nome')) {
            DB::statement("
                ALTER TABLE `user_management`
                CHANGE `name` `nome` VARCHAR(150)
                CHARACTER SET latin1 COLLATE latin1_swedish_ci
                NULL DEFAULT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte apenas se a coluna `nome` existir e `name` não existir
        if (Schema::hasTable('user_management') && Schema::hasColumn('user_management', 'nome') && ! Schema::hasColumn('user_management', 'name')) {
            DB::statement("
                ALTER TABLE `user_management`
                CHANGE `nome` `name` VARCHAR(150)
                CHARACTER SET latin1 COLLATE latin1_swedish_ci
                NULL DEFAULT NULL
            ");
        }
    }
}
