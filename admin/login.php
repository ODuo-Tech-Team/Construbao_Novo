<?php
/**
 * ADMIN - Login
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Se já está logado, redireciona
if (isLoggedIn()) {
    redirect(SITE_URL . '/admin/');
}

$error = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = post('email');
    $senha = post('senha');

    if (empty($email) || empty($senha)) {
        $error = 'Preencha todos os campos.';
    } elseif (login($email, $senha)) {
        setFlash('success', 'Bem-vindo de volta!');
        redirect(SITE_URL . '/admin/');
    } else {
        $error = 'E-mail ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin | <?= SITE_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= ASSETS_URL ?>images/logo-construbao.png">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/styles.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/admin/assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="<?= ASSETS_URL ?>images/logo-construbao.png" alt="<?= SITE_NAME ?>" class="login-logo">
                <h1>Painel Administrativo</h1>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <?= showFlash() ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" class="form-input" required autofocus
                           value="<?= e($_POST['email'] ?? '') ?>"
                           placeholder="seu@email.com">
                </div>

                <div class="form-group">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-input" required
                           placeholder="Sua senha">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Entrar
                </button>
            </form>

            <div class="login-footer">
                <a href="<?= SITE_URL ?>">Voltar ao site</a>
            </div>
        </div>
    </div>
</body>
</html>
