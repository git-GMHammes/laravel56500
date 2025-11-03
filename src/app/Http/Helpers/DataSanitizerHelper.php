<?php

namespace App\Http\Helpers;

# DataSanitizerHelper
# Helper para sanitização e limpeza de dados de entrada
# Remove máscaras de formatação (CPF, telefone, CEP, etc)
# @author Gustavo Hammes
# @version 1.0.0
class DataSanitizerHelper
{
    # Campos que devem ter máscaras removidas
    const FIELDS_TO_SANITIZE = [
        'cpf',
        'whatsapp',
        'phone',
        'zip_code',
    ];

    # Remove TODOS os caracteres não numéricos de uma string
    # @param string|null $value Valor a ser sanitizado
    # @return string|null String apenas com números ou null
    # @example
    # removeMask('123.456.789-00') -> '12345678900'
    # removeMask('(11) 98888-7777') -> '11988887777'
    # removeMask('12345-678') -> '12345678'
    public static function removeMask(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Remove tudo que não seja número
        return preg_replace('/\D/', '', $value);
    }

    # Remove máscara de CPF
    # @param string|null $cpf CPF com ou sem máscara
    # @return string|null CPF apenas com números
    # @example
    # sanitizeCpf('123.456.789-00') -> '12345678900'
    # sanitizeCpf('12345678900') -> '12345678900'
    public static function sanitizeCpf(?string $cpf): ?string
    {
        return self::removeMask($cpf);
    }

    # Remove máscara de telefone/WhatsApp
    # @param string|null $phone Telefone com ou sem máscara
    # @return string|null Telefone apenas com números
    # @example
    # sanitizePhone('(11) 98888-7777') -> '11988887777'
    # sanitizePhone('11 98888-7777') -> '11988887777'
    # sanitizePhone('(11)988887777') -> '11988887777'
    public static function sanitizePhone(?string $phone): ?string
    {
        return self::removeMask($phone);
    }

    # Remove máscara de CEP
    # @param string|null $zipCode CEP com ou sem máscara
    # @return string|null CEP apenas com números
    # @example
    # sanitizeZipCode('12345-678') -> '12345678'
    # sanitizeZipCode('12345678') -> '12345678'
    public static function sanitizeZipCode(?string $zipCode): ?string
    {
        return self::removeMask($zipCode);
    }

    # Sanitiza um array de dados removendo máscaras dos campos específicos
    # @param array $data Array de dados a serem sanitizados
    # @return array Array com dados sanitizados
    # @example
    #
    # sanitize([
    #     'name' => 'João Silva',
    #     'cpf' => '123.456.789-00',
    #     'phone' => '(11) 98888-7777',
    #     'zip_code' => '12345-678'
    # ])
    #
    # Retorna:
    # [
    #     'name' => 'João Silva',
    #     'cpf' => '12345678900',
    #     'phone' => '11988887777',
    #     'zip_code' => '12345678'
    # ]
    public static function sanitize(array $data): array
    {
        $sanitized = $data;

        foreach (self::FIELDS_TO_SANITIZE as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = self::removeMask($sanitized[$field]);
            }
        }

        return $sanitized;
    }

    # Sanitiza apenas os campos especificados de um array
    # @param array $data Array de dados
    # @param array $fields Lista de campos a serem sanitizados
    # @return array Array com campos especificados sanitizados
    # @example
    # sanitizeFields(
    #     ['cpf' => '123.456.789-00', 'name' => 'João'],
    #     ['cpf']
    # ) -> ['cpf' => '12345678900', 'name' => 'João']
    public static function sanitizeFields(array $data, array $fields): array
    {
        $sanitized = $data;

        foreach ($fields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = self::removeMask($sanitized[$field]);
            }
        }

        return $sanitized;
    }

    # Remove espaços extras e trim em uma string
    # @param string|null $value String a ser limpa
    # @return string|null String sem espaços extras
    # @example
    # cleanString('  João   Silva  ') -> 'João Silva'
    public static function cleanString(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Remove espaços do início e fim
        $value = trim($value);

        // Remove espaços múltiplos internos
        $value = preg_replace('/\s+/', ' ', $value);

        return $value;
    }

    # Sanitiza todos os campos de texto (trim e remove espaços extras)
    # @param array $data Array de dados
    # @return array Array com strings limpas
    public static function cleanStrings(array $data): array
    {
        $cleaned = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $cleaned[$key] = self::cleanString($value);
            } else {
                $cleaned[$key] = $value;
            }
        }

        return $cleaned;
    }

    # Sanitização completa: remove máscaras E limpa strings
    # @param array $data Array de dados
    # @return array Array completamente sanitizado
    # @example
    # fullSanitize([
    #     'name' => '  João Silva  ',
    #     'cpf' => '123.456.789-00',
    #     'phone' => '(11) 98888-7777'
    # ])
    #
    # Retorna:
    # [
    #     'name' => 'João Silva',
    #     'cpf' => '12345678900',
    #     'phone' => '11988887777'
    # ]
    public static function fullSanitize(array $data): array
    {
        // Primeiro remove as máscaras
        $data = self::sanitize($data);

        // Depois limpa as strings
        $data = self::cleanStrings($data);

        return $data;
    }
}
