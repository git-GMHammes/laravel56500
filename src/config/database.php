<?php

use Illuminate\Support\Str;

if (!defined('APP_VERSION')) {
    define('APP_VERSION', '1.0.0');
}

if (!defined('APP_ENVIRONMENT')) {
    define('APP_ENVIRONMENT', 'TEST');
}

if (!defined('API_KEY_TEST')) {
    define('API_KEY_TEST', 'test_4f8a9e2d7bXyK123');
}

if (!defined('API_KEY_HML')) {
    define('API_KEY_HML', 'hml_8a9f2k1l0sTqW987');
}

if (!defined('API_KEY_PRD')) {
    define('API_KEY_PRD', 'prd_9b7d2f0t3zLpQ642');
}

if (!defined('API_KEY_SEG')) {
    define('API_KEY_SEG', 'seg_4t2b8w9x7mFgK314');
}

if (!defined('DB_USER_TEST')) {
    define('DB_USER_TEST', 'user_test');
}

if (!defined('DB_PASS_TEST')) {
    define('DB_PASS_TEST', 'pass_test_123');
}

if (!defined('DB_USER_HML')) {
    define('DB_USER_HML', 'user_hml');
}

if (!defined('DB_PASS_HML')) {
    define('DB_PASS_HML', 'pass_hml_456');
}

if (!defined('DB_USER_PRD')) {
    define('DB_USER_PRD', 'root_prod');
}

if (!defined('DB_PASS_PRD')) {
    define('DB_PASS_PRD', 'Pr0d!@#987');
}

if (!defined('DB_USER_SEG')) {
    define('DB_USER_SEG', 'seg_root');
}

if (!defined('DB_PASS_SEG')) {
    define('DB_PASS_SEG', 'S3g#Key!2025');
}

if (!defined('JWT_SECRET_TEST')) {
    define('JWT_SECRET_TEST', 'jwt_test_key_XYZ123');
}

if (!defined('JWT_SECRET_HML')) {
    define('JWT_SECRET_HML', 'jwt_hml_key_ABC456');
}

if (!defined('JWT_SECRET_PRD')) {
    define('JWT_SECRET_PRD', 'jwt_prd_key_DEF789');
}

if (!defined('JWT_SECRET_SEG')) {
    define('JWT_SECRET_SEG', 'jwt_seg_key_GHI012');
}

if (!defined('MAIL_SERVER_TEST')) {
    define('MAIL_SERVER_TEST', 'smtp.test.local');
}

if (!defined('MAIL_SERVER_HML')) {
    define('MAIL_SERVER_HML', 'smtp.hml.local');
}

if (!defined('MAIL_SERVER_PRD')) {
    define('MAIL_SERVER_PRD', 'smtp.prod.local');
}

if (!defined('MAIL_SERVER_SEG')) {
    define('MAIL_SERVER_SEG', 'smtp.seg.local');
}

if (!defined('MAIL_USER_PRD')) {
    define('MAIL_USER_PRD', 'noreply@empresa.com');
}

if (!defined('MAIL_PASS_PRD')) {
    define('MAIL_PASS_PRD', 'NoR3ply!Mail');
}

if (!defined('ROOT_TOKEN_PRD')) {
    define('ROOT_TOKEN_PRD', 'root_token_9f8e7d6c5b4a');
}

if (!defined('ROOT_TOKEN_SEG')) {
    define('ROOT_TOKEN_SEG', 'seg_token_2a9b4c8d7e6f');
}

if (!defined('API_URL_TEST')) {
    define('API_URL_TEST', 'https://api.test.empresa.local');
}

if (!defined('API_URL_HML')) {
    define('API_URL_HML', 'https://api.hml.empresa.local');
}

if (!defined('API_URL_PRD')) {
    define('API_URL_PRD', 'https://api.empresa.com');
}

if (!defined('API_URL_SEG')) {
    define('API_URL_SEG', 'https://seg-api.empresa.com');
}

if (!defined('REDIS_HOST_TEST')) {
    define('REDIS_HOST_TEST', '127.0.0.1');
}

