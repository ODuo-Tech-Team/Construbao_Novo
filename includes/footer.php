    </main>

    <!-- Footer -->
    <footer style="background: var(--color-secondary); color: white;">
        <!-- Main Footer -->
        <div style="padding: 64px 0;">
            <div class="container">
                <div class="footer-grid" style="display: grid; grid-template-columns: 1fr; gap: 48px;">
                    <!-- Brand -->
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <img src="<?= ASSETS_URL ?>images/sobre-mascote.png" alt="<?= SITE_NAME ?>" style="height: 128px; width: auto; object-fit: contain; align-self: flex-start;">
                        <p style="color: rgba(255, 255, 255, 0.8); line-height: 1.6; max-width: 320px;">
                            Há mais de 10 anos oferecendo as melhores soluções em locação de equipamentos e venda de postes padrão para São Carlos e região.
                        </p>
                        <div style="display: flex; gap: 12px;">
                            <a href="https://instagram.com/construbao" target="_blank" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease;" onmouseover="this.style.background='var(--color-primary)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'" aria-label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                </svg>
                            </a>
                            <a href="https://facebook.com/construbao" target="_blank" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease;" onmouseover="this.style.background='var(--color-primary)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'" aria-label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div>
                        <h4 style="font-family: var(--font-heading); font-weight: 700; font-size: 1.125rem; margin-bottom: 16px; color: #fff;">Navegação</h4>
                        <nav style="display: flex; flex-direction: column; gap: 12px;">
                            <a href="<?= SITE_URL ?>" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Home</a>
                            <a href="<?= SITE_URL ?>/equipamentos.php" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Equipamentos</a>
                            <a href="<?= SITE_URL ?>/sobre.php" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Sobre Nós</a>
                            <a href="<?= SITE_URL ?>/blog.php" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Blog</a>
                            <a href="<?= SITE_URL ?>/contato.php" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Contato</a>
                        </nav>
                    </div>

                    <!-- Equipment -->
                    <div>
                        <h4 style="font-family: var(--font-heading); font-weight: 700; font-size: 1.125rem; margin-bottom: 16px; color: #fff;">Equipamentos</h4>
                        <nav style="display: flex; flex-direction: column; gap: 12px;">
                            <a href="<?= SITE_URL ?>/equipamento.php?slug=betoneiras" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Betoneiras</a>
                            <a href="<?= SITE_URL ?>/equipamento.php?slug=compactadores" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Compactadores</a>
                            <a href="<?= SITE_URL ?>/equipamento.php?slug=geradores" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Geradores</a>
                            <a href="<?= SITE_URL ?>/equipamento.php?slug=andaimes" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Andaimes</a>
                            <a href="<?= SITE_URL ?>/equipamento.php?slug=escoras-6m" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Escoras</a>
                            <a href="<?= SITE_URL ?>/poste.php?slug=poste-frontal" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Postes Padrão</a>
                        </nav>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 style="font-family: var(--font-heading); font-weight: 700; font-size: 1.125rem; margin-bottom: 16px; color: #fff;">Contato</h4>
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span style="color: rgba(255, 255, 255, 0.8); line-height: 1.5;">Rod. Washington Luiz, Km 230<br>São Carlos/SP</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <a href="https://wa.link/9ohsml" target="_blank" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">(16) 99700-7775</a>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg>
                                <a href="mailto:contato@construbao.com.br" style="color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">contato@construbao.com.br</a>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span style="color: rgba(255, 255, 255, 0.8); line-height: 1.5;">Seg-Sex: 8h às 18h<br>Sáb: 8h às 12h</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 24px 0;">
            <div class="container">
                <div class="footer-bottom-content" style="display: flex; flex-direction: column; align-items: center; gap: 16px; text-align: center;">
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.875rem;">
                        &copy; <?= date('Y') ?> <?= SITE_NAME ?>. Todos os direitos reservados.
                    </p>
                    <div style="display: flex; gap: 24px;">
                        <a href="<?= SITE_URL ?>/privacidade.php" style="color: rgba(255, 255, 255, 0.6); font-size: 0.875rem; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.6)'">Política de Privacidade</a>
                        <a href="<?= SITE_URL ?>/termos.php" style="color: rgba(255, 255, 255, 0.6); font-size: 0.875rem; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.6)'">Termos de Uso</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Responsive Footer Grid -->
    <style>
        @media (min-width: 768px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            .footer-bottom-content {
                flex-direction: row !important;
                justify-content: space-between !important;
                text-align: left !important;
            }
        }
        @media (min-width: 1024px) {
            .footer-grid {
                grid-template-columns: 1.5fr 1fr 1fr 1fr !important;
            }
        }
    </style>

    <!-- WhatsApp Floating Button -->
    <?php include __DIR__ . '/whatsapp-button.php'; ?>

    <!-- JavaScript -->
    <script src="<?= ASSETS_URL ?>js/main.js"></script>
    <script src="<?= ASSETS_URL ?>js/header.js"></script>
</body>
</html>
