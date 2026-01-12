<?php
/**
 * API - Configuracao
 * Headers CORS, helpers e configuracoes gerais da API
 */

// Carregar configuracoes do site
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Responder preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/**
 * Envia resposta JSON padronizada
 */
function apiResponse($data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Envia erro padronizado
 */
function apiError(string $message, int $status = 400, array $errors = []): void {
    $response = ['error' => true, 'message' => $message];
    if (!empty($errors)) {
        $response['errors'] = $errors;
    }
    apiResponse($response, $status);
}

/**
 * Envia sucesso padronizado
 */
function apiSuccess($data = null, string $message = 'Success', int $status = 200): void {
    $response = ['error' => false, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    apiResponse($response, $status);
}

/**
 * Obtem corpo da requisicao JSON
 */
function getRequestBody(): array {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

/**
 * Obtem metodo HTTP
 */
function getMethod(): string {
    return $_SERVER['REQUEST_METHOD'];
}

/**
 * Obtem path da URI
 */
function getPath(): string {
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);

    // Remove /api do inicio
    $path = preg_replace('#^/api#', '', $path);

    // Remove trailing slash
    return rtrim($path, '/') ?: '/';
}

/**
 * Valida campos obrigatorios
 */
function validateRequired(array $data, array $required): array {
    $errors = [];
    foreach ($required as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $errors[] = "Campo '{$field}' e obrigatorio";
        }
    }
    return $errors;
}

/**
 * Gera API Key unica
 */
function generateApiKey(): string {
    return bin2hex(random_bytes(32));
}
