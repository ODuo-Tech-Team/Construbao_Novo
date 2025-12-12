<?php
/**
 * ADMIN - Excluir Usuário
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();
requireAdmin();

$id = intval($_GET['id'] ?? 0);

// Não pode excluir a si mesmo
if ($id === getLoggedUser()['id']) {
    setFlash('error', 'Você não pode excluir sua própria conta.');
    redirect(SITE_URL . '/admin/usuarios/');
}

$usuario = fetchById('usuarios', $id);

if (!$usuario) {
    setFlash('error', 'Usuário não encontrado.');
    redirect(SITE_URL . '/admin/usuarios/');
}

// Excluir usuário
$success = delete('usuarios', $id);

if ($success) {
    setFlash('success', 'Usuário excluído com sucesso!');
} else {
    setFlash('error', 'Erro ao excluir usuário. Tente novamente.');
}

redirect(SITE_URL . '/admin/usuarios/');
