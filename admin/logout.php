<?php
/**
 * ADMIN - Logout
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

logout();
setFlash('success', 'Você saiu com sucesso.');
redirect(SITE_URL . '/admin/login.php');
