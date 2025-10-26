<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUserManagementTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cria a tabela somente se não existir
        if (! Schema::hasTable('user_management')) {
            Schema::create('user_management', function (Blueprint $table) {
                // definimos engine/charset/collation para corresponder ao dump
                $table->engine = 'InnoDB';
                $table->charset = 'latin1';
                $table->collation = 'latin1_swedish_ci';

                // id auto increment (bigint)
                $table->bigIncrements('id');

                // colunas conforme seu CREATE TABLE (com collation latin1_swedish_ci)
                $table->string('name', 150)->nullable()->collation('latin1_swedish_ci');
                $table->string('cpf', 50)->nullable()->collation('latin1_swedish_ci');
                $table->string('whatsapp', 50)->nullable()->collation('latin1_swedish_ci');
                $table->string('user', 50)->nullable()->collation('latin1_swedish_ci');
                $table->string('password', 200)->nullable()->collation('latin1_swedish_ci');
                $table->string('profile', 200)->nullable()->collation('latin1_swedish_ci');
                $table->string('mail', 150)->nullable()->collation('latin1_swedish_ci');
                $table->string('phone', 50)->nullable()->collation('latin1_swedish_ci');
                $table->date('date_birth')->nullable();
                $table->string('zip_code', 50)->nullable()->collation('latin1_swedish_ci');
                $table->string('address', 50)->nullable()->collation('latin1_swedish_ci');

                // created_at e updated_at com DEFAULT CURRENT_TIMESTAMP e ON UPDATE
                // usamos dateTime e useCurrent/useCurrentOnUpdate para preservar o comportamento
                $table->dateTime('created_at')->nullable()->useCurrent();
                $table->dateTime('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                // deleted_at como DATETIME NULL
                $table->dateTime('deleted_at')->nullable();

                // primary key já criada com bigIncrements
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_management');
    }
}
