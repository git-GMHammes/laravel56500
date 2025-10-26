# 🚀 ROAD MAP COMPLETO - API Laravel com Docker

## 📋 Sumário Executivo

Este é um guia completo e definitivo para configurar uma API RESTful em Laravel usando Docker, MySQL, Adminer e Nginx. **Tudo que você precisa está neste documento**.

### 🎯 O que será criado:
- API Laravel 11 com formato de resposta padronizado
- MySQL 8.0 com credenciais configuradas
- Adminer para gerenciar o banco de dados
- Ambiente 100% dockerizado com auto-restart
- Dados estáticos (sem conexão com banco inicialmente)

### 🔌 Portas do Projeto:
- **56500** → Aplicação Laravel
- **56501** → MySQL
- **56502** → Adminer

### 💻 Compatibilidade:
- ✅ Windows 10/11 (PowerShell)
- ✅ Linux (Bash)
- ✅ macOS (Bash/Zsh)

---

## 📁 ESTRUTURA DO PROJETO

```
laravel-api-project/
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       └── Dockerfile
├── src/                    # Laravel será instalado aqui
├── docker-compose.yml
└── README.md              # Este arquivo
```

---

## 📄 ARQUIVO 1: docker-compose.yml

**Copie este conteúdo e salve como `docker-compose.yml` na raiz do projeto:**

```yaml
version: '3.8'

services:
  mysql:
    image: mysql:8.0
    container_name: laravel_mysql
    restart: unless-stopped
    ports:
      - "56501:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root_S3cur3P@ss_2024
      MYSQL_DATABASE: laravel_api_db
      MYSQL_USER: laravel_user
      MYSQL_PASSWORD: laravel_P@ssw0rd_2024
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - laravel_network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-proot_S3cur3P@ss_2024"]
      interval: 10s
      timeout: 5s
      retries: 5
      start_period: 30s

  adminer:
    image: adminer:latest
    container_name: laravel_adminer
    restart: unless-stopped
    ports:
      - "56502:8080"
    environment:
      ADMINER_DEFAULT_SERVER: mysql
    networks:
      - laravel_network
    depends_on:
      mysql:
        condition: service_healthy

  php:
    build:
      context: ./docker/php
      dockerfile: Dockerfile
    container_name: laravel_php
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./src:/var/www/html
    environment:
      DB_CONNECTION: mysql
      DB_HOST: mysql
      DB_PORT: 3306
      DB_DATABASE: laravel_api_db
      DB_USERNAME: laravel_user
      DB_PASSWORD: laravel_P@ssw0rd_2024
      APP_NAME: "Laravel API"
      APP_ENV: local
      APP_DEBUG: "true"
      APP_URL: http://localhost:56500
    networks:
      - laravel_network
    depends_on:
      mysql:
        condition: service_healthy

  nginx:
    image: nginx:alpine
    container_name: laravel_nginx
    restart: unless-stopped
    ports:
      - "56500:80"
    volumes:
      - ./src:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - laravel_network
    depends_on:
      - php

networks:
  laravel_network:
    driver: bridge

volumes:
  mysql_data:
    driver: local
```

---

## 📄 ARQUIVO 2: docker/php/Dockerfile

```dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip vim \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

USER www-data

CMD ["php-fpm"]
```

---

## 📄 ARQUIVO 3: docker/nginx/default.conf

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 🚀 INSTALAÇÃO PASSO A PASSO

### PASSO 1: Criar estrutura

```powershell
New-Item -ItemType Directory -Path "laravel-api-project\docker\nginx" -Force
New-Item -ItemType Directory -Path "laravel-api-project\docker\php" -Force
New-Item -ItemType Directory -Path "laravel-api-project\src" -Force
cd laravel-api-project
```

### PASSO 2: Criar os 3 arquivos acima

### PASSO 3: Subir containers

```powershell
clear
cd C:\laragon\www\laravel56500
docker-compose down
docker-compose build --no-cache
docker-compose up --build -d
 
```

### PASSO 4: Instalar Laravel 11 + Sanctum

```powershell
docker exec -it laravel_php composer create-project laravel/laravel . "^11.0" --no-interaction
docker exec -it laravel_php composer require laravel/sanctum --no-interaction
docker exec -it laravel_php php artisan key:generate
```

### PASSO 5: Criar Controller

```powershell
docker exec -it laravel_php php artisan make:controller Api/V1/ContatoController
```

### PASSO 6: Editar arquivos

**Arquivo: `src/app/Http/Controllers/Api/V1/ContatoController.php`**

