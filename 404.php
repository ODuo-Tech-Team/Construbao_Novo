<?php
/**
 * CONSTRUBÃO - Página 404
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Página não encontrada';
$pageDescription = 'A página que você procura não foi encontrada.';

http_response_code(404);

include __DIR__ . '/includes/header.php';
?>

<section class="bg-muted" style="padding: var(--spacing-20) 0; margin-top: 80px; min-height: 60vh; display: flex; align-items: center;">
    <div class="container text-center">
        <h1 style="font-size: 8rem; line-height: 1; color: var(--color-secondary); margin-bottom: var(--spacing-4);">404</h1>
        <h2 style="margin-bottom: var(--spacing-4);">Página não encontrada</h2>
        <p style="color: var(--color-text-muted); margin-bottom: var(--spacing-8); max-width: 400px; margin-left: auto; margin-right: auto;">
            A página que você está procurando não existe ou foi movida.
        </p>
        <div class="flex gap-4" style="justify-content: center; flex-wrap: wrap;">
            <a href="<?= SITE_URL ?>" class="btn btn-primary">
                Voltar ao Início
            </a>
            <a href="<?= SITE_URL ?>/equipamentos.php" class="btn btn-outline">
                Ver Equipamentos
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
