<?php

namespace App\Services\v1\User;

use App\Models\v1\UserManagementModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

# UserManagementService
# Service Layer para gerenciamento de usuários
# Concentra toda a lógica de negócio relacionada a usuários
# Responsabilidades:
# - Aplicar regras de negócio
# - Hash de senhas
# - Chamadas ao Model
# - Tratamento de erros de negócio
# @author Gustavo Hammes
# @version 1.0.0
class UserManagementService
{
    # Model de usuário
    # @var UserManagementModel
    protected UserManagementModel $model;

    # Construtor do Service
    # @param UserManagementModel $model
    public function __construct(UserManagementModel $model)
    {
        $this->model = $model;
    }

    # Lista todos os usuários com paginação
    # Retorna apenas usuários ATIVOS (sem soft delete)
    # @param int $limit Quantidade de registros por página (1-100)
    # @return LengthAwarePaginator
    # @example
    # $users = $service->getAllUsers(15);
    # // Retorna 15 usuários por página
    public function getAllUsers(int $limit = 15): LengthAwarePaginator
    {
        // Valida o limite (entre 1 e 100)
        $limit = max(1, min(100, $limit));

        // Retorna usuários paginados (apenas ativos)
        return $this->model->paginate($limit);
    }

    # Busca um usuário específico por ID
    # Retorna apenas se o usuário estiver ATIVO (não soft deleted)
    # @param int $id ID do usuário
    # @return UserManagementModel|null Usuário encontrado ou null
    # @example
    # $user = $service->getUserById(5);
    # if ($user) {
    #     echo $user->name;
    # }

    public function getUserById(int $id): ?UserManagementModel
    {
        return $this->model->find($id);
    }

    # Cria um novo usuário
    # Aplica as seguintes regras de negócio:
    # - Hash da senha antes de salvar
    # - Valida se dados obrigatórios estão presentes
    # @param array $data Dados do usuário (já validados pelo Request)
    # @return UserManagementModel Usuário criado
    # @throws \Exception Se houver erro ao criar usuário
    # @example
    # $user = $service->createUser([
    #     'name' => 'João Silva',
    #     'cpf' => '12345678900',
    #     'user' => 'joaosilva',
    #     'password' => 'senha123',
    #     'mail' => 'joao@email.com'
    # ]);
    public function createUser(array $data): UserManagementModel
    {
        try {
            // Regra de negócio: Hash da senha
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Cria o usuário no banco de dados
            $user = $this->model->create($data);

            // Log de sucesso
            Log::info('Usuário criado com sucesso', [
                'user_id' => $user->id,
                'user' => $user->user,
                'mail' => $user->mail
            ]);

            return $user;

        } catch (\Exception $e) {
            // Log de erro
            Log::error('Erro ao criar usuário no Service', [
                'exception' => $e->getMessage(),
                'data' => array_diff_key($data, ['password' => '']) // Remove senha do log
            ]);

            throw $e;
        }
    }

