<?php
/**
 * CONSTRUBÃO - Home Page
 * Página inicial com todas as seções
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

// Meta tags para SEO
$pageTitle = 'Venda de Poste Padrão e Locação de Equipamentos';
$pageDescription = 'Construbão - Locação de equipamentos para construção e venda de postes padrão em São Carlos e região. Mais de 10 anos de experiência!';

// Buscar dados do banco
try {
    $equipamentos = fetchAll('equipamentos', 'ativo = 1', [], 'ordem ASC');
    $postes = fetchAll('postes', 'ativo = 1', [], 'ordem ASC');
    $depoimentos = fetchAll('depoimentos', 'ativo = 1', [], 'ordem ASC');
} catch (Exception $e) {
    $equipamentos = [];
    $postes = [];
    $depoimentos = [];
}

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" style="position: relative; min-height: 100vh; display: flex; align-items: center; padding-top: 80px; background: #000;">
    <!-- Background Image -->
    <div style="position: absolute; inset: 0; background: url('<?= ASSETS_URL ?>images/hero-construction.jpg') center/cover no-repeat; opacity: 0.4;"></div>
    <!-- Overlay -->
    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.6);"></div>

    <div class="container" style="position: relative; z-index: 1; padding-top: 48px; padding-bottom: 48px;">
        <div class="hero-content" style="max-width: 800px;">
            <!-- Badge -->
            <div class="animate-fade-in" style="margin-bottom: var(--spacing-6);">
                <span style="display: inline-flex; align-items: center; gap: 8px; background: rgba(10, 42, 63, 0.8); border: 1px solid var(--color-secondary); color: white; padding: 8px 16px; border-radius: 50px; font-family: var(--font-subtitle); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">
                    <span style="width: 8px; height: 8px; background: var(--color-primary); border-radius: 50%; display: inline-block; animation: pulse 2s infinite;"></span>
                    São Carlos e Região
                </span>
            </div>

            <!-- Title -->
            <h1 class="animate-slide-up" style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 600; color: white; line-height: 1.1; margin-bottom: var(--spacing-5);">
                Venda de Poste Padrão e Locação de <span style="color: var(--color-primary);">Equipamentos para Construção</span>
            </h1>

            <!-- Description -->
            <p class="animate-slide-up" style="font-family: var(--font-subtitle); font-size: clamp(1rem, 1.5vw, 1.25rem); color: rgba(255,255,255,0.8); max-width: 580px; margin-bottom: var(--spacing-8); animation-delay: 0.1s;">
                Soluções seguras, rápidas e com preço justo para sua obra.
            </p>

            <!-- CTAs -->
            <div class="animate-slide-up" style="display: flex; flex-wrap: wrap; gap: var(--spacing-3); margin-bottom: var(--spacing-12); animation-delay: 0.2s;">
                <a href="<?= whatsappLink() ?>" target="_blank" class="btn" style="display: inline-flex; align-items: center; gap: 8px; background: #25D366; color: white; padding: 14px 32px; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4); transition: all 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                    </svg>
                    Fazer Orçamento
                </a>
                <a href="<?= SITE_URL ?>/equipamentos.php" class="btn" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; color: var(--color-primary); padding: 14px 32px; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border: 2px solid var(--color-primary); border-radius: 8px; transition: all 0.3s ease;">
                    Ver Equipamentos
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Stats -->
            <div class="animate-slide-up" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--spacing-6); max-width: 420px; animation-delay: 0.3s;">
                <div>
                    <p style="font-family: var(--font-display); font-size: clamp(2rem, 3vw, 2.75rem); color: var(--color-primary); line-height: 1; margin: 0;">10+</p>
                    <p style="font-family: var(--font-subtitle); font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.7); margin-top: 4px;">Anos de Experiência</p>
                </div>
                <div>
                    <p style="font-family: var(--font-display); font-size: clamp(2rem, 3vw, 2.75rem); color: var(--color-primary); line-height: 1; margin: 0;">500+</p>
                    <p style="font-family: var(--font-subtitle); font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.7); margin-top: 4px;">Clientes Atendidos</p>
                </div>
                <div>
                    <p style="font-family: var(--font-display); font-size: clamp(2rem, 3vw, 2.75rem); color: var(--color-primary); line-height: 1; margin: 0;">100%</p>
                    <p style="font-family: var(--font-subtitle); font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.7); margin-top: 4px;">Satisfação</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Diagonal stripes -->
    <div class="diagonal-stripes" style="position: absolute; bottom: 0; left: 0; right: 0; height: 16px; opacity: 0.8;"></div>
</section>

<!-- Postes Section -->
<section style="padding: 96px 0; background: white;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-primary); font-weight: 600;">Nossos Produtos</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Venda de Poste Padrão</h2>
            <p style="font-family: var(--font-body); color: var(--color-text-muted); margin-top: var(--spacing-4); line-height: 1.6;">Postes de concreto homologados pela CPFL para entrada de energia. Diversos modelos para atender sua necessidade.</p>
        </div>

        <!-- Grid de Postes -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 24px;" class="postes-grid">
            <?php foreach ($postes as $index => $poste): ?>
            <a href="<?= SITE_URL ?>/poste.php?slug=<?= e($poste['slug']) ?>" class="poste-card" data-animate="slide-up" data-animate-delay="<?= $index + 1 ?>" style="display: block; background: white; border-radius: 16px; border: 1px solid var(--color-border); box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease;">
                <div style="aspect-ratio: 1; padding: 24px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                    <img src="<?= SITE_URL ?>/<?= e($poste['imagem']) ?>" alt="<?= e($poste['nome']) ?>" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.5s ease;">
                </div>
                <div style="padding: 20px; text-align: center;">
                    <h3 style="font-family: var(--font-display); font-size: var(--text-lg); color: var(--color-foreground); margin: 0;"><?= e($poste['nome']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Footnote -->
        <p style="text-align: center; margin-top: 32px; color: var(--color-text-muted); font-size: var(--text-sm);">
            * Modelo e cores de tampas podem variar conforme disponibilidade
        </p>
    </div>
</section>

<style>
/* Postes Section - Responsive */
@media (min-width: 640px) {
    .postes-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (min-width: 1024px) {
    .postes-grid {
        grid-template-columns: repeat(4, 1fr) !important;
    }
}
.poste-card:hover {
    border-color: var(--color-primary);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    transform: translateY(-4px);
}
.poste-card:hover img {
    transform: scale(1.05);
}
</style>

<!-- Equipamentos Section -->
<section style="padding: 96px 0; background: var(--color-background-muted);">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header -->
        <div style="display: flex; flex-direction: column; gap: var(--spacing-6); margin-bottom: var(--spacing-16);" class="equipamentos-header">
            <div data-animate="slide-up">
                <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-primary); font-weight: 600;">Equipamentos Disponíveis</span>
                <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Locação de Equipamentos para Construção</h2>
            </div>
            <a href="<?= SITE_URL ?>/equipamentos.php" data-animate="slide-up" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; color: var(--color-primary); padding: 12px 24px; font-family: var(--font-subtitle); font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border: 2px solid var(--color-primary); border-radius: 8px; transition: all 0.3s ease; width: fit-content;">
                Ver Todos
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="m12 5 7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- Grid de Equipamentos -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--spacing-4);" class="equipamentos-grid">
            <?php
            $equipamentosPreview = array_slice($equipamentos, 0, 5);
            foreach ($equipamentosPreview as $index => $equip):
            ?>
            <a href="<?= SITE_URL ?>/equipamento.php?slug=<?= e($equip['slug']) ?>" class="equip-card" data-animate="slide-up" data-animate-delay="<?= $index + 1 ?>" style="position: relative; aspect-ratio: 1; border-radius: 16px; overflow: hidden; display: block; background: #f5f5f5;">
                <img src="<?= SITE_URL ?>/<?= e($equip['imagem']) ?>" alt="<?= e($equip['nome']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 60%); transition: background 0.3s ease;"></div>
                <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: var(--spacing-4); transform: translateY(0); transition: transform 0.3s ease;">
                    <h3 style="font-family: var(--font-display); font-size: var(--text-lg); color: white; margin: 0; line-height: 1.2;"><?= e($equip['nome']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
/* Equipamentos Section - Responsive */
@media (min-width: 640px) {
    .equipamentos-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}
@media (min-width: 1024px) {
    .equipamentos-grid {
        grid-template-columns: repeat(5, 1fr) !important;
    }
    .equipamentos-header {
        flex-direction: row !important;
        align-items: flex-end !important;
        justify-content: space-between !important;
    }
}
.equip-card:hover img {
    transform: scale(1.1);
}
.equip-card:hover > div:first-of-type {
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 100%);
}
</style>

