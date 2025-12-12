<?php
/**
 * CONSTRUBÃO - Página de Contato
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contato';
$pageDescription = 'Entre em contato com a Construbão. Estamos prontos para atender você em São Carlos e região.';

include __DIR__ . '/includes/header.php';
?>

<!-- Contact Section - Header + Cards -->
<section style="padding: 96px 0; background: #FFF5E6; margin-top: 100px;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header centralizado -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Fale Conosco</span>
            <h1 style="font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 3.5rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Entre em Contato</h1>
            <p style="font-family: var(--font-body); font-size: 1.125rem; color: var(--color-text-muted); margin-top: var(--spacing-4); line-height: 1.6;">
                Estamos prontos para atender você. Escolha a forma mais conveniente.
            </p>
        </div>

        <!-- Contact Cards Grid -->
        <div class="contact-grid" style="display: grid; grid-template-columns: 1fr; gap: 24px;">
            <!-- Endereço -->
            <div class="contact-card" data-animate="slide-up" style="background: var(--color-secondary); border-radius: 16px; padding: 32px; text-align: center; transition: all 0.3s ease;">
                <div style="width: 64px; height: 64px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.125rem; color: white; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Endereço</h3>
                <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); line-height: 1.6;">
                    Rod. Washington Luiz, Km 230<br>
                    São Carlos/SP
                </p>
            </div>

            <!-- Telefone -->
            <div class="contact-card" data-animate="slide-up" style="background: var(--color-secondary); border-radius: 16px; padding: 32px; text-align: center; transition: all 0.3s ease; animation-delay: 0.1s;">
                <div style="width: 64px; height: 64px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.125rem; color: white; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Telefone</h3>
                <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); line-height: 1.6;">
                    <a href="https://wa.link/9ohsml" target="_blank" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">(16) 99700-7775</a>
                </p>
            </div>

            <!-- E-mail -->
            <div class="contact-card" data-animate="slide-up" style="background: var(--color-secondary); border-radius: 16px; padding: 32px; text-align: center; transition: all 0.3s ease; animation-delay: 0.2s;">
                <div style="width: 64px; height: 64px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.125rem; color: white; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em;">E-mail</h3>
                <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); line-height: 1.6;">
                    <a href="mailto:contato@construbao.com.br" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">contato@construbao.com.br</a>
                </p>
            </div>

            <!-- Horário -->
            <div class="contact-card" data-animate="slide-up" style="background: var(--color-secondary); border-radius: 16px; padding: 32px; text-align: center; transition: all 0.3s ease; animation-delay: 0.3s;">
                <div style="width: 64px; height: 64px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-display); font-size: 1.125rem; color: white; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Horário</h3>
                <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); line-height: 1.6;">
                    Seg - Sex: 8h às 18h<br>
                    Sábado: 8h às 12h
                </p>
            </div>
        </div>
    </div>
</section>

<!-- WhatsApp CTA Section -->
<section style="padding: 64px 0; background: #FFF5E6;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div class="whatsapp-card" data-animate="slide-up" style="max-width: 700px; margin: 0 auto; background: white; border-radius: 16px; padding: 48px 32px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: center; margin-bottom: 24px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="#25D366">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <h2 style="font-family: var(--font-display); font-size: clamp(1.25rem, 3vw, 1.75rem); color: var(--color-foreground); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.05em;">Atendimento Rápido pelo WhatsApp</h2>
            <p style="font-family: var(--font-body); color: var(--color-text-muted); margin-bottom: 32px; line-height: 1.6; max-width: 480px; margin-left: auto; margin-right: auto;">
                Resposta imediata para orçamentos, dúvidas e informações sobre nossos produtos e serviços.
            </p>
            <a href="<?= whatsappLink() ?>" target="_blank" class="whatsapp-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 12px; padding: 16px 48px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                </svg>
                Chamar no WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- Map Section -->
<section style="padding: 96px 0; background: #fff;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div style="text-align: center; margin-bottom: 48px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-accent, #f37520); font-weight: 600;">Localização</span>
            <h2 style="font-family: var(--font-display); font-size: clamp(1.875rem, 4vw, 2.5rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Nossa Localização</h2>
            <p style="font-family: var(--font-body); color: var(--color-text-muted); margin-top: var(--spacing-4); line-height: 1.6;">Visite nossa loja em São Carlos.</p>
        </div>

        <div data-animate="slide-up" style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3699.0!2d-47.89!3d-22.01!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDAwJzM2LjAiUyA0N8KwNTMnMjQuMCJX!5e0!3m2!1spt-BR!2sbr!4v1234567890"
                width="100%"
                height="450"
                style="border:0; display: block;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Localização Construbão">
            </iframe>
        </div>
    </div>
</section>

<style>
/* Contact Grid - Responsive */
@media (min-width: 480px) {
    .contact-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (min-width: 1024px) {
    .contact-grid {
        grid-template-columns: repeat(4, 1fr) !important;
    }
}

/* Contact Card Hover */
.contact-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

/* WhatsApp Button Hover */
.whatsapp-btn:hover {
    background: #1faa54;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
}
</style>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
