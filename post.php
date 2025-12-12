<?php
/**
 * CONSTRUBÃO - Post Individual
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$slug = get('slug');

if (!$slug) {
    header('Location: ' . SITE_URL . '/blog.php');
    exit;
}

// Buscar post
$post = fetchOne('blog_posts', 'slug = ? AND status = "publicado"', [$slug]);

if (!$post) {
    header('Location: ' . SITE_URL . '/404.php');
    exit;
}

// Incrementar views
update('blog_posts', ['views' => $post['views'] + 1], 'id = ?', [$post['id']]);

// Buscar categoria e autor
$categoria = $post['categoria_id'] ? fetchById('categorias_blog', $post['categoria_id']) : null;
$autor = $post['autor_id'] ? fetchById('usuarios', $post['autor_id']) : null;

// Posts relacionados
$relacionados = fetchAll(
    'blog_posts',
    'id != ? AND status = "publicado"' . ($categoria ? ' AND categoria_id = ' . $categoria['id'] : ''),
    [$post['id']],
    'publicado_em DESC'
);
$relacionados = array_slice($relacionados, 0, 3);

// Meta tags
$pageTitle = $post['meta_title'] ?: $post['titulo'];
$pageDescription = $post['meta_description'] ?: truncate(strip_tags($post['conteudo']), 160);
$pageImage = $post['og_image'] ?: ($post['imagem_destaque'] ? UPLOAD_URL . $post['imagem_destaque'] : '');

include __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb -->
<section style="padding: var(--spacing-4) 0; margin-top: 80px; background: var(--color-background-muted);">
    <div class="container">
        <nav class="breadcrumb">
            <a href="<?= SITE_URL ?>" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= SITE_URL ?>/blog.php" class="breadcrumb-link">Blog</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current"><?= e(truncate($post['titulo'], 30)) ?></span>
        </nav>
    </div>
</section>

<!-- Post Content -->
<article class="bg-muted" style="padding: var(--spacing-12) 0;">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Header -->
            <header data-animate="slide-up">
                <?php if ($categoria): ?>
                <span class="badge" style="background-color: <?= e($categoria['cor'] ?? '#FFBA00') ?>; margin-bottom: var(--spacing-4);">
                    <?= e($categoria['nome']) ?>
                </span>
                <?php endif; ?>

                <h1 style="font-size: var(--text-hero-sm); margin-bottom: var(--spacing-4); line-height: 1.2;">
                    <?= e($post['titulo']) ?>
                </h1>

                <!-- Meta -->
                <div style="display: flex; align-items: center; gap: var(--spacing-6); color: var(--color-text-muted); margin-bottom: var(--spacing-8); flex-wrap: wrap;">
                    <?php if ($autor): ?>
                    <div style="display: flex; align-items: center; gap: var(--spacing-2);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span><?= e($autor['nome']) ?></span>
                    </div>
                    <?php endif; ?>

                    <div style="display: flex; align-items: center; gap: var(--spacing-2);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                            <line x1="16" x2="16" y1="2" y2="6"></line>
                            <line x1="8" x2="8" y1="2" y2="6"></line>
                            <line x1="3" x2="21" y1="10" y2="10"></line>
                        </svg>
                        <span><?= formatDate($post['publicado_em'] ?? $post['created_at']) ?></span>
                    </div>

                    <div style="display: flex; align-items: center; gap: var(--spacing-2);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span><?= number_format($post['views']) ?> visualizações</span>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if ($post['imagem_destaque']): ?>
            <div data-animate="slide-up" style="margin-bottom: var(--spacing-8); border-radius: var(--radius-xl); overflow: hidden;">
                <img src="<?= UPLOAD_URL . e($post['imagem_destaque']) ?>" alt="<?= e($post['titulo']) ?>" style="width: 100%; height: auto;">
            </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="card" style="padding: var(--spacing-8);" data-animate="slide-up">
                <div class="post-content" style="font-size: var(--text-lg); line-height: 1.8; color: var(--color-text);">
                    <?= $post['conteudo'] ?>
                </div>

                <!-- Share -->
                <div style="margin-top: var(--spacing-8); padding-top: var(--spacing-6); border-top: 1px solid var(--color-border);">
                    <p style="font-weight: 600; margin-bottom: var(--spacing-4);">Compartilhe:</p>
                    <div class="flex gap-4">
                        <a href="https://wa.me/?text=<?= urlencode($post['titulo'] . ' - ' . SITE_URL . '/post.php?slug=' . $post['slug']) ?>" target="_blank" class="btn btn-whatsapp btn-sm">
                            WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL . '/post.php?slug=' . $post['slug']) ?>" target="_blank" class="btn btn-outline btn-sm">
                            Facebook
                        </a>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="card" style="padding: var(--spacing-8); margin-top: var(--spacing-8); background: var(--color-secondary); text-align: center;" data-animate="slide-up">
                <h3 style="color: white; margin-bottom: var(--spacing-4);">Precisa de equipamentos para sua obra?</h3>
                <p style="color: rgba(255,255,255,0.8); margin-bottom: var(--spacing-6);">
                    Entre em contato conosco e faça seu orçamento sem compromisso.
                </p>
                <a href="<?= whatsappLink() ?>" target="_blank" class="btn btn-whatsapp">
                    Falar no WhatsApp
                </a>
            </div>
        </div>
    </div>
</article>

<!-- Posts Relacionados -->
<?php if (!empty($relacionados)): ?>
<section class="bg-white" style="padding: var(--spacing-16) 0;">
    <div class="container">
        <h2 class="section-title text-center" style="margin-bottom: var(--spacing-8);">Posts Relacionados</h2>

        <div class="blog-grid grid grid-3">
            <?php foreach ($relacionados as $index => $rel):
                $relCategoria = $rel['categoria_id'] ? fetchById('categorias_blog', $rel['categoria_id']) : null;
            ?>
            <article class="card card-elevated" data-animate="slide-up" data-animate-delay="<?= $index + 1 ?>">
                <a href="<?= SITE_URL ?>/post.php?slug=<?= e($rel['slug']) ?>" style="display: block; aspect-ratio: 16/10; overflow: hidden;">
                    <?php if ($rel['imagem_destaque']): ?>
                    <img src="<?= UPLOAD_URL . e($rel['imagem_destaque']) ?>" alt="<?= e($rel['titulo']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                    <div style="width: 100%; height: 100%; background: var(--color-secondary);"></div>
                    <?php endif; ?>
                </a>
                <div class="card-body">
                    <h3 style="font-size: var(--text-lg); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <a href="<?= SITE_URL ?>/post.php?slug=<?= e($rel['slug']) ?>" style="color: inherit;">
                            <?= e($rel['titulo']) ?>
                        </a>
                    </h3>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.post-content h2 {
    font-size: var(--text-2xl);
    margin: var(--spacing-8) 0 var(--spacing-4);
}

.post-content h3 {
    font-size: var(--text-xl);
    margin: var(--spacing-6) 0 var(--spacing-3);
}

.post-content p {
    margin-bottom: var(--spacing-4);
}

.post-content ul,
.post-content ol {
    margin: var(--spacing-4) 0;
    padding-left: var(--spacing-6);
}

.post-content li {
    margin-bottom: var(--spacing-2);
}

.post-content a {
    color: var(--color-secondary);
    text-decoration: underline;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius-md);
    margin: var(--spacing-4) 0;
}

.post-content blockquote {
    border-left: 4px solid var(--color-primary);
    padding-left: var(--spacing-4);
    margin: var(--spacing-6) 0;
    font-style: italic;
    color: var(--color-text-muted);
}
</style>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
