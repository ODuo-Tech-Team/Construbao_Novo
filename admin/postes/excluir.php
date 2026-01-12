<?php
/**
 * ADMIN - Excluir Poste
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

$id = intval($_GET['id'] ?? 0);
$poste = fetchById('postes', $id);

if (!$poste) {
    setFlash('error', 'Poste não encontrado.');
    redirect(SITE_URL . '/admin/postes/');
}

// Deletar imagem se existir
if ($poste['imagem']) {
    deleteImage($poste['imagem'], 'postes');
}

$success = delete('postes', 'id = ?', [$id]);

if ($success) {
    setFlash('success', 'Poste excluído com sucesso!');
} else {
    setFlash('error', 'Erro ao excluir poste. Tente novamente.');
}

redirect(SITE_URL . '/admin/postes/');
