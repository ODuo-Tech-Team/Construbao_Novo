<?php
/**
 * CONSTRUBÃO - Página Sobre
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Sobre Nós';
$pageDescription = 'Conheça a Construbão - Há mais de 10 anos oferecendo soluções em locação de equipamentos e venda de postes padrão em São Carlos e região.';

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section style="position: relative; background: var(--color-secondary); padding: 96px 0; margin-top: 100px; overflow: hidden;">
    <!-- Background Image -->
    <div style="position: absolute; inset: 0;">
        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920&h=600&fit=crop" alt="Construção" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.2;">
        <div style="position: absolute; inset: 0; background: linear-gradient(to right, var(--color-secondary), rgba(10, 42, 63, 0.95), rgba(10, 42, 63, 0.8));"></div>
    </div>
    <!-- Content -->
    <div class="container" style="position: relative; padding: 0 var(--spacing-4);">
        <div style="max-width: 768px;">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Quem Somos</span>
            <h1 style="font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 3.5rem); color: white; margin-top: var(--spacing-4); line-height: 1.1;">Seu Parceiro Ideal para Obras e Construção</h1>
        </div>
    </div>
</section>

<!-- Nossa História Section -->
<section style="padding: 96px 0; background: white;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div class="historia-grid" style="display: grid; grid-template-columns: 1fr; gap: 64px; align-items: center;">
            <!-- Content -->
            <div data-animate="slide-right">
                <span style="display: inline-block; font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600; margin-bottom: var(--spacing-4);">Nossa História</span>
                <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-bottom: 8px; line-height: 1.1;">Tradição e Qualidade</h2>
                <h3 style="font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2rem); color: var(--color-primary); margin-bottom: 32px;">em São Carlos</h3>
                <div style="width: 80px; height: 4px; background: var(--color-secondary); margin-bottom: 32px; border-radius: 9999px;"></div>

                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.7; font-size: 1.125rem; margin: 0;">
                        <span style="color: var(--color-secondary); font-weight: 600;">Somos referência</span> na venda de postes padrão e na locação de equipamentos para construção, atendendo obras residenciais, comerciais e industriais em São Carlos e região.
                    </p>
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.7; font-size: 1.125rem; margin: 0;">
                        Oferecemos <span style="color: var(--color-secondary); font-weight: 600;">produtos homologados</span>, equipamentos modernos e um atendimento personalizado que garante segurança, agilidade e tranquilidade em cada etapa da sua obra.
                    </p>
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.7; font-size: 1.125rem; margin: 0;">
                        Nossa história é marcada pelo <span style="color: var(--color-secondary); font-weight: 600;">compromisso com a qualidade</span> e pelo relacionamento de confiança que construímos com cada cliente. Trabalhamos para ser mais que um fornecedor: <span style="color: var(--color-primary); font-weight: 700;">somos parceiros do seu projeto.</span>
                    </p>
                </div>
            </div>

            <!-- Image -->
            <div data-animate="slide-left" style="position: relative;">
                <div style="aspect-ratio: 1; border-radius: 16px; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 32px;">
                    <img src="<?= ASSETS_URL ?>images/sobre-mascote.png" alt="Mascote Construbão" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <!-- Decorative elements -->
                <div style="position: absolute; bottom: -32px; left: -32px; width: 192px; height: 192px; background: rgba(255, 186, 0, 0.1); border-radius: 16px; z-index: -1;"></div>
                <div style="position: absolute; top: -32px; right: -32px; width: 128px; height: 128px; background: rgba(255, 186, 0, 0.2); border-radius: 16px; z-index: -1;"></div>
            </div>
        </div>
    </div>
</section>

<style>
/* Historia Section - Responsive */
@media (min-width: 1024px) {
    .historia-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}
</style>

