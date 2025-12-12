<?php
/**
 * ADMIN - Excluir Post do Blog
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

$id = intval($_GET['id'] ?? 0);
$post = fetchById('blog_posts', $id);

if (!$post) {
    setFlash('error', 'Post não encontrado.');
    redirect(SITE_URL . '/admin/blog/');
}

// Deletar imagem se existir
if ($post['imagem_destaque']) {
    deleteImage($post['imagem_destaque'], 'blog');
}

$success = delete('blog_posts', $id);

if ($success) {
    setFlash('success', 'Post excluído com sucesso!');
} else {
    setFlash('error', 'Erro ao excluir post. Tente novamente.');
}

redirect(SITE_URL . '/admin/blog/');