```php
<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function index(Request $request)
    {
        $todosContatos = [
            ["nome" => "João da Silva", "endereco" => "Rua das Flores, 123 - Centro, Rio de Janeiro - RJ", "telefone" => "(21) 98765-4321"],
            ["nome" => "Maria Oliveira", "endereco" => "Av. Brasil, 456 - Copacabana, Rio de Janeiro - RJ", "telefone" => "(21) 99876-5432"]
        ];

        $limite = (int) $request->query('limite', 50);
        $pagina = (int) $request->query('pagina', 1);
        $totalRegistros = count($todosContatos);
        $totalPaginas = (int) ceil($totalRegistros / $limite);
        $offset = ($pagina - 1) * $limite;
        $contatosPaginados = array_slice($todosContatos, $offset, $limite);

        return response()->json([
            "header" => [
                "http_code" => 200,
                "status" => "OK",
                "method" => $request->method(),
                "api_version" => "v1.0",
                "message" => "Requisição processada com sucesso"
            ],
            "result" => $contatosPaginados,
            "metadata" => [
                "url_sequence" => $request->segments(),
                "paginacao" => [
                    "pagina_atual" => $pagina,
                    "total_paginas" => $totalPaginas,
                    "total_registros" => $totalRegistros
                ],
                "limite" => $limite,
                "base_url" => $request->root()
            ]
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $contatos = [
            "1" => ["nome" => "João da Silva", "endereco" => "Rua das Flores, 123", "telefone" => "(21) 98765-4321"],
            "2" => ["nome" => "Maria Oliveira", "endereco" => "Av. Brasil, 456", "telefone" => "(21) 99876-5432"]
        ];

        if (!isset($contatos[$id])) {
            return response()->json([
                "header" => ["http_code" => 404, "status" => "NOT_FOUND", "method" => $request->method(), "api_version" => "v1.0", "message" => "Contato não encontrado"],
                "result" => [],
                "metadata" => ["url_sequence" => $request->segments(), "base_url" => $request->root()]
            ], 404);
        }

        return response()->json([
            "header" => ["http_code" => 200, "status" => "OK", "method" => $request->method(), "api_version" => "v1.0", "message" => "Requisição processada com sucesso"],
            "result" => [$contatos[$id]],
            "metadata" => ["url_sequence" => $request->segments(), "base_url" => $request->root()]
        ], 200);
    }
}
```

**Arquivo: `src/routes/api.php`**

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContatoController;

Route::get('/health', function () {
    return response()->json([
        "header" => ["http_code" => 200, "status" => "OK", "method" => "GET", "api_version" => "v1.0", "message" => "API funcionando"],
        "result" => ["service" => "Laravel API", "version" => "1.0.0", "timestamp" => now()->toIso8601String()],
        "metadata" => ["url_sequence" => ["api", "health"], "base_url" => url('/')]
    ]);
});

Route::prefix('v1')->group(function () {
    Route::get('/contatos', [ContatoController::class, 'index']);
    Route::get('/contatos/{id}', [ContatoController::class, 'show']);
});
```

**Arquivo: `src/bootstrap/app.php`** - EDITE E ADICIONE A LINHA `api:`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',        // ← ADICIONE ESTA LINHA
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### PASSO 7: Limpar cache e testar

```powershell
docker exec -it laravel_php php artisan config:clear
docker exec -it laravel_php php artisan route:list
curl http://localhost:56500/api/health
curl http://localhost:56500/api/v1/contatos
```

---

## ✅ VERIFICAÇÃO FINAL

Se tudo estiver correto:
- ✅ `route:list` mostra: `api/health`, `api/v1/contatos`, `api/v1/contatos/{id}`

- ✅ `curl http://localhost:56500/api/health` 

>> >> retorna JSON

- ✅ `curl http://localhost:56500/api/v1/contatos` 

>> >> retorna array de contatos

---

## 🔧 TROUBLESHOOTING

**Erro 404 na API:**
1. Verifique se `src/bootstrap/app.php` tem a linha `api:`
2. Execute: `docker exec -it laravel_php php artisan config:clear`
3. Execute: `docker exec -it laravel_php php artisan route:list`

**Erro Sanctum:**
```powershell
docker exec -it laravel_php composer require laravel/sanctum
docker exec -it laravel_php php artisan config:clear
```

**Resetar tudo:**
```powershell
docker-compose down -v
Remove-Item -Recurse -Force .\src\*
# Depois refaça do PASSO 3
```

---

## 📊 FORMATO DA RESPOSTA

```json
{
  "header": {
    "http_code": 200,
    "status": "OK",
    "method": "GET",
    "api_version": "v1.0",
    "message": "Requisição processada com sucesso"
  },
  "result": [
    {
      "nome": "João da Silva",
      "endereco": "Rua das Flores, 123 - Centro, Rio de Janeiro - RJ",
      "telefone": "(21) 98765-4321"
    }
  ],
  "metadata": {
    "url_sequence": ["api", "v1", "contatos"],
    "paginacao": {
      "pagina_atual": 1,
      "total_paginas": 1,
      "total_registros": 2
    },
    "limite": 50,
    "base_url": "http://localhost:56500"
  }
}
```

---

**Versão:** 2.0.0 (Corrigida)  
**Stack:** Laravel 11 + PHP 8.2 + MySQL 8.0 + Nginx + Docker