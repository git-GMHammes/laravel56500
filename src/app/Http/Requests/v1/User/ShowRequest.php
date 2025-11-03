<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Helpers\ApiResponseHelper;

# ShowRequest
# Form Request para validação do método show()
# Valida o ID do usuário na rota GET /api/v1/users/{id}
# @author Gustavo Hammes
# @version 1.0.0
class ShowRequest extends FormRequest
{
    # Determina se o usuário está autorizado a fazer esta requisição
    # @return bool
    public function authorize(): bool
    {
        // Por enquanto, todos podem acessar
        // Futuramente: adicionar verificação de permissões/roles
        return true;
    }

    # Prepara os dados ANTES da validação
    # Captura o ID da rota e adiciona aos dados validáveis
    # @return void
    protected function prepareForValidation(): void
    {
        // Pega o ID da rota e adiciona aos dados para validação
        $this->merge([
            'id' => $this->route('id')
        ]);
    }

    # Regras de validação para o ID
    # @return array<string, mixed>
    public function rules(): array
    {
        return [
            'id' => [
                'required',    // ID é obrigatório
                'integer',     // Deve ser um número inteiro
                'min:1',       // Deve ser maior que 0
            ],
        ];
    }

    # Mensagens personalizadas de validação em português
    # @return array<string, string>
    public function messages(): array
    {
        return [
            'id.required' => 'O ID do usuário é obrigatório',
            'id.integer' => 'O ID deve ser um número inteiro',
            'id.min' => 'O ID deve ser maior que zero',
        ];
    }

    # Customiza a resposta de erro de validação
    # Usa o ApiResponseHelper para manter o padrão da API
    # @param Validator $validator
    # @return void
    # @throws HttpResponseException
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ApiResponseHelper::validationError(
                validator: $validator,
                message: 'ID inválido. Deve ser um número inteiro positivo'
            )
        );
    }

    # Retorna apenas o ID validado
    # Útil para usar no Controller
    # @return int
    public function getValidatedId(): int
    {
        return (int) $this->validated()['id'];
    }
}