<!-- Categorias Section -->
<section style="padding: 80px 0; background: var(--color-background-muted);">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; margin-bottom: 48px;" data-animate="slide-up">
            <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3rem); color: var(--color-secondary); line-height: 1.1;">Categorias de Equipamentos</h2>
        </div>

        <!-- Grid 2 colunas -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 32px; max-width: 900px; margin: 0 auto;" class="categorias-grid">
            <!-- Poste Padrão -->
            <a href="<?= SITE_URL ?>/poste.php" class="categoria-card" data-animate="slide-up" data-animate-delay="1" style="display: block; overflow: hidden; border-radius: 16px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <img src="<?= ASSETS_URL ?>images/categoria-poste-padrao.png" alt="Poste Padrão" style="width: 100%; height: auto; object-fit: contain; display: block;">
            </a>

            <!-- Locação de Equipamentos -->
            <a href="<?= SITE_URL ?>/equipamentos.php" class="categoria-card" data-animate="slide-up" data-animate-delay="2" style="display: block; overflow: hidden; border-radius: 16px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <img src="<?= ASSETS_URL ?>images/categoria-locacao-equipamentos.png" alt="Locação de Equipamentos" style="width: 100%; height: auto; object-fit: contain; display: block;">
            </a>
        </div>
    </div>
