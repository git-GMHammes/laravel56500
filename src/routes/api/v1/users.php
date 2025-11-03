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


Route::prefix('users')->name('api.v1.users.')->group(function () {

    # ============================================
    # ROTAS DE METADADOS
    # ============================================

    # GET /api/v1/users/columns
    # Retorna informações detalhadas das colunas da tabela
    # Necessita Token: Não
    # @return \Illuminate\Http\JsonResponse
    Route::get('/columns', [UserManagementController::class, 'getColumns'])
        ->name('columns');

    # GET /api/v1/users/column-names
    # Retorna apenas os nomes das colunas
    # Necessita Token: Não
    # @return \Illuminate\Http\JsonResponse
    Route::get('/column-names', [UserManagementController::class, 'getColumnNames'])
        ->name('column-names');


    # ============================================
    # CRUD BÁSICO
    # ============================================

    # GET /api/v1/users
    # Lista todos os usuários
    # Necessita Token: Não
    # @queryParam page int Número da página (opcional)
    # @queryParam per_page int Itens por página (opcional)
    # @return \Illuminate\Http\JsonResponse
    Route::get('/', [UserManagementController::class, 'index'])
        ->name('index');

    # POST /api/v1/users
    # Cria um novo usuário
    # Necessita Token: Não
    # @bodyParam nome string required Nome do usuário
    # @bodyParam email string required Email do usuário
    # @bodyParam password string required Senha do usuário
    # @return \Illuminate\Http\JsonResponse
    Route::post('/', [UserManagementController::class, 'store'])
        ->name('store');

    # GET /api/v1/users/{id}
    # Exibe um usuário específico
    # Necessita Token: Não
    # @urlParam id int required ID do usuário
    # @return \Illuminate\Http\JsonResponse
    Route::get('/{id}', [UserManagementController::class, 'show'])
        ->name('show');

    # PUT /api/v1/users/{id}
    # Atualiza um usuário completo (substitui todos os campos)
    # Necessita Token: Não
    # @urlParam id int required ID do usuário
    # @bodyParam nome string required Nome do usuário
    # @bodyParam email string required Email do usuário
    # @bodyParam password string optional Senha do usuário
    # @return \Illuminate\Http\JsonResponse
    Route::put('/{id}', [UserManagementController::class, 'update'])
        ->name('update');

    # PATCH /api/v1/users/{id}
    # Atualiza um usuário parcialmente (atualiza apenas campos enviados)
    # Necessita Token: Não
    # @urlParam id int required ID do usuário
    # @bodyParam nome string optional Nome do usuário
    # @bodyParam email string optional Email do usuário
    # @bodyParam password string optional Senha do usuário
    # @return \Illuminate\Http\JsonResponse    Route::patch('/{id}', [UserManagementController::class, 'update'])
        ->name('patch');

    # ============================================
    # OPERAÇÕES DE EXCLUSÃO
    # ============================================

    # DELETE /api/v1/users/{id}
    # Remove um usuário (SOFT DELETE - Exclusão Lógica)
    # Preenche o campo deleted_at, mantendo o registro no banco
    # Necessita Token: Não
    # ⚠️ ATENÇÃO: O registro será marcado como deletado mas não removido
    # @urlParam id int required ID do usuário
    # @return \Illuminate\Http\JsonResponse    Route::delete('/{id}', [UserManagementController::class, 'delete'])
        ->name('delete');

    # DELETE /api/v1/users/{id}/force
    # Remove um usuário PERMANENTEMENTE (HARD DELETE)
    # Remove o registro definitivamente do banco de dados
    # Necessita Token: Não
    # ⚠️ ATENÇÃO: Esta ação é IRREVERSÍVEL!
    # O registro será completamente removido do banco de dados
    # @urlParam id int required ID do usuário
    # @return \Illuminate\Http\JsonResponse    Route::delete('/{id}/force', [UserManagementController::class, 'destroy'])
        ->name('force-delete');

    # DELETE /api/v1/users/clear
    # Remove PERMANENTEMENTE todos os registros soft deleted
    # Necessita Token: Não
    # ⚠️ ATENÇÃO MÁXIMA: Remove TODOS os registros marcados como deletados!
    # Esta ação é IRREVERSÍVEL e afeta múltiplos registros!
    # Use com extremo cuidado!
    # @return \Illuminate\Http\JsonResponse    Route::delete('/clear', [UserManagementController::class, 'clear'])
        ->name('clear');

});
