<?php
/**
 * CONSTRUBÃO - Blog
 * Listagem de posts
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Blog - Dicas e Novidades';
$pageDescription = 'Confira dicas sobre construção, equipamentos e postes padrão. Conteúdo atualizado para ajudar sua obra.';

// Filtro por categoria
$categoriaSlug = get('categoria');

// Buscar categorias
$categorias = fetchAll('categorias_blog', 'ativo = 1', [], 'nome ASC');

// Buscar posts
if ($categoriaSlug && $categoriaSlug !== 'todos') {
    $categoria = fetchOne('categorias_blog', 'slug = ?', [$categoriaSlug]);
    if ($categoria) {
        $posts = fetchAll('blog_posts', 'categoria_id = ? AND status = "publicado"', [$categoria['id']], 'publicado_em DESC');
    } else {
        $posts = [];
    }
} else {
    $posts = fetchAll('blog_posts', 'status = "publicado"', [], 'publicado_em DESC');
}

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section style="background: var(--color-secondary); padding: 96px 0; margin-top: 100px;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div style="max-width: 768px;">
            <span style="font-family: var(--font-subtitle); font-size: var(--text-sm); text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary); font-weight: 600;">Blog</span>
            <h1 style="font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 3.5rem); color: white; margin-top: var(--spacing-4); line-height: 1.1;">Dicas e Novidades</h1>
            <p style="font-family: var(--font-body); font-size: 1.25rem; color: rgba(255,255,255,0.7); margin-top: var(--spacing-6); line-height: 1.6;">
                Conteúdo sobre construção, equipamentos e boas práticas para sua obra.
            </p>
        </div>
    </div>
</section>

<!-- Filtros Section -->
<section style="background: #fff; padding: 32px 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="<?= SITE_URL ?>/blog.php" class="blog-filter-btn <?= !$categoriaSlug || $categoriaSlug === 'todos' ? 'active' : '' ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; white-space: nowrap; font-family: var(--font-subtitle); font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 8px 16px; border-radius: 6px; transition: all 0.3s ease; <?= !$categoriaSlug || $categoriaSlug === 'todos' ? 'background: var(--color-primary); color: var(--color-foreground); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);' : 'background: transparent; color: var(--color-primary); border: 2px solid var(--color-primary);' ?>">
                Todos
            </a>
            <?php foreach ($categorias as $cat): ?>
            <a href="<?= SITE_URL ?>/blog.php?categoria=<?= e($cat['slug']) ?>" class="blog-filter-btn <?= $categoriaSlug === $cat['slug'] ? 'active' : '' ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; white-space: nowrap; font-family: var(--font-subtitle); font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 8px 16px; border-radius: 6px; transition: all 0.3s ease; <?= $categoriaSlug === $cat['slug'] ? 'background: var(--color-primary); color: var(--color-foreground); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);' : 'background: transparent; color: var(--color-primary); border: 2px solid var(--color-primary);' ?>">
                <?= e($cat['nome']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Blog Posts Section -->
<section style="padding: 96px 0; background: #fff;">
    <div class="container" style="padding: 0 var(--spacing-4);">
        <?php if (!empty($posts)): ?>
        <div class="blog-grid" style="display: grid; grid-template-columns: 1fr; gap: 32px;">
            <?php foreach ($posts as $index => $post):
                $postCategoria = $post['categoria_id'] ? fetchById('categorias_blog', $post['categoria_id']) : null;
            ?>
            <article class="blog-card" data-animate="slide-up" style="background: white; border-radius: 16px; border: 1px solid var(--color-border); overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); transition: all 0.3s ease; animation-delay: <?= min($index * 0.1, 0.5) ?>s;">
                <!-- Image -->
                <a href="<?= SITE_URL ?>/post.php?slug=<?= e($post['slug']) ?>" style="display: block; aspect-ratio: 16/10; overflow: hidden;">
                    <?php if ($post['imagem_destaque']): ?>
                    <img src="<?= UPLOAD_URL . e($post['imagem_destaque']) ?>" alt="<?= e($post['titulo']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                    <?php else: ?>
                    <div style="width: 100%; height: 100%; background: var(--color-secondary); display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                            <circle cx="9" cy="9" r="2"></circle>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                        </svg>
                    </div>
                    <?php endif; ?>
                </a>

                <!-- Content -->
                <div style="padding: 24px;">
                    <!-- Meta -->
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                        <?php if ($postCategoria): ?>
                        <span style="padding: 4px 12px; border-radius: 9999px; background: rgba(255, 186, 0, 0.1); color: var(--color-primary); font-family: var(--font-subtitle); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                            <?= e($postCategoria['nome']) ?>
                        </span>
                        <?php endif; ?>
                        <span style="display: flex; align-items: center; gap: 4px; color: var(--color-text-muted); font-size: var(--text-sm);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            <?= formatDate($post['publicado_em'] ?? $post['created_at'], 'd M Y') ?>
                        </span>
                    </div>

                    <!-- Title -->
                    <h3 style="font-family: var(--font-display); font-size: 1.25rem; color: var(--color-foreground); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;">
                        <a href="<?= SITE_URL ?>/post.php?slug=<?= e($post['slug']) ?>" style="color: inherit; text-decoration: none;">
                            <?= e($post['titulo']) ?>
                        </a>
                    </h3>

                    <!-- Excerpt -->
                    <p style="font-family: var(--font-body); color: var(--color-text-muted); font-size: var(--text-sm); margin-bottom: 24px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                        <?= e($post['resumo'] ?? truncate(strip_tags($post['conteudo']), 120)) ?>
                    </p>

                    <!-- Button -->
                    <a href="<?= SITE_URL ?>/post.php?slug=<?= e($post['slug']) ?>" class="blog-btn" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 8px 16px; font-family: var(--font-subtitle); font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-primary); background: transparent; border: 2px solid var(--color-primary); border-radius: 6px; transition: all 0.3s ease;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            Ler Mais
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 96px 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--color-gray-400)" stroke-width="2" style="margin: 0 auto 16px;">
                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--color-text-muted); margin-bottom: 8px;">Nenhum post encontrado</h3>
            <p style="color: var(--color-gray-500);">Ainda não há posts nesta categoria.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Blog Grid - Responsive */
@media (min-width: 768px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (min-width: 1024px) {
    .blog-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}

/* Blog Card Hover */
.blog-card:hover {
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    transform: translateY(-8px);
}
.blog-card:hover img {
    transform: scale(1.1);
}

/* Blog Button Hover */
.blog-btn:hover {
    background: var(--color-primary);
    color: var(--color-foreground);
}

/* Filter Button Hover */
.blog-filter-btn:not(.active):hover {
    background: var(--color-primary);
    color: var(--color-foreground);
}
</style>

<?php include __DIR__ . '/includes/cta-final.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
