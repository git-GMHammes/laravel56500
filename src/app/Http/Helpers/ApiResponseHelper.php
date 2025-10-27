<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    /**
     * Gera uma resposta padronizada para a API
     *
     * @param bool $success Status da operação
     * @param int $httpCode Código HTTP da resposta
     * @param string|null $message Mensagem descritiva (null para usar mensagem padrão)
     * @param mixed $dbReturn Dados retornados do banco (array, object, collection, etc)
     * @param string|null $tableName Nome da tabela (opcional)
     * @param array $additionalMeta Metadados adicionais (opcional)
     * @return JsonResponse
     */
    public static function response(
        bool $success,
        int $httpCode,
        ?string $message = null,
        $dbReturn = null,
        ?string $tableName = null,
        array $additionalMeta = []
    ): JsonResponse {
        // Se message for null, usa a mensagem padrão do código HTTP
        if ($message === null) {
            $message = self::getHttpMessage($httpCode);
        }

        // Prepara os dados
        $data = null;
        if ($dbReturn !== null) {
            $data = self::formatData($dbReturn, $tableName);
        }

        // Monta a estrutura base da resposta
        $response = [
            'success' => $success,
            'http_code' => $httpCode,
            'message' => $message,
        ];

        // Adiciona dados se existirem
        if ($data !== null) {
            $response['data'] = $data;
        }

        // Monta os metadados (agora automático!)
        $metaData = self::buildMetaData($additionalMeta);
        if (!empty($metaData)) {
            $response['meta_data'] = $metaData;
        }

        return response()->json($response, $httpCode);
    }

    /**
     * Retorna a mensagem padrão para cada código HTTP
     *
     * @param int $httpCode
     * @return string
     */
    private static function getHttpMessage(int $httpCode): string
    {
        return match ($httpCode) {
            // 1xx - Informativos
            100 => 'Continuar requisição',
            101 => 'Mudando protocolo',
            102 => 'Processando requisição',
            103 => 'Cabeçalhos iniciais enviados',

            // 2xx - Sucesso
            200 => 'Sucesso',
            201 => 'Criado com sucesso',
            202 => 'Aceito para processamento',
            203 => 'Informação não autorizada',
            204 => 'Sem conteúdo',
            205 => 'Resetar conteúdo',
            206 => 'Conteúdo parcial',
            207 => 'Status múltiplos',
            208 => 'Já reportado',
            226 => 'IM usado',

            // 3xx - Redirecionamentos
            300 => 'Múltiplas escolhas',
            301 => 'Movido permanentemente',
            302 => 'Encontrado / redirecionado',
            303 => 'Ver outro recurso',
            304 => 'Não modificado',
            305 => 'Usar proxy',
            306 => 'Proxy obsoleto',
            307 => 'Redirecionamento temporário',
            308 => 'Redirecionamento permanente',

            // 4xx - Erros do Cliente
            400 => 'Requisição inválida',
            401 => 'Não autorizado',
            402 => 'Pagamento requerido',
            403 => 'Proibido',
            404 => 'Não encontrado',
            405 => 'Método não permitido',
            406 => 'Não aceitável',
            407 => 'Autenticação proxy requerida',
            408 => 'Tempo esgotado',
            409 => 'Conflito',
            410 => 'Recurso removido',
            411 => 'Comprimento necessário',
            412 => 'Pré-condição falhou',
            413 => 'Corpo muito grande',
            414 => 'URI muito longa',
            415 => 'Tipo não suportado',
            416 => 'Faixa não satisfatória',
            417 => 'Expectativa falhou',
            418 => 'Sou um bule de chá',
            421 => 'Requisição mal direcionada',
            422 => 'Entidade não processável',
            423 => 'Recurso bloqueado',
            424 => 'Dependência falhou',
            425 => 'Muito cedo',
            426 => 'Upgrade necessário',
            428 => 'Pré-condição obrigatória',
            429 => 'Muitas requisições',
            431 => 'Cabeçalhos muito grandes',
            451 => 'Bloqueado por razões legais',

            // 5xx - Erros do Servidor
            500 => 'Erro interno servidor',
            501 => 'Não implementado',
            502 => 'Gateway ruim',
            503 => 'Serviço indisponível',
            504 => 'Tempo de gateway esgotado',
            505 => 'Versão HTTP não suportada',
            506 => 'Variante também negocia',
            507 => 'Armazenamento insuficiente',
            508 => 'Loop detectado',
            510 => 'Não estendido',
            511 => 'Autenticação de rede necessária',

            // Código desconhecido
            default => 'Código HTTP desconhecido',
        };
    }

    /**
     * Resposta de sucesso padronizada
     *
     * @param int $httpCode Código HTTP (padrão: 200)
     * @param string|null $message Mensagem customizada (null = mensagem padrão)
     * @param mixed $dbReturn Dados do banco
     * @param string|null $tableName Nome da tabela
     * @return JsonResponse
     */
    public static function success(
        int $httpCode = 200,
        ?string $message = null,
        $dbReturn = null,
        ?string $tableName = null
    ): JsonResponse {
        return self::response(
            success: true,
            httpCode: $httpCode,
            message: $message,
            dbReturn: $dbReturn,
            tableName: $tableName
        );
    }

    /**
     * Resposta de erro padronizada
     *
     * @param int $httpCode Código HTTP (padrão: 500)
     * @param string|null $message Mensagem customizada (null = mensagem padrão)
     * @param mixed $errors Detalhes dos erros
     * @return JsonResponse
     */
    public static function error(
        int $httpCode = 500,
        ?string $message = null,
        $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'http_code' => $httpCode,
            'message' => $message ?? self::getHttpMessage($httpCode),
        ];

        // Adiciona erros se existirem
        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        // Adiciona metadados (agora automático!)
        $metaData = self::buildMetaData();
        if (!empty($metaData)) {
            $response['meta_data'] = $metaData;
        }

        return response()->json($response, $httpCode);
    }

    /**
     * Resposta de validação com erros
     *
     * @param mixed $validator
     * @param string|null $message
     * @return JsonResponse
     */
    public static function validationError(
        $validator,
        ?string $message = null
    ): JsonResponse {
        return self::error(
            httpCode: 422,
            message: $message ?? 'Entidade não processável',
            errors: $validator->errors()
        );
    }

    /**
     * Formata os dados retornados do banco
     *
     * @param mixed $dbReturn
     * @param string|null $tableName
     * @return array
     */
    private static function formatData($dbReturn, ?string $tableName): array
    {
        // Se for uma Collection do Laravel
        if (is_object($dbReturn) && method_exists($dbReturn, 'toArray')) {
            $dbReturn = $dbReturn->toArray();
        }

        // Se for um array simples (ex: lista de colunas)
        if (is_array($dbReturn) && !self::isAssociative($dbReturn)) {
            return [
                'table' => $tableName,
                'columns' => $dbReturn,
                'total' => count($dbReturn)
            ];
        }

        // Se for um array associativo ou objeto único
        if (self::isAssociative($dbReturn)) {
            $data = [
                'table' => $tableName,
                'record' => $dbReturn,
            ];
            return $data;
        }

        // Se for uma lista de registros
        if (is_array($dbReturn)) {
            return [
                'table' => $tableName,
                'records' => $dbReturn,
                'total' => count($dbReturn)
            ];
        }

        // Retorno genérico
        return [
            'table' => $tableName,
            'data' => $dbReturn
        ];
    }

    /**
     * Constrói os metadados da resposta com captura automática da URL
     *
     * @param array $additionalMeta
     * @return array
     */
    private static function buildMetaData(array $additionalMeta = []): array
    {
        $metaData = [];

        // Captura automática da URL atual (como CodeIgniter!)
        $request = request();

        $metaData['www'] = [
            'base_url' => config('app.url'),
            'path' => $request->path(),                    // Ex: "api/v1/users/column-names"
            'method' => $request->method(),                // Ex: "GET", "POST", etc
            'full_url' => $request->fullUrl(),             // URL completa com query strings
            'segments' => $request->segments()             // Array dos segmentos: ["api", "v1", "users", "column-names"]
        ];

        // Adiciona timestamp
        $metaData['timestamp'] = now()->format('Y-m-d H:i:s');

        // Adiciona metadados adicionais
        if (!empty($additionalMeta)) {
            $metaData = array_merge($metaData, $additionalMeta);
        }

        return $metaData;
    }

    /**
     * Verifica se um array é associativo
     *
     * @param array $array
     * @return bool
     */
    private static function isAssociative(array $array): bool
    {
        if (empty($array)) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
