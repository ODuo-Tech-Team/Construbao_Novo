<!-- CTA Final Section -->
<section style="padding: 48px 0; background: var(--color-primary);">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div class="cta-final-content" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; gap: 24px; text-align: center;">
            <div>
                <h3 style="font-family: var(--font-display); font-size: clamp(1.875rem, 3vw, 2.25rem); color: var(--color-foreground); margin: 0;">Precisa de um orçamento rápido?</h3>
                <p style="font-family: var(--font-subtitle); font-size: var(--text-lg); color: rgba(0,0,0,0.7); margin-top: 8px;">Atendimento imediato e personalizado via WhatsApp</p>
            </div>
            <a href="<?= whatsappLink() ?>" target="_blank" class="cta-final-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--color-secondary); color: white; padding: 16px 40px; font-family: var(--font-subtitle); font-size: var(--text-lg); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; transition: all 0.3s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                </svg>
                Chamar Agora
            </a>
        </div>
    </div>
</section>

<style>
/* CTA Final Section - Responsive */
@media (min-width: 1024px) {
    .cta-final-content {
        flex-direction: row !important;
        text-align: left !important;
    }
}
.cta-final-btn:hover {
    background: var(--color-secondary);
    opacity: 0.9;
    transform: scale(1.02);
}
</style>