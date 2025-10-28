<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContatoController;
use App\Http\Controllers\Api\V1\UserManagementController;


 # Rota: `/api/health` - Verifica status da API
 # Necessita Token: Não
 # Autor: Gustavo Hammes
Route::get('/health', function () {
    return response()->json([
        "header" => ["http_code" => 200, "status" => "OK", "method" => "GET", "api_version" => "v1.0", "message" => "API funcionando"],
        "result" => ["service" => "Laravel API", "version" => "1.0.0", "timestamp" => now()->toIso8601String()],
        "metadata" => ["url_sequence" => ["api", "health"], "www" => url('/')]
    ]);
});

Route::prefix('v1')->group(function () {


     # Rota: `{{www}}/api/v1/contatos` - Lista todos os contatos
     # Necessita Token: Não
     # Autor: Gustavo Hammes
    Route::get('/contatos', [ContatoController::class, 'index']);


     # Rota: `{{www}}/api/v1/contatos/{id}` - Exibe um contato específico
     # Necessita Token: Não
     # Autor: Gustavo Hammes
    Route::get('/contatos/{id}', [ContatoController::class, 'show']);

    Route::prefix('users')->group(function () {


         # Rota: `{{www}}/api/v1/users/columns` - Informações das colunas
         # Necessita Token: Não
         # Autor: Gustavo Hammes
        Route::get('/columns', [UserManagementController::class, 'getColumns']);


         # Rota: `{{www}}/api/v1/users/column-names` - Nomes das colunas
         # Necessita Token: Não
         # Autor: Gustavo Hammes
        Route::get('/column-names', [UserManagementController::class, 'getColumnNames']);


         # Rota: `{{www}}/api/v1/users` - Lista todos os usuários
         # Necessita Token: Não
         # Autor: Gustavo Hammes
        Route::get('/', [UserManagementController::class, 'index']);


         # Rota: `{{www}}/api/v1/users` - Cria um novo usuário
         # Necessita Token: Não
         # Autor: Gustavo Hammes
        Route::post('/', [UserManagementController::class, 'store']);


         # Rota: `{{www}}/api/v1/users/{id}` - Exibe um usuário específico
         # Necessita Token: Não
         # Autor: Gustavo Hammes
        Route::get('/{id}', [UserManagementController::class, 'show']);


         # Rota: `{{www}}/api/v1/users/{id}` - Atualiza um usuário (completo)
         # Necessita Token: Não
         # Autor: Gustavo Hammes
        Route::put('/{id}', [UserManagementController::class, 'update']);

        # Rota: `{{www}}/api/v1/users/{id}` - Atualiza um usuário (parcial)
        # Necessita Token: Não
        # Autor: Gustavo Hammes
        Route::patch('/{id}', [UserManagementController::class, 'update']);

        # Rota: `{{www}}/api/v1/users/{id}` - Remove um usuário (SOFT DELETE - Exclusão Lógica)
        # Preenche o campo deleted_at, mas mantém o registro no banco
        # Necessita Token: Não
        # Autor: Gustavo Hammes
        Route::delete('/{id}', [UserManagementController::class, 'delete']);

        # Rota: `{{www}}/api/v1/users/{id}/force` - Remove um usuário PERMANENTEMENTE (HARD DELETE)
        # Remove o registro definitivamente do banco de dados
        # ⚠️ ATENÇÃO: Esta ação é irreversível!
        # Necessita Token: Não
        # Autor: Gustavo Hammes
        Route::delete('/{id}/force', [UserManagementController::class, 'destroy']);

    });

});
