<?php
/**
 * ADMIN - Listar Usuários
 */

$pageTitle = 'Usuários';
require_once __DIR__ . '/../includes/header.php';

// Apenas admin pode gerenciar usuários
requireAdmin();

// Busca
$search = $_GET['q'] ?? '';
$where = '';
$params = [];

if ($search) {
    $where = "WHERE nome LIKE :search OR email LIKE :search";
    $params['search'] = "%{$search}%";
}

// Paginação
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$total = countRows('usuarios', $where ? str_replace('WHERE ', '', $where) : '1=1', $params);
$totalPages = ceil($total / $perPage);

// Query
$sql = "SELECT * FROM usuarios {$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}";
$usuarios = query($sql, $params);
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Usuários</h1>
    <a href="<?= SITE_URL ?>/admin/usuarios/criar.php" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        Novo Usuário
    </a>
</div>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <form method="GET" class="data-table-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
            </svg>
            <input type="text" name="q" placeholder="Buscar usuários..." value="<?= e($search) ?>">
        </form>
        <span style="color: var(--color-text-muted); font-size: var(--text-sm);">
            <?= $total ?> usuário(s)
        </span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Nível</th>
                <th>Status</th>
                <th>Criado em</th>
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
            <tr>
                <td colspan="6" class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <p>Nenhum usuário encontrado</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: var(--spacing-3);">
                            <div class="admin-user-avatar" style="width: 32px; height: 32px; font-size: var(--text-sm);">
                                <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
                            </div>
                            <?= e($usuario['nome']) ?>
                        </div>
                    </td>
                    <td><?= e($usuario['email']) ?></td>
                    <td>
                        <span class="badge badge-<?= $usuario['nivel'] === 'admin' ? 'primary' : 'secondary' ?>">
                            <?= ucfirst($usuario['nivel']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?= $usuario['ativo'] ? 'active' : 'inactive' ?>">
                            <?= $usuario['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td><?= formatDate($usuario['created_at'], 'd/m/Y') ?></td>
                    <td>
                        <div class="data-table-actions">
                            <a href="<?= SITE_URL ?>/admin/usuarios/editar.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                </svg>
                            </a>
                            <?php if ($usuario['id'] !== $currentUser['id']): ?>
                            <a href="<?= SITE_URL ?>/admin/usuarios/excluir.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline confirm-delete" data-confirm="Tem certeza que deseja excluir este usuário?" title="Excluir">
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
