<?php
/**
 * ADMIN - Excluir Categoria de Equipamentos
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

$id = intval($_GET['id'] ?? 0);
$categoria = fetchById('categorias_equipamentos', $id);

if (!$categoria) {
    setFlash('error', 'Categoria não encontrada.');
    redirect(SITE_URL . '/admin/categorias-equip/');
}

// Verificar se tem equipamentos
$totalEquipamentos = countRows('equipamentos', 'categoria_id = ' . $id);
if ($totalEquipamentos > 0) {
    setFlash('error', 'Não é possível excluir uma categoria que possui equipamentos.');
    redirect(SITE_URL . '/admin/categorias-equip/');
}

$success = delete('categorias_equipamentos', $id);

if ($success) {
    setFlash('success', 'Categoria excluída com sucesso!');
} else {
    setFlash('error', 'Erro ao excluir categoria. Tente novamente.');
}

redirect(SITE_URL . '/admin/categorias-equip/');
