<?php
/**
 * ADMIN - Editar Poste
 */

$pageTitle = 'Editar Poste';
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
$poste = fetchById('postes', $id);

if (!$poste) {
    setFlash('error', 'Poste não encontrado.');
    redirect(SITE_URL . '/admin/postes/');
}

$errors = [];
$data = $poste;

// Converter características JSON para texto
if (!empty($data['caracteristicas'])) {
    $caractArray = json_decode($data['caracteristicas'], true);
    if (is_array($caractArray)) {
        $data['caracteristicas'] = implode("\n", $caractArray);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['_token'] ?? '')) {
        $errors[] = 'Token de segurança inválido.';
    } else {
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
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
            $exists = fetchOne('postes', 'slug = ? AND id != ?', [$data['slug'], $id]);
            if ($exists) {
                $errors[] = 'Este slug já está em uso.';
            }
        }

        // Processar características como JSON
        $caracteristicasTexto = $data['caracteristicas'];
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

        // Upload de nova imagem
        $novaImagem = null;
        if (!empty($_FILES['imagem']['name'])) {
            $novaImagem = uploadImage($_FILES['imagem'], 'postes');
            if ($novaImagem === null) {
                $errors[] = 'Erro ao fazer upload da imagem. Verifique o tipo e tamanho do arquivo.';
            }
        }

        if (empty($errors)) {
            // Deletar imagem antiga se nova foi enviada
            if ($novaImagem && $poste['imagem']) {
                deleteImage($poste['imagem'], 'postes');
            }

            if ($novaImagem) {
                $data['imagem'] = $novaImagem;
            }

            $success = update('postes', $data, $id);

            if ($success) {
                setFlash('success', 'Poste atualizado com sucesso!');
                redirect(SITE_URL . '/admin/postes/');
            } else {
                $errors[] = 'Erro ao atualizar poste. Tente novamente.';
                $data['caracteristicas'] = $caracteristicasTexto;
            }
        } else {
            $data['caracteristicas'] = $caracteristicasTexto;
        }
    }
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Editar Poste</h1>
    <div style="display: flex; gap: var(--spacing-2);">
        <a href="<?= SITE_URL ?>/poste.php?slug=<?= $poste['slug'] ?>" target="_blank" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" x2="21" y1="14" y2="3"></line>
            </svg>
            Ver no Site
        </a>
        <a href="<?= SITE_URL ?>/admin/postes/" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>
            Voltar
        </a>
    </div>
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

            <div class="form-group">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-input" rows="4"><?= e($data['descricao']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="caracteristicas" class="form-label">Características</label>
                <textarea id="caracteristicas" name="caracteristicas" class="form-input" rows="5" placeholder="Uma característica por linha"><?= e($data['caracteristicas'] ?? '') ?></textarea>
                <small style="color: var(--color-text-muted);">Digite uma característica por linha</small>
            </div>

            <div class="form-group">
                <label for="ordem" class="form-label">Ordem</label>
                <input type="number" id="ordem" name="ordem" class="form-input" value="<?= $data['ordem'] ?>" min="0" style="max-width: 150px;">
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Imagem</h3>

            <div class="form-group">
                <label class="form-label">Imagem do Poste</label>
                <div class="image-upload <?= $poste['imagem'] ? 'has-image' : '' ?>">
                    <input type="file" name="imagem" accept="image/*" style="display: none;">
                    <?php if ($poste['imagem']): ?>
                    <img src="<?= imageUrl($poste['imagem'], 'postes') ?>" alt="<?= e($poste['nome']) ?>" class="image-preview">
                    <?php endif; ?>
                    <div class="image-upload-placeholder" <?= $poste['imagem'] ? 'style="display: none;"' : '' ?>>
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
                    <span>Poste ativo</span>
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
                Salvar Alterações
            </button>
            <a href="<?= SITE_URL ?>/admin/postes/" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>

<div style="margin-top: var(--spacing-6); padding: var(--spacing-4); background: var(--color-gray-100); border-radius: var(--radius-md);">
    <small style="color: var(--color-text-muted);">
        Criado em: <?= formatDate($poste['created_at'], 'd/m/Y H:i') ?> |
        Atualizado em: <?= formatDate($poste['updated_at'], 'd/m/Y H:i') ?>
    </small>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
