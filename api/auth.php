<?php
/**
 * API - Autenticacao
 * Middleware para validar API Key
 */

require_once __DIR__ . '/config.php';

/**
 * Obtem API Key do header
 */
function getApiKey(): ?string {
    // Verifica header X-API-Key
    $headers = getallheaders();

    if (isset($headers['X-API-Key'])) {
        return $headers['X-API-Key'];
    }

    if (isset($headers['x-api-key'])) {
        return $headers['x-api-key'];
    }

    // Verifica header Authorization: Bearer
    if (isset($headers['Authorization'])) {
        if (preg_match('/Bearer\s+(.+)/i', $headers['Authorization'], $matches)) {
            return $matches[1];
        }
    }

    if (isset($headers['authorization'])) {
        if (preg_match('/Bearer\s+(.+)/i', $headers['authorization'], $matches)) {
            return $matches[1];
        }
    }

    return null;
}

/**
 * Valida API Key e retorna usuario
 */
function validateApiKey(): ?array {
    $apiKey = getApiKey();

    if (!$apiKey) {
        return null;
    }

    $user = fetchOne('usuarios', 'api_key = ? AND ativo = 1', [$apiKey]);

    return $user ?: null;
}

/**
 * Requer autenticacao - aborta se nao autenticado
 */
function requireApiAuth(): array {
    $user = validateApiKey();

    if (!$user) {
        apiError('API Key invalida ou ausente', 401);
    }

    return $user;
}

/**
 * Requer nivel admin
 */
function requireApiAdmin(): array {
    $user = requireApiAuth();

    if ($user['nivel'] !== 'admin') {
        apiError('Acesso negado. Requer nivel admin.', 403);
    }

    return $user;
}
