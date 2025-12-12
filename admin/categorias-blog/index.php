<?php
/**
 * ADMIN - Listar Categorias do Blog
 */

$pageTitle = 'Categorias do Blog';
require_once __DIR__ . '/../includes/header.php';

// Query
$sql = "SELECT c.*, (SELECT COUNT(*) FROM blog_posts WHERE categoria_id = c.id) as total_posts
        FROM categorias_blog c ORDER BY c.nome ASC";
$categorias = query($sql);
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Categorias do Blog</h1>
    <a href="<?= SITE_URL ?>/admin/categorias-blog/criar.php" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        Nova Categoria
    </a>
</div>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <span style="color: var(--color-text-muted); font-size: var(--text-sm);">
            <?= count($categorias) ?> categoria(s)
        </span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Slug</th>
                <th>Cor</th>
                <th>Posts</th>
                <th>Status</th>
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categorias)): ?>
            <tr>
                <td colspan="6" class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"></path>
                        <path d="M7 7h.01"></path>
                    </svg>
                    <p>Nenhuma categoria encontrada</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><strong><?= e($categoria['nome']) ?></strong></td>
                    <td><code style="font-size: var(--text-sm);"><?= e($categoria['slug']) ?></code></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: var(--spacing-2);">
                            <span style="width: 20px; height: 20px; border-radius: 4px; background: <?= e($categoria['cor']) ?>;"></span>
                            <code style="font-size: var(--text-xs);"><?= e($categoria['cor']) ?></code>
                        </div>
                    </td>
                    <td><?= $categoria['total_posts'] ?></td>
                    <td>
                        <span class="status-badge <?= $categoria['ativo'] ? 'active' : 'inactive' ?>">
                            <?= $categoria['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td>
                        <div class="data-table-actions">
                            <a href="<?= SITE_URL ?>/admin/categorias-blog/editar.php?id=<?= $categoria['id'] ?>" class="btn btn-sm btn-outline" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                </svg>
                            </a>
                            <?php if ($categoria['total_posts'] == 0): ?>
                            <a href="<?= SITE_URL ?>/admin/categorias-blog/excluir.php?id=<?= $categoria['id'] ?>" class="btn btn-sm btn-outline confirm-delete" data-confirm="Tem certeza que deseja excluir esta categoria?" title="Excluir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
