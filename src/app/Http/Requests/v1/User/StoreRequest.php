<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Helpers\ApiResponseHelper;
use App\Http\Helpers\DataSanitizerHelper;

# StoreRequest
# Form Request para validação do método store()
# Valida dados para criação de usuário na rota POST /api/v1/users
# @author Gustavo Hammes
# @version 1.0.0
class StoreRequest extends FormRequest
{
    # Determina se o usuário está autorizado a fazer esta requisição
    # @return bool
    public function authorize(): bool
    {
        // Por enquanto, todos podem criar usuários
        // Futuramente: adicionar verificação de permissões/roles
        return true;
    }

    # Prepara os dados ANTES da validação
    # Sanitiza campos que possuem máscaras (CPF, telefones, CEP)
    # @return void
    protected function prepareForValidation(): void
    {
        // Sanitiza os dados antes de validar
        $sanitized = DataSanitizerHelper::sanitize($this->all());

        // Também limpa espaços extras das strings
        $sanitized = DataSanitizerHelper::cleanStrings($sanitized);

        // Substitui os dados da requisição pelos dados sanitizados
        $this->replace($sanitized);
    }

    # Regras de validação para criação de usuário
    # @return array<string, mixed>
    public function rules(): array
    {
        return [
            // Dados pessoais
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'cpf' => [
                'required',
                'string',
                'max:50',
                'unique:user_management,cpf',
            ],

            'date_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            // Contatos
            'whatsapp' => [
                'nullable',
                'string',
                'max:50',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'mail' => [
                'required',
                'email',
                'max:150',
                'unique:user_management,mail',
            ],

            // Endereço
            'zip_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
                'max:50',
            ],

            // Dados de acesso
            'user' => [
                'required',
                'string',
                'max:50',
                'unique:user_management,user',
                'regex:/^[a-zA-Z0-9_]+$/',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'max:200',
            ],

            'profile' => [
                'nullable',
                'string',
                'max:200',
            ],
        ];
    }

    # Mensagens personalizadas de validação em português
    # @return array<string, string>
    public function messages(): array
    {
        return [
            // Name
            'name.required' => 'O nome é obrigatório',
            'name.string' => 'O nome deve ser um texto',
            'name.max' => 'O nome não pode ter mais de 150 caracteres',

            // CPF
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.string' => 'O CPF deve ser um texto',
            'cpf.max' => 'O CPF não pode ter mais de 50 caracteres',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema',

            // Date Birth
            'date_birth.date' => 'A data de nascimento deve ser uma data válida',
            'date_birth.before' => 'A data de nascimento deve ser anterior a hoje',

            // WhatsApp
            'whatsapp.string' => 'O WhatsApp deve ser um texto',
            'whatsapp.max' => 'O WhatsApp não pode ter mais de 50 caracteres',

            // Phone
            'phone.string' => 'O telefone deve ser um texto',
            'phone.max' => 'O telefone não pode ter mais de 50 caracteres',

            // Mail
            'mail.required' => 'O e-mail é obrigatório',
            'mail.email' => 'O e-mail deve ser um endereço válido',
            'mail.max' => 'O e-mail não pode ter mais de 150 caracteres',
            'mail.unique' => 'Este e-mail já está cadastrado no sistema',

            // Zip Code
            'zip_code.string' => 'O CEP deve ser um texto',
            'zip_code.max' => 'O CEP não pode ter mais de 50 caracteres',

            // Address
            'address.string' => 'O endereço deve ser um texto',
            'address.max' => 'O endereço não pode ter mais de 50 caracteres',

            // User
            'user.required' => 'O nome de usuário é obrigatório',
            'user.string' => 'O nome de usuário deve ser um texto',
            'user.max' => 'O nome de usuário não pode ter mais de 50 caracteres',
            'user.unique' => 'Este nome de usuário já está em uso',
            'user.regex' => 'O nome de usuário deve conter apenas letras, números e underscore',

            // Password
            'password.required' => 'A senha é obrigatória',
            'password.string' => 'A senha deve ser um texto',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'password.max' => 'A senha não pode ter mais de 200 caracteres',

            // Profile
            'profile.string' => 'O perfil deve ser um texto',
            'profile.max' => 'O perfil não pode ter mais de 200 caracteres',
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
                message: 'Dados inválidos para cadastro de usuário'
            )
        );
    }

    # Retorna os dados validados e já sanitizados
    # Útil para usar diretamente no Service/Model
    # @return array
    public function getSanitizedData(): array
    {
        return $this->validated();
    }

    # Nomes personalizados dos atributos para as mensagens de erro
    #
    # @return array<string, string>
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'cpf' => 'CPF',
            'whatsapp' => 'WhatsApp',
            'user' => 'usuário',
            'password' => 'senha',
            'profile' => 'perfil',
            'mail' => 'e-mail',
            'phone' => 'telefone',
            'date_birth' => 'data de nascimento',
            'zip_code' => 'CEP',
            'address' => 'endereço',
        ];
    }
}
