<?php
/**
 * CONSTRUBÃO - Política de Privacidade
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Política de Privacidade';
$pageDescription = 'Política de Privacidade da Construbão. Saiba como coletamos, usamos e protegemos suas informações.';

include __DIR__ . '/includes/header.php';
?>

<!-- Privacy Policy Section -->
<section style="padding: 96px 0; background: #FFF5E6; margin-top: 100px;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <!-- Header -->
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px;" data-animate="slide-up">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Legal</span>
            <h1 style="font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 3.5rem); color: var(--color-foreground); margin-top: var(--spacing-4); line-height: 1.1;">Política de Privacidade</h1>
            <p style="font-family: var(--font-body); font-size: 1.125rem; color: var(--color-text-muted); margin-top: var(--spacing-4); line-height: 1.6;">
                Última atualização: <?= date('d/m/Y') ?>
            </p>
        </div>

        <!-- Content -->
        <div style="max-width: 800px; margin: 0 auto; background: white; border-radius: 16px; padding: 48px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);" data-animate="slide-up">
            <div style="font-family: var(--font-body); color: var(--color-text-muted); line-height: 1.8;">

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">1. Informações que Coletamos</h2>
                <p style="margin-bottom: 24px;">
                    A Construbão coleta informações que você nos fornece diretamente, como nome, telefone, e-mail e endereço quando você entra em contato conosco para solicitar orçamentos ou informações sobre nossos produtos e serviços de locação de equipamentos e venda de postes.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">2. Como Usamos suas Informações</h2>
                <p style="margin-bottom: 16px;">Utilizamos as informações coletadas para:</p>
                <ul style="margin-bottom: 24px; padding-left: 24px;">
                    <li style="margin-bottom: 8px;">Responder às suas solicitações e fornecer orçamentos</li>
                    <li style="margin-bottom: 8px;">Entrar em contato sobre nossos produtos e serviços</li>
                    <li style="margin-bottom: 8px;">Melhorar nosso atendimento ao cliente</li>
                    <li style="margin-bottom: 8px;">Cumprir obrigações legais</li>
                </ul>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">3. Compartilhamento de Informações</h2>
                <p style="margin-bottom: 24px;">
                    Não vendemos, alugamos ou compartilhamos suas informações pessoais com terceiros para fins de marketing. Podemos compartilhar informações apenas quando necessário para prestação de serviços (como entrega de equipamentos) ou quando exigido por lei.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">4. Segurança dos Dados</h2>
                <p style="margin-bottom: 24px;">
                    Implementamos medidas de segurança apropriadas para proteger suas informações pessoais contra acesso não autorizado, alteração, divulgação ou destruição.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">5. Cookies</h2>
                <p style="margin-bottom: 24px;">
                    Nosso site pode utilizar cookies para melhorar sua experiência de navegação. Cookies são pequenos arquivos armazenados em seu dispositivo que nos ajudam a entender como você utiliza nosso site.
                </p>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">6. Seus Direitos</h2>
                <p style="margin-bottom: 16px;">De acordo com a LGPD (Lei Geral de Proteção de Dados), você tem direito a:</p>
                <ul style="margin-bottom: 24px; padding-left: 24px;">
                    <li style="margin-bottom: 8px;">Acessar seus dados pessoais</li>
                    <li style="margin-bottom: 8px;">Corrigir dados incompletos ou desatualizados</li>
                    <li style="margin-bottom: 8px;">Solicitar a exclusão de seus dados</li>
                    <li style="margin-bottom: 8px;">Revogar seu consentimento</li>
                </ul>

                <h2 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-foreground); margin-bottom: 16px;">7. Contato</h2>
                <p style="margin-bottom: 24px;">
                    Para exercer seus direitos ou esclarecer dúvidas sobre esta política, entre em contato conosco:
                </p>
                <p style="margin-bottom: 8px;"><strong>E-mail:</strong> <a href="mailto:contato@construbao.com.br" style="color: var(--color-primary);">contato@construbao.com.br</a></p>
                <p style="margin-bottom: 8px;"><strong>Telefone:</strong> <a href="https://wa.link/9ohsml" target="_blank" style="color: var(--color-primary);">(16) 99700-7775</a></p>
                <p><strong>Endereço:</strong> Rod. Washington Luiz, Km 230 - São Carlos/SP</p>

            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