</section>

<style>
/* Categorias Section - Responsive */
@media (min-width: 768px) {
    .categorias-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
.categoria-card:hover {
    transform: scale(1.03);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
</style>

<!-- Vantagens Section -->
<section style="padding: 96px 0; background: var(--color-background-muted);">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-primary); font-weight: 600;">Por que escolher a Construbão?</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Diferenciais que Fazem a Diferença</h2>
        </div>

        <!-- Grid de Cards -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 32px;" class="diferenciais-grid">
            <?php
            $vantagens = [
                ['icon' => 'wrench', 'title' => 'Equipamentos Profissionais', 'desc' => 'Máquinas modernas, revisadas e prontas para qualquer tipo de obra.'],
                ['icon' => 'users', 'title' => 'Equipe Qualificada', 'desc' => 'Profissionais experientes garantindo orientação e escolha correta.'],
                ['icon' => 'truck', 'title' => 'Entrega Veloz', 'desc' => 'Agilidade e compromisso total com prazos de entrega.'],
                ['icon' => 'shield', 'title' => 'Produtos Homologados', 'desc' => 'Postes e equipamentos certificados pela CPFL e órgãos competentes.'],
                ['icon' => 'clock', 'title' => 'Atendimento Ágil', 'desc' => 'Resposta rápida via WhatsApp e suporte durante toda a locação.'],
                ['icon' => 'award', 'title' => 'Preço Justo', 'desc' => 'Excelente custo-benefício para obras de todos os portes.']
            ];

            $icons = [
                'wrench' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>',
                'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                'truck' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path><path d="M15 18H9"></path><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path><circle cx="17" cy="18" r="2"></circle><circle cx="7" cy="18" r="2"></circle></svg>',
                'shield' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>',
                'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
                'award' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path><circle cx="12" cy="8" r="6"></circle></svg>'
            ];

            foreach ($vantagens as $index => $vantagem):
            ?>
            <div class="diferencial-card" data-animate="slide-up" style="background: white; border-radius: 16px; border: 2px solid rgba(255, 186, 0, 0.2); box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; animation-delay: <?= $index * 0.1 ?>s;">
                <div style="padding: 32px;">
                    <div style="width: 56px; height: 56px; border-radius: 12px; background: rgba(255, 186, 0, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; color: var(--color-primary);">
                        <?= $icons[$vantagem['icon']] ?>
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 12px;"><?= $vantagem['title'] ?></h3>
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.6; margin: 0;"><?= $vantagem['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
/* Diferenciais Section - Responsive */
@media (min-width: 768px) {
    .diferenciais-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (min-width: 1024px) {
    .diferenciais-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}
.diferencial-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    border-color: rgba(255, 186, 0, 0.4);
    transform: translateY(-8px);
}
</style>

<!-- Sobre Section -->
<section style="padding: 96px 0; background: #ffb700;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div class="sobre-grid" style="display: grid; grid-template-columns: 1fr; gap: 64px; align-items: center;">
            <!-- Image -->
            <div data-animate="slide-right" style="position: relative;">
                <div style="aspect-ratio: 4/3; border-radius: 16px; overflow: hidden;">
                    <img src="<?= ASSETS_URL ?>images/sobre-betoneira.png" alt="Betoneira Menegotti - Construbão" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <!-- Elemento decorativo -->
                <div style="position: absolute; bottom: -32px; right: -32px; width: 256px; height: 256px; background: rgba(255, 186, 0, 0.1); border-radius: 16px; z-index: -1;"></div>
                <!-- Badge Anos -->
                <div style="position: absolute; bottom: -24px; left: -24px; background: var(--color-secondary); border-radius: 16px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); z-index: 2;">
                    <p style="font-family: var(--font-display); font-size: 3rem; color: var(--color-primary); line-height: 1; margin: 0;">10+</p>
                    <p style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.7); margin-top: 4px; line-height: 1.3;">Anos de<br>Experiência</p>
                </div>
            </div>

            <!-- Content -->
            <div data-animate="slide-left">
                <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: #f37520; font-weight: 600;">Sobre a Construbão</span>
                <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-top: var(--spacing-4); margin-bottom: var(--spacing-6); line-height: 1.1;">Seu Parceiro Ideal para Obras</h2>

                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px;">
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.7; margin: 0;">
                        Há anos atendendo São Carlos e região com produtos homologados, atendimento diferenciado e soluções ágeis para obras residenciais, comerciais e industriais.
                    </p>
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.7; margin: 0;">
                        Oferecemos produtos homologados, equipamentos modernos e um atendimento personalizado que garante segurança, agilidade e tranquilidade em cada etapa da sua obra.
                    </p>
                </div>

                <!-- Stats -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 40px;">
                    <div style="text-align: center;">
                        <p style="font-family: var(--font-display); font-size: 1.875rem; color: var(--color-secondary); margin: 0;">500+</p>
                        <p style="font-family: var(--font-subtitle); font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); margin-top: 4px;">Clientes</p>
                        <div style="width: 48px; height: 4px; background: var(--color-secondary); margin: 12px auto 0; border-radius: 9999px;"></div>
                    </div>
                    <div style="text-align: center;">
                        <p style="font-family: var(--font-display); font-size: 1.875rem; color: var(--color-secondary); margin: 0;">50+</p>
                        <p style="font-family: var(--font-subtitle); font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); margin-top: 4px;">Equipamentos</p>
                        <div style="width: 48px; height: 4px; background: var(--color-secondary); margin: 12px auto 0; border-radius: 9999px;"></div>
                    </div>
                    <div style="text-align: center;">
                        <p style="font-family: var(--font-display); font-size: 1.875rem; color: var(--color-secondary); margin: 0;">100%</p>
                        <p style="font-family: var(--font-subtitle); font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); margin-top: 4px;">Satisfação</p>
                        <div style="width: 48px; height: 4px; background: var(--color-secondary); margin: 12px auto 0; border-radius: 9999px;"></div>
                    </div>
                </div>

                <a href="<?= SITE_URL ?>/sobre.php" class="sobre-btn" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; color: var(--color-secondary); padding: 12px 32px; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border: 2px solid var(--color-secondary); border-radius: 8px; transition: all 0.3s ease;">
                    Conhecer Mais
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Sobre Section - Responsive */
@media (min-width: 1024px) {
    .sobre-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}
