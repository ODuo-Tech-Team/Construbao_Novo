<?php
/**
 * ADMIN - Listar Depoimentos
 */

$pageTitle = 'Depoimentos';
require_once __DIR__ . '/../includes/header.php';

// Query
$depoimentos = query("SELECT * FROM depoimentos ORDER BY ordem ASC, created_at DESC");
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Depoimentos</h1>
    <a href="<?= SITE_URL ?>/admin/depoimentos/criar.php" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        Novo Depoimento
    </a>
</div>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <span style="color: var(--color-text-muted); font-size: var(--text-sm);">
            <?= count($depoimentos) ?> depoimento(s)
        </span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="60">Foto</th>
                <th>Nome</th>
                <th>Depoimento</th>
                <th>Avaliação</th>
                <th>Status</th>
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($depoimentos)): ?>
            <tr>
                <td colspan="6" class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <p>Nenhum depoimento encontrado</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($depoimentos as $depoimento): ?>
                <tr>
                    <td>
                        <?php if ($depoimento['foto']): ?>
                        <img src="<?= imageUrl($depoimento['foto'], 'depoimentos') ?>" alt="<?= e($depoimento['nome']) ?>" class="table-thumbnail" style="border-radius: 50%;">
                        <?php else: ?>
                        <div class="admin-user-avatar" style="width: 50px; height: 50px;">
                            <?= strtoupper(substr($depoimento['nome'], 0, 1)) ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= e($depoimento['nome']) ?></strong>
                        <?php if ($depoimento['data_depoimento']): ?>
                        <br>
                        <small style="color: var(--color-text-muted);"><?= formatDate($depoimento['data_depoimento'], 'd/m/Y') ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= e(truncate($depoimento['texto'], 80)) ?></td>
                    <td>
                        <span style="color: var(--color-primary);">
                            <?= str_repeat('★', $depoimento['avaliacao']) ?><?= str_repeat('☆', 5 - $depoimento['avaliacao']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?= $depoimento['ativo'] ? 'active' : 'inactive' ?>">
                            <?= $depoimento['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td>
                        <div class="data-table-actions">
                            <a href="<?= SITE_URL ?>/admin/depoimentos/editar.php?id=<?= $depoimento['id'] ?>" class="btn btn-sm btn-outline" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                </svg>
                            </a>
                            <a href="<?= SITE_URL ?>/admin/depoimentos/excluir.php?id=<?= $depoimento['id'] ?>" class="btn btn-sm btn-outline confirm-delete" data-confirm="Tem certeza que deseja excluir este depoimento?" title="Excluir">
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
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
