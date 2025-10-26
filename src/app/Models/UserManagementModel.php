<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserManagementModel  extends Model
{
    use SoftDeletes;

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
}
