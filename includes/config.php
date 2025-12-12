<?php
/**
 * Configurações do Site Construbão
 * Carrega variáveis do arquivo .env
 */

// Prevenir acesso direto
if (!defined('SITE_ROOT')) {
    define('SITE_ROOT', dirname(__DIR__));
}

/**
 * Carrega variáveis do arquivo .env
 */
function loadEnv(string $path): void {
    if (!file_exists($path)) {
        die('Arquivo .env não encontrado. Copie .env.example para .env e configure.');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignora comentários
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Separa chave=valor
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove aspas se existirem
            $value = trim($value, '"\'');

            // Define como variável de ambiente
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

/**
 * Obtém variável de ambiente
 */
function env(string $key, $default = null) {
    $value = $_ENV[$key] ?? getenv($key);

    if ($value === false || $value === '') {
        return $default;
    }

    // Converte strings booleanas
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'null':
        case '(null)':
            return null;
    }

    return $value;
}

// Carrega o arquivo .env
loadEnv(SITE_ROOT . '/.env');

// =====================================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// =====================================================
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'construbao'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

// =====================================================
// CONFIGURAÇÕES DO SITE
// =====================================================

/**
 * Detecta automaticamente a URL base do site
 */
function detectSiteUrl(): string {
    // Se definido no .env, usa esse valor
    $envUrl = env('SITE_URL', '');
    if (!empty($envUrl) && $envUrl !== 'auto') {
        return rtrim($envUrl, '/');
    }

    // Detecta automaticamente
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
                ? 'https' : 'http';

    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';

    // Detecta o subdiretório baseado no SCRIPT_NAME
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptDir = dirname($scriptName);

    // Remove /includes se estiver nele
    if (strpos($scriptDir, '/includes') !== false) {
        $scriptDir = dirname($scriptDir);
    }

    // Limpa o path
    $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;

    return $protocol . '://' . $host . $basePath;
}

define('SITE_URL', detectSiteUrl());
define('SITE_NAME', env('SITE_NAME', 'Construbão'));
define('SITE_DESCRIPTION', env('SITE_DESCRIPTION', 'Venda de Poste Padrão e Locação de Equipamentos'));

// =====================================================
// CAMINHOS
// =====================================================
define('UPLOAD_PATH', SITE_ROOT . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('ASSETS_URL', SITE_URL . '/assets/');

// =====================================================
// WHATSAPP
// =====================================================
define('WHATSAPP_NUMBER', env('WHATSAPP_NUMBER', '5516997007775'));
define('WHATSAPP_MESSAGE', env('WHATSAPP_MESSAGE', 'Olá! Vim pelo site e gostaria de um orçamento.'));

// =====================================================
// CONFIGURAÇÕES DE UPLOAD
// =====================================================
define('MAX_UPLOAD_SIZE', (int) env('MAX_UPLOAD_SIZE', 2 * 1024 * 1024));
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// =====================================================
// AMBIENTE
// =====================================================
define('APP_ENV', env('APP_ENV', 'development'));
define('DEV_MODE', env('APP_DEBUG', true));

// =====================================================
// TIMEZONE
// =====================================================
date_default_timezone_set('America/Sao_Paulo');

// =====================================================
// CONFIGURAÇÕES DE ERRO
// =====================================================
if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// =====================================================
// INICIAR SESSÃO
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
