<?php

use Illuminate\Support\Facades\Route;


 # ========================================
 # API ROUTES - Estrutura Modular
 # ========================================

 # Este arquivo é o ponto de entrada principal para todas as rotas da API.
 # As rotas estão organizadas em arquivos separados por contexto/módulo.

 # Estrutura:
 # routes/
 # ├── api.php (este arquivo)
 # └── api/
 #     └── v1/
 #         ├── health.php
 #         ├── users.php
 #         └── contatos.php

 # Autor: Gustavo Hammes
 # Data: 2025-11-02

# ============================================
# HEALTH CHECK - Rota de verificação da API
# ============================================
require __DIR__ . '/api/v1/health.php';

# ============================================
# API VERSION 1
# ============================================
Route::prefix('v1')->group(function () {

    // Módulo de Contatos
    require __DIR__ . '/api/v1/contatos.php';

    // Módulo de Usuários
    require __DIR__ . '/api/v1/users.php';

});

# ============================================
# API VERSION 2 (Preparado para futuro)
# ============================================
# Route::prefix('v2')->group(function () {
#     require __DIR__.'/api/v2/users.php';
# });
