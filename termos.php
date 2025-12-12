<?php
/**
 * CONSTRUBÃO - Termos de Uso
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Termos de Uso';
$pageDescription = 'Termos de Uso do site da Construbão. Conheça as condições para utilização de nossos serviços.';

include __DIR__ . '/includes/header.php';
?>

<!-- Terms of Use Section -->
<section style="padding: 96px 0; background: #FFF5E6; margin-top: 100px;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Legal</span>
            <h1 style="font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 3.5rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Termos de Uso</h1>
            <p style="font-family: var(--font-body); font-size: 1.125rem; color: var(--color-text-muted); margin-top: var(--spacing-4); line-height: 1.6;">
                Última atualização: <?= date('d/m/Y') ?>
            </p>
        </div>

        <!-- Content -->
        <div style="max-width: 800px; margin: 0 auto; background: white; border-radius: 16px; padding: 48px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);" data-animate="slide-up">
            <div style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.8;">

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">1. Aceitação dos Termos</h2>
                <p style="margin-bottom: 24px;">
                    Ao acessar e utilizar o site da Construbão, você concorda com estes Termos de Uso. Se você não concordar com algum termo, pedimos que não utilize nosso site.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">2. Sobre a Construbão</h2>
                <p style="margin-bottom: 24px;">
                    A Construbão é uma empresa especializada em locação de equipamentos para construção civil e venda de postes padrão CPFL, atendendo São Carlos e região há mais de 10 anos.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">3. Serviços Oferecidos</h2>
                <p style="margin-bottom: 16px;">Nossos principais serviços incluem:</p>
                <ul style="margin-bottom: 24px; padding-left: 24px;">
                    <li style="margin-bottom: 8px;">Locação de equipamentos (betoneiras, compactadores, geradores, andaimes, escoras, etc.)</li>
                    <li style="margin-bottom: 8px;">Venda de postes padrão homologados pela CPFL</li>
                    <li style="margin-bottom: 8px;">Consultoria técnica para escolha de equipamentos</li>
                </ul>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">4. Uso do Site</h2>
                <p style="margin-bottom: 16px;">Ao utilizar nosso site, você se compromete a:</p>
                <ul style="margin-bottom: 24px; padding-left: 24px;">
                    <li style="margin-bottom: 8px;">Fornecer informações verdadeiras e atualizadas</li>
                    <li style="margin-bottom: 8px;">Não utilizar o site para fins ilegais ou não autorizados</li>
                    <li style="margin-bottom: 8px;">Não tentar acessar áreas restritas do site</li>
                    <li style="margin-bottom: 8px;">Respeitar os direitos de propriedade intelectual</li>
                </ul>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">5. Propriedade Intelectual</h2>
                <p style="margin-bottom: 24px;">
                    Todo o conteúdo do site, incluindo textos, imagens, logotipos e design, são de propriedade da Construbão ou de seus licenciadores e estão protegidos por leis de direitos autorais.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">6. Orçamentos e Preços</h2>
                <p style="margin-bottom: 24px;">
                    Os orçamentos fornecidos através do site têm validade informada no momento da solicitação. Os preços podem variar conforme disponibilidade, período de locação e condições de mercado. As condições finais serão acordadas no momento da contratação.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">7. Limitação de Responsabilidade</h2>
                <p style="margin-bottom: 24px;">
                    A Construbão não se responsabiliza por danos decorrentes do uso inadequado das informações do site. As informações sobre produtos são meramente informativas e podem ser alteradas sem aviso prévio.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">8. Alterações nos Termos</h2>
                <p style="margin-bottom: 24px;">
                    Reservamo-nos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após sua publicação no site.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">9. Legislação Aplicável</h2>
                <p style="margin-bottom: 24px;">
                    Estes termos são regidos pelas leis brasileiras. Qualquer disputa será resolvida no foro da comarca de São Carlos/SP.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">10. Contato</h2>
                <p style="margin-bottom: 24px;">
                    Em caso de dúvidas sobre estes termos, entre em contato:
                </p>
                <p style="margin-bottom: 8px;"><strong>E-mail:</strong> <a href="mailto:contato@construbao.com.br" style="color: var(--color-primary);">contato@construbao.com.br</a></p>
                <p style="margin-bottom: 8px;"><strong>Telefone:</strong> <a href="https://wa.link/9ohsml" target="_blank" style="color: var(--color-primary);">(16) 99700-7775</a></p>
                <p><strong>Endereço:</strong> Rod. Washington Luiz, Km 230 - São Carlos/SP</p>

            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>