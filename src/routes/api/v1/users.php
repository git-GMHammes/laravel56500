<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserManagementController;

# ========================================
# USERS ROUTES - API V1
# ========================================
#
# Rotas para gerenciamento de usuários
# Base URL: /api/v1/users
#
# Autor: Gustavo Hammes
# Versão: 2.0.0 (Adicionadas rotas para trabalhar com registros deletados)


Route::prefix('users')->name('api.v1.users.')->group(function () {

    # ============================================
    # ROTAS DE METADADOS
    # ============================================

    # GET /api/v1/users/columns
    # Retorna informações detalhadas das colunas da tabela
    Route::get('/columns', [UserManagementController::class, 'getColumns'])
        ->name('columns');

    # GET /api/v1/users/column-names
    # Retorna apenas os nomes das colunas
    Route::get('/column-names', [UserManagementController::class, 'getColumnNames'])
        ->name('column-names');


    # ============================================
    # ROTAS PARA REGISTROS DELETADOS (NOVAS)
    # ============================================

    # GET /api/v1/users/with-trashed
    # Lista TODOS os usuários (ATIVOS + DELETADOS)
    Route::get('/with-trashed', [UserManagementController::class, 'indexWithTrashed'])
        ->name('with-trashed');

    # GET /api/v1/users/only-trashed
    # Lista APENAS usuários DELETADOS
    Route::get('/only-trashed', [UserManagementController::class, 'indexOnlyTrashed'])
        ->name('only-trashed');


    # ============================================
    # CRUD BÁSICO
    # ============================================

    # GET /api/v1/users
    # Lista todos os usuários ATIVOS
    Route::get('/', [UserManagementController::class, 'index'])
        ->name('index');

    # POST /api/v1/users
    # Cria um novo usuário
    Route::post('/', [UserManagementController::class, 'store'])
        ->name('store');

    # GET /api/v1/users/{id}/with-trashed
    # Exibe um usuário específico (ATIVO OU DELETADO)
    Route::get('/{id}/with-trashed', [UserManagementController::class, 'showWithTrashed'])
        ->name('show-with-trashed');

    # GET /api/v1/users/{id}
    # Exibe um usuário específico (APENAS ATIVOS)
    Route::get('/{id}', [UserManagementController::class, 'show'])
        ->name('show');

    # PUT /api/v1/users/{id}
    # Atualiza um usuário completo
    Route::put('/{id}', [UserManagementController::class, 'update'])
        ->name('update');

    # PATCH /api/v1/users/{id}
    # Atualiza um usuário parcialmente
    Route::patch('/{id}', [UserManagementController::class, 'update'])
        ->name('patch');

    # ============================================
    # OPERAÇÕES DE EXCLUSÃO
    # ============================================

    # DELETE /api/v1/users/{id}
    # Remove um usuário (SOFT DELETE)
    Route::delete('/{id}', [UserManagementController::class, 'delete'])
        ->name('delete');

    # DELETE /api/v1/users/{id}/force
    # Remove um usuário PERMANENTEMENTE (HARD DELETE)
    Route::delete('/{id}/force', [UserManagementController::class, 'destroy'])
        ->name('force-delete');

    # DELETE /api/v1/users/clear
    # Remove PERMANENTEMENTE todos os registros soft deleted
    Route::delete('/clear', [UserManagementController::class, 'clear'])
        ->name('clear');

});
