<?php
/**
 * API - Blog Posts
 * CRUD completo para posts do blog
 */

$method = getMethod();
$id = $_GET['id'] ?? null;
$slug = $_GET['slug'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            getPostById($id);
        } elseif ($slug) {
            getPostBySlug($slug);
        } else {
            listPosts();
        }
        break;

    case 'POST':
        requireApiAuth();
        createPost();
        break;

    case 'PUT':
        requireApiAuth();
        if (!$id) {
            apiError('ID do post e obrigatorio', 400);
        }
        updatePost($id);
        break;

    case 'DELETE':
        requireApiAuth();
        if (!$id) {
            apiError('ID do post e obrigatorio', 400);
        }
        deletePost($id);
        break;

    default:
        apiError('Metodo nao permitido', 405);
}

/**
 * Listar posts com paginacao
 */
function listPosts(): void {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = min(100, max(1, (int)($_GET['limit'] ?? 10)));
    $offset = ($page - 1) * $limit;

    $status = $_GET['status'] ?? null;
    $categoria = $_GET['categoria'] ?? null;

    // Construir query
    $where = '1=1';
    $params = [];

    if ($status) {
        $where .= ' AND p.status = ?';
        $params[] = $status;
    }

    if ($categoria) {
        $where .= ' AND p.categoria_id = ?';
        $params[] = $categoria;
    }

    // Contar total
    $countSql = "SELECT COUNT(*) as total FROM blog_posts p WHERE $where";
    $countResult = query($countSql, $params);
    $total = $countResult[0]['total'] ?? 0;

    // Buscar posts
    $sql = "
        SELECT
            p.*,
            c.nome as categoria_nome,
            c.slug as categoria_slug,
            u.nome as autor_nome
        FROM blog_posts p
        LEFT JOIN categorias_blog c ON p.categoria_id = c.id
        LEFT JOIN usuarios u ON p.autor_id = u.id
        WHERE $where
        ORDER BY p.created_at DESC
        LIMIT $limit OFFSET $offset
    ";

    $posts = query($sql, $params);

    apiSuccess([
        'posts' => $posts,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => (int)$total,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

/**
 * Obter post por ID
 */
function getPostById(int $id): void {
    $sql = "
        SELECT
            p.*,
            c.nome as categoria_nome,
            c.slug as categoria_slug,
            u.nome as autor_nome
        FROM blog_posts p
        LEFT JOIN categorias_blog c ON p.categoria_id = c.id
        LEFT JOIN usuarios u ON p.autor_id = u.id
        WHERE p.id = ?
    ";

    $posts = query($sql, [$id]);

    if (empty($posts)) {
        apiError('Post nao encontrado', 404);
    }

    apiSuccess(['post' => $posts[0]]);
}

/**
 * Obter post por slug
 */
function getPostBySlug(string $slug): void {
    $sql = "
        SELECT
            p.*,
            c.nome as categoria_nome,
            c.slug as categoria_slug,
            u.nome as autor_nome
        FROM blog_posts p
        LEFT JOIN categorias_blog c ON p.categoria_id = c.id
        LEFT JOIN usuarios u ON p.autor_id = u.id
        WHERE p.slug = ?
    ";

    $posts = query($sql, [$slug]);

    if (empty($posts)) {
        apiError('Post nao encontrado', 404);
    }

    apiSuccess(['post' => $posts[0]]);
}

/**
 * Criar novo post
 */
function createPost(): void {
    $data = getRequestBody();

    // Validar campos obrigatorios
    $errors = validateRequired($data, ['titulo', 'conteudo']);
    if (!empty($errors)) {
        apiError('Dados invalidos', 400, $errors);
    }

    // Gerar slug se nao informado
    $slug = !empty($data['slug']) ? $data['slug'] : slugify($data['titulo']);

    // Verificar se slug ja existe
    $existing = fetchOne('blog_posts', 'slug = ?', [$slug]);
    if ($existing) {
        $slug = $slug . '-' . time();
    }

    // Preparar dados
    $postData = [
        'titulo' => $data['titulo'],
        'slug' => $slug,
        'conteudo' => $data['conteudo'],
        'resumo' => isset($data['resumo']) ? substr($data['resumo'], 0, 300) : null,
        'categoria_id' => $data['categoria_id'] ?? null,
        'autor_id' => $data['autor_id'] ?? null,
        'imagem_destaque' => $data['imagem_destaque'] ?? null,
        'focus_keyword' => $data['focus_keyword'] ?? null,
        'meta_title' => isset($data['meta_title']) ? substr($data['meta_title'], 0, 70) : null,
        'meta_description' => isset($data['meta_description']) ? substr($data['meta_description'], 0, 160) : null,
        'canonical_url' => $data['canonical_url'] ?? null,
        'og_title' => $data['og_title'] ?? null,
        'og_description' => $data['og_description'] ?? null,
        'og_image' => $data['og_image'] ?? null,
        'seo_score' => $data['seo_score'] ?? 0,
        'status' => $data['status'] ?? 'rascunho',
        'publicado_em' => null,
        'views' => 0
    ];

    // Se status for publicado, definir data de publicacao
    if ($postData['status'] === 'publicado') {
        $postData['publicado_em'] = $data['publicado_em'] ?? date('Y-m-d H:i:s');
    } elseif ($postData['status'] === 'agendado' && !empty($data['publicado_em'])) {
        $postData['publicado_em'] = $data['publicado_em'];
    }

    // Inserir
    $id = insert('blog_posts', $postData);

    // Buscar post criado
    $post = fetchById('blog_posts', $id);

    apiSuccess(['post' => $post], 'Post criado com sucesso', 201);
}

/**
 * Atualizar post
 */
function updatePost(int $id): void {
    // Verificar se existe
    $post = fetchById('blog_posts', $id);
    if (!$post) {
        apiError('Post nao encontrado', 404);
    }

    $data = getRequestBody();

    // Preparar dados para update
    $updateData = [];

    if (isset($data['titulo'])) {
        $updateData['titulo'] = $data['titulo'];
    }

    if (isset($data['slug'])) {
        // Verificar se slug ja existe (exceto o proprio post)
        $existing = fetchOne('blog_posts', 'slug = ? AND id != ?', [$data['slug'], $id]);
        if ($existing) {
            apiError('Slug ja existe', 400);
        }
        $updateData['slug'] = $data['slug'];
    }

    if (isset($data['conteudo'])) {
        $updateData['conteudo'] = $data['conteudo'];
    }

    if (isset($data['resumo'])) {
        $updateData['resumo'] = substr($data['resumo'], 0, 300);
    }

    if (array_key_exists('categoria_id', $data)) {
        $updateData['categoria_id'] = $data['categoria_id'];
    }

    if (array_key_exists('autor_id', $data)) {
        $updateData['autor_id'] = $data['autor_id'];
    }

    if (array_key_exists('imagem_destaque', $data)) {
        $updateData['imagem_destaque'] = $data['imagem_destaque'];
    }

    // Campos SEO
    $seoFields = ['focus_keyword', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'og_image', 'seo_score'];
    foreach ($seoFields as $field) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }

    // Status
    if (isset($data['status'])) {
        $updateData['status'] = $data['status'];

        if ($data['status'] === 'publicado' && empty($post['publicado_em'])) {
            $updateData['publicado_em'] = date('Y-m-d H:i:s');
        }
    }

    if (isset($data['publicado_em'])) {
        $updateData['publicado_em'] = $data['publicado_em'];
    }

    if (empty($updateData)) {
        apiError('Nenhum dado para atualizar', 400);
    }

    // Atualizar
    update('blog_posts', $updateData, 'id = ?', [$id]);

    // Buscar post atualizado
    $post = fetchById('blog_posts', $id);

    apiSuccess(['post' => $post], 'Post atualizado com sucesso');
}

/**
 * Deletar post
 */
function deletePost(int $id): void {
    // Verificar se existe
    $post = fetchById('blog_posts', $id);
    if (!$post) {
        apiError('Post nao encontrado', 404);
    }

    // Deletar imagem se existir
    if (!empty($post['imagem_destaque'])) {
        deleteImage($post['imagem_destaque']);
    }

    // Deletar post
    delete('blog_posts', 'id = ?', [$id]);

    apiSuccess(null, 'Post deletado com sucesso');
}
