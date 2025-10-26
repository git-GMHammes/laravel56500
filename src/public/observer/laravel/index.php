<?php
/**
 * Laravel Structure Viewer
 * Gera visualização da estrutura de diretórios do projeto
 * Executado de: C:\laragon\www\laravel56500\src\public\observer\laravel.php
 */

header('Content-Type: text/plain; charset=utf-8');

// Caminho base do projeto (3 níveis acima de src/public/observer)
$baseDir = dirname(dirname(dirname(__DIR__)));

// Diretórios e arquivos a ignorar
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
function shouldIgnore($path, $ignore) {
    foreach ($ignore as $pattern) {
        if (strpos($path, DIRECTORY_SEPARATOR . $pattern) !== false) {
            return true;
        }
        if (strpos($path, $pattern . DIRECTORY_SEPARATOR) === 0) {
            return true;
        }
    }
    return false;
}

/**
 * Gera a estrutura de diretórios
 */
function generateStructure($dir, $ignore, $prefix = '', $isLast = true) {
    $result = '';
    $items = [];

    // Ler diretório
    if (!is_dir($dir)) {
        return $result;
    }

    $files = scandir($dir);
    if ($files === false) {
        return $result;
    }

    // Filtrar . e ..
    $files = array_diff($files, ['.', '..']);

    // Separar diretórios e arquivos
    $dirs = [];
    $regularFiles = [];

    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        $relativePath = str_replace($GLOBALS['baseDir'] . DIRECTORY_SEPARATOR, '', $path);

        if (shouldIgnore($relativePath, $ignore)) {
            continue;
        }

        if (is_dir($path)) {
            $dirs[] = $file;
        } else {
            $regularFiles[] = $file;
        }
    }

    // Ordenar
    sort($dirs);
    sort($regularFiles);

    // Combinar (diretórios primeiro)
    $items = array_merge($dirs, $regularFiles);
    $total = count($items);

    foreach ($items as $index => $item) {
        $isLastItem = ($index === $total - 1);
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = str_replace($GLOBALS['baseDir'] . DIRECTORY_SEPARATOR, '', $path);

        // Desenhar item
        $connector = $isLastItem ? '└── ' : '├── ';
        $result .= $prefix . $connector . $item;

        // Adicionar comentário se for diretório importante
        if (is_dir($path)) {
            $comments = [
                'src' => '# Laravel será instalado aqui',
                'docker' => '# Configurações Docker',
                'storage' => '# Arquivos de armazenamento',
                'public' => '# Arquivos públicos',
                'app' => '# Código da aplicação',
                'config' => '# Configurações',
                'database' => '# Migrations e seeds',
                'routes' => '# Rotas da API'
            ];

            if (isset($comments[$item])) {
                $result .= ' ' . $comments[$item];
            }

            $result .= "\n";

            // Recursão para subdiretórios (limitar profundidade)
            $depth = substr_count($relativePath, DIRECTORY_SEPARATOR);
            if ($depth < 4) {
                $newPrefix = $prefix . ($isLastItem ? '    ' : '│   ');
                $result .= generateStructure($path, $ignore, $newPrefix, $isLastItem);
            }
        } else {
            $result .= "\n";
        }
    }

    return $result;
}

// Obter nome do projeto
$projectName = basename($baseDir);

// Gerar estrutura
echo "$projectName/\n";
echo generateStructure($baseDir, $ignore);

// Informações adicionais
echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Gerado em: " . date('Y-m-d H:i:s') . "\n";
echo "Diretório base: $baseDir\n";
echo "Ignorados: " . implode(', ', $ignore) . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
