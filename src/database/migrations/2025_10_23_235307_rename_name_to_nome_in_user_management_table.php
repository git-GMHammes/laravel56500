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
        // Só executar se a coluna `name` existir e `nome` NÃO existir
        if (Schema::hasTable('user_management') && Schema::hasColumn('user_management', 'name') && ! Schema::hasColumn('user_management', 'nome')) {
            // Usamos SQL direto para preservar o charset/collation exatamente como no seu dump
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
        // Reverter: renomeia `nome` de volta para `name` se existir
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
