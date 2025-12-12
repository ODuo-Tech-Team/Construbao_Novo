<?php
/**
 * ADMIN - Criar Categoria do Blog
 */

$pageTitle = 'Nova Categoria';
require_once __DIR__ . '/../includes/header.php';

$errors = [];
$data = [
    'nome' => '',
    'slug' => '',
    'descricao' => '',
    'cor' => '#FFBA00',
    'ativo' => 1
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['_token'] ?? '')) {
        $errors[] = 'Token de segurança inválido.';
    } else {
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'cor' => $_POST['cor'] ?? '#FFBA00',
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        if (empty($data['slug'])) {
            $data['slug'] = slugify($data['nome']);
        } else {
            $data['slug'] = slugify($data['slug']);
        }

        if (empty($data['nome'])) {
            $errors[] = 'O nome é obrigatório.';
        }

        if (empty($data['slug'])) {
            $errors[] = 'O slug é obrigatório.';
        } else {
            $exists = fetchOne('categorias_blog', 'slug = ?', [$data['slug']]);
            if ($exists) {
                $errors[] = 'Este slug já está em uso.';
            }
        }

        if (empty($errors)) {
            $id = insert('categorias_blog', $data);

            if ($id) {
                setFlash('success', 'Categoria criada com sucesso!');
                redirect(SITE_URL . '/admin/categorias-blog/');
            } else {
                $errors[] = 'Erro ao criar categoria. Tente novamente.';
            }
        }
    }
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Nova Categoria</h1>
    <a href="<?= SITE_URL ?>/admin/categorias-blog/" class="btn btn-outline">
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
            <div class="form-row">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" class="form-input" value="<?= e($data['nome']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-input" value="<?= e($data['slug']) ?>">
                    <small style="color: var(--color-text-muted);">Gerado automaticamente se deixado em branco</small>
                </div>
            </div>

            <div class="form-group">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-input" rows="3"><?= e($data['descricao']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cor" class="form-label">Cor</label>
                    <div style="display: flex; align-items: center; gap: var(--spacing-2);">
                        <input type="color" id="cor" name="cor" value="<?= e($data['cor']) ?>" style="width: 50px; height: 38px; padding: 2px; cursor: pointer;">
                        <input type="text" value="<?= e($data['cor']) ?>" class="form-input" style="width: 100px;" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <label style="display: flex; align-items: center; gap: var(--spacing-2); cursor: pointer;">
                        <input type="checkbox" name="ativo" value="1" <?= $data['ativo'] ? 'checked' : '' ?>>
                        <span>Categoria ativa</span>
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
                Salvar Categoria
            </button>
            <a href="<?= SITE_URL ?>/admin/categorias-blog/" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.getElementById('cor').addEventListener('change', function() {
    this.nextElementSibling.value = this.value;
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
