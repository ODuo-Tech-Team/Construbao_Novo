<?php
/**
 * Header do Site - Navegação Principal
 */

// Definir página ativa
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php if (isset($pageTitle) && isset($pageDescription)): ?>
        <?= metaTags($pageTitle, $pageDescription, $pageImage ?? '') ?>
    <?php else: ?>
        <title><?= SITE_NAME ?></title>
        <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= ASSETS_URL ?>images/logo-construbao.png">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/styles.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/components.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/animations.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/responsive.css">

    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
    <!-- Header -->
    <header style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; background: var(--color-primary); box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div class="container" style="padding: 0 var(--spacing-4);">
            <div style="display: flex; align-items: center; justify-content: space-between; height: 100px;">
                <!-- Logo -->
                <div style="flex: 1; flex-shrink: 0;">
                    <a href="<?= SITE_URL ?>" style="display: inline-block; padding: 8px 0;">
                        <img src="<?= ASSETS_URL ?>images/sobre-mascote.png" alt="<?= SITE_NAME ?>" class="header-logo" style="height: 70px; width: auto; max-width: 120px; object-fit: contain;">
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Abrir menu" style="display: flex; align-items: center; justify-content: center; padding: 10px; background: none; border: none; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" x2="20" y1="12" y2="12"></line>
                        <line x1="4" x2="20" y1="6" y2="6"></line>
                        <line x1="4" x2="20" y1="18" y2="18"></line>
                    </svg>
                </button>

                <!-- Desktop Navigation -->
                <nav class="desktop-nav" style="display: none; gap: 40px;">
                    <a href="<?= SITE_URL ?>" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">Home</a>
                    <a href="<?= SITE_URL ?>/equipamentos.php" class="nav-link <?= $currentPage === 'equipamentos' || $currentPage === 'equipamento' ? 'active' : '' ?>">Equipamentos</a>
                    <a href="<?= SITE_URL ?>/poste.php" class="nav-link <?= $currentPage === 'poste' ? 'active' : '' ?>">Postes</a>
                    <a href="<?= SITE_URL ?>/sobre.php" class="nav-link <?= $currentPage === 'sobre' ? 'active' : '' ?>">Sobre Nós</a>
                    <a href="<?= SITE_URL ?>/blog.php" class="nav-link <?= $currentPage === 'blog' || $currentPage === 'post' ? 'active' : '' ?>">Blog</a>
                    <a href="<?= SITE_URL ?>/contato.php" class="nav-link <?= $currentPage === 'contato' ? 'active' : '' ?>">Contato</a>
                </nav>

                <!-- Desktop CTA -->
                <div class="desktop-cta" style="display: none; flex: 1; justify-content: flex-end; gap: 16px;">
                    <a href="<?= whatsappLink() ?>" target="_blank" rel="noopener noreferrer" class="header-whatsapp-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 32px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--color-secondary); border-top: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div style="padding: 24px var(--spacing-4);">
                <nav style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px;">
                    <a href="<?= SITE_URL ?>" class="mobile-nav-link <?= $currentPage === 'index' ? 'active' : '' ?>" style="display: block; padding: 12px 16px; font-family: var(--font-subtitle); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">Home</a>
                    <a href="<?= SITE_URL ?>/equipamentos.php" class="mobile-nav-link <?= $currentPage === 'equipamentos' || $currentPage === 'equipamento' ? 'active' : '' ?>" style="display: block; padding: 12px 16px; font-family: var(--font-subtitle); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">Equipamentos</a>
                    <a href="<?= SITE_URL ?>/poste.php" class="mobile-nav-link <?= $currentPage === 'poste' ? 'active' : '' ?>" style="display: block; padding: 12px 16px; font-family: var(--font-subtitle); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">Postes</a>
                    <a href="<?= SITE_URL ?>/sobre.php" class="mobile-nav-link <?= $currentPage === 'sobre' ? 'active' : '' ?>" style="display: block; padding: 12px 16px; font-family: var(--font-subtitle); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">Sobre Nós</a>
                    <a href="<?= SITE_URL ?>/blog.php" class="mobile-nav-link <?= $currentPage === 'blog' || $currentPage === 'post' ? 'active' : '' ?>" style="display: block; padding: 12px 16px; font-family: var(--font-subtitle); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">Blog</a>
                    <a href="<?= SITE_URL ?>/contato.php" class="mobile-nav-link <?= $currentPage === 'contato' ? 'active' : '' ?>" style="display: block; padding: 12px 16px; font-family: var(--font-subtitle); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">Contato</a>
                </nav>
                <a href="<?= whatsappLink() ?>" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px 24px; background: #25D366; color: white; font-family: var(--font-subtitle); font-size: var(--text-base); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                    </svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </header>

    <style>
    /* Header Navigation Links - Dark text on yellow background */
    .nav-link {
        position: relative;
        font-family: var(--font-subtitle);
        font-size: 1.125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--color-secondary);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        width: 100%;
        transform: scaleX(0);
        height: 2px;
        bottom: -4px;
        left: 0;
        background: var(--color-secondary);
        transform-origin: bottom right;
        transition: transform 0.3s ease;
    }
    .nav-link:hover {
        color: var(--color-secondary);
        opacity: 0.7;
    }
    .nav-link:hover::after {
        transform: scaleX(1);
        transform-origin: bottom left;
    }
    .nav-link.active {
        color: var(--color-secondary);
    }
    .nav-link.active::after {
        transform: scaleX(1);
    }

    /* Header WhatsApp Button Hover */
    .header-whatsapp-btn:hover {
        background: #1faa54;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
    }

    /* Mobile Navigation Links */
    .mobile-nav-link:hover,
    .mobile-nav-link.active {
        background: rgba(255,255,255,0.1);
        color: var(--color-primary);
    }

    /* Mobile Menu Toggle - Hidden on Desktop */
    @media (min-width: 1024px) {
        .mobile-menu-toggle {
            display: none !important;
        }
        .desktop-nav {
            display: flex !important;
        }
        .desktop-cta {
            display: flex !important;
        }
    }

    /* Mobile Menu Animation */
    .mobile-menu.open {
        display: block !important;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Logo responsive */
    @media (max-width: 480px) {
        .header-logo {
            height: 60px !important;
            max-width: 100px !important;
        }
    }
    </style>

    <script>
    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('mobileMenuToggle');
        const menu = document.getElementById('mobileMenu');

        if (toggle && menu) {
            toggle.addEventListener('click', function() {
                menu.classList.toggle('open');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('open');
                }
            });
        }
    });
    </script>

    <!-- Main Content -->
    <main>
