<?php
/**
 * ADMIN - Excluir Equipamento
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

$id = intval($_GET['id'] ?? 0);
$equipamento = fetchById('equipamentos', $id);

if (!$equipamento) {
    setFlash('error', 'Equipamento não encontrado.');
    redirect(SITE_URL . '/admin/equipamentos/');
}

// Deletar imagem se existir
if ($equipamento['imagem']) {
    deleteImage($equipamento['imagem'], 'equipamentos');
}

$success = delete('equipamentos', 'id = ?', [$id]);

if ($success) {
    setFlash('success', 'Equipamento excluído com sucesso!');
} else {
    setFlash('error', 'Erro ao excluir equipamento. Tente novamente.');
}

redirect(SITE_URL . '/admin/equipamentos/');
