<?php
/**
 * ADMIN - Listar Postes
 */

$pageTitle = 'Postes';
require_once __DIR__ . '/../includes/header.php';

// Filtros
$search = $_GET['q'] ?? '';
$where = [];
$params = [];

if ($search) {
    $where[] = "(nome LIKE :search OR descricao LIKE :search)";
    $params['search'] = "%{$search}%";
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Paginação
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$countSql = "SELECT COUNT(*) FROM postes {$whereClause}";
$stmt = db()->prepare($countSql);
$stmt->execute($params);
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Query
$sql = "SELECT * FROM postes {$whereClause} ORDER BY ordem ASC, nome ASC LIMIT {$perPage} OFFSET {$offset}";
$postes = query($sql, $params);
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Postes</h1>
    <a href="<?= SITE_URL ?>/admin/postes/criar.php" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        Novo Poste
    </a>
</div>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <form method="GET" class="data-table-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
            </svg>
            <input type="text" name="q" placeholder="Buscar postes..." value="<?= e($search) ?>">
        </form>
        <span style="color: var(--color-text-muted); font-size: var(--text-sm);">
            <?= $total ?> poste(s)
        </span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="60">Img</th>
                <th>Nome</th>
                <th>Slug</th>
                <th>Status</th>
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($postes)): ?>
            <tr>
                <td colspan="5" class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20"></path>
                        <path d="M2 5h20"></path>
                        <path d="M5 2v3"></path>
                        <path d="M19 2v3"></path>
                    </svg>
                    <p>Nenhum poste encontrado</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($postes as $poste): ?>
                <tr>
                    <td>
                        <?php if ($poste['imagem']): ?>
                        <img src="<?= imageUrl($poste['imagem'], 'postes') ?>" alt="<?= e($poste['nome']) ?>" class="table-thumbnail">
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
                        <strong><?= e($poste['nome']) ?></strong>
                    </td>
                    <td><code style="font-size: var(--text-sm);"><?= e($poste['slug']) ?></code></td>
                    <td>
                        <span class="status-badge <?= $poste['ativo'] ? 'active' : 'inactive' ?>">
                            <?= $poste['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td>
                        <div class="data-table-actions">
                            <a href="<?= SITE_URL ?>/poste.php?slug=<?= $poste['slug'] ?>" target="_blank" class="btn btn-sm btn-outline" title="Ver no site">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" x2="21" y1="14" y2="3"></line>
                                </svg>
                            </a>
                            <a href="<?= SITE_URL ?>/admin/postes/editar.php?id=<?= $poste['id'] ?>" class="btn btn-sm btn-outline" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                </svg>
                            </a>
                            <a href="<?= SITE_URL ?>/admin/postes/excluir.php?id=<?= $poste['id'] ?>" class="btn btn-sm btn-outline confirm-delete" data-confirm="Tem certeza que deseja excluir este poste?" title="Excluir">
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
        <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?><?= $search ? "&q={$search}" : '' ?>" class="btn btn-sm btn-outline">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?><?= $search ? "&q={$search}" : '' ?>" class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?><?= $search ? "&q={$search}" : '' ?>" class="btn btn-sm btn-outline">Próximo</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
