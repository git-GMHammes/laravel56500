<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function index(Request $request)
    {
        $todosContatos = [
            ["nome" => "João da Silva", "endereco" => "Rua das Flores, 123 - Centro, Rio de Janeiro - RJ", "telefone" => "(21) 98765-4321"],
            ["nome" => "Maria Oliveira", "endereco" => "Av. Brasil, 456 - Copacabana, Rio de Janeiro - RJ", "telefone" => "(21) 99876-5432"]
        ];

        $limite = (int) $request->query('limite', 50);
        $pagina = (int) $request->query('pagina', 1);
        $totalRegistros = count($todosContatos);
        $totalPaginas = (int) ceil($totalRegistros / $limite);
        $offset = ($pagina - 1) * $limite;
        $contatosPaginados = array_slice($todosContatos, $offset, $limite);

        return response()->json([
            "header" => [
                "http_code" => 200,
                "status" => "OK",
                "method" => $request->method(),
                "api_version" => "v1.0",
                "message" => "Requisição processada com sucesso"
            ],
            "result" => $contatosPaginados,
            "metadata" => [
                "url_sequence" => $request->segments(),
                "paginacao" => [
                    "pagina_atual" => $pagina,
                    "total_paginas" => $totalPaginas,
                    "total_registros" => $totalRegistros
                ],
                "limite" => $limite,
                "base_url" => $request->root()
            ]
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $contatos = [
            "1" => ["nome" => "João da Silva", "endereco" => "Rua das Flores, 123", "telefone" => "(21) 98765-4321"],
            "2" => ["nome" => "Maria Oliveira", "endereco" => "Av. Brasil, 456", "telefone" => "(21) 99876-5432"]
        ];

        if (!isset($contatos[$id])) {
            return response()->json([
                "header" => ["http_code" => 404, "status" => "NOT_FOUND", "method" => $request->method(), "api_version" => "v1.0", "message" => "Contato não encontrado"],
                "result" => [],
                "metadata" => ["url_sequence" => $request->segments(), "base_url" => $request->root()]
            ], 404);
        }

        return response()->json([
            "header" => ["http_code" => 200, "status" => "OK", "method" => $request->method(), "api_version" => "v1.0", "message" => "Requisição processada com sucesso"],
            "result" => [$contatos[$id]],
            "metadata" => ["url_sequence" => $request->segments(), "base_url" => $request->root()]
        ], 200);
    }
}
