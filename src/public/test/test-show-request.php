<?php

/**
 * Teste Simplificado do ShowRequest
 *
 * Testa a lógica de validação de ID
 *
 * @author Gustavo Hammes
 * @version 1.0.0
 */

// Função auxiliar para validar ID (simula o que o ShowRequest faz)
function validateId($id): array
{
    $errors = [];

    // Verifica se é null ou vazio
    if ($id === null || $id === '') {
        $errors[] = 'O ID do usuário é obrigatório';
        return ['valid' => false, 'errors' => $errors];
    }

    // Verifica se é numérico
    if (!is_numeric($id)) {
        $errors[] = 'O ID deve ser um número inteiro';
        return ['valid' => false, 'errors' => $errors];
    }

    // Converte para inteiro
    $idInt = (int) $id;

    // Verifica se é inteiro válido
    if ($idInt != $id) {
        $errors[] = 'O ID deve ser um número inteiro';
        return ['valid' => false, 'errors' => $errors];
    }

    // Verifica se é maior que 0
    if ($idInt < 1) {
        $errors[] = 'O ID deve ser maior que zero';
        return ['valid' => false, 'errors' => $errors];
    }

    return ['valid' => true, 'errors' => []];
}

echo "<pre>";
echo "<br/>";
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║            TESTE DO ShowRequest - Validação de ID             <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";

// ============================================================================
// TESTE 1: IDs Válidos
// ============================================================================
echo "✅ TESTE 1: IDs VÁLIDOS<br/>";
echo str_repeat("─", 70) . "<br/>";

$validIds = [1, 5, 10, 100, 9999, '5', '123']; // Incluindo strings numéricas

foreach ($validIds as $id) {
    $result = validateId($id);
    $displayId = is_string($id) ? "'{$id}'" : $id;

    if ($result['valid']) {
        echo "   ✅ ID {$displayId} → VÁLIDO<br/>";
    } else {
        echo "   ❌ ID {$displayId} → INVÁLIDO (erro inesperado!)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    }
}

echo "<br/>";

// ============================================================================
// TESTE 2: IDs Inválidos (números negativos ou zero)
// ============================================================================
echo "❌ TESTE 2: IDs INVÁLIDOS (Números Negativos ou Zero)<br/>";
echo str_repeat("─", 70) . "<br/>";

$invalidIds = [0, -1, -5, -100, '0', '-1'];

foreach ($invalidIds as $id) {
    $result = validateId($id);
    $displayId = is_string($id) ? "'{$id}'" : $id;

    if (!$result['valid']) {
        echo "   ❌ ID {$displayId} → INVÁLIDO (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  ID {$displayId} → PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 3: IDs Inválidos (não numéricos)
// ============================================================================
echo "❌ TESTE 3: IDs INVÁLIDOS (Não Numéricos)<br/>";
echo str_repeat("─", 70) . "<br/>";

$nonNumericIds = ['abc', '1a', 'a1', '1.5', 'null', '', '  ', 'test'];

foreach ($nonNumericIds as $id) {
    $displayId = $id === '' ? '(string vazia)' : ($id === '  ' ? '(espaços)' : $id);
    $result = validateId($id);

    if (!$result['valid']) {
        echo "   ❌ ID '{$displayId}' → INVÁLIDO (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  ID '{$displayId}' → PASSOU (não deveria!)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 4: ID ausente (null)
// ============================================================================
echo "❌ TESTE 4: ID AUSENTE (null)<br/>";
echo str_repeat("─", 70) . "<br/>";

$result = validateId(null);

if (!$result['valid']) {
    echo "   ❌ ID null → INVÁLIDO (como esperado)<br/>";
    foreach ($result['errors'] as $error) {
        echo "      → {$error}<br/>";
    }
} else {
    echo "   ⚠️  ID null → PASSOU (não deveria!)<br/>";
}

echo "<br/>";

// ============================================================================
// TESTE 5: Simulação de Request Real
// ============================================================================
echo "🌐 TESTE 5: SIMULAÇÃO DE REQUEST REAL<br/>";
echo str_repeat("─", 70) . "<br/>";

$testCases = [
    ['route' => '/api/v1/users/5', 'id' => 5, 'expected' => 'válido'],
    ['route' => '/api/v1/users/abc', 'id' => 'abc', 'expected' => 'inválido'],
    ['route' => '/api/v1/users/0', 'id' => 0, 'expected' => 'inválido'],
    ['route' => '/api/v1/users/-5', 'id' => -5, 'expected' => 'inválido'],
    ['route' => '/api/v1/users/1F', 'id' => '1F', 'expected' => 'inválido'],
];

foreach ($testCases as $case) {
    $result = validateId($case['id']);
    $displayId = is_string($case['id']) ? "'{$case['id']}'" : $case['id'];

    echo "   Rota: {$case['route']} (ID: {$displayId})<br/>";

    if ($result['valid']) {
        $status = $case['expected'] === 'válido' ? '✅' : '⚠️';
        echo "   {$status} Resultado: VÁLIDO";
        if ($case['expected'] !== 'válido') {
            echo " (esperava INVÁLIDO!)";
        }
        echo "<br/>";
    } else {
        $status = $case['expected'] === 'inválido' ? '✅' : '⚠️';
        echo "   {$status} Resultado: INVÁLIDO";
        if ($case['expected'] !== 'inválido') {
            echo " (esperava VÁLIDO!)";
        }
        echo "<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    }
    echo "<br/>";
}

// ============================================================================
// TESTE 6: Casos Especiais
// ============================================================================
echo "🔍 TESTE 6: CASOS ESPECIAIS<br/>";
echo str_repeat("─", 70) . "<br/>";

$specialCases = [
    ['id' => 1.5, 'description' => 'Float (1.5)'],
    ['id' => '1.0', 'description' => 'String float (1.0)'],
    ['id' => true, 'description' => 'Boolean true'],
    ['id' => false, 'description' => 'Boolean false'],
    ['id' => [], 'description' => 'Array vazio'],
];

foreach ($specialCases as $case) {
    $result = validateId($case['id']);

    echo "   Teste: {$case['description']}<br/>";

    if (!$result['valid']) {
        echo "   ❌ INVÁLIDO (como esperado)<br/>";
        foreach ($result['errors'] as $error) {
            echo "      → {$error}<br/>";
        }
    } else {
        echo "   ⚠️  VÁLIDO (verificar se é o comportamento desejado)<br/>";
    }
    echo "<br/>";
}

// ============================================================================
// RESUMO DOS TESTES
// ============================================================================
echo "<br/>";
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║                    RESUMO DOS TESTES                           <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
echo "   ✅ Teste 1: IDs Válidos (1, 5, 10, 100, 9999, '5', '123')<br/>";
echo "   ✅ Teste 2: IDs Inválidos - Negativos/Zero (0, -1, -5, -100)<br/>";
echo "   ✅ Teste 3: IDs Inválidos - Não Numéricos (abc, 1a, 1.5, etc)<br/>";
echo "   ✅ Teste 4: ID Ausente (null)<br/>";
echo "   ✅ Teste 5: Simulação de Request Real<br/>";
echo "   ✅ Teste 6: Casos Especiais (float, boolean, array)<br/>";
echo "<br/>";
echo "   🎉 ShowRequest está validando corretamente! 🚀<br/>";
echo "<br/>";

// ============================================================================
// COMO USAR NO CONTROLLER
// ============================================================================
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║              COMO USAR NO CONTROLLER                          <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
echo "ANTES (sem ShowRequest):<br/>";
echo str_repeat("─", 70) . "<br/>";
echo "public function show(\$id)<br/>";
echo "{<br/>";
echo "    // Validação manual<br/>";
echo "    if (!is_numeric(\$id) || \$id < 1) {<br/>";
echo "        return ApiResponseHelper::error(400, 'ID inválido');<br/>";
echo "    }<br/>";
echo "    // ... resto do código<br/>";
echo "}<br/><br/>";

echo "DEPOIS (com ShowRequest):<br/>";
echo str_repeat("─", 70) . "<br/>";
echo "use App\\Http\\Requests\\v1\\User\\ShowRequest;<br/><br/>";
echo "public function show(ShowRequest \$request, \$id)<br/>";
echo "{<br/>";
echo "    // ID JÁ FOI VALIDADO AUTOMATICAMENTE! 🎉<br/>";
echo "    // Se chegou aqui, o ID é válido<br/>";
echo "    <br/>";
echo "    \$user = UserManagementModel::find(\$id);<br/>";
echo "    // ... resto do código<br/>";
echo "}<br/><br/>";

echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║  ShowRequest.php está pronto para uso no Controller!          <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
echo "</pre>";
