<?php
/**
 * ADMIN - Listar Posts do Blog
 */

$pageTitle = 'Posts do Blog';
require_once __DIR__ . '/../includes/header.php';

// Filtros
$search = $_GET['q'] ?? '';
$categoriaFilter = $_GET['categoria'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$where = [];
$params = [];

if ($search) {
    $where[] = "(p.titulo LIKE :search OR p.resumo LIKE :search)";
    $params['search'] = "%{$search}%";
}

if ($categoriaFilter) {
    $where[] = "p.categoria_id = :categoria";
    $params['categoria'] = $categoriaFilter;
}

if ($statusFilter) {
    $where[] = "p.status = :status";
    $params['status'] = $statusFilter;
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Paginação
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$countSql = "SELECT COUNT(*) FROM blog_posts p {$whereClause}";
$stmt = db()->prepare($countSql);
$stmt->execute($params);
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Query
$sql = "SELECT p.*, c.nome as categoria_nome, c.cor as categoria_cor, u.nome as autor_nome
        FROM blog_posts p
        LEFT JOIN categorias_blog c ON p.categoria_id = c.id
        LEFT JOIN usuarios u ON p.autor_id = u.id
        {$whereClause}
        ORDER BY p.created_at DESC
        LIMIT {$perPage} OFFSET {$offset}";
$posts = query($sql, $params);

// Categorias para filtro
$categorias = query("SELECT * FROM categorias_blog WHERE ativo = 1 ORDER BY nome");
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Posts do Blog</h1>
    <a href="<?= SITE_URL ?>/admin/blog/criar.php" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        Novo Post
    </a>
</div>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <form method="GET" style="display: flex; gap: var(--spacing-3); flex-wrap: wrap;">
            <div class="data-table-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input type="text" name="q" placeholder="Buscar posts..." value="<?= e($search) ?>">
            </div>
            <select name="categoria" class="form-input" style="width: auto;" onchange="this.form.submit()">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $categoriaFilter == $cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['nome']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select name="status" class="form-input" style="width: auto;" onchange="this.form.submit()">
                <option value="">Todos os status</option>
                <option value="publicado" <?= $statusFilter === 'publicado' ? 'selected' : '' ?>>Publicado</option>
                <option value="rascunho" <?= $statusFilter === 'rascunho' ? 'selected' : '' ?>>Rascunho</option>
                <option value="agendado" <?= $statusFilter === 'agendado' ? 'selected' : '' ?>>Agendado</option>
            </select>
        </form>
        <span style="color: var(--color-text-muted); font-size: var(--text-sm);">
            <?= $total ?> post(s)
        </span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="60">Img</th>
                <th>Título</th>
                <th>Categoria</th>
                <th>SEO</th>
                <th>Status</th>
                <th>Data</th>
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
            <tr>
                <td colspan="7" class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <p>Nenhum post encontrado</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <td>
                        <?php if ($post['imagem_destaque']): ?>
                        <img src="<?= imageUrl($post['imagem_destaque'], 'blog') ?>" alt="<?= e($post['titulo']) ?>" class="table-thumbnail">
                        <?php else: ?>
                        <div class="table-thumbnail" style="background: var(--color-gray-200); display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                            </svg>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= e(truncate($post['titulo'], 50)) ?></strong>
                        <br>
                        <small style="color: var(--color-text-muted);">por <?= e($post['autor_nome'] ?? 'Desconhecido') ?></small>
                    </td>
                    <td>
                        <?php if ($post['categoria_nome']): ?>
                        <span class="badge" style="background: <?= e($post['categoria_cor'] ?? '#FFBA00') ?>20; color: <?= e($post['categoria_cor'] ?? '#FFBA00') ?>;">
                            <?= e($post['categoria_nome']) ?>
                        </span>
                        <?php else: ?>
                        <span style="color: var(--color-text-muted);">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $seoScore = $post['seo_score'] ?? 0;
                        $seoClass = $seoScore >= 70 ? 'good' : ($seoScore >= 40 ? 'ok' : 'bad');
                        ?>
                        <span class="seo-score-value <?= $seoClass ?>" style="font-size: var(--text-sm);">
                            <?= $seoScore ?>/100
                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?= $post['status'] ?>">
                            <?= ucfirst($post['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?= formatDate($post['publicado_em'] ?? $post['created_at'], 'd/m/Y') ?>
                    </td>
                    <td>
                        <div class="data-table-actions">
                            <?php if ($post['status'] === 'publicado'): ?>
                            <a href="<?= SITE_URL ?>/post.php?slug=<?= $post['slug'] ?>" target="_blank" class="btn btn-sm btn-outline" title="Ver no site">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" x2="21" y1="14" y2="3"></line>
                                </svg>
                            </a>
                            <?php endif; ?>
                            <a href="<?= SITE_URL ?>/admin/blog/editar.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                </svg>
                            </a>
                            <a href="<?= SITE_URL ?>/admin/blog/excluir.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline confirm-delete" data-confirm="Tem certeza que deseja excluir este post?" title="Excluir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
    <div style="padding: var(--spacing-4); border-top: 1px solid var(--color-border); display: flex; justify-content: center; gap: var(--spacing-2);">
        <?php
        $queryParams = [];
        if ($search) $queryParams[] = "q={$search}";
        if ($categoriaFilter) $queryParams[] = "categoria={$categoriaFilter}";
        if ($statusFilter) $queryParams[] = "status={$statusFilter}";
        $queryString = $queryParams ? '&' . implode('&', $queryParams) : '';
        ?>

        <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?><?= $queryString ?>" class="btn btn-sm btn-outline">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?><?= $queryString ?>" class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?><?= $queryString ?>" class="btn btn-sm btn-outline">Próximo</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