.sobre-btn:hover {
    background: var(--color-secondary);
    color: white;
}
</style>

<!-- Depoimentos Section -->
<?php if (!empty($depoimentos)): ?>
<section style="padding: 96px 0; background: var(--color-secondary);">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Depoimentos</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: white; margin-top: var(--spacing-4); line-height: 1.1;">O Que Nossos Clientes Dizem</h2>
        </div>

        <!-- Carousel -->
        <div id="depoimentos-carousel" class="carousel" data-carousel data-autoplay="true" data-interval="5000" data-slides="3" data-gap="24">
            <div class="carousel-track">
                <?php foreach ($depoimentos as $depoimento): ?>
                <div class="carousel-slide">
                    <div class="depoimento-card" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 32px; height: 100%; display: flex; flex-direction: column;">
                        <!-- Google Badge -->
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"></path>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"></path>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"></path>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"></path>
                            </svg>
                            <span style="font-family: var(--font-body); font-size: 12px; color: rgba(255,255,255,0.6);">Google</span>
                        </div>

                        <!-- Quote Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(255, 186, 0, 0.3); margin-bottom: 16px;">
                            <path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"></path>
                            <path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"></path>
                        </svg>

                        <!-- Quote Text -->
                        <p style="font-family: var(--font-body); color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 24px; flex-grow: 1;">
                            "<?= e($depoimento['texto']) ?>"
                        </p>

                        <!-- Rating -->
                        <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 16px;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="<?= $i <= $depoimento['avaliacao'] ? 'var(--color-primary)' : 'rgba(255,255,255,0.3)' ?>" stroke="<?= $i <= $depoimento['avaliacao'] ? 'var(--color-primary)' : 'rgba(255,255,255,0.3)' ?>" stroke-width="2">
                                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                            </svg>
                            <?php endfor; ?>
                        </div>

                        <!-- Author -->
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <?php if ($depoimento['foto']): ?>
                            <img src="<?= SITE_URL ?>/<?= e($depoimento['foto']) ?>" alt="<?= e($depoimento['nome']) ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            <?php else: ?>
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <?php endif; ?>
                            <div>
                                <p style="font-family: var(--font-subtitle); font-weight: 600; color: white; margin: 0;"><?= e($depoimento['nome']) ?></p>
                                <p style="font-family: var(--font-body); font-size: 12px; color: rgba(255,255,255,0.6); margin: 0;"><?= formatDate($depoimento['data_depoimento'], 'd/m/Y') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation Buttons (centralizados abaixo) -->
            <div style="display: flex; justify-content: center; gap: 16px; margin-top: 32px;">
                <button class="carousel-prev depoimento-nav-btn" aria-label="Anterior" style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-primary); border: none; color: var(--color-foreground); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                </button>
                <button class="carousel-next depoimento-nav-btn" aria-label="Próximo" style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-primary); border: none; color: var(--color-foreground); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

