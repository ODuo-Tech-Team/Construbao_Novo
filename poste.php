<?php
/**
 * CONSTRUBÃO - Página de Postes
 * Listagem de todos os postes ou detalhe de um poste específico
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

// Obter slug do poste (se houver)
$slug = get('slug');

// Se tiver slug, mostrar página de detalhe
if ($slug) {
    // Buscar poste
    $poste = fetchOne('postes', 'slug = ? AND ativo = 1', [$slug]);

    if (!$poste) {
        header('Location: ' . SITE_URL . '/404.php');
        exit;
    }

    // Incrementar views
    update('postes', ['views' => $poste['views'] + 1], 'id = ?', [$poste['id']]);

    // Buscar outros postes (exceto o atual)
    $outrosPostes = fetchAll('postes', 'id != ? AND ativo = 1', [$poste['id']], 'ordem ASC');

    // Meta tags
    $pageTitle = $poste['meta_title'] ?: $poste['nome'];
    $pageDescription = $poste['meta_description'] ?: truncate($poste['descricao'], 160);

    // Características
    $caracteristicas = getCaracteristicas($poste['caracteristicas']);

    include __DIR__ . '/includes/header.php';
    ?>

    <!-- Breadcrumb -->
    <div style="background: #FAF6EB; padding: 24px 0; padding-top: 124px;">
        <div class="container" style="padding: 0 var(--spacing-4);">
            <a href="<?= SITE_URL ?>/poste.php" style="display: inline-flex; align-items: center; gap: 8px; color: var(--color-text-muted); text-decoration: none; font-family: var(--font-body); transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='var(--color-text-muted)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Voltar para Postes
            </a>
        </div>
    </div>

    <!-- Detail Section -->
    <section style="padding: 64px 0; background: #FAF6EB;">
        <div class="container" style="padding: 0 var(--spacing-4);">
            <div class="poste-detail-grid" style="display: grid; grid-template-columns: 1fr; gap: 48px; align-items: start;">
                <!-- Image Card -->
                <div data-animate="slide-up" style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div style="aspect-ratio: 1; display: flex; align-items: center; justify-content: center;">
                        <img src="<?= SITE_URL ?>/<?= e($poste['imagem']) ?>" alt="<?= e($poste['nome']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                </div>

                <!-- Content -->
                <div data-animate="slide-up" style="animation-delay: 0.1s;">
                    <span style="display: inline-block; padding: 6px 16px; background: rgba(255,186,0,0.1); color: var(--color-primary); font-family: var(--font-subtitle); font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 6px; margin-bottom: 16px;">Venda de Poste Padrão</span>

                    <h1 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.5rem); color: var(--color-foreground); margin-bottom: 16px; line-height: 1.2;"><?= e($poste['nome']) ?></h1>

                    <p style="font-family: var(--font-body); font-size: 1.125rem; color: var(--color-text-muted); line-height: 1.7; margin-bottom: 32px;">
                        <?= e($poste['descricao']) ?>
                    </p>

                    <?php if (!empty($caracteristicas)): ?>
                    <h3 style="font-family: var(--font-display); font-size: 1.25rem; color: var(--color-foreground); margin-bottom: 16px;">Características</h3>
                    <ul style="list-style: none; padding: 0; margin: 0 0 32px 0;">
                        <?php foreach ($caracteristicas as $carac): ?>
                        <li style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; font-family: var(--font-body); color: var(--color-text-muted);">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: rgba(37, 211, 102, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#25D366" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <?= e($carac) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                    <p style="font-family: var(--font-body); font-size: var(--text-sm); color: var(--color-text-muted); font-style: italic; margin-bottom: 32px;">
                        * Modelo e cores de tampas podem variar conforme disponibilidade
                    </p>

                    <a href="<?= whatsappLink('Olá! Gostaria de um orçamento para ' . $poste['nome']) ?>" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 12px; padding: 16px 40px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);" onmouseover="this.style.background='#1faa54'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#25D366'; this.style.transform='translateY(0)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                        </svg>
                        Solicitar Orçamento
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Outros Postes -->
    <?php if (!empty($outrosPostes)): ?>
    <section style="padding: 96px 0; background: white;">
        <div class="container" style="padding: 0 var(--spacing-4);">
            <div style="text-align: center; margin-bottom: 48px;" data-animate="slide-up">
                <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600;">Outros Modelos</span>
                <h2 style="font-family: var(--font-display); font-size: clamp(1.875rem, 4vw, 2.5rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Conheça Outros Postes</h2>
            </div>

            <div class="outros-postes-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                <?php foreach ($outrosPostes as $index => $p): ?>
                <div class="outro-poste-card" data-animate="slide-up" style="display: flex; flex-direction: column; background: white; border-radius: 16px; border: 1px solid var(--color-border); box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation-delay: <?= $index * 0.1 ?>s;">
                    <a href="<?= SITE_URL ?>/poste.php?slug=<?= e($p['slug']) ?>" style="display: block; flex: 1;">
                        <div style="aspect-ratio: 1; padding: 16px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                            <img src="<?= SITE_URL ?>/<?= e($p['imagem']) ?>" alt="<?= e($p['nome']) ?>" class="outro-poste-img" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s ease;">
                        </div>
                        <div style="padding: 16px 16px 8px; text-align: center;">
                            <h3 style="font-family: var(--font-display); font-size: var(--text-lg); color: var(--color-foreground); margin: 0;"><?= e($p['nome']) ?></h3>
                        </div>
                    </a>
                    <div style="padding: 0 16px 16px; text-align: center;">
                        <a href="<?= whatsappLink('Olá! Gostaria de um orçamento para ' . $p['nome']) ?>" target="_blank" class="btn-card-cta" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 12px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>
                            Orçamento
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <style>
    @media (min-width: 1024px) {
        .poste-detail-grid {
            grid-template-columns: 1fr 1fr !important;
        }
    }
    @media (min-width: 768px) {
        .outros-postes-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    .outro-poste-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        border-color: var(--color-primary);
        transform: translateY(-4px);
    }
    .outro-poste-card:hover .outro-poste-img {
        transform: scale(1.05);
    }
    .btn-card-cta:hover {
        background: #1faa54 !important;
        transform: scale(1.02);
    }
    </style>

    <?php
    include __DIR__ . '/includes/cta-final.php';
    include __DIR__ . '/includes/footer.php';

} else {
    // Página de listagem de postes

    // Meta tags
    $pageTitle = 'Poste Padrão CPFL';
    $pageDescription = 'Oferecemos postes padrão homologados pela CPFL, ideais para residências, comércios e obras. Qualidade e durabilidade garantidas para sua instalação elétrica.';

    // Buscar todos os postes
    $postes = fetchAll('postes', 'ativo = 1', [], 'ordem ASC');

    include __DIR__ . '/includes/header.php';
    ?>

    <!-- Hero Section - Banner Promocional -->
    <section class="hero-promo-poste" style="position: relative; min-height: 500px; display: flex; align-items: center; margin-top: 100px; overflow: hidden;">
        <!-- Background Image -->
        <div style="position: absolute; inset: 0; background: url('<?= ASSETS_URL ?>images/POSTE_ANA.png') center/cover no-repeat;"></div>
        <!-- Overlay Gradient -->
        <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(10,42,63,0.95) 0%, rgba(10,42,63,0.7) 50%, rgba(10,42,63,0.3) 100%);"></div>

        <div class="container" style="position: relative; z-index: 1; padding: 64px var(--spacing-4);">
            <div style="max-width: 600px;">
                <!-- Badge Promo -->
                <span style="display: inline-block; background: var(--color-primary); color: var(--color-secondary); padding: 8px 16px; border-radius: 50px; font-family: var(--font-subtitle); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 24px;">
                    Oferta Especial
                </span>

                <h1 style="font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3rem); color: white; line-height: 1.1; margin-bottom: 16px;">
                    Na compra de poste, ganhe <span style="color: var(--color-primary);">1 mes de locacao de container + frete gratis</span>
                </h1>

                <p style="font-family: var(--font-body); font-size: 1.25rem; color: rgba(255,255,255,0.85); line-height: 1.6; margin-bottom: 32px; max-width: 500px;">
                    A solucao completa para organizar sua obra desde o inicio, reduzindo custos e otimizando o espaco.
                </p>

                <a href="<?= whatsappLink('Ola! Vi a promocao do poste com container gratis e gostaria de falar com um especialista.') ?>" target="_blank" class="btn-promo-poste" style="display: inline-flex; align-items: center; gap: 12px; background: #25D366; color: white; padding: 16px 32px; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                    </svg>
                    Fale com um Especialista
                </a>
            </div>
        </div>
    </section>

    <style>
    .btn-promo-poste:hover {
        background: #1faa54 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
    }
    @media (max-width: 768px) {
        .hero-promo-poste {
            min-height: 450px !important;
        }
        .hero-promo-poste > div:first-child {
            background-position: 70% center !important;
        }
    }
    </style>

    <!-- Postes Section -->
    <section style="padding: 96px 0; background: #FAF6EB;">
        <div class="container" style="padding: 0 var(--spacing-4);">
            <!-- Header -->
            <div style="margin-bottom: 64px;" data-animate="slide-up">
                <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600;">Modelos Disponíveis</span>
                <h2 style="font-family: var(--font-display); font-size: clamp(1.875rem, 4vw, 2.5rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Venda de Poste Padrão</h2>
                <p style="font-family: var(--font-body); color: var(--color-text-muted); margin-top: var(--spacing-4); line-height: 1.6;">Postes homologados pela CPFL, ideais para residências, comércios e obras.</p>
            </div>

            <!-- Grid de Postes -->
            <div class="postes-list-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                <?php foreach ($postes as $index => $poste): ?>
                <div class="poste-list-card" data-animate="slide-up" style="display: flex; flex-direction: column; background: white; border-radius: 16px; border: 1px solid var(--color-border); box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation-delay: <?= $index * 0.05 ?>s;">
                    <a href="<?= SITE_URL ?>/poste.php?slug=<?= e($poste['slug']) ?>" style="display: block; flex: 1;">
                        <div style="aspect-ratio: 1; padding: 16px; display: flex; align-items: center; justify-content: center; background: white;">
                            <img src="<?= SITE_URL ?>/<?= e($poste['imagem']) ?>" alt="<?= e($poste['nome']) ?>" class="poste-list-img" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s ease;">
                        </div>
                        <div style="padding: 24px 24px 12px; text-align: center;">
                            <h3 style="font-family: var(--font-display); font-size: 1.25rem; color: var(--color-foreground); margin: 0;"><?= e($poste['nome']) ?></h3>
                        </div>
                    </a>
                    <div style="padding: 0 24px 24px; text-align: center;">
                        <a href="<?= whatsappLink('Olá! Gostaria de um orçamento para ' . $poste['nome']) ?>" target="_blank" class="btn-card-cta" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px 16px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>
                            Orçamento
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Footnote -->
            <p style="text-align: center; margin-top: 32px; color: var(--color-text-muted); font-size: var(--text-sm);">
                * Modelo e cores de tampas podem variar conforme disponibilidade.
            </p>
        </div>
    </section>

    <style>
    /* Postes List Grid - Responsive */
    @media (min-width: 640px) {
        .postes-list-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    @media (min-width: 1024px) {
        .postes-list-grid {
            grid-template-columns: repeat(4, 1fr) !important;
        }
    }
    .poste-list-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        border-color: var(--color-primary);
        transform: translateY(-4px);
    }
    .poste-list-card:hover .poste-list-img {
        transform: scale(1.05);
    }
    .btn-card-cta:hover {
        background: #1faa54 !important;
        transform: scale(1.02);
    }
    </style>

    <?php
    include __DIR__ . '/includes/cta-final.php';
    include __DIR__ . '/includes/footer.php';
}
?>