    # Atualiza um usuário existente
    # Aplica as seguintes regras de negócio:
    # - Hash da senha se ela foi enviada
    # - Apenas campos enviados são atualizados
    # @param int $id ID do usuário a ser atualizado
    # @param array $data Dados a serem atualizados (já validados)
    # @return UserManagementModel|null Usuário atualizado ou null se não encontrado
    # @throws \Exception Se houver erro ao atualizar
    # @example
    # $user = $service->updateUser(5, [
    #     'name' => 'João Silva Atualizado',
    #     'mail' => 'novoemail@email.com'
    # ]);
    public function updateUser(int $id, array $data): ?UserManagementModel
    {
        try {
            // Busca o usuário
            $user = $this->model->find($id);

            if (!$user) {
                return null;
            }

            // Regra de negócio: Hash da senha se foi enviada
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // Remove senha do array se veio vazia
                unset($data['password']);
            }

            // Atualiza o usuário
            $user->update($data);

            // Recarrega o usuário do banco (fresh data)
            $user->refresh();

            // Log de sucesso
            Log::info('Usuário atualizado com sucesso', [
                'user_id' => $user->id,
                'updated_fields' => array_keys(array_diff_key($data, ['password' => '']))
            ]);

            return $user;

        } catch (\Exception $e) {
            // Log de erro
            Log::error('Erro ao atualizar usuário no Service', [
                'exception' => $e->getMessage(),
                'user_id' => $id,
                'data' => array_diff_key($data, ['password' => ''])
            ]);

            throw $e;
        }
    }

    # Remove um usuário (SOFT DELETE - Exclusão Lógica)
    # O usuário é marcado como deletado (deleted_at preenchido)
    # mas permanece no banco de dados
    # @param int $id ID do usuário a ser removido
    # @return bool True se removido com sucesso, False se não encontrado
    # @throws \Exception Se houver erro ao deletar
    # @example
    # $deleted = $service->deleteUser(5);
    # if ($deleted) {
    #     echo "Usuário removido";
    # }
    public function deleteUser(int $id): bool
    {
        try {
            // Busca APENAS usuários ativos (não soft deleted)
            $user = $this->model->find($id);

            if (!$user) {
                return false;
            }

            // SOFT DELETE - Marca como deletado
            $user->delete();

            // Log de sucesso
            Log::info('Usuário removido (soft delete)', [
                'user_id' => $id,
                'deleted_at' => $user->deleted_at
            ]);

            return true;

        } catch (\Exception $e) {
            // Log de erro
            Log::error('Erro ao remover usuário (soft delete) no Service', [
                'exception' => $e->getMessage(),
                'user_id' => $id
            ]);

            throw $e;
        }
    }

    # Remove um usuário PERMANENTEMENTE (HARD DELETE)
    # O registro é removido definitivamente do banco de dados
    # Esta ação é IRREVERSÍVEL!
    # @param int $id ID do usuário a ser removido permanentemente
    # @return bool True se removido, False se não encontrado
    # @throws \Exception Se houver erro ao deletar
    # @example
    # $deleted = $service->forceDeleteUser(5);
    # if ($deleted) {
    #     echo "Usuário removido PERMANENTEMENTE";
    # }
    public function forceDeleteUser(int $id): bool
    {
        try {
            // Busca usuário incluindo os soft deleted
            $user = $this->model->withTrashed()->find($id);

            if (!$user) {
                return false;
            }

            // HARD DELETE - Remove definitivamente
            $user->forceDelete();

            // Log de sucesso
            Log::warning('Usuário removido PERMANENTEMENTE (hard delete)', [
                'user_id' => $id,
                'user' => $user->user,
                'mail' => $user->mail
            ]);

            return true;

        } catch (\Exception $e) {
            // Log de erro
            Log::error('Erro ao remover usuário permanentemente no Service', [
                'exception' => $e->getMessage(),
                'user_id' => $id
            ]);

            throw $e;
        }
    }

    # Remove PERMANENTEMENTE todos os registros soft deleted
    # ATENÇÃO: Remove TODOS os registros marcados como deletados!
    # Esta ação é IRREVERSÍVEL!
    # @return int Quantidade de registros removidos
    # @throws \Exception Se houver erro ao limpar
    # @example
    # $count = $service->clearDeletedUsers();
    # echo "Removidos {$count} usuários";
    public function clearDeletedUsers(): int
    {
        try {
            // Busca APENAS registros soft deleted
            $softDeletedUsers = $this->model->onlyTrashed()->get();

            // Se não houver registros para limpar
            if ($softDeletedUsers->isEmpty()) {
                return 0;
            }

            // Conta quantos serão removidos
            $totalToDelete = $softDeletedUsers->count();

            // Remove PERMANENTEMENTE todos os soft deleted
            $this->model->onlyTrashed()->forceDelete();

            // Log de sucesso
            Log::warning('Limpeza de usuários soft deleted realizada', [
                'total_cleared' => $totalToDelete
            ]);

            return $totalToDelete;

        } catch (\Exception $e) {
            // Log de erro
            Log::error('Erro ao limpar usuários deletados no Service', [
                'exception' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    # Retorna informações detalhadas sobre as colunas da tabela
    # @return array Informações das colunas
    # @example
    # $columns = $service->getTableColumns();
    # // Retorna: ['table' => 'user_management', 'total_columns' => 14, 'columns' => [...]]
    public function getTableColumns(): array
    {
        return $this->model->getTableColumns();
    }

    # Retorna apenas os nomes das colunas da tabela
    # @return array Lista de nomes das colunas
    # @example
    # $names = $service->getColumnNames();
    # // Retorna: ['id', 'name', 'cpf', 'user', ...]
    public function getColumnNames(): array
    {
        return $this->model->getColumnNames();
    }

    # Verifica se um usuário existe (apenas ativos)
    #
    # @param int $id ID do usuário
    # @return bool True se existe e está ativo
    #
    # @example
    # if ($service->userExists(5)) {
    #     echo "Usuário existe";
    # }

    public function userExists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    # Busca usuário por CPF
    # @param string $cpf CPF do usuário (já sanitizado)
    # @return UserManagementModel|null
    # @example
    # $user = $service->findByCpf('12345678900');
    public function findByCpf(string $cpf): ?UserManagementModel
    {
        return $this->model->where('cpf', $cpf)->first();
    }

    # Busca usuário por email
    # @param string $mail Email do usuário
    # @return UserManagementModel|null
    # @example
    # $user = $service->findByMail('joao@email.com');
    public function findByMail(string $mail): ?UserManagementModel
    {
        return $this->model->where('mail', $mail)->first();
    }

    # Busca usuário por username
    # @param string $user Username do usuário
    # @return UserManagementModel|null
    # @example
    # $user = $service->findByUsername('joaosilva');
    public function findByUsername(string $user): ?UserManagementModel
    {
        return $this->model->where('user', $user)->first();
    }

    # Conta total de usuários ativos
    # @return int Total de usuários
    # @example
    # $total = $service->countActiveUsers();
    # echo "Total: {$total} usuários";
    public function countActiveUsers(): int
    {
        return $this->model->count();
    }

    # Conta total de usuários deletados (soft delete)
    # @return int Total de usuários deletados
    # @example
    # $total = $service->countDeletedUsers();
    # echo "Deletados: {$total}";
    public function countDeletedUsers(): int
    {
        return $this->model->onlyTrashed()->count();
    }
}
