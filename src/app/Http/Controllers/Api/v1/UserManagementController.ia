<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponseHelper;
use App\Http\Requests\v1\User\ShowRequest;
use App\Http\Requests\v1\User\StoreRequest;
use App\Http\Requests\v1\User\UpdateRequest;
use App\Services\v1\User\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

# UserManagementController
# Controller para gerenciamento de usuários
# Responsabilidade: Apenas ORQUESTRAR (receber → delegar → responder)
# Camadas utilizadas:
# - Requests: Validação automática de dados
# - Service: Lógica de negócio
# - Helper: Sanitização de dados
# - Model: Persistência no banco
# @author Gustavo Hammes
# @version 2.1.0 (Adicionados métodos para trabalhar com registros deletados)
class UserManagementController extends Controller
{
    # Service de gerenciamento de usuários
    # @var UserManagementService
    protected UserManagementService $userService;

    # Construtor com injeção de dependência
    # @param UserManagementService $userService
    public function __construct(UserManagementService $userService)
    {
        $this->userService = $userService;
    }

    # Lista todos os usuários com paginação
    # GET /api/v1/users
    # Query params: ?page=1&limit=15
    # @param Request $request
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->input('limit', 15);

            // Service já valida o limite internamente
            $users = $this->userService->getAllUsers($limit);

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuários recuperados com sucesso',
                dbReturn: $users,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao listar usuários', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao listar usuários'
            );
        }
    }

    # Lista TODOS os usuários com paginação (ATIVOS + DELETADOS)
    # GET /api/v1/users/with-trashed
    # Query params: ?page=1&limit=15
    # @param Request $request
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function indexWithTrashed(Request $request)
    {
        try {
            $limit = (int) $request->input('limit', 15);

            // Busca todos os usuários (ativos e deletados)
            $users = $this->userService->getAllUsersWithTrashed($limit);

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Todos os usuários recuperados (ativos e deletados)',
                dbReturn: $users,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao listar usuários com deletados', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao listar usuários'
            );
        }
    }

    # Lista APENAS usuários DELETADOS com paginação
    # GET /api/v1/users/only-trashed
    # Query params: ?page=1&limit=15
    # @param Request $request
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function indexOnlyTrashed(Request $request)
    {
        try {
            $limit = (int) $request->input('limit', 15);

            // Busca apenas usuários deletados
            $users = $this->userService->getOnlyDeletedUsers($limit);

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuários deletados recuperados com sucesso',
                dbReturn: $users,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao listar usuários deletados', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao listar usuários deletados'
            );
        }
    }

    # Exibe um usuário específico
    # GET /api/v1/users/{id}
    # @param ShowRequest $request Request com validação automática do ID
    # @param int $id ID do usuário
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function show(ShowRequest $request, $id)
    {
        try {
            // ID já foi validado automaticamente pelo ShowRequest!
            $user = $this->userService->getUserById($id);

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
            Log::error('Erro ao buscar usuário', [
                'exception' => $e->getMessage(),
                'id' => $id
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao buscar usuário'
            );
        }
    }

    # Exibe um usuário específico (ATIVO OU DELETADO)
    # GET /api/v1/users/{id}/with-trashed
    # @param ShowRequest $request Request com validação automática do ID
    # @param int $id ID do usuário
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function showWithTrashed(ShowRequest $request, $id)
    {
        try {
            // ID já foi validado automaticamente pelo ShowRequest!
            $user = $this->userService->getUserByIdWithTrashed($id);

            if (!$user) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado'
                );
            }

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuário recuperado com sucesso (incluindo deletados)',
                dbReturn: $user,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao buscar usuário (incluindo deletados)', [
                'exception' => $e->getMessage(),
                'id' => $id
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao buscar usuário'
            );
        }
    }

    # Cria um novo usuário
    # POST /api/v1/users
    # @param StoreRequest $request Request com validação e sanitização automática
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function store(StoreRequest $request)
    {
        try {
            // Dados já validados e sanitizados pelo StoreRequest!
            // Service fará o hash da senha automaticamente!
            $user = $this->userService->createUser($request->getSanitizedData());

            return ApiResponseHelper::success(
                httpCode: 201,
                message: 'Usuário criado com sucesso',
                dbReturn: $user,
                tableName: 'user_management'
            );

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Erro de banco ao criar usuário', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql() ?? null
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao salvar usuário no banco de dados'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário', [
                'exception' => $e->getMessage()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao criar usuário'
            );
        }
    }

    # Atualiza um usuário existente
    # PUT/PATCH /api/v1/users/{id}
    # @param UpdateRequest $request Request com validação e sanitização automática
    # @param int $id ID do usuário
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function update(UpdateRequest $request, $id)
    {
        try {
            // ID e dados já validados e sanitizados pelo UpdateRequest!
            // Service fará o hash da senha se ela foi enviada!
            $user = $this->userService->updateUser($id, $request->getSanitizedData());

            if (!$user) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado'
                );
            }

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuário atualizado com sucesso',
                dbReturn: $user,
                tableName: 'user_management'
            );

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Erro de banco ao atualizar usuário', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql() ?? null,
                'id' => $id
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao atualizar usuário no banco de dados'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar usuário', [
                'exception' => $e->getMessage(),
                'id' => $id
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao atualizar usuário'
            );
        }
    }

    # Remove um usuário (SOFT DELETE - Exclusão Lógica)
    # DELETE /api/v1/users/{id}
    # Preenche o campo deleted_at, mas mantém o registro no banco
    # @param int $id ID do usuário
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function delete($id)
    {
        try {
            $deleted = $this->userService->deleteUser($id);

            if (!$deleted) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado ou já foi removido'
                );
            }

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Usuário removido com sucesso (exclusão lógica)',
                dbReturn: [
                    'id' => $id,
                    'status' => 'soft_deleted'
                ],
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao remover usuário (soft delete)', [
                'exception' => $e->getMessage(),
                'id' => $id
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao remover usuário'
            );
        }
    }

    # Remove um usuário PERMANENTEMENTE (HARD DELETE)
    # DELETE /api/v1/users/{id}/force
    # Remove o registro definitivamente do banco de dados
    # ⚠️ ATENÇÃO: Esta ação é irreversível!
    # @param int $id ID do usuário
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function destroy($id)
    {
        try {
            $deleted = $this->userService->forceDeleteUser($id);

            if (!$deleted) {
                return ApiResponseHelper::error(
                    httpCode: 404,
                    message: 'Usuário não encontrado no banco de dados'
                );
            }

            return ApiResponseHelper::success(
                httpCode: 200,
                message: '⚠️ Usuário removido PERMANENTEMENTE do banco de dados',
                dbReturn: [
                    'id' => $id,
                    'status' => 'permanently_deleted'
                ],
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao remover usuário permanentemente', [
                'exception' => $e->getMessage(),
                'id' => $id
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao remover usuário permanentemente'
            );
        }
    }

    # Remove PERMANENTEMENTE todos os registros soft deleted
    # DELETE /api/v1/users/clear
    # ⚠️ ATENÇÃO: Remove TODOS os registros marcados como deletados!
    # Esta ação é IRREVERSÍVEL!
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function clear()
    {
        try {
            $totalCleared = $this->userService->clearDeletedUsers();

            if ($totalCleared === 0) {
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

            return ApiResponseHelper::success(
                httpCode: 200,
                message: "🧹 Limpeza concluída! {$totalCleared} registro(s) removido(s) permanentemente do banco",
                dbReturn: [
                    'total_cleared' => $totalCleared,
                    'status' => 'cleanup_completed'
                ],
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao executar limpeza (clear)', [
                'exception' => $e->getMessage()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao executar limpeza do banco de dados'
            );
        }
    }

    # Retorna informações detalhadas sobre as colunas da tabela
    # GET /api/v1/users/columns
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function getColumns()
    {
        try {
            $columnsInfo = $this->userService->getTableColumns();

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Colunas da tabela recuperadas com sucesso',
                dbReturn: $columnsInfo,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao buscar colunas da tabela', [
                'exception' => $e->getMessage()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao buscar informações das colunas'
            );
        }
    }

    # Retorna apenas os nomes das colunas da tabela
    # GET /api/v1/users/column-names
    # @return \Illuminate\Http\JsonResponse
    # @author Gustavo Hammes
    public function getColumnNames()
    {
        try {
            $columnNames = $this->userService->getColumnNames();

            return ApiResponseHelper::success(
                httpCode: 200,
                message: 'Nomes das colunas recuperados com sucesso',
                dbReturn: $columnNames,
                tableName: 'user_management'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao buscar nomes das colunas', [
                'exception' => $e->getMessage()
            ]);

            return ApiResponseHelper::error(
                httpCode: 500,
                message: 'Erro ao buscar nomes das colunas'
            );
        }
    }
}
