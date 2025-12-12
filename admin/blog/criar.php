<?php
/**
 * ADMIN - Criar Post do Blog
 */

$pageTitle = 'Novo Post';
require_once __DIR__ . '/../includes/header.php';
$extraScripts = [SITE_URL . '/admin/assets/js/seo-analyzer.js'];

$errors = [];
$data = [
    'titulo' => '',
    'slug' => '',
    'categoria_id' => '',
    'resumo' => '',
    'conteudo' => '',
    'focus_keyword' => '',
    'meta_title' => '',
    'meta_description' => '',
    'status' => 'rascunho',
    'publicado_em' => ''
];

$categorias = query("SELECT * FROM categorias_blog WHERE ativo = 1 ORDER BY nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['_token'] ?? '')) {
        $errors[] = 'Token de segurança inválido.';
    } else {
        $data = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'categoria_id' => intval($_POST['categoria_id'] ?? 0) ?: null,
            'autor_id' => $currentUser['id'],
            'resumo' => trim($_POST['resumo'] ?? ''),
            'conteudo' => $_POST['conteudo'] ?? '',
            'focus_keyword' => trim($_POST['focus_keyword'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'seo_score' => intval($_POST['seo_score'] ?? 0),
            'status' => $_POST['status'] ?? 'rascunho',
            'publicado_em' => null
        ];

        // Gerar slug se vazio
        if (empty($data['slug'])) {
            $data['slug'] = slugify($data['titulo']);
        } else {
            $data['slug'] = slugify($data['slug']);
        }

        // Data de publicação
        if ($data['status'] === 'publicado') {
            $data['publicado_em'] = date('Y-m-d H:i:s');
        } elseif ($data['status'] === 'agendado' && !empty($_POST['publicado_em'])) {
            $data['publicado_em'] = $_POST['publicado_em'];
        }

        // Validações
        if (empty($data['titulo'])) {
            $errors[] = 'O título é obrigatório.';
        }

        if (empty($data['slug'])) {
            $errors[] = 'O slug é obrigatório.';
        } else {
            $exists = fetchOne('blog_posts', 'slug = ?', [$data['slug']]);
            if ($exists) {
                $errors[] = 'Este slug já está em uso.';
            }
        }

        // Upload de imagem
        $imagem = null;
        if (!empty($_FILES['imagem_destaque']['name'])) {
            $imagem = uploadImage($_FILES['imagem_destaque'], 'blog');
            if ($imagem === null) {
                $errors[] = 'Erro ao fazer upload da imagem. Verifique o tipo e tamanho do arquivo.';
            }
        }

        if (empty($errors)) {
            $data['imagem_destaque'] = $imagem;
            $id = insert('blog_posts', $data);

            if ($id) {
                setFlash('success', 'Post criado com sucesso!');
                redirect(SITE_URL . '/admin/blog/');
            } else {
                $errors[] = 'Erro ao criar post. Tente novamente.';
                if ($imagem) {
                    deleteImage($imagem, 'blog');
                }
            }
        }
    }
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Novo Post</h1>
    <a href="<?= SITE_URL ?>/admin/blog/" class="btn btn-outline">
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

<form method="POST" enctype="multipart/form-data" id="postForm">
    <?= csrfField() ?>
    <input type="hidden" name="seo_score" id="seo_score" value="0">

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: var(--spacing-6); align-items: start;">
        <!-- Main Content -->
        <div>
            <div class="admin-form">
                <div class="form-section">
                    <h3 class="form-section-title">Conteúdo</h3>

                    <div class="form-group">
                        <label for="titulo" class="form-label">Título *</label>
                        <input type="text" id="titulo" name="titulo" class="form-input" value="<?= e($data['titulo']) ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-input" value="<?= e($data['slug']) ?>">
                        </div>

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
                    </div>

                    <div class="form-group">
                        <label for="resumo" class="form-label">Resumo</label>
                        <textarea id="resumo" name="resumo" class="form-input" rows="2" maxlength="300"><?= e($data['resumo']) ?></textarea>
                        <small style="color: var(--color-text-muted);">Máximo 300 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Conteúdo</label>
                        <div id="editor" style="height: 300px; background: white;"><?= $data['conteudo'] ?></div>
                        <input type="hidden" name="conteudo" id="conteudo">
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Imagem Destaque</h3>

                    <div class="form-group">
                        <div class="image-upload">
                            <input type="file" name="imagem_destaque" accept="image/*" style="display: none;">
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
                        <label for="focus_keyword" class="form-label">Palavra-chave Foco</label>
                        <input type="text" id="focus_keyword" name="focus_keyword" class="form-input" value="<?= e($data['focus_keyword']) ?>" placeholder="Ex: aluguel de equipamentos">
                        <small style="color: var(--color-text-muted);">A palavra-chave principal para otimização SEO</small>
                    </div>

                    <div class="form-group">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-input" value="<?= e($data['meta_title']) ?>" maxlength="70">
                        <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                            <small style="color: var(--color-text-muted);">Título que aparece nos resultados do Google</small>
                            <small id="meta_title_count" style="color: var(--color-text-muted);">0/70</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-input" rows="2" maxlength="160"><?= e($data['meta_description']) ?></textarea>
                        <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                            <small style="color: var(--color-text-muted);">Descrição que aparece nos resultados do Google</small>
                            <small id="meta_description_count" style="color: var(--color-text-muted);">0/160</small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Publicação</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-input">
                                <option value="rascunho" <?= $data['status'] === 'rascunho' ? 'selected' : '' ?>>Rascunho</option>
                                <option value="publicado" <?= $data['status'] === 'publicado' ? 'selected' : '' ?>>Publicado</option>
                                <option value="agendado" <?= $data['status'] === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                            </select>
                        </div>

                        <div class="form-group" id="publicado_em_group" style="display: none;">
                            <label for="publicado_em" class="form-label">Data de Publicação</label>
                            <input type="datetime-local" id="publicado_em" name="publicado_em" class="form-input" value="<?= $data['publicado_em'] ?>">
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
                        Salvar Post
                    </button>
                    <a href="<?= SITE_URL ?>/admin/blog/" class="btn btn-outline">Cancelar</a>
                </div>
            </div>
        </div>

        <!-- SEO Analysis Panel -->
        <div class="seo-panel" id="seoPanel">
            <div class="seo-panel-header">
                <h3 class="seo-panel-title">Análise SEO</h3>
                <div class="seo-score">
                    <span class="seo-score-value" id="seoScoreDisplay">0</span>
                    <span style="color: var(--color-text-muted);">/100</span>
                </div>
            </div>
            <div class="seo-panel-body" id="seoChecks">
                <p style="color: var(--color-text-muted); text-align: center; padding: var(--spacing-4);">
                    Preencha os campos para ver a análise SEO
                </p>
            </div>
        </div>
    </div>
</form>

<script>
// Quill Editor
var quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Escreva o conteúdo do post...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['blockquote', 'link', 'image'],
            ['clean']
        ]
    }
});

// Update hidden input before submit
document.getElementById('postForm').addEventListener('submit', function() {
    document.getElementById('conteudo').value = quill.root.innerHTML;
});

// Show/hide publicado_em field based on status
document.getElementById('status').addEventListener('change', function() {
    const group = document.getElementById('publicado_em_group');
    group.style.display = this.value === 'agendado' ? 'block' : 'none';
});

// Character counters
document.getElementById('meta_title').addEventListener('input', function() {
    document.getElementById('meta_title_count').textContent = this.value.length + '/70';
});

document.getElementById('meta_description').addEventListener('input', function() {
    document.getElementById('meta_description_count').textContent = this.value.length + '/160';
});

// Initialize counters
document.getElementById('meta_title_count').textContent = document.getElementById('meta_title').value.length + '/70';
document.getElementById('meta_description_count').textContent = document.getElementById('meta_description').value.length + '/160';
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
