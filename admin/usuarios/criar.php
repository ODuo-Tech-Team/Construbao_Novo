<?php
/**
 * ADMIN - Criar Usuário
 */

$pageTitle = 'Novo Usuário';
require_once __DIR__ . '/../includes/header.php';

// Apenas admin pode gerenciar usuários
requireAdmin();

$errors = [];
$data = [
    'nome' => '',
    'email' => '',
    'nivel' => 'editor',
    'ativo' => 1
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!validateCsrfToken($_POST['_token'] ?? '')) {
        $errors[] = 'Token de segurança inválido.';
    } else {
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'senha' => $_POST['senha'] ?? '',
            'senha_confirma' => $_POST['senha_confirma'] ?? '',
            'nivel' => $_POST['nivel'] ?? 'editor',
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        // Validações
        if (empty($data['nome'])) {
            $errors[] = 'O nome é obrigatório.';
        }

        if (empty($data['email'])) {
            $errors[] = 'O email é obrigatório.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        } else {
            // Verificar se email já existe
            $exists = fetchOne('usuarios', 'email = ?', [$data['email']]);
            if ($exists) {
                $errors[] = 'Este email já está em uso.';
            }
        }

        if (empty($data['senha'])) {
            $errors[] = 'A senha é obrigatória.';
        } elseif (strlen($data['senha']) < 6) {
            $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
        } elseif ($data['senha'] !== $data['senha_confirma']) {
            $errors[] = 'As senhas não conferem.';
        }

        if (!in_array($data['nivel'], ['admin', 'editor'])) {
            $errors[] = 'Nível inválido.';
        }

        // Se não houver erros, inserir
        if (empty($errors)) {
            $id = insert('usuarios', [
                'nome' => $data['nome'],
                'email' => $data['email'],
                'senha' => hashPassword($data['senha']),
                'nivel' => $data['nivel'],
                'ativo' => $data['ativo']
            ]);

            if ($id) {
                setFlash('success', 'Usuário criado com sucesso!');
                redirect(SITE_URL . '/admin/usuarios/');
            } else {
                $errors[] = 'Erro ao criar usuário. Tente novamente.';
            }
        }
    }
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Novo Usuário</h1>
    <a href="<?= SITE_URL ?>/admin/usuarios/" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"></path>
            <path d="M19 12H5"></path>
        </svg>
        Voltar
    </a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-error" style="margin-bottom: var(--spacing-4);">
    <ul style="margin: 0; padding-left: var(--spacing-4);">
        <?php foreach ($errors as $error): ?>
        <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="admin-form">
    <form method="POST">
        <?= csrfField() ?>

        <div class="form-section">
            <h3 class="form-section-title">Informações Básicas</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" class="form-input" value="<?= e($data['nome']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-input" value="<?= e($data['email']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="senha" class="form-label">Senha *</label>
                    <input type="password" id="senha" name="senha" class="form-input" minlength="6" required>
                    <small style="color: var(--color-text-muted);">Mínimo de 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="senha_confirma" class="form-label">Confirmar Senha *</label>
                    <input type="password" id="senha_confirma" name="senha_confirma" class="form-input" minlength="6" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Permissões</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="nivel" class="form-label">Nível de Acesso *</label>
                    <select id="nivel" name="nivel" class="form-input">
                        <option value="editor" <?= $data['nivel'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                        <option value="admin" <?= $data['nivel'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                    <small style="color: var(--color-text-muted);">
                        Administradores podem gerenciar usuários
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <label style="display: flex; align-items: center; gap: var(--spacing-2); cursor: pointer;">
                        <input type="checkbox" name="ativo" value="1" <?= $data['ativo'] ? 'checked' : '' ?>>
                        <span>Usuário ativo</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: var(--spacing-3);">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Salvar Usuário
            </button>
            <a href="<?= SITE_URL ?>/admin/usuarios/" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
