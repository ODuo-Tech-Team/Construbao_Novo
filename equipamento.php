<?php
/**
 * CONSTRUBÃO - Página de Detalhe do Equipamento
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

// Obter slug do equipamento
$slug = get('slug');

if (!$slug) {
    header('Location: ' . SITE_URL . '/equipamentos.php');
    exit;
}

// Buscar equipamento
$equipamento = fetchOne('equipamentos', 'slug = ? AND ativo = 1', [$slug]);

if (!$equipamento) {
    header('Location: ' . SITE_URL . '/404.php');
    exit;
}

// Incrementar views
update('equipamentos', ['views' => $equipamento['views'] + 1], 'id = ?', [$equipamento['id']]);

// Buscar outros equipamentos (exceto o atual)
$outrosEquipamentos = fetchAll('equipamentos', 'id != ? AND ativo = 1', [$equipamento['id']], 'RAND()');
$outrosEquipamentos = array_slice($outrosEquipamentos, 0, 4);

// Meta tags
$pageTitle = $equipamento['meta_title'] ?: $equipamento['nome'];
$pageDescription = $equipamento['meta_description'] ?: truncate($equipamento['descricao'], 160);

// Características
$caracteristicas = getCaracteristicas($equipamento['caracteristicas']);

include __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb -->
<div style="background: #FAF6EB; padding: 24px 0; padding-top: 124px;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <a href="<?= SITE_URL ?>/equipamentos.php" style="display: inline-flex; align-items: center; gap: 8px; color: var(--color-text-muted); text-decoration: none; font-family: var(--font-body); transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='var(--color-text-muted)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>
            Voltar para Equipamentos
        </a>
    </div>
</div>

<!-- Detail Section -->
<section style="padding: 64px 0; background: #FAF6EB;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div class="detail-grid" style="display: grid; grid-template-columns: 1fr; gap: 48px; align-items: start;">
            <!-- Image Card -->
            <div data-animate="slide-right" style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                <div style="aspect-ratio: 1; display: flex; align-items: center; justify-content: center;">
                    <img src="<?= imageUrl($equipamento['imagem']) ?>" alt="<?= e($equipamento['nome']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
            </div>

            <!-- Content -->
            <div data-animate="slide-left" style="display: flex; flex-direction: column; gap: 32px;">
                <!-- Header -->
                <div>
                    <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600;">Locação de Equipamentos</span>
                    <h1 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;"><?= e($equipamento['nome']) ?></h1>
                </div>

                <!-- Description -->
                <p style="font-family: var(--font-body); font-size: 1.125rem; color: var(--color-text-muted); line-height: 1.6; margin: 0;">
                    <?= e($equipamento['descricao']) ?>
                </p>

                <!-- Características -->
                <?php if (!empty($caracteristicas)): ?>
                <div>
                    <h3 style="font-family: var(--font-display); font-size: 1.25rem; color: var(--color-foreground); margin-bottom: 16px;">Características</h3>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($caracteristicas as $carac): ?>
                        <li style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: rgba(255, 186, 0, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </div>
                            <span style="font-family: var(--font-body); color: var(--color-foreground);"><?= e($carac) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- CTA Button -->
                <div style="display: flex; flex-direction: column; gap: 16px; padding-top: 16px;">
                    <a href="<?= whatsappLink('Olá, gostaria de mais informações sobre ' . $equipamento['nome'] . '! [Por favor, enviar essa mensagem]') ?>" target="_blank" rel="noopener noreferrer" class="whatsapp-cta-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 16px 32px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                        </svg>
                        Solicitar Orçamento
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Outros Equipamentos -->
<?php if (!empty($outrosEquipamentos)): ?>
<section style="padding: 64px 0; background: #fff;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <h2 style="font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2rem); color: var(--color-foreground); margin-bottom: 32px;">Outros Equipamentos</h2>

        <div class="outros-equip-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <?php foreach ($outrosEquipamentos as $equip): ?>
            <a href="<?= SITE_URL ?>/equipamento.php?slug=<?= e($equip['slug']) ?>" class="outro-equip-card" style="display: block; background: white; border-radius: 12px; border: 1px solid var(--color-border); padding: 16px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; text-decoration: none;">
                <div style="aspect-ratio: 1; margin-bottom: 16px; overflow: hidden; border-radius: 8px; background: white; display: flex; align-items: center; justify-content: center; padding: 8px;">
                    <img src="<?= imageUrl($equip['imagem']) ?>" alt="<?= e($equip['nome']) ?>" class="outro-equip-img" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s ease;">
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.125rem; color: var(--color-foreground); margin: 0;"><?= e($equip['nome']) ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
/* Detail Grid - Responsive */
@media (min-width: 1024px) {
    .detail-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}

/* Outros Equipamentos Grid - Responsive */
@media (min-width: 640px) {
    .outros-equip-grid {
        grid-template-columns: repeat(4, 1fr) !important;
    }
}

/* WhatsApp CTA Button Hover */
.whatsapp-cta-btn:hover {
    background: #1faa54;
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(37, 211, 102, 0.4);
}

/* Outro Equipamento Card Hover */
.outro-equip-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    border-color: var(--color-primary);
}

.outro-equip-card:hover .outro-equip-img {
    transform: scale(1.05);
}
</style>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>