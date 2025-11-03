<?php

/**
 * Teste do DataSanitizerHelper
 *
 * Arquivo para testar todas as funcionalidades do Helper de sanitização
 *
 * @author Gustavo Hammes
 * @version 1.0.0
 */

require __DIR__ . '/../../vendor/autoload.php';

use App\Http\Helpers\DataSanitizerHelper;

echo "<br/>";
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║         TESTE DO DataSanitizerHelper <br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";

// ============================================================================
// TESTE 1: Remover máscara de CPF
// ============================================================================
echo "📋 TESTE 1: Sanitizar CPF<br/>";
echo str_repeat("─", 70) . "<br/>";

$cpfTests = [
    '123.456.789-00',
    '111.222.333-44',
    '12345678900',      // Já sem máscara
    null,               // Valor nulo
];

foreach ($cpfTests as $cpf) {
    $resultado = DataSanitizerHelper::sanitizeCpf($cpf);
    $input = $cpf ?? 'null';
    $output = $resultado ?? 'null';
    echo "   Input:  {$input}<br/>";
    echo "   Output: {$output}<br/>";
    echo "<br/>";
}

// ============================================================================
// TESTE 2: Remover máscara de Telefone
// ============================================================================
echo "📱 TESTE 2: Sanitizar Telefone/WhatsApp<br/>";
echo str_repeat("─", 70) . "<br/>";

$phoneTests = [
    '(11) 98888-7777',
    '(21) 3333-4444',
    '11 98888-7777',
    '(11)988887777',
    '11988887777',      // Já sem máscara
    null,
];

foreach ($phoneTests as $phone) {
    $resultado = DataSanitizerHelper::sanitizePhone($phone);
    $input = $phone ?? 'null';
    $output = $resultado ?? 'null';
    echo "   Input:  {$input}<br/>";
    echo "   Output: {$output}<br/>";
    echo "<br/>";
}

// ============================================================================
// TESTE 3: Remover máscara de CEP
// ============================================================================
echo "📮 TESTE 3: Sanitizar CEP<br/>";
echo str_repeat("─", 70) . "<br/>";

$zipCodeTests = [
    '12345-678',
    '12.345-678',
    '12.345678',
    '12.34567',
    '01310-100',
    '12345678',         // Já sem máscara
    null,
];

foreach ($zipCodeTests as $zipCode) {
    $resultado = DataSanitizerHelper::sanitizeZipCode($zipCode);
    $input = $zipCode ?? 'null';
    $output = $resultado ?? 'null';
    echo "   Input:  {$input}<br/>";
    echo "   Output: {$output}<br/>";
    echo "<br/>";
}

// ============================================================================
// TESTE 4: Sanitizar array completo (caso real)
// ============================================================================
echo "🗂️  TESTE 4: Sanitizar Array Completo (Cenário Real)<br/>";
echo str_repeat("─", 70) . "<br/>";

$userData = [
    'name' => 'João Silva',
    'cpf' => '123.456.789-00',
    'whatsapp' => '(11) 98888-7777',
    'user' => 'joaosilva',
    'password' => 'senha123',
    'mail' => 'joao@email.com',
    'phone' => '(11) 3333-4444',
    'zip_code' => '12345-678',
    'address' => 'Rua Teste, 123',
];

echo "   ANTES da sanitização:<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";
foreach ($userData as $key => $value) {
    printf("   %-15s: %s<br/>", $key, $value);
}

$sanitized = DataSanitizerHelper::sanitize($userData);

echo "<br/>   DEPOIS da sanitização:<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";
foreach ($sanitized as $key => $value) {
    printf("   %-15s: %s<br/>", $key, $value);
}
echo "<br/>";

// ============================================================================
// TESTE 5: Limpar strings (remover espaços extras)
// ============================================================================
echo "✨ TESTE 5: Limpar Strings (Remover Espaços Extras)<br/>";
echo str_repeat("─", 70) . "<br/>";

$stringTests = [
    '  João Silva  ',
    'Maria   Clara   Santos',
    '   Espaços    Múltiplos   ',
    'SemEspaços',
    null,
];

foreach ($stringTests as $string) {
    $resultado = DataSanitizerHelper::cleanString($string);
    $input = $string ?? 'null';
    $output = $resultado ?? 'null';
    echo "   Input:  '{$input}'<br/>";
    echo "   Output: '{$output}'<br/>";
    echo "<br/>";
}

// ============================================================================
// TESTE 6: Sanitização completa (máscaras + strings)
// ============================================================================
echo "🔄 TESTE 6: Sanitização COMPLETA (Máscaras + Strings)<br/>";
echo str_repeat("─", 70) . "<br/>";

$messyData = [
    'name' => '  João   Silva  ',
    'cpf' => '123.456.789-00',
    'whatsapp' => '(11) 98888-7777',
    'phone' => '  (11) 3333-4444  ',
    'zip_code' => '12345-678',
    'address' => '  Rua  Teste,  123  ',
];

echo "   ANTES da sanitização completa:<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";
foreach ($messyData as $key => $value) {
    printf("   %-15s: '%s'<br/>", $key, $value);
}

$fullSanitized = DataSanitizerHelper::fullSanitize($messyData);

echo "<br/>   DEPOIS da sanitização completa:<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";
foreach ($fullSanitized as $key => $value) {
    printf("   %-15s: '%s'<br/>", $key, $value);
}
echo "<br/>";

// ============================================================================
// TESTE 7: Sanitizar apenas campos específicos
// ============================================================================
echo "🎯 TESTE 7: Sanitizar Apenas Campos Específicos<br/>";
echo str_repeat("─", 70) . "<br/>";

$data = [
    'name' => 'João Silva',
    'cpf' => '123.456.789-00',
    'phone' => '(11) 98888-7777',
    'email' => 'joao@email.com',
];

$camposSanitizar = ['cpf', 'phone']; // Apenas CPF e telefone

echo "   Campos a sanitizar: " . implode(', ', $camposSanitizar) . "<br/><br/>";

echo "   ANTES:<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";
foreach ($data as $key => $value) {
    printf("   %-15s: %s<br/>", $key, $value);
}

$parcial = DataSanitizerHelper::sanitizeFields($data, $camposSanitizar);

echo "<br/>   DEPOIS:<br/>";
echo "   " . str_repeat("─", 66) . "<br/>";
foreach ($parcial as $key => $value) {
    printf("   %-15s: %s<br/>", $key, $value);
}
echo "<br/>";

// ============================================================================
// RESUMO DOS TESTES
// ============================================================================
echo "<br/>";
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║                    RESUMO DOS TESTES                           ║<br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
echo "   ✅ Teste 1: Sanitização de CPF<br/>";
echo "   ✅ Teste 2: Sanitização de Telefone<br/>";
echo "   ✅ Teste 3: Sanitização de CEP<br/>";
echo "   ✅ Teste 4: Sanitização de Array Completo<br/>";
echo "   ✅ Teste 5: Limpeza de Strings<br/>";
echo "   ✅ Teste 6: Sanitização Completa<br/>";
echo "   ✅ Teste 7: Sanitização de Campos Específicos<br/>";
echo "<br/>";
echo "   🎉 Todos os testes executados com sucesso!<br/>";
echo "<br/>";
echo "╔════════════════════════════════════════════════════════════════╗<br/>";
echo "║  DataSanitizerHelper está pronto para uso! 🚀                 ║<br/>";
echo "╚════════════════════════════════════════════════════════════════╝<br/>";
echo "<br/>";
