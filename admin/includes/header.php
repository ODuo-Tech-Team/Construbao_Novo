<?php
/**
 * Admin Header
 */

ob_start(); // Habilita output buffering para permitir redirects após output

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

// Requer login
requireLogin();

$currentUser = getLoggedUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> | <?= SITE_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= ASSETS_URL ?>images/logo-construbao.png">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/styles.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/admin/assets/css/admin.css">
    <!-- Quill Editor (local) -->
    <link href="<?= SITE_URL ?>/admin/assets/vendor/quill.snow.css" rel="stylesheet">
    <script src="<?= SITE_URL ?>/admin/assets/vendor/quill.min.js"></script>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Overlay para mobile -->
        <div class="admin-overlay" id="adminOverlay"></div>

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <a href="<?= SITE_URL ?>/admin/">
                    <img src="<?= ASSETS_URL ?>images/logo-construbao.png" alt="<?= SITE_NAME ?>" class="admin-sidebar-logo">
                </a>
            </div>

            <nav class="admin-sidebar-nav">
                <!-- Dashboard -->
                <a href="<?= SITE_URL ?>/admin/" class="admin-nav-link <?= $currentPage === 'index' && $currentDir === 'admin' ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                        <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                    </svg>
                    Dashboard
                </a>

                <!-- Catálogo -->
                <div class="admin-nav-section">
                    <div class="admin-nav-title">Catálogo</div>

                    <a href="<?= SITE_URL ?>/admin/equipamentos/" class="admin-nav-link <?= $currentDir === 'equipamentos' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                        Equipamentos
                    </a>

                    <a href="<?= SITE_URL ?>/admin/postes/" class="admin-nav-link <?= $currentDir === 'postes' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v20"></path>
                            <path d="M2 5h20"></path>
                            <path d="M5 2v3"></path>
                            <path d="M19 2v3"></path>
                        </svg>
                        Postes
                    </a>

                    <a href="<?= SITE_URL ?>/admin/categorias-equip/" class="admin-nav-link <?= $currentDir === 'categorias-equip' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"></path>
                        </svg>
                        Categorias
                    </a>
                </div>

                <!-- Blog -->
                <div class="admin-nav-section">
                    <div class="admin-nav-title">Blog</div>

                    <a href="<?= SITE_URL ?>/admin/blog/" class="admin-nav-link <?= $currentDir === 'blog' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" x2="8" y1="13" y2="13"></line>
                            <line x1="16" x2="8" y1="17" y2="17"></line>
                            <line x1="10" x2="8" y1="9" y2="9"></line>
                        </svg>
                        Posts
                    </a>

                    <a href="<?= SITE_URL ?>/admin/categorias-blog/" class="admin-nav-link <?= $currentDir === 'categorias-blog' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"></path>
                            <path d="M7 7h.01"></path>
                        </svg>
                        Categorias
                    </a>
                </div>

                <!-- Sistema -->
                <div class="admin-nav-section">
                    <div class="admin-nav-title">Sistema</div>

                    <a href="<?= SITE_URL ?>/admin/depoimentos/" class="admin-nav-link <?= $currentDir === 'depoimentos' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Depoimentos
                    </a>

                    <?php if (isAdmin()): ?>
                    <a href="<?= SITE_URL ?>/admin/usuarios/" class="admin-nav-link <?= $currentDir === 'usuarios' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Usuários
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Links -->
                <div class="admin-nav-section" style="margin-top: auto; padding-top: var(--spacing-4); border-top: 1px solid rgba(255,255,255,0.1);">
                    <a href="<?= SITE_URL ?>" target="_blank" class="admin-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" x2="21" y1="14" y2="3"></line>
                        </svg>
                        Ver Site
                    </a>

                    <a href="<?= SITE_URL ?>/admin/logout.php" class="admin-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" x2="9" y1="12" y2="12"></line>
                        </svg>
                        Sair
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="admin-header-title">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12"></line>
                            <line x1="4" x2="20" y1="6" y2="6"></line>
                            <line x1="4" x2="20" y1="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="admin-header-actions">
                    <div class="admin-user-menu">
                        <div class="admin-user-avatar">
                            <?= strtoupper(substr($currentUser['nome'], 0, 1)) ?>
                        </div>
                        <div>
                            <strong style="display: block; font-size: var(--text-sm);"><?= e($currentUser['nome']) ?></strong>
                            <small style="color: var(--color-text-muted);"><?= ucfirst($currentUser['nivel']) ?></small>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                <?= showFlash() ?>
