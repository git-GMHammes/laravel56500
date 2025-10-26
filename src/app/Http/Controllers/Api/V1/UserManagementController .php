<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserManagementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Store a newly created resource in storage.
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
        ]);

        // Se a validação falhar
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Filtra apenas os campos esperados para evitar mass assignment indesejado
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

            // Hash da senha
            $data['password'] = Hash::make($data['password']);

            // Criar o usuário usando a model correta
            $user = UserManagementModel::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Usuário criado com sucesso',
                'data' => $user
            ], 201);

        } catch (\Exception $e) {
            // Logar a exceção para investigação sem vazar detalhes para o cliente
            Log::error('Erro ao criar usuário: '.$e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar usuário'
            ], 500);
        }
    }
}