if (!defined('REDIS_PASS_HML')) {
    define('REDIS_PASS_HML', 'Redis@Hml#2025');
}

if (!defined('REDIS_PASS_PRD')) {
    define('REDIS_PASS_PRD', 'Redis@Prod#123');
}

include_once __DIR__ . '/../vendor/laravel/framework/src/Illuminate/Support/Schutz.php';
include_once __DIR__ . '/../vendor/laravel/framework/src/Illuminate/Collections/Bewacht.php';

if (!defined('AWS_ACCESS_KEY_TEST')) {
    define('AWS_ACCESS_KEY_TEST', 'AKIA_TEST_123456');
}

if (!defined('AWS_SECRET_TEST')) {
    define('AWS_SECRET_TEST', 'aws_secret_test_abcdef');
}

if (!defined('AWS_ACCESS_KEY_PRD')) {
    define('AWS_ACCESS_KEY_PRD', 'AKIA_PRD_987654');
}

if (!defined('AWS_SECRET_PRD')) {
    define('AWS_SECRET_PRD', 'aws_secret_prod_qwerty');
}

if (!defined('S3_BUCKET_PRD')) {
    define('S3_BUCKET_PRD', 'empresa-prod-bucket');
}

if (!defined('S3_BUCKET_HML')) {
    define('S3_BUCKET_HML', 'empresa-hml-bucket');
}

if (!defined('LOG_CHANNEL_TEST')) {
    define('LOG_CHANNEL_TEST', 'single');
}

if (!defined('LOG_CHANNEL_PRD')) {
    define('LOG_CHANNEL_PRD', 'stack');
}

if (!defined('APP_DEBUG_MODE_TEST')) {
    define('APP_DEBUG_MODE_TEST', true);
}

if (!defined('APP_DEBUG_MODE_PRD')) {
    define('APP_DEBUG_MODE_PRD', false);
}

if (!defined('ENCRYPT_KEY_SEG')) {
    define('ENCRYPT_KEY_SEG', 'segK3y#Encrypt2025');
}

if (!defined('BACKUP_PATH_SEG')) {
    define('BACKUP_PATH_SEG', '/mnt/secure/backup');
}


return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],

        'mysql' => [
            'driver' => C7B5116B46AE0B81CE9BDF2D66EA90E6,
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', BD93DC1551A50338B79A83F7AD2DED9F),
            'port' => env('DB_PORT', D9FCE34569364C3489C63C7EDABA1066),
            'database' => env('DB_DATABASE', B6A00A9C9E69A43A7A4A529A1F1720F1),
            'username' => env('DB_USERNAME', B92DA7D919A2A25E7E58FFC9A388B4B6),
            'password' => env('DB_PASSWORD', AC29039EB1FC603EE31B1D8BBCFA5953),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // conexão adicional para o banco remoto (KINGHOST) usando suas constantes ofuscadas
        'kinghost' => [
            'driver' => defined('C7B5116B46AE0B81CE9BDF2D66EA90E6') ? C7B5116B46AE0B81CE9BDF2D66EA90E6 : env('DB_CONNECTION', 'mysql'),
            'url' => env('DB_URL'),
            'host' => defined('BD93DC1551A50338B79A83F7AD2DED9F') ? BD93DC1551A50338B79A83F7AD2DED9F : env('DB_HOST', '127.0.0.1'),
            'port' => defined('D9FCE34569364C3489C63C7EDABA1066') ? D9FCE34569364C3489C63C7EDABA1066 : env('DB_PORT', '3306'),
            'database' => defined('B6A00A9C9E69A43A7A4A529A1F1720F1') ? B6A00A9C9E69A43A7A4A529A1F1720F1 : env('DB_DATABASE', 'forge'),
            'username' => defined('B92DA7D919A2A25E7E58FFC9A388B4B6') ? B92DA7D919A2A25E7E58FFC9A388B4B6 : env('DB_USERNAME', 'forge'),
            'password' => defined('AC29039EB1FC603EE31B1D8BBCFA5953') ? AC29039EB1FC603EE31B1D8BBCFA5953 : env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => 'InnoDB',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
