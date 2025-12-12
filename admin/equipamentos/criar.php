<?php
/**
 * ADMIN - Criar Equipamento
 */

$pageTitle = 'Novo Equipamento';
require_once __DIR__ . '/../includes/header.php';

$errors = [];
$data = [
    'nome' => '',
    'slug' => '',
    'categoria_id' => '',
    'descricao' => '',
    'caracteristicas' => '',
    'meta_title' => '',
    'meta_description' => '',
    'ordem' => 0,
    'ativo' => 1
];

$categorias = query("SELECT * FROM categorias_equipamentos WHERE ativo = 1 ORDER BY nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['_token'] ?? '')) {
        $errors[] = 'Token de segurança inválido.';
    } else {
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'categoria_id' => intval($_POST['categoria_id'] ?? 0) ?: null,
            'descricao' => trim($_POST['descricao'] ?? ''),
            'caracteristicas' => trim($_POST['caracteristicas'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'ordem' => intval($_POST['ordem'] ?? 0),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        // Gerar slug se vazio
        if (empty($data['slug'])) {
            $data['slug'] = slugify($data['nome']);
        } else {
            $data['slug'] = slugify($data['slug']);
        }

        // Validações
        if (empty($data['nome'])) {
            $errors[] = 'O nome é obrigatório.';
        }

        if (empty($data['slug'])) {
            $errors[] = 'O slug é obrigatório.';
        } else {
            $exists = fetchOne('equipamentos', 'slug = ?', [$data['slug']]);
            if ($exists) {
                $errors[] = 'Este slug já está em uso.';
            }
        }

        // Processar características como JSON
        if (!empty($data['caracteristicas'])) {
            $linhas = explode("\n", $data['caracteristicas']);
            $caracteristicasArray = [];
            foreach ($linhas as $linha) {
                $linha = trim($linha);
                if ($linha) {
                    $caracteristicasArray[] = $linha;
                }
            }
            $data['caracteristicas'] = json_encode($caracteristicasArray, JSON_UNESCAPED_UNICODE);
        } else {
            $data['caracteristicas'] = null;
        }

        // Upload de imagem
        $imagem = null;
        if (!empty($_FILES['imagem']['name'])) {
            $imagem = uploadImage($_FILES['imagem'], 'equipamentos');
            if ($imagem === null) {
                $errors[] = 'Erro ao fazer upload da imagem. Verifique o tipo e tamanho do arquivo.';
            }
        }

        if (empty($errors)) {
            $data['imagem'] = $imagem;
            $id = insert('equipamentos', $data);

            if ($id) {
                setFlash('success', 'Equipamento criado com sucesso!');
                redirect(SITE_URL . '/admin/equipamentos/');
            } else {
                $errors[] = 'Erro ao criar equipamento. Tente novamente.';
                // Limpar imagem se houve erro
                if ($imagem) {
                    deleteImage($imagem, 'equipamentos');
                }
            }
        }
    }
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Novo Equipamento</h1>
    <a href="<?= SITE_URL ?>/admin/equipamentos/" class="btn btn-outline">
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
    <form method="POST" enctype="multipart/form-data">
        <?= csrfField() ?>

        <div class="form-section">
            <h3 class="form-section-title">Informações Básicas</h3>

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

            <div class="form-row">
                <div class="form-group">
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select id="categoria_id" name="categoria_id" class="form-input">
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $data['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= e($cat['nome']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ordem" class="form-label">Ordem</label>
                    <input type="number" id="ordem" name="ordem" class="form-input" value="<?= $data['ordem'] ?>" min="0">
                </div>
            </div>

            <div class="form-group">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-input" rows="4"><?= e($data['descricao']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="caracteristicas" class="form-label">Características</label>
                <textarea id="caracteristicas" name="caracteristicas" class="form-input" rows="5" placeholder="Uma característica por linha"><?= e(is_string($data['caracteristicas']) ? $data['caracteristicas'] : '') ?></textarea>
                <small style="color: var(--color-text-muted);">Digite uma característica por linha</small>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Imagem</h3>

            <div class="form-group">
                <label class="form-label">Imagem do Equipamento</label>
                <div class="image-upload">
                    <input type="file" name="imagem" accept="image/*" style="display: none;">
                    <div class="image-upload-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                            <circle cx="9" cy="9" r="2"></circle>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                        </svg>
                        <p>Clique para selecionar uma imagem</p>
                        <small>JPG, PNG ou WebP (max 2MB)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">SEO</h3>

            <div class="form-group">
                <label for="meta_title" class="form-label">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" class="form-input" value="<?= e($data['meta_title']) ?>" maxlength="70">
                <small style="color: var(--color-text-muted);">Máximo 70 caracteres</small>
            </div>

            <div class="form-group">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea id="meta_description" name="meta_description" class="form-input" rows="2" maxlength="160"><?= e($data['meta_description']) ?></textarea>
                <small style="color: var(--color-text-muted);">Máximo 160 caracteres</small>
            </div>
        </div>

        <div class="form-section">
            <div class="form-group">
                <label class="form-label">Status</label>
                <label style="display: flex; align-items: center; gap: var(--spacing-2); cursor: pointer;">
                    <input type="checkbox" name="ativo" value="1" <?= $data['ativo'] ? 'checked' : '' ?>>
                    <span>Equipamento ativo</span>
                </label>
            </div>
        </div>

        <div style="display: flex; gap: var(--spacing-3);">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Salvar Equipamento
            </button>
            <a href="<?= SITE_URL ?>/admin/equipamentos/" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
