<?php
/**
 * ADMIN - Excluir Categoria do Blog
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

$id = intval($_GET['id'] ?? 0);
$categoria = fetchById('categorias_blog', $id);

if (!$categoria) {
    setFlash('error', 'Categoria não encontrada.');
    redirect(SITE_URL . '/admin/categorias-blog/');
}

// Verificar se tem posts
$totalPosts = countRows('blog_posts', 'categoria_id = ' . $id);
if ($totalPosts > 0) {
    setFlash('error', 'Não é possível excluir uma categoria que possui posts.');
    redirect(SITE_URL . '/admin/categorias-blog/');
}

$success = delete('categorias_blog', $id);

if ($success) {
    setFlash('success', 'Categoria excluída com sucesso!');
} else {
    setFlash('error', 'Erro ao excluir categoria. Tente novamente.');
}

redirect(SITE_URL . '/admin/categorias-blog/');
