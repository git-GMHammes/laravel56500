<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserManagementModel extends Model
{
    use SoftDeletes;

    /**
     * Define qual conexão usar (KINGHOST)
     */
    protected $connection = 'kinghost';
    /**
     * Nome da tabela no banco de dados
     */
    protected $table = 'user_management';

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
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
    ];

    /**
     * Campos que devem ser tratados como datas
     */
    protected $casts = [
        'date_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Campos ocultos (não aparecem em JSON)
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Define que o Laravel deve gerenciar created_at e updated_at
     */
    public $timestamps = true;

    /**
     * Nome da coluna de soft delete
     */
    const DELETED_AT = 'deleted_at';

    /**
     * Retorna informações detalhadas sobre as colunas da tabela
     *
     * @return array
     */
    public static function getTableColumns()
    {
        $instance = new static;
        $connection = $instance->getConnectionName();
        $tableName = $instance->getTable();
        $columns = Schema::connection($connection)->getColumnListing($tableName);

        $detailedColumns = [];

        foreach ($columns as $column) {
            $columnType = Schema::connection($connection)->getColumnType($tableName, $column);

            $detailedColumns[] = [
                'name' => $column,
                'type' => $columnType,
            ];
        }

        return [
            'table' => $tableName,
            'total_columns' => count($columns),
            'columns' => $detailedColumns,
        ];
    }

    /**
     * Retorna apenas os nomes das colunas (versão simples)
     *
     * @return array
     */
    public static function getColumnNames()
    {
        $instance = new static;
        return Schema::connection($instance->getConnectionName())->getColumnListing($instance->getTable());
    }
}