<!-- Nossos Diferenciais Section -->
<section style="padding: 96px 0; background: white;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600;">Nossos Diferenciais</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">O Que Nos Torna Únicos</h2>
        </div>

        <!-- Cards Grid -->
        <div class="diferenciais-grid" style="display: grid; grid-template-columns: 1fr; gap: 32px;">
            <!-- Card 1 - Segurança -->
            <div class="diferencial-card" data-animate="slide-up" style="background: white; border-radius: 12px; border: 2px solid rgba(255, 186, 0, 0.2); box-shadow: 0 10px 40px rgba(0,0,0,0.08); transition: all 0.3s ease; animation-delay: 0s;">
                <div style="padding: 32px; text-align: center;">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(255, 186, 0, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                        </svg>
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">Compromisso com Segurança</h3>
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.6; margin: 0;">Todos os nossos equipamentos passam por rigorosa inspeção e manutenção, garantindo a segurança da sua equipe.</p>
                </div>
            </div>

            <!-- Card 2 - Equipamentos -->
            <div class="diferencial-card" data-animate="slide-up" style="background: white; border-radius: 12px; border: 2px solid rgba(255, 186, 0, 0.2); box-shadow: 0 10px 40px rgba(0,0,0,0.08); transition: all 0.3s ease; animation-delay: 0.1s;">
                <div style="padding: 32px; text-align: center;">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(255, 186, 0, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">Equipamentos Modernos e Revisados</h3>
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.6; margin: 0;">Investimos constantemente em equipamentos de última geração, sempre revisados e prontos para uso.</p>
                </div>
            </div>

            <!-- Card 3 - Atendimento -->
            <div class="diferencial-card" data-animate="slide-up" style="background: white; border-radius: 12px; border: 2px solid rgba(255, 186, 0, 0.2); box-shadow: 0 10px 40px rgba(0,0,0,0.08); transition: all 0.3s ease; animation-delay: 0.2s;">
                <div style="padding: 32px; text-align: center;">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(255, 186, 0, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">Atendimento Especializado</h3>
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.6; margin: 0;">Nossa equipe é treinada para orientar você na escolha do equipamento ideal para cada tipo de obra.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Diferenciais Section - Responsive */
@media (min-width: 640px) {
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
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
    border-color: rgba(255, 186, 0, 0.4);
    transform: translateY(-8px);
}
</style>

<!-- Nossos Pilares Section -->
<section style="padding: 96px 0; background: #FAF6EB;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600;">O Que Nos Guia</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Nossos Pilares</h2>
        </div>

        <!-- Cards Grid -->
        <div class="pilares-grid" style="display: grid; grid-template-columns: 1fr; gap: 32px;">
            <!-- Missão -->
            <div class="pilar-card" data-animate="slide-up" style="background: var(--color-secondary); border-radius: 16px; padding: 32px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.15); transition: all 0.3s ease; animation-delay: 0s;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <circle cx="12" cy="12" r="6"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: white; margin-bottom: 16px;">Missão</h3>
                <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); line-height: 1.6; margin: 0;">Fornecer equipamentos de qualidade e soluções completas para construção, contribuindo para o sucesso de cada projeto dos nossos clientes.</p>
            </div>

            <!-- Visão -->
            <div class="pilar-card" data-animate="slide-up" style="background: var(--color-secondary); border-radius: 16px; padding: 32px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.15); transition: all 0.3s ease; animation-delay: 0.1s;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: white; margin-bottom: 16px;">Visão</h3>
                <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); line-height: 1.6; margin: 0;">Ser referência em locação de equipamentos e venda de postes padrão na região de São Carlos, reconhecida pela excelência e confiabilidade.</p>
            </div>

            <!-- Valores -->
            <div class="pilar-card" data-animate="slide-up" style="background: var(--color-secondary); border-radius: 16px; padding: 32px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.15); transition: all 0.3s ease; animation-delay: 0.2s;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: white; margin-bottom: 16px;">Valores</h3>
                <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); line-height: 1.6; margin: 0;">Compromisso, qualidade, agilidade, segurança e atendimento personalizado são os pilares que norteiam todas as nossas ações.</p>
            </div>
        </div>
    </div>
</section>

<style>
/* Pilares Section - Responsive */
@media (min-width: 640px) {
    .pilares-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (min-width: 1024px) {
    .pilares-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}
.pilar-card:hover {
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    transform: translateY(-4px);
}
</style>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
