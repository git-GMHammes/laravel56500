<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserManagementTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('user_management')) {
            Schema::create('user_management', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150)->nullable();
                $table->string('cpf', 50)->nullable();
                $table->string('whatsapp', 50)->nullable();
                $table->string('user', 50)->nullable();
                $table->string('password', 200)->nullable();
                $table->string('profile', 200)->nullable();
                $table->string('mail', 150)->nullable();
                $table->string('phone', 50)->nullable();
                $table->date('date_birth')->nullable();
                $table->string('zip_code', 50)->nullable();
                $table->string('address', 50)->nullable();
                $table->softDeletes();
                // timestamps() cria created_at e updated_at (sem DEFAULT CURRENT_TIMESTAMP explícito)
                $table->timestamps();
                // Forçar engine igual ao dump (opcional)
                $table->engine = 'InnoDB';
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
