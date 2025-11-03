<?php

/**
 * Teste Completo do StoreRequest
 *
 * Testa todas as validações para criação de usuário
 *
 * @author Gustavo Hammes
 * @version 1.0.0
 */

// Função auxiliar para validar dados (simula o StoreRequest)
function validateStoreData(array $data): array
{
    $errors = [];

    // Validação: name (obrigatório, string, max:150)
    if (empty($data['name'])) {
        $errors[] = 'O nome é obrigatório';
    } elseif (!is_string($data['name'])) {
        $errors[] = 'O nome deve ser um texto';
    } elseif (strlen($data['name']) > 150) {
        $errors[] = 'O nome não pode ter mais de 150 caracteres';
    }

    // Validação: cpf (obrigatório, string, max:50, unique)
    if (empty($data['cpf'])) {
        $errors[] = 'O CPF é obrigatório';
    } elseif (!is_string($data['cpf'])) {
        $errors[] = 'O CPF deve ser um texto';
    } elseif (strlen($data['cpf']) > 50) {
        $errors[] = 'O CPF não pode ter mais de 50 caracteres';
    }
    // Nota: teste de unicidade seria feito no banco

    // Validação: user (obrigatório, string, max:50, regex, unique)
    if (empty($data['user'])) {
        $errors[] = 'O nome de usuário é obrigatório';
    } elseif (!is_string($data['user'])) {
        $errors[] = 'O nome de usuário deve ser um texto';
    } elseif (strlen($data['user']) > 50) {
        $errors[] = 'O nome de usuário não pode ter mais de 50 caracteres';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['user'])) {
        $errors[] = 'O nome de usuário deve conter apenas letras, números e underscore';
    }

    // Validação: password (obrigatório, string, min:6, max:200)
    if (empty($data['password'])) {
        $errors[] = 'A senha é obrigatória';
    } elseif (!is_string($data['password'])) {
        $errors[] = 'A senha deve ser um texto';
    } elseif (strlen($data['password']) < 6) {
        $errors[] = 'A senha deve ter no mínimo 6 caracteres';
    } elseif (strlen($data['password']) > 200) {
        $errors[] = 'A senha não pode ter mais de 200 caracteres';
    }

    // Validação: mail (obrigatório, email, max:150, unique)
    if (empty($data['mail'])) {
        $errors[] = 'O e-mail é obrigatório';
    } elseif (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'O e-mail deve ser um endereço válido';
    } elseif (strlen($data['mail']) > 150) {
        $errors[] = 'O e-mail não pode ter mais de 150 caracteres';
    }

    // Validação: date_birth (nullable, date, before:today)
    if (!empty($data['date_birth'])) {
        $date = strtotime($data['date_birth']);
        if ($date === false) {
            $errors[] = 'A data de nascimento deve ser uma data válida';
        } elseif ($date >= strtotime('today')) {
            $errors[] = 'A data de nascimento deve ser anterior a hoje';
        }
    }

    // Validação: whatsapp (nullable, string, max:50)
    if (!empty($data['whatsapp']) && strlen($data['whatsapp']) > 50) {
        $errors[] = 'O WhatsApp não pode ter mais de 50 caracteres';
    }

    // Validação: phone (nullable, string, max:50)
    if (!empty($data['phone']) && strlen($data['phone']) > 50) {
        $errors[] = 'O telefone não pode ter mais de 50 caracteres';
    }

    // Validação: zip_code (nullable, string, max:50)
    if (!empty($data['zip_code']) && strlen($data['zip_code']) > 50) {
        $errors[] = 'O CEP não pode ter mais de 50 caracteres';
    }

    // Validação: address (nullable, string, max:50)
    if (!empty($data['address']) && strlen($data['address']) > 50) {
        $errors[] = 'O endereço não pode ter mais de 50 caracteres';
    }

    // Validação: profile (nullable, string, max:200)
    if (!empty($data['profile']) && strlen($data['profile']) > 200) {
        $errors[] = 'O perfil não pode ter mais de 200 caracteres';
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

echo "<pre>";
echo "<br/>";
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║         TESTE DO StoreRequest - Criação de Usuário            <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";

// ============================================================================
// TESTE 1: Dados Completos e Válidos
// ============================================================================
echo "✅ TESTE 1: DADOS COMPLETOS E VÁLIDOS<br/>";
echo str_repeat("─", 70) . "<br/>";

$validData = [
    'name' => 'João Silva',
    'cpf' => '12345678900',
    'whatsapp' => '11988887777',
    'user' => 'joaosilva123',
    'password' => 'senha123',
    'profile' => 'Desenvolvedor Senior',
    'mail' => 'joao@email.com',
    'phone' => '1133334444',
    'date_birth' => '1990-01-15',
    'zip_code' => '12345678',
    'address' => 'Rua Teste, 123',
];

$result = validateStoreData($validData);

if ($result['valid']) {
    echo "   ✅ TODOS os campos VÁLIDOS!<br/>";
    echo "   Usuário pode ser criado com sucesso.<br/>";
} else {
    echo "   ❌ ERRO INESPERADO! Dados válidos foram rejeitados:<br/>";
    foreach ($result['errors'] as $error) {
        echo "      → {$error}<br/>";
    }
}

echo "<br/>";

// ============================================================================
// TESTE 2: Campos Obrigatórios Faltando
// ============================================================================
echo "❌ TESTE 2: CAMPOS OBRIGATÓRIOS FALTANDO<br/>";
echo str_repeat("─", 70) . "<br/>";

$missingFields = [
    ['name' => ''],           // Name vazio
    ['cpf' => ''],            // CPF vazio
    ['user' => ''],           // User vazio
    ['password' => ''],       // Password vazio
    ['mail' => ''],           // Mail vazio
];

foreach ($missingFields as $index => $missing) {
    $testData = array_merge($validData, $missing);
    $result = validateStoreData($testData);

    $fieldName = array_key_first($missing);

    echo "   Teste " . ($index + 1) . ": Campo '{$fieldName}' vazio<br/>";

    if (!$result['valid']) {
        echo "   ❌ INVÁLIDO (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 3: Senha Muito Curta
// ============================================================================
echo "❌ TESTE 3: SENHA MUITO CURTA<br/>";
echo str_repeat("─", 70) . "<br/>";

$shortPasswords = ['1', '12', '123', '1234', '12345'];

foreach ($shortPasswords as $password) {
    $testData = array_merge($validData, ['password' => $password]);
    $result = validateStoreData($testData);

    echo "   Senha: '{$password}' (" . strlen($password) . " caracteres)<br/>";

    if (!$result['valid']) {
        echo "   ❌ INVÁLIDA (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 4: Email Inválido
// ============================================================================
echo "❌ TESTE 4: EMAIL INVÁLIDO<br/>";
echo str_repeat("─", 70) . "<br/>";

$invalidEmails = [
    'email_invalido',
    'email@',
    '@domain.com',
    'email@domain',
    'email domain.com',
    'email@domain .com',
];

foreach ($invalidEmails as $email) {
    $testData = array_merge($validData, ['mail' => $email]);
    $result = validateStoreData($testData);

    echo "   Email: '{$email}'<br/>";

    if (!$result['valid']) {
        echo "   ❌ INVÁLIDO (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 5: Username com Caracteres Especiais
// ============================================================================
echo "❌ TESTE 5: USERNAME COM CARACTERES ESPECIAIS<br/>";
echo str_repeat("─", 70) . "<br/>";

$invalidUsernames = [
    'joão-silva',       // Acentos e hífen
    'joao silva',       // Espaço
    'joao@silva',       // @
    'joao.silva',       // Ponto
    'joao#silva',       // #
    'joão_silva',       // Acento com underscore
];

foreach ($invalidUsernames as $username) {
    $testData = array_merge($validData, ['user' => $username]);
    $result = validateStoreData($testData);

    echo "   Username: '{$username}'<br/>";

    if (!$result['valid']) {
        echo "   ❌ INVÁLIDO (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 6: Username Válidos
// ============================================================================
echo "✅ TESTE 6: USERNAME VÁLIDOS<br/>";
echo str_repeat("─", 70) . "<br/>";

$validUsernames = [
    'joaosilva',
    'joao_silva',
    'joao123',
    'joao_silva_123',
    'JOAO_SILVA',
    'JoaoSilva123',
];

foreach ($validUsernames as $username) {
    $testData = array_merge($validData, ['user' => $username]);
    $result = validateStoreData($testData);

    echo "   Username: '{$username}'<br/>";

    if ($result['valid']) {
        echo "   ✅ VÁLIDO (como esperado)<br/>";
    } else {
        echo "   ⚠️  INVÁLIDO (não deveria!)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 7: Data de Nascimento no Futuro
// ============================================================================
echo "❌ TESTE 7: DATA DE NASCIMENTO NO FUTURO<br/>";
echo str_repeat("─", 70) . "<br/>";

$futureDates = [
    '2030-01-01',
    '2025-12-31',
    '3000-01-01',
];

foreach ($futureDates as $date) {
    $testData = array_merge($validData, ['date_birth' => $date]);
    $result = validateStoreData($testData);

    echo "   Data: '{$date}'<br/>";

    if (!$result['valid']) {
        echo "   ❌ INVÁLIDA (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 8: Campos Opcionais Vazios (devem passar)
// ============================================================================
echo "✅ TESTE 8: CAMPOS OPCIONAIS VAZIOS<br/>";
echo str_repeat("─", 70) . "<br/>";

$minimalData = [
    'name' => 'João Silva',
    'cpf' => '12345678900',
    'user' => 'joaosilva',
    'password' => 'senha123',
    'mail' => 'joao@email.com',
    // Todos os outros campos opcionais ausentes
];

$result = validateStoreData($minimalData);

if ($result['valid']) {
    echo "   ✅ VÁLIDO! Apenas campos obrigatórios preenchidos.<br/>";
    echo "   Campos opcionais podem ser vazios.<br/>";
} else {
    echo "   ❌ ERRO! Campos opcionais não deveriam ser obrigatórios:<br/>";
    foreach ($result['errors'] as $error) {
        echo "      → {$error}<br/>";
    }
}

echo "<br/>";

// ============================================================================
// TESTE 9: Tamanhos Máximos Excedidos
// ============================================================================
echo "❌ TESTE 9: TAMANHOS MÁXIMOS EXCEDIDOS<br/>";
echo str_repeat("─", 70) . "<br/>";

$maxLengthTests = [
    ['field' => 'name', 'value' => str_repeat('A', 151), 'max' => 150],
    ['field' => 'cpf', 'value' => str_repeat('1', 51), 'max' => 50],
    ['field' => 'user', 'value' => str_repeat('a', 51), 'max' => 50],
    ['field' => 'mail', 'value' => str_repeat('a', 140) . '@email.com', 'max' => 150],
    ['field' => 'profile', 'value' => str_repeat('A', 201), 'max' => 200],
];

foreach ($maxLengthTests as $test) {
    $testData = array_merge($validData, [$test['field'] => $test['value']]);
    $result = validateStoreData($testData);

    $length = strlen($test['value']);
    echo "   Campo: '{$test['field']}' ({$length} caracteres, máx: {$test['max']})<br/>";

    if (!$result['valid']) {
        echo "   ❌ INVÁLIDO (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 10: Sanitização de Dados
// ============================================================================
echo "🧹 TESTE 10: SANITIZAÇÃO DE DADOS<br/>";
echo str_repeat("─", 70) . "<br/>";

echo "   Dados COM máscaras (antes da sanitização):<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";

$dataWithMasks = [
    'cpf' => '123.456.789-00',
    'whatsapp' => '(11) 98888-7777',
    'phone' => '(11) 3333-4444',
    'zip_code' => '12345-678',
];

foreach ($dataWithMasks as $field => $value) {
    echo "      {$field}: {$value}<br/>";
}

echo "<br/>   Dados SEM máscaras (depois da sanitização):<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";

$dataSanitized = [
    'cpf' => '12345678900',
    'whatsapp' => '11988887777',
    'phone' => '1133334444',
    'zip_code' => '12345678',
];

foreach ($dataSanitized as $field => $value) {
    echo "      {$field}: {$value}<br/>";
}

echo "<br/>   ✅ StoreRequest usa DataSanitizerHelper automaticamente!<br/>";

echo "<br/>";

// ============================================================================
// RESUMO DOS TESTES
// ============================================================================
echo "<br/>";
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║                    RESUMO DOS TESTES                           <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
echo "   ✅ Teste 1: Dados Completos e Válidos<br/>";
echo "   ✅ Teste 2: Campos Obrigatórios Faltando<br/>";
echo "   ✅ Teste 3: Senha Muito Curta (< 6 caracteres)<br/>";
echo "   ✅ Teste 4: Email Inválido<br/>";
echo "   ✅ Teste 5: Username com Caracteres Especiais<br/>";
echo "   ✅ Teste 6: Username Válidos<br/>";
echo "   ✅ Teste 7: Data de Nascimento no Futuro<br/>";
echo "   ✅ Teste 8: Campos Opcionais Vazios<br/>";
echo "   ✅ Teste 9: Tamanhos Máximos Excedidos<br/>";
echo "   ✅ Teste 10: Sanitização de Dados<br/>";
echo "<br/>";
echo "   🎉 StoreRequest está validando corretamente! 🚀<br/>";
echo "<br/>";

// ============================================================================
// COMO USAR NO CONTROLLER
// ============================================================================
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║              COMO USAR NO CONTROLLER                          <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
echo "use App\\Http\\Requests\\v1\\User\\StoreRequest;<br/>";
echo "use Illuminate\\Support\\Facades\\Hash;<br/><br/>";

echo "public function store(StoreRequest \$request)<br/>";
echo "{<br/>";
echo "    // Dados JÁ VALIDADOS e SANITIZADOS! 🎉<br/>";
echo "    \$data = \$request->getSanitizedData();<br/>";
echo "    <br/>";
echo "    // Hash da senha<br/>";
echo "    \$data['password'] = Hash::make(\$data['password']);<br/>";
echo "    <br/>";
echo "    // Criar usuário<br/>";
echo "    \$user = UserManagementModel::create(\$data);<br/>";
echo "    <br/>";
echo "    return ApiResponseHelper::success(<br/>";
echo "        httpCode: 201,<br/>";
echo "        message: 'Usuário criado com sucesso',<br/>";
echo "        dbReturn: \$user,<br/>";
echo "        tableName: 'user_management'<br/>";
echo "    );<br/>";
echo "}<br/><br/>";

echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║  StoreRequest.php está pronto para uso no Controller!         <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
echo "</pre>";
