<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Helpers\ApiResponseHelper;
use App\Http\Helpers\DataSanitizerHelper;

# UpdateRequest
# Form Request para validação do método update()
# Valida dados para atualização de usuário nas rotas PUT/PATCH /api/v1/users/{id}
# Diferenças do StoreRequest:
# - Todos os campos são opcionais (nullable)
# - Unique ignora o próprio registro
# - Valida o ID também
# @author Gustavo Hammes
# @version 1.0.0

class UpdateRequest extends FormRequest
{
    # Determina se o usuário está autorizado a fazer esta requisição
    # @return bool
    public function authorize(): bool
    {
        // Por enquanto, todos podem atualizar usuários
        // Futuramente: adicionar verificação de permissões/roles
        // Exemplo: return $this->user()->can('update', User::class);
        return true;
    }

    # Prepara os dados ANTES da validação
    # 1. Captura o ID da rota
    # 2. Sanitiza campos que possuem máscaras (CPF, telefones, CEP)
    # 3. Limpa espaços extras das strings
    # @return void
    protected function prepareForValidation(): void
    {
        // 1. Captura o ID da rota e adiciona aos dados
        $this->merge([
            'id' => $this->route('id')
        ]);

        // 2. Sanitiza os dados antes de validar
        $sanitized = DataSanitizerHelper::sanitize($this->all());

        // 3. Limpa espaços extras das strings
        $sanitized = DataSanitizerHelper::cleanStrings($sanitized);

        // Substitui os dados da requisição pelos dados sanitizados
        $this->replace($sanitized);
    }

    # Regras de validação para atualização de usuário
    # IMPORTANTE: Todos os campos são OPCIONAIS (nullable)
    # A validação unique ignora o próprio registro usando o ID
    # @return array<string, mixed>
    public function rules(): array
    {
        // Pega o ID da rota para usar nas regras de unique
        $userId = $this->route('id');

        return [
            // Validação do ID (da rota)
            'id' => [
                'required',
                'integer',
                'min:1',
            ],

            // Dados pessoais (todos opcionais)
            'name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'cpf' => [
                'nullable',
                'string',
                'max:50',
                "unique:user_management,cpf,{$userId}",
            ],

            'date_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            // Contatos (opcionais)
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
                'nullable',
                'email',
                'max:150',
                "unique:user_management,mail,{$userId}",
            ],

            // Endereço (opcionais)
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

            // Dados de acesso (opcionais)
            'user' => [
                'nullable',
                'string',
                'max:50',
                "unique:user_management,user,{$userId}",
                'regex:/^[a-zA-Z0-9_]+$/',
            ],

            'password' => [
                'nullable',
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
    #
    # @return array<string, string>

    public function messages(): array
    {
        return [
            // ID
            'id.required' => 'O ID do usuário é obrigatório',
            'id.integer' => 'O ID deve ser um número inteiro',
            'id.min' => 'O ID deve ser maior que zero',

            // Name
            'name.string' => 'O nome deve ser um texto',
            'name.max' => 'O nome não pode ter mais de 150 caracteres',

            // CPF
            'cpf.string' => 'O CPF deve ser um texto',
            'cpf.max' => 'O CPF não pode ter mais de 50 caracteres',
            'cpf.unique' => 'Este CPF já está cadastrado em outro usuário',

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
            'mail.email' => 'O e-mail deve ser um endereço válido',
            'mail.max' => 'O e-mail não pode ter mais de 150 caracteres',
            'mail.unique' => 'Este e-mail já está cadastrado em outro usuário',

            // Zip Code
            'zip_code.string' => 'O CEP deve ser um texto',
            'zip_code.max' => 'O CEP não pode ter mais de 50 caracteres',

            // Address
            'address.string' => 'O endereço deve ser um texto',
            'address.max' => 'O endereço não pode ter mais de 50 caracteres',

            // User
            'user.string' => 'O nome de usuário deve ser um texto',
            'user.max' => 'O nome de usuário não pode ter mais de 50 caracteres',
            'user.unique' => 'Este nome de usuário já está em uso por outro usuário',
            'user.regex' => 'O nome de usuário deve conter apenas letras, números e underscore',

            // Password
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
    #
    # @param Validator $validator
    # @return void
    #
    # @throws HttpResponseException

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ApiResponseHelper::validationError(
                validator: $validator,
                message: 'Dados inválidos para atualização de usuário'
            )
        );
    }

    # Retorna os dados validados e já sanitizados
    # Remove o campo 'id' dos dados (não deve ser atualizado)
    # @return array
    public function getSanitizedData(): array
    {
        $validated = $this->validated();

        // Remove o ID dos dados (não queremos atualizar o ID)
        unset($validated['id']);

        return $validated;
    }

    # Retorna o ID validado
    # Útil para usar no Controller
    # @return int
    public function getValidatedId(): int
    {
        return (int) $this->validated()['id'];
    }

    # Verifica se a senha foi enviada na requisição
    # @return bool
    public function hasPassword(): bool
    {
        return $this->has('password') && !empty($this->input('password'));
    }

    # Nomes personalizados dos atributos para as mensagens de erro
    # @return array<string, string>
    public function attributes(): array
    {
        return [
            'id' => 'ID',
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
