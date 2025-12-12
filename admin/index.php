<?php
/**
 * ADMIN - Dashboard
 */

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// Estatísticas
$totalEquipamentos = countRows('equipamentos', 'ativo = 1');
$totalPostes = countRows('postes', 'ativo = 1');
$totalPosts = countRows('blog_posts');
$totalUsuarios = countRows('usuarios', 'ativo = 1');

// Posts recentes
$postsRecentes = query("
    SELECT p.*, c.nome as categoria_nome, u.nome as autor_nome
    FROM blog_posts p
    LEFT JOIN categorias_blog c ON p.categoria_id = c.id
    LEFT JOIN usuarios u ON p.autor_id = u.id
    ORDER BY p.created_at DESC
    LIMIT 5
");

// Equipamentos recentes
$equipamentosRecentes = query("
    SELECT e.*, c.nome as categoria_nome
    FROM equipamentos e
    LEFT JOIN categorias_equipamentos c ON e.categoria_id = c.id
    ORDER BY e.created_at DESC
    LIMIT 5
");
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Dashboard</h1>
</div>

<!-- Stats -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-card-icon primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
        </div>
        <div class="stat-card-value"><?= $totalEquipamentos ?></div>
        <div class="stat-card-label">Equipamentos</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v20"></path>
                <path d="M2 5h20"></path>
                <path d="M5 2v3"></path>
                <path d="M19 2v3"></path>
            </svg>
        </div>
        <div class="stat-card-value"><?= $totalPostes ?></div>
        <div class="stat-card-label">Postes</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon success">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" x2="8" y1="13" y2="13"></line>
                <line x1="16" x2="8" y1="17" y2="17"></line>
                <line x1="10" x2="8" y1="9" y2="9"></line>
            </svg>
        </div>
        <div class="stat-card-value"><?= $totalPosts ?></div>
        <div class="stat-card-label">Posts do Blog</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon info">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-card-value"><?= $totalUsuarios ?></div>
        <div class="stat-card-label">Usuários</div>
    </div>
</div>

<!-- Recent Content -->
<div class="dashboard-grid">
    <!-- Posts Recentes -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3 class="dashboard-card-title">Posts Recentes</h3>
            <a href="<?= SITE_URL ?>/admin/blog/" class="btn btn-sm btn-outline">Ver Todos</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($postsRecentes)): ?>
                <tr>
                    <td colspan="3" style="text-align: center; padding: var(--spacing-8); color: var(--color-text-muted);">
                        Nenhum post encontrado
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($postsRecentes as $post): ?>
                    <tr>
                        <td>
                            <a href="<?= SITE_URL ?>/admin/blog/editar.php?id=<?= $post['id'] ?>">
                                <?= e(truncate($post['titulo'], 40)) ?>
                            </a>
                        </td>
                        <td>
                            <span class="status-badge <?= $post['status'] ?>">
                                <?= ucfirst($post['status']) ?>
                            </span>
                        </td>
                        <td><?= formatDate($post['created_at'], 'd/m/Y') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Equipamentos Recentes -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3 class="dashboard-card-title">Equipamentos Recentes</h3>
            <a href="<?= SITE_URL ?>/admin/equipamentos/" class="btn btn-sm btn-outline">Ver Todos</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($equipamentosRecentes)): ?>
                <tr>
                    <td colspan="3" style="text-align: center; padding: var(--spacing-8); color: var(--color-text-muted);">
                        Nenhum equipamento encontrado
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($equipamentosRecentes as $equip): ?>
                    <tr>
                        <td>
                            <a href="<?= SITE_URL ?>/admin/equipamentos/editar.php?id=<?= $equip['id'] ?>">
                                <?= e(truncate($equip['nome'], 40)) ?>
                            </a>
                        </td>
                        <td><?= e($equip['categoria_nome'] ?? 'Sem categoria') ?></td>
                        <td>
                            <span class="status-badge <?= $equip['ativo'] ? 'active' : 'inactive' ?>">
                                <?= $equip['ativo'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<h3 class="dashboard-card-title" style="margin-bottom: var(--spacing-4);">Ações Rápidas</h3>
<div class="quick-actions">
    <a href="<?= SITE_URL ?>/admin/equipamentos/criar.php" class="quick-action">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
        </svg>
        <span>Novo Equipamento</span>
    </a>
    <a href="<?= SITE_URL ?>/admin/postes/criar.php" class="quick-action">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20"></path>
            <path d="M2 5h20"></path>
            <path d="M5 2v3"></path>
            <path d="M19 2v3"></path>
        </svg>
        <span>Novo Poste</span>
    </a>
    <a href="<?= SITE_URL ?>/admin/blog/criar.php" class="quick-action">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
        </svg>
        <span>Novo Post</span>
    </a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
