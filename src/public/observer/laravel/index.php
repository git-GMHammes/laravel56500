<?php
/**
 * Laravel Structure Viewer (Versão Corrigida)
 * --------------------------------------------
 * Gera visualização da estrutura de diretórios do projeto Laravel.
 * Executado de: C:\laragon\www\laravel56500\src\public\observer\laravel\index.php
 *
 * Corrigido para exibir corretamente subpastas e arquivos (ex: User, v1, etc.)
 */

header('Content-Type: text/plain; charset=utf-8');

/**
 * Caminho base do projeto
 *
 * ⚙️ Ajuste automático: sobe apenas até /html/
 * O script é executado dentro de /src/public/observer/laravel
 */
$baseDir = realpath(__DIR__ . '/../../..' . DIRECTORY_SEPARATOR . 'html');

// Se o diretório não existir, tenta usar um fallback
if (!is_dir($baseDir)) {
    $baseDir = realpath(__DIR__ . '/../../..');
}

/**
 * Diretórios e arquivos a ignorar
 */
$ignore = [
    'vendor',
    'node_modules',
    '.git',
    '.idea',
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

/**
 * Verifica se o caminho deve ser ignorado
 */
function shouldIgnore($path, $ignore): bool {
    $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    foreach ($ignore as $pattern) {
        $pattern = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $pattern);

        // Correspondência exata
        if ($normalizedPath === $pattern) {
            return true;
        }

        // Caminho começa com o padrão (ex: vendor/, storage/logs/)
        if (str_starts_with($normalizedPath, $pattern . DIRECTORY_SEPARATOR)) {
            return true;
        }

        // Caminho contém subdiretório ignorado (ex: src/vendor/)
        if (strpos($normalizedPath, DIRECTORY_SEPARATOR . $pattern . DIRECTORY_SEPARATOR) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Gera a estrutura de diretórios recursivamente
 */
function generateStructure($dir, $ignore, $prefix = '', $isLast = true): string {
    global $baseDir;

    $result = '';
    if (!is_dir($dir)) {
        return $result;
    }

    $files = scandir($dir);
    if ($files === false) {
        return $result;
    }

    $files = array_diff($files, ['.', '..']);

    $dirs = [];
    $regularFiles = [];

    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $path);

        if (shouldIgnore($relativePath, $ignore)) {
            continue;
        }

        if (is_dir($path)) {
            $dirs[] = $file;
        } else {
            $regularFiles[] = $file;
        }
    }

    sort($dirs);
    sort($regularFiles);
    $items = array_merge($dirs, $regularFiles);
    $total = count($items);

    foreach ($items as $index => $item) {
        $isLastItem = ($index === $total - 1);
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $path);

        $connector = $isLastItem ? '└── ' : '├── ';
        $result .= $prefix . $connector . $item;

        // Comentários para pastas conhecidas
        $comments = [
            'app' => '# Código da aplicação',
            'config' => '# Configurações',
            'database' => '# Migrations e seeds',
            'routes' => '# Rotas da API',
            'storage' => '# Arquivos de armazenamento',
            'public' => '# Arquivos públicos'
        ];

        if (isset($comments[$item])) {
            $result .= ' ' . $comments[$item];
        }

        $result .= "\n";

        // Limita a profundidade (pode ajustar)
        $depth = substr_count($relativePath, DIRECTORY_SEPARATOR);
        if ($depth < 6 && is_dir($path)) {
            $newPrefix = $prefix . ($isLastItem ? '    ' : '│   ');
            $result .= generateStructure($path, $ignore, $newPrefix, $isLastItem);
        }
    }

    return $result;
}

/**
 * Identifica o nome do projeto
 */
$projectName = basename($baseDir);

/**
 * Exibe resultado
 */
echo "$projectName/\n";
echo generateStructure($baseDir, $ignore);

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Gerado em: " . date('Y-m-d H:i:s') . "\n";
echo "Diretório base: $baseDir\n";
echo "Ignorados: " . implode(', ', $ignore) . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
