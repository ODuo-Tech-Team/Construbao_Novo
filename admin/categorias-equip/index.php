<?php
/**
 * ADMIN - Listar Categorias de Equipamentos
 */

$pageTitle = 'Categorias de Equipamentos';
require_once __DIR__ . '/../includes/header.php';

// Busca
$search = $_GET['q'] ?? '';
$where = '';
$params = [];

if ($search) {
    $where = "WHERE nome LIKE :search";
    $params['search'] = "%{$search}%";
}

// Query
$sql = "SELECT c.*, (SELECT COUNT(*) FROM equipamentos WHERE categoria_id = c.id) as total_equipamentos
        FROM categorias_equipamentos c {$where} ORDER BY c.ordem ASC, c.nome ASC";
$categorias = query($sql, $params);
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Categorias de Equipamentos</h1>
    <a href="<?= SITE_URL ?>/admin/categorias-equip/criar.php" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        Nova Categoria
    </a>
</div>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <form method="GET" class="data-table-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
            </svg>
            <input type="text" name="q" placeholder="Buscar categorias..." value="<?= e($search) ?>">
        </form>
        <span style="color: var(--color-text-muted); font-size: var(--text-sm);">
            <?= count($categorias) ?> categoria(s)
        </span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="60">Ordem</th>
                <th>Nome</th>
                <th>Slug</th>
                <th>Equipamentos</th>
                <th>Status</th>
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categorias)): ?>
            <tr>
                <td colspan="6" class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"></path>
                    </svg>
                    <p>Nenhuma categoria encontrada</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td style="text-align: center;"><?= $categoria['ordem'] ?></td>
                    <td><strong><?= e($categoria['nome']) ?></strong></td>
                    <td><code style="font-size: var(--text-sm);"><?= e($categoria['slug']) ?></code></td>
                    <td><?= $categoria['total_equipamentos'] ?></td>
                    <td>
                        <span class="status-badge <?= $categoria['ativo'] ? 'active' : 'inactive' ?>">
                            <?= $categoria['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td>
                        <div class="data-table-actions">
                            <a href="<?= SITE_URL ?>/admin/categorias-equip/editar.php?id=<?= $categoria['id'] ?>" class="btn btn-sm btn-outline" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                </svg>
                            </a>
                            <?php if ($categoria['total_equipamentos'] == 0): ?>
                            <a href="<?= SITE_URL ?>/admin/categorias-equip/excluir.php?id=<?= $categoria['id'] ?>" class="btn btn-sm btn-outline confirm-delete" data-confirm="Tem certeza que deseja excluir esta categoria?" title="Excluir">
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
