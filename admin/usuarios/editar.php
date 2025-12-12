<?php
/**
 * ADMIN - Editar Usuário
 */

$pageTitle = 'Editar Usuário';
require_once __DIR__ . '/../includes/header.php';

// Apenas admin pode gerenciar usuários
requireAdmin();

$id = intval($_GET['id'] ?? 0);
$usuario = fetchById('usuarios', $id);

if (!$usuario) {
    setFlash('error', 'Usuário não encontrado.');
    redirect(SITE_URL . '/admin/usuarios/');
}

$errors = [];
$data = $usuario;

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
            // Verificar se email já existe (exceto o atual)
            $exists = fetchOne('usuarios', 'email = ? AND id != ?', [$data['email'], $id]);
            if ($exists) {
                $errors[] = 'Este email já está em uso por outro usuário.';
            }
        }

        // Validar senha apenas se preenchida
        if (!empty($data['senha'])) {
            if (strlen($data['senha']) < 6) {
                $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
            } elseif ($data['senha'] !== $data['senha_confirma']) {
                $errors[] = 'As senhas não conferem.';
            }
        }

        if (!in_array($data['nivel'], ['admin', 'editor'])) {
            $errors[] = 'Nível inválido.';
        }

        // Não permitir que o admin se torne editor ou se desative
        if ($id === $currentUser['id']) {
            if ($data['nivel'] !== 'admin') {
                $errors[] = 'Você não pode rebaixar seu próprio nível de acesso.';
            }
            if (!$data['ativo']) {
                $errors[] = 'Você não pode desativar sua própria conta.';
            }
        }

        // Se não houver erros, atualizar
        if (empty($errors)) {
            $updateData = [
                'nome' => $data['nome'],
                'email' => $data['email'],
                'nivel' => $data['nivel'],
                'ativo' => $data['ativo']
            ];

            // Atualizar senha apenas se preenchida
            if (!empty($data['senha'])) {
                $updateData['senha'] = hashPassword($data['senha']);
            }

            $success = update('usuarios', $updateData, $id);

            if ($success) {
                setFlash('success', 'Usuário atualizado com sucesso!');
                redirect(SITE_URL . '/admin/usuarios/');
            } else {
                $errors[] = 'Erro ao atualizar usuário. Tente novamente.';
            }
        }
    }
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Editar Usuário</h1>
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
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Alterar Senha</h3>
            <p style="color: var(--color-text-muted); margin-bottom: var(--spacing-4);">
                Deixe em branco para manter a senha atual.
            </p>

            <div class="form-row">
                <div class="form-group">
                    <label for="senha" class="form-label">Nova Senha</label>
                    <input type="password" id="senha" name="senha" class="form-input" minlength="6">
                    <small style="color: var(--color-text-muted);">Mínimo de 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="senha_confirma" class="form-label">Confirmar Nova Senha</label>
                    <input type="password" id="senha_confirma" name="senha_confirma" class="form-input" minlength="6">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Permissões</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="nivel" class="form-label">Nível de Acesso *</label>
                    <select id="nivel" name="nivel" class="form-input" <?= $id === $currentUser['id'] ? 'disabled' : '' ?>>
                        <option value="editor" <?= $data['nivel'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                        <option value="admin" <?= $data['nivel'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                    <?php if ($id === $currentUser['id']): ?>
                    <input type="hidden" name="nivel" value="admin">
                    <small style="color: var(--color-text-muted);">Você não pode alterar seu próprio nível</small>
                    <?php else: ?>
                    <small style="color: var(--color-text-muted);">Administradores podem gerenciar usuários</small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <?php if ($id === $currentUser['id']): ?>
                    <label style="display: flex; align-items: center; gap: var(--spacing-2); cursor: not-allowed; opacity: 0.6;">
                        <input type="checkbox" name="ativo" value="1" checked disabled>
                        <span>Usuário ativo</span>
                    </label>
                    <input type="hidden" name="ativo" value="1">
                    <small style="color: var(--color-text-muted);">Você não pode desativar sua própria conta</small>
                    <?php else: ?>
                    <label style="display: flex; align-items: center; gap: var(--spacing-2); cursor: pointer;">
                        <input type="checkbox" name="ativo" value="1" <?= $data['ativo'] ? 'checked' : '' ?>>
                        <span>Usuário ativo</span>
                    </label>
                    <?php endif; ?>
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
                Salvar Alterações
            </button>
            <a href="<?= SITE_URL ?>/admin/usuarios/" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>

<div style="margin-top: var(--spacing-6); padding: var(--spacing-4); background: var(--color-gray-100); border-radius: var(--radius-md);">
    <small style="color: var(--color-text-muted);">
        Criado em: <?= formatDate($usuario['created_at'], 'd/m/Y H:i') ?> |
        Atualizado em: <?= formatDate($usuario['updated_at'], 'd/m/Y H:i') ?>
    </small>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
