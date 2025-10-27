<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponseHelper;
use App\Models\UserManagementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Retorna informações sobre as colunas da tabela user_management
     *
     * GET /api/v1/users/columns
     */
    public function getColumns()
    {

        try {
            $columnsInfo = UserManagementModel::getTableColumns();

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Colunas da tabela recuperadas com sucesso',
                dbReturn: $columnsInfo,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao buscar colunas da tabela: ' . $e->getMessage(), ['exception' => $e]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao buscar informações das colunas'
            );
        }
    }

    /**
     * Retorna apenas os nomes das colunas (versão simples)
     *
     * GET /api/v1/users/column-names
     */
    public function getColumnNames()
    {

        try {
            $columnNames = UserManagementModel::getColumnNames();

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Nomes das colunas recuperados com sucesso',
                dbReturn: $columnNames,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao buscar nomes das colunas: ' . $e->getMessage(), ['exception' => $e]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao buscar nomes das colunas'
            );
        }
    }

    /**
     * Cria um novo usuário no banco de dados
     *
     * POST /api/v1/users
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'cpf' => 'required|string|max:50|unique:user_management,cpf',
            'whatsapp' => 'nullable|string|max:50',
            'user' => 'required|string|max:50|unique:user_management,user',
            'password' => 'required|string|min:6|max:200',
            'profile' => 'nullable|string|max:200',
            'mail' => 'required|email|max:150|unique:user_management,mail',
            'phone' => 'nullable|string|max:50',
            'date_birth' => 'nullable|date',
            'zip_code' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:50',
        ], [
            // Mensagens personalizadas em português
            'name.required' => 'O nome é obrigatório',
            'name.max' => 'O nome não pode ter mais de 150 caracteres',
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'user.required' => 'O usuário é obrigatório',
            'user.unique' => 'Este nome de usuário já está em uso',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'mail.required' => 'O e-mail é obrigatório',
            'mail.email' => 'O e-mail deve ser válido',
            'mail.unique' => 'Este e-mail já está cadastrado',
            'date_birth.date' => 'A data de nascimento deve ser válida',
        ]);

        // Se a validação falhar
        if ($validator->fails()) {
            return ApiResponseHelper::validationError(
                validator: $validator,
                message: 'Dados inválidos para cadastro de usuário'
            );
        }

        try {
            // Filtra apenas os campos esperados
            $data = $request->only([
                'name',
                'cpf',
                'whatsapp',
                'user',
                'password',
                'profile',
                'mail',
                'phone',
                'date_birth',
                'zip_code',
                'address',
            ]);

            // Hash da senha antes de salvar
            $data['password'] = Hash::make($data['password']);

            // Criar o usuário no banco de dados
            $user = UserManagementModel::create($data);

            // Remove a senha do retorno
            $user->makeHidden(['password']);

            return ApiResponseHelper::success(
                httpCode: 201,
                message: 'Usuário criado com sucesso',
                dbReturn: $user,
                tableName: 'user_management'
            );

        } catch (\Illuminate\Database\QueryException $e) {
            // Erro específico de banco de dados
            Log::error('Erro de banco ao criar usuário: ' . $e->getMessage(), [
                'exception' => $e,
                'sql' => $e->getSql() ?? null
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao salvar usuário no banco de dados'
            );

        } catch (\Exception $e) {
            // Erro genérico
            Log::error('Erro ao criar usuário: ' . $e->getMessage(), ['exception' => $e]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao criar usuário'
            );
        }
    }
}
