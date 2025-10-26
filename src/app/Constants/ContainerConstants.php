<?php

namespace App\Constants;

class ContainerConstants
{
    // ==========================================
    // AMBIENTE: HOMOLOGAÇÃO (HML)
    // ==========================================
    public const HML_API_ENDPOINT = 'https://api-hml.seudominio.com';
    public const HML_DATABASE_PREFIX = 'hml_';
    public const HML_DEBUG_MODE = true;

    // ==========================================
    // AMBIENTE: PRODUÇÃO (PRD)
    // ==========================================
    public const PRD_API_ENDPOINT = 'https://api.seudominio.com';
    public const PRD_DATABASE_PREFIX = 'prd_';
    public const PRD_DEBUG_MODE = false;

    // ==========================================
    // AMBIENTE: TESTE (TEST)
    // ==========================================
    public const TEST_API_ENDPOINT = 'https://api-test.seudominio.com';
    public const TEST_DATABASE_PREFIX = 'test_';
    public const TEST_DEBUG_MODE = true;

    // ==========================================
    // AMBIENTE: SEGURANÇA (SECURITY)
    // ==========================================
    public const SECURITY_API_ENDPOINT = 'https://api-security.seudominio.com';
    public const SECURITY_DATABASE_PREFIX = 'sec_';
    public const SECURITY_DEBUG_MODE = false;

    // ==========================================
    // TIPOS DE RESPOSTAS HTTP
    // ==========================================
    public const RESPONSE_SUCCESS = 200;
    public const RESPONSE_CREATED = 201;
    public const RESPONSE_BAD_REQUEST = 400;
    public const RESPONSE_UNAUTHORIZED = 401;
    public const RESPONSE_FORBIDDEN = 403;
    public const RESPONSE_NOT_FOUND = 404;
    public const RESPONSE_INTERNAL_ERROR = 500;

    // ==========================================
    // DECISÕES DE AMBIENTE
    // ==========================================
    public const ENV_HOMOLOGACAO = 'homologacao';
    public const ENV_PRODUCAO = 'producao';
    public const ENV_TESTE = 'teste';
    public const ENV_SECURITY = 'security';
}
