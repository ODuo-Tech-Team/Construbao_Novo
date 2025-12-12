<?php
/**
 * ADMIN - Editar Depoimento
 */

$pageTitle = 'Editar Depoimento';
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
$depoimento = fetchById('depoimentos', $id);

if (!$depoimento) {
    setFlash('error', 'Depoimento não encontrado.');
    redirect(SITE_URL . '/admin/depoimentos/');
}

$errors = [];
$data = $depoimento;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['_token'] ?? '')) {
        $errors[] = 'Token de segurança inválido.';
    } else {
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'texto' => trim($_POST['texto'] ?? ''),
            'avaliacao' => intval($_POST['avaliacao'] ?? 5),
            'data_depoimento' => $_POST['data_depoimento'] ?? null,
            'ordem' => intval($_POST['ordem'] ?? 0),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        // Validações
        if (empty($data['nome'])) {
            $errors[] = 'O nome é obrigatório.';
        }

        if (empty($data['texto'])) {
            $errors[] = 'O texto do depoimento é obrigatório.';
        }

        if ($data['avaliacao'] < 1 || $data['avaliacao'] > 5) {
            $data['avaliacao'] = 5;
        }

        // Upload de nova foto
        $novaFoto = null;
        if (!empty($_FILES['foto']['name'])) {
            $novaFoto = uploadImage($_FILES['foto'], 'depoimentos');
            if ($novaFoto === null) {
                $errors[] = 'Erro ao fazer upload da foto. Verifique o tipo e tamanho do arquivo.';
            }
        }

        if (empty($errors)) {
            // Deletar foto antiga se nova foi enviada
            if ($novaFoto && $depoimento['foto']) {
                deleteImage($depoimento['foto'], 'depoimentos');
            }

            if ($novaFoto) {
                $data['foto'] = $novaFoto;
            }

            $success = update('depoimentos', $data, $id);

            if ($success) {
                setFlash('success', 'Depoimento atualizado com sucesso!');
                redirect(SITE_URL . '/admin/depoimentos/');
            } else {
                $errors[] = 'Erro ao atualizar depoimento. Tente novamente.';
            }
        }
    }
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Editar Depoimento</h1>
    <a href="<?= SITE_URL ?>/admin/depoimentos/" class="btn btn-outline">
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
            <h3 class="form-section-title">Informações</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" class="form-input" value="<?= e($data['nome']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="data_depoimento" class="form-label">Data</label>
                    <input type="date" id="data_depoimento" name="data_depoimento" class="form-input" value="<?= $data['data_depoimento'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="texto" class="form-label">Depoimento *</label>
                <textarea id="texto" name="texto" class="form-input" rows="4" required><?= e($data['texto']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="avaliacao" class="form-label">Avaliação</label>
                    <select id="avaliacao" name="avaliacao" class="form-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>" <?= $data['avaliacao'] == $i ? 'selected' : '' ?>>
                            <?= str_repeat('★', $i) ?><?= str_repeat('☆', 5 - $i) ?> (<?= $i ?> estrela<?= $i > 1 ? 's' : '' ?>)
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ordem" class="form-label">Ordem</label>
                    <input type="number" id="ordem" name="ordem" class="form-input" value="<?= $data['ordem'] ?>" min="0">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Foto (opcional)</h3>

            <div class="form-group">
                <div class="image-upload <?= $depoimento['foto'] ? 'has-image' : '' ?>">
                    <input type="file" name="foto" accept="image/*" style="display: none;">
                    <?php if ($depoimento['foto']): ?>
                    <img src="<?= imageUrl($depoimento['foto'], 'depoimentos') ?>" alt="<?= e($depoimento['nome']) ?>" class="image-preview" style="border-radius: 50%;">
                    <?php endif; ?>
                    <div class="image-upload-placeholder" <?= $depoimento['foto'] ? 'style="display: none;"' : '' ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <p>Clique para selecionar uma foto</p>
                        <small>JPG, PNG ou WebP (max 2MB)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-group">
                <label class="form-label">Status</label>
                <label style="display: flex; align-items: center; gap: var(--spacing-2); cursor: pointer;">
                    <input type="checkbox" name="ativo" value="1" <?= $data['ativo'] ? 'checked' : '' ?>>
                    <span>Depoimento ativo</span>
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
            <a href="<?= SITE_URL ?>/admin/depoimentos/" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>

<div style="margin-top: var(--spacing-6); padding: var(--spacing-4); background: var(--color-gray-100); border-radius: var(--radius-md);">
    <small style="color: var(--color-text-muted);">
        Criado em: <?= formatDate($depoimento['created_at'], 'd/m/Y H:i') ?>
    </small>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
