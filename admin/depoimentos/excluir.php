<?php
/**
 * ADMIN - Excluir Depoimento
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

$id = intval($_GET['id'] ?? 0);
$depoimento = fetchById('depoimentos', $id);

if (!$depoimento) {
    setFlash('error', 'Depoimento não encontrado.');
    redirect(SITE_URL . '/admin/depoimentos/');
}

// Deletar foto se existir
if ($depoimento['foto']) {
    deleteImage($depoimento['foto'], 'depoimentos');
}

$success = delete('depoimentos', 'id = ?', [$id]);

if ($success) {
    setFlash('success', 'Depoimento excluído com sucesso!');
} else {
    setFlash('error', 'Erro ao excluir depoimento. Tente novamente.');
}

redirect(SITE_URL . '/admin/depoimentos/');
