<?php

use Illuminate\Support\Facades\Route;


 # ========================================
 # HEALTH CHECK ROUTES
 # ========================================

 # Rotas para verificação de status da API
 # Autor: Gustavo Hammes

 # GET /api/health
 # Verifica o status e disponibilidade da API
 # @return \Illuminate\Http\JsonResponse

 # @response 200 {
 #   "header": {
 #     "http_code": 200,
 #     "status": "OK",
 #     "method": "GET",
 #     "api_version": "v1.0",
 #     "message": "API funcionando"
 #   },
 #   "result": {
 #     "service": "Laravel API",
 #     "version": "1.0.0",
 #     "timestamp": "2025-11-02T23:38:45.000000Z"
 #   },
 #   "metadata": {
 #     "url_sequence": ["api", "health"],
 #     "www": "http://localhost"
 #   }
 # }
Route::get('/health', function () {
    return response()->json([
        "header" => [
            "http_code" => 200,
            "status" => "OK",
            "method" => "GET",
            "api_version" => "v1.0",
            "message" => "API funcionando"
        ],
        "result" => [
            "service" => "Laravel API",
            "version" => "1.0.0",
            "timestamp" => now()->toIso8601String()
        ],
        "metadata" => [
            "url_sequence" => ["api", "health"],
            "www" => url('/')
        ]
    ]);
})->name('api.health');