<style>
/* Depoimentos Section */
.depoimento-card {
    transition: all 0.3s ease;
}
/* Override carousel.js default button positioning */
#depoimentos-carousel .carousel-prev,
#depoimentos-carousel .carousel-next {
    position: static !important;
    transform: none !important;
}
.depoimento-nav-btn:hover {
    background: var(--color-primary);
    opacity: 0.9;
    transform: scale(1.05) !important;
}
/* Carousel slides sizing */
#depoimentos-carousel .carousel-slide {
    box-sizing: border-box;
}
#depoimentos-carousel .carousel-track {
    align-items: stretch;
}
</style>
<?php endif; ?>

<!-- Localização Section -->
<section style="padding: 96px 0; background: white;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Localização</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Onde Estamos</h2>
            <p style="font-family: var(--font-body); color: var(--color-text-muted); margin-top: var(--spacing-4); line-height: 1.6;">Venha nos visitar ou solicite atendimento em São Carlos e região.</p>
        </div>

        <div data-animate="slide-up" style="border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d118726.91428545655!2d-47.94855799999999!3d-22.01715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94b87726d5a4be0f%3A0x36ce2f7f14e3c1d5!2sS%C3%A3o%20Carlos%2C%20SP!5e0!3m2!1spt-BR!2sbr!4v1699999999999!5m2!1spt-BR!2sbr"
                width="100%"
                height="450"
                style="border: 0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Localização Construbão">
            </iframe>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="padding: 48px 0; background: var(--color-primary);">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div class="cta-content" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; gap: 24px; text-align: center;">
            <div>
                <h3 style="font-family: var(--font-display); font-size: clamp(1.875rem, 3vw, 2.25rem); color: var(--color-foreground); margin: 0;">Precisa de um orçamento rápido?</h3>
                <p style="font-family: var(--font-subtitle); font-size: var(--text-lg); color: rgba(0,0,0,0.7); margin-top: 8px;">Atendimento imediato e personalizado via WhatsApp</p>
            </div>
            <a href="<?= whatsappLink() ?>" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--color-secondary); color: white; padding: 16px 40px; font-family: var(--font-subtitle); font-size: var(--text-lg); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; transition: all 0.3s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                </svg>
                Chamar Agora
            </a>
        </div>
    </div>
</section>

<style>
/* CTA Section - Responsive */
@media (min-width: 1024px) {
    .cta-content {
        flex-direction: row !important;
        text-align: left !important;
    }
}
.cta-content a:hover {
    background: var(--color-secondary);
    opacity: 0.9;
    transform: scale(1.02);
}
</style>

<script src="<?= ASSETS_URL ?>js/carousel.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
