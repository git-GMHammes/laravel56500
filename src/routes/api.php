<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContatoController;
use App\Http\Controllers\Api\V1\UserManagementController;

Route::get('/health', function () {
    return response()->json([
        "header" => ["http_code" => 200, "status" => "OK", "method" => "GET", "api_version" => "v1.0", "message" => "API funcionando"],
        "result" => ["service" => "Laravel API", "version" => "1.0.0", "timestamp" => now()->toIso8601String()],
        "metadata" => ["url_sequence" => ["api", "health"], "base_url" => url('/')]
    ]);
});

Route::prefix('v1')->group(function () {
    Route::get('/contatos', [ContatoController::class, 'index']);
    Route::get('/contatos/{id}', [ContatoController::class, 'show']);
    Route::post('/users', [UserManagementController::class, 'store']);
});
