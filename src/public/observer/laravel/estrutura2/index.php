<?php
/**
 * Laravel Architecture Flow Viewer - SEM HELPERS
 * -----------------------------------------------
 * Exibe APENAS: Requests, Controllers, Services, Models, routes
 */

header('Content-Type: text/plain; charset=utf-8');

$baseDir = realpath(__DIR__ . '/../../../../..' . DIRECTORY_SEPARATOR . 'html');
if (!is_dir($baseDir)) {
    $baseDir = realpath(__DIR__ . '/../../../../..');
}

// Pastas que queremos exibir - SEM HELPERS
$targetFolders = [
    'app/Http/Requests',
    'app/Http/Controllers',
    'app/Services',
    'app/Models',
    'routes'
];

// Pastas que precisam de separador visual antes
$needsSeparator = ['Controllers', 'Requests', 'Services', 'Models', 'routes'];

/**
 * Verifica se um caminho faz parte de alguma pasta alvo ou é pai dela
 */
function isRelevantPath($relativePath, $targetFolders) {
    $relativePath = str_replace('\\', '/', $relativePath);

    foreach ($targetFolders as $target) {
        $target = str_replace('\\', '/', $target);

        if ($relativePath === $target) {
            return true;
        }

        if (strpos($relativePath, $target . '/') === 0) {
            return true;
        }

        if (strpos($target, $relativePath . '/') === 0) {
            return true;
        }
    }

    return false;
}

/**
 * Verifica se deve mostrar no output
 */
function shouldShow($relativePath, $targetFolders) {
    $relativePath = str_replace('\\', '/', $relativePath);

    foreach ($targetFolders as $target) {
        $target = str_replace('\\', '/', $target);

        if ($relativePath === $target || strpos($relativePath, $target . '/') === 0) {
            return true;
        }
    }

    return false;
}

/**
 * Gera a árvore de diretórios
 */
function generateTree($dir, $targetFolders, $baseDir, $needsSeparator, $prefix = '', $isLast = true) {
    $output = '';

    if (!is_dir($dir)) {
        return $output;
    }

    $items = @scandir($dir);
    if ($items === false) {
        return $output;
    }

    $items = array_diff($items, ['.', '..']);

    $dirs = [];
    $files = [];

    foreach ($items as $item) {
        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $fullPath);
        $relativePath = str_replace('\\', '/', $relativePath);

        if (!isRelevantPath($relativePath, $targetFolders)) {
            continue;
        }

        if (is_dir($fullPath)) {
            $dirs[] = $item;
        } else {
            if (shouldShow($relativePath, $targetFolders)) {
                $files[] = $item;
            }
        }
    }

    sort($dirs);
    sort($files);
    $allItems = array_merge($dirs, $files);
    $total = count($allItems);

    if ($total === 0) {
        return $output;
    }

    foreach ($allItems as $index => $item) {
        $isLastItem = ($index === $total - 1);
        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $fullPath);
        $relativePath = str_replace('\\', '/', $relativePath);

        // Adiciona separador visual antes das pastas específicas
        if (in_array($item, $needsSeparator) && $index > 0) {
            $output .= $prefix . "│\n";
            $output .= $prefix . "│\n";
        }

        $connector = $isLastItem ? '└── ' : '├── ';
        $output .= $prefix . $connector . $item;

        // Comentários
        $comments = [
            'Requests' => '# [1] Validacao de entrada (HTTP)',
            'Controllers' => '# [2] Orquestracao (max 10 linhas)',
            'Services' => '# [3] Logica de negocio',
            'Models' => '# [4] Acesso ao banco de dados',
            'routes' => '# [*] Definicao de rotas da API',
            'v1' => '# Versao 1 da API',
            'api' => '# Rotas da API',
            'User' => '# Dominio: Usuarios'
        ];

        if (isset($comments[$item])) {
            $output .= ' ' . $comments[$item];
        }

        $output .= "\n";

        if (is_dir($fullPath)) {
            $newPrefix = $prefix . ($isLastItem ? '    ' : '│   ');
            $output .= generateTree($fullPath, $targetFolders, $baseDir, $needsSeparator, $newPrefix, $isLastItem);
        }
    }

    return $output;
}

// Cabeçalho
echo "============================================================\n";
echo "        FLUXO DE ARQUITETURA - LARAVEL API\n";
echo "============================================================\n\n";
echo "FLUXO DE EXECUCAO:\n\n";
echo "  [1] Request      -> Valida entrada HTTP\n";
echo "  [2] Controller   -> Orquestra o fluxo (max 10 linhas)\n";
echo "  [3] Service      -> Executa logica de negocio\n";
echo "  [4] Model        -> Interage com banco de dados\n\n";
echo "============================================================\n\n";

// Árvore
$projectName = basename($baseDir);
echo "$projectName/\n";
echo generateTree($baseDir, $targetFolders, $baseDir, $needsSeparator);

// Rodapé
echo "\n============================================================\n";
echo "Gerado em: " . date('Y-m-d H:i:s') . "\n";
echo "Diretorio base: $baseDir\n";
echo "Exibindo apenas: " . implode(', ', $targetFolders) . "\n";
echo "============================================================\n";
