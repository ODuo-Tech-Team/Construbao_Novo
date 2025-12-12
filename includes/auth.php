<?php
/**
 * Sistema de Autenticação
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

/**
 * Verifica se o usuário está logado
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Obtém o usuário logado
 */
function getLoggedUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }

    return fetchById('usuarios', $_SESSION['user_id']);
}

/**
 * Verifica se o usuário é admin
 */
function isAdmin(): bool {
    $user = getLoggedUser();
    return $user && $user['nivel'] === 'admin';
}

/**
 * Requer que o usuário esteja logado
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        setFlash('error', 'Você precisa fazer login para acessar esta página.');
        redirect(SITE_URL . '/admin/login.php');
    }

    // Verificar se usuário ainda está ativo
    $user = getLoggedUser();
    if (!$user || !$user['ativo']) {
        logout();
        setFlash('error', 'Sua conta foi desativada.');
        redirect(SITE_URL . '/admin/login.php');
    }
}

/**
 * Requer que o usuário seja admin
 */
function requireAdmin(): void {
    requireLogin();

    if (!isAdmin()) {
        setFlash('error', 'Você não tem permissão para acessar esta página.');
        redirect(SITE_URL . '/admin/');
    }
}

/**
 * Realiza o login
 */
function login(string $email, string $senha): bool {
    $user = fetchOne('usuarios', 'email = ? AND ativo = 1', [$email]);

    if (!$user || !password_verify($senha, $user['senha'])) {
        return false;
    }

    // Regenerar ID da sessão por segurança
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nome'] = $user['nome'];
    $_SESSION['user_nivel'] = $user['nivel'];

    // Atualizar último login
    update('usuarios', ['ultimo_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);

    return true;
}

/**
 * Realiza o logout
 */
function logout(): void {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}

/**
 * Cria hash de senha
 */
function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

/**
 * Valida força da senha
 */
function validatePassword(string $password): array {
    $errors = [];

    if (strlen($password) < 6) {
        $errors[] = 'A senha deve ter pelo menos 6 caracteres.';
    }

    return $errors;
}
