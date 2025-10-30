<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponseHelper;
use App\Models\v1\UserManagementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    # GET /api/v1/users
    # Query params: ?page=1&limit=15
    # Autor: Gustavo Hammes
    # @param Request $request
    public function index(Request $request)
    {
        try {
            // Pega o limite da query string ou usa 15 como padrão
            $limit = $request->input('limit', 15);

            // Valida se o limite é um número válido (entre 1 e 100)
            if (!is_numeric($limit) || $limit < 1 || $limit > 100) {
                $limit = 15;
            }

            // Busca os usuários com paginação
            $users = UserManagementModel::paginate($limit);

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuários recuperados com sucesso',
                dbReturn: $users,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao listar usuários: ' . $e->getMessage(), ['exception' => $e]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao listar usuários'
            );
        }
    }

    # GET /api/v1/users/{id}
    # Autor: Gustavo Hammes
    # @param int $id
    public function show($id)
    {
        try {
            // Busca o usuário pelo ID
            $user = UserManagementModel::find($id);

            // Se não encontrar o usuário
            if (!$user) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado'
                );
            }

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuário recuperado com sucesso',
                dbReturn: $user,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao buscar usuário: ' . $e->getMessage(), ['exception' => $e]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao buscar usuário'
            );
        }
    }

    # POST /api/v1/users
    # Autor: Gustavo Hammes
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

    # PUT /api/v1/users/{id}
    # Autor: Gustavo Hammes
    # @param Request $request
    # @param int $id
    public function update(Request $request, $id)
    {
        try {
            // Busca o usuário pelo ID
            $user = UserManagementModel::find($id);

            // Se não encontrar o usuário
            if (!$user) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado'
                );
            }

            // Validação dos dados (sem obrigatoriedade e com unique ignorando o próprio usuário)
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:150',
                'cpf' => 'nullable|string|max:50|unique:user_management,cpf,' . $id,
                'whatsapp' => 'nullable|string|max:50',
                'user' => 'nullable|string|max:50|unique:user_management,user,' . $id,
                'password' => 'nullable|string|min:6|max:200',
                'profile' => 'nullable|string|max:200',
                'mail' => 'nullable|email|max:150|unique:user_management,mail,' . $id,
                'phone' => 'nullable|string|max:50',
                'date_birth' => 'nullable|date',
                'zip_code' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:50',
            ], [
                // Mensagens personalizadas em português
                'name.max' => 'O nome não pode ter mais de 150 caracteres',
                'cpf.unique' => 'Este CPF já está cadastrado',
                'user.unique' => 'Este nome de usuário já está em uso',
                'password.min' => 'A senha deve ter no mínimo 6 caracteres',
                'mail.email' => 'O e-mail deve ser válido',
                'mail.unique' => 'Este e-mail já está cadastrado',
                'date_birth.date' => 'A data de nascimento deve ser válida',
            ]);

            // Se a validação falhar
            if ($validator->fails()) {
                return ApiResponseHelper::validationError(
                    validator: $validator,
                    message: 'Dados inválidos para atualização de usuário'
                );
            }

            // Filtra apenas os campos que foram enviados
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

            // Se a senha foi enviada, faz o hash
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Atualiza o usuário
            $user->update($data);

            // Remove a senha do retorno
            $user->makeHidden(['password']);

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuário atualizado com sucesso',
                dbReturn: $user,
                tableName: 'user_management'
            );

        } catch (\Illuminate\Database\QueryException $e) {
            // Erro específico de banco de dados
            Log::error('Erro de banco ao atualizar usuário: ' . $e->getMessage(), [
                'exception' => $e,
                'sql' => $e->getSql() ?? null
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao atualizar usuário no banco de dados'
            );

        } catch (\Exception $e) {
            // Erro genérico
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage(), ['exception' => $e]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao atualizar usuário'
            );
        }
    }

    # DELETE /api/v1/users/{id} (SOFT DELETE - Exclusão Lógica)
    # Autor: Gustavo Hammes
    # @param int $id
    public function delete($id)
    {
        try {
            // Busca APENAS usuários ativos (deleted_at = NULL)
            $user = UserManagementModel::find($id);

            // Se não encontrar o usuário ativo
            if (!$user) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado ou já foi removido'
                );
            }

            // SOFT DELETE - Executa: UPDATE user_management SET deleted_at = NOW() WHERE id = ?
            // O registro PERMANECE no banco, apenas fica "marcado" como deletado
            $user->delete();

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuário removido com sucesso (exclusão lógica)',
                dbReturn: [
                    'id' => $user->id,
                    'deleted_at' => $user->deleted_at,
                    'status' => 'soft_deleted'
                ],
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao remover usuário (soft delete): ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao remover usuário'
            );
        }
    }

    # DELETE /api/v1/users/{id}/force (HARD DELETE - Remove do banco)
    # Autor: Gustavo Hammes
    # @param int $id
    public function destroy($id)
    {
        try {
            // Busca o usuário incluindo os que já foram soft deleted
            // withTrashed() permite encontrar registros com deleted_at preenchido
            $user = UserManagementModel::withTrashed()->find($id);

            // Se não encontrar o usuário nem nos deletados
            if (!$user) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado no banco de dados'
                );
            }

            // Guarda o ID antes de deletar (pois após o delete o objeto não existe mais)
            $userId = $user->id;

            // HARD DELETE - Executa: DELETE FROM user_management WHERE id = ?
            // O registro é PERMANENTEMENTE removido do banco de dados
            $user->forceDelete();

            return ApiResponseHelper::success(
                httpCode: 200,
                message: '⚠️ Usuário removido PERMANENTEMENTE do banco de dados',
                dbReturn: [
                    'id' => $userId,
                    'status' => 'permanently_deleted'
                ],
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao remover usuário permanentemente: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao remover usuário permanentemente'
            );
        }
    }

    # DELETE /api/v1/clear (Remove PERMANENTEMENTE todos os registros soft deleted)
    # Autor: Gustavo Hammes
    public function clear()
    {
        try {
            // Busca APENAS os registros soft deleted (deleted_at IS NOT NULL)
            $softDeletedUsers = UserManagementModel::onlyTrashed()->get();

            // Se não houver registros para limpar
            if ($softDeletedUsers->isEmpty()) {
                return ApiResponseHelper::success(
                    httpCode: 200,
                    message: 'Nenhum registro para limpar. Banco já está limpo!',
                    dbReturn: [
                        'total_cleared' => 0,
                        'status' => 'nothing_to_clear'
                    ],
                    tableName: 'user_management'
                );
            }

            // Conta quantos registros serão removidos
            $totalToDelete = $softDeletedUsers->count();

            // Remove PERMANENTEMENTE todos os registros soft deleted
            // Executa: DELETE FROM user_management WHERE deleted_at IS NOT NULL
            UserManagementModel::onlyTrashed()->forceDelete();

            return ApiResponseHelper::success(
                httpCode: 200,
                message: "🧹 Limpeza concluída! {$totalToDelete} registro(s) removido(s) permanentemente do banco",
                dbReturn: [
                    'total_cleared' => $totalToDelete,
                    'status' => 'cleanup_completed'
                ],
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao executar limpeza (clear): ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao executar limpeza do banco de dados'
            );
        }
    }

    # GET /api/v1/users/columns
    # Autor: Gustavo Hammes
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

    # GET /api/v1/users/column-names
    # Autor: Gustavo Hammes
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
}
