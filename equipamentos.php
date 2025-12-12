<?php
/**
 * CONSTRUBÃO - Página de Equipamentos
 * Listagem de todos os equipamentos
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

// Meta tags para SEO
$pageTitle = 'Equipamentos para Locação';
$pageDescription = 'Confira nosso catálogo completo de equipamentos para locação. Betoneiras, compactadores, geradores, rompedores e muito mais para sua obra!';

// Buscar dados do banco
$equipamentos = fetchAll('equipamentos', 'ativo = 1', [], 'ordem ASC');

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section style="background: var(--color-secondary); padding: 96px 0; margin-top: 100px;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div style="max-width: 768px;">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Locação de Equipamentos</span>
            <h1 style="font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 3.5rem); color: white; margin-top: var(--spacing-4); line-height: 1.1;">Equipamentos para Construção</h1>
            <p style="font-family: var(--font-body); font-size: 1.25rem; color: rgba(255,255,255,0.7); margin-top: var(--spacing-6); max-width: 640px; line-height: 1.6;">
                Disponibilizamos uma ampla linha de equipamentos revisados e prontos para facilitar o dia a dia da sua obra. Conte com a Construbão para ter produtividade, segurança e qualidade em cada etapa do seu projeto.
            </p>
        </div>
    </div>
</section>

<!-- Equipamentos Section -->
<section style="padding: 96px 0; background: white;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header -->
        <div style="margin-bottom: 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600;">Equipamentos Disponíveis</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(1.875rem, 4vw, 2.5rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Locação de Equipamentos para Construção</h2>
        </div>

        <!-- Grid de Equipamentos -->
        <div class="equipamentos-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <?php foreach ($equipamentos as $index => $equip): ?>
            <a href="<?= SITE_URL ?>/equipamento.php?slug=<?= e($equip['slug']) ?>" class="equip-card" data-animate="slide-up" style="display: block; background: white; border-radius: 12px; border: 1px solid var(--color-border); padding: 16px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; animation-delay: <?= $index * 0.05 ?>s;">
                <div style="aspect-ratio: 1; margin-bottom: 16px; overflow: hidden; border-radius: 8px; background: white; display: flex; align-items: center; justify-content: center; padding: 8px;">
                    <img src="<?= SITE_URL ?>/<?= e($equip['imagem']) ?>" alt="<?= e($equip['nome']) ?>" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s ease;">
                </div>
                <h3 style="font-family: var(--font-display); font-size: var(--text-lg); color: var(--color-foreground); margin: 0;"><?= e($equip['nome']) ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Responsive Grid -->
<style>
@media (min-width: 640px) {
    .equipamentos-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}
@media (min-width: 1024px) {
    .equipamentos-grid {
        grid-template-columns: repeat(4, 1fr) !important;
    }
}
@media (min-width: 1280px) {
    .equipamentos-grid {
        grid-template-columns: repeat(5, 1fr) !important;
    }
}
.equip-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    border-color: var(--color-primary);
    transform: translateY(-4px);
}
.equip-card:hover img {
    transform: scale(1.05);
}
</style>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>