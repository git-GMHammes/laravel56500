<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContatoController;

# ========================================
# CONTATOS ROUTES - API V1
# ========================================

# Rotas para gerenciamento de contatos
# Base URL: /api/v1/contatos

# Autor: Gustavo Hammes
# GET /api/v1/contatos
# Lista todos os contatos
# Necessita Token: Não
# @return \Illuminate\Http\JsonResponse

Route::get('/contatos', [ContatoController::class, 'index'])
    ->name('api.v1.contatos.index');

# GET /api/v1/contatos/{id}
# Exibe um contato específico
# Necessita Token: Não
# @param int $id ID do contato
# @return \Illuminate\Http\JsonResponse

Route::get('/contatos/{id}', [ContatoController::class, 'show'])
    ->name('api.v1.contatos.show');

# POST /api/v1/contatos
#
# Cria um novo contato (preparado para futuro)
# Necessita Token: Não

// Route::post('/contatos', [ContatoController::class, 'store'])
//     ->name('api.v1.contatos.store');

# PUT/PATCH /api/v1/contatos/{id}
#
# Atualiza um contato (preparado para futuro)
# Necessita Token: Não

// Route::match(['put', 'patch'], '/contatos/{id}', [ContatoController::class, 'update'])
//     ->name('api.v1.contatos.update');

# DELETE /api/v1/contatos/{id}
#
# Remove um contato (preparado para futuro)
# Necessita Token: Não

// Route::delete('/contatos/{id}', [ContatoController::class, 'destroy'])
//     ->name('api.v1.contatos.destroy');
