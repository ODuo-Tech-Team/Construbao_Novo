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

<!-- Hero Section - Banner Promocional -->
<section class="hero-promo-equip" style="position: relative; min-height: 500px; display: flex; align-items: center; margin-top: 100px; overflow: hidden;">
    <!-- Background Image -->
    <div style="position: absolute; inset: 0; background: url('<?= ASSETS_URL ?>images/BETONEIRA_ANA.png') center/cover no-repeat;"></div>
    <!-- Overlay Gradient -->
    <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(10,42,63,0.95) 0%, rgba(10,42,63,0.7) 50%, rgba(10,42,63,0.3) 100%);"></div>

    <div class="container" style="position: relative; z-index: 1; padding: 64px var(--spacing-4);">
        <div style="max-width: 600px;">
            <!-- Badge Promo -->
            <span style="display: inline-block; background: var(--color-primary); color: var(--color-secondary); padding: 8px 16px; border-radius: 50px; font-family: var(--font-subtitle); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 24px;">
                Combo Promocional
            </span>

            <h1 style="font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3rem); color: white; line-height: 1.1; margin-bottom: 16px;">
                Alugue container e betoneira com <span style="color: var(--color-primary);">frete gratis</span>
            </h1>

            <p style="font-family: var(--font-body); font-size: 1.25rem; color: rgba(255,255,255,0.85); line-height: 1.6; margin-bottom: 32px; max-width: 500px;">
                Mais praticidade e economia para sua obra, com equipamentos essenciais entregues sem custo de transporte.
            </p>

            <a href="<?= whatsappLink('Ola! Vi a promocao de container + betoneira com frete gratis e gostaria de um orcamento.') ?>" target="_blank" class="btn-promo-equip" style="display: inline-flex; align-items: center; gap: 12px; background: #25D366; color: white; padding: 16px 32px; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                </svg>
                Solicite um Orcamento
            </a>
        </div>
    </div>
</section>

<style>
.btn-promo-equip:hover {
    background: #1faa54 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
}
@media (max-width: 768px) {
    .hero-promo-equip {
        min-height: 450px !important;
    }
    .hero-promo-equip > div:first-child {
        background-position: 70% center !important;
    }
}
</style>

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
            <div class="equip-card" data-animate="slide-up" style="display: flex; flex-direction: column; background: white; border-radius: 12px; border: 1px solid var(--color-border); padding: 16px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; animation-delay: <?= $index * 0.05 ?>s;">
                <a href="<?= SITE_URL ?>/equipamento.php?slug=<?= e($equip['slug']) ?>" style="display: block; flex: 1;">
                    <div style="aspect-ratio: 1; margin-bottom: 16px; overflow: hidden; border-radius: 8px; background: white; display: flex; align-items: center; justify-content: center; padding: 8px;">
                        <img src="<?= SITE_URL ?>/<?= e($equip['imagem']) ?>" alt="<?= e($equip['nome']) ?>" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s ease;">
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: var(--text-lg); color: var(--color-foreground); margin: 0 0 12px 0;"><?= e($equip['nome']) ?></h3>
                </a>
                <a href="<?= whatsappLink('Olá! Gostaria de alugar ' . $equip['nome']) ?>" target="_blank" class="btn-card-cta" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 12px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>
                    Alugar
                </a>
            </div>
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
.btn-card-cta:hover {
    background: #1faa54 !important;
    transform: scale(1.02);
}
</style>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>