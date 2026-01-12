<?php
/**
 * API - Blog Categories
 * CRUD para categorias do blog
 */

$method = getMethod();
$id = $_GET['id'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            getCategoryById($id);
        } else {
            listCategories();
        }
        break;

    case 'POST':
        requireApiAuth();
        createCategory();
        break;

    case 'PUT':
        requireApiAuth();
        if (!$id) {
            apiError('ID da categoria e obrigatorio', 400);
        }
        updateCategory($id);
        break;

    case 'DELETE':
        requireApiAuth();
        if (!$id) {
            apiError('ID da categoria e obrigatorio', 400);
        }
        deleteCategory($id);
        break;

    default:
        apiError('Metodo nao permitido', 405);
}

/**
 * Listar categorias
 */
function listCategories(): void {
    $sql = "
        SELECT
            c.*,
            COUNT(p.id) as total_posts
        FROM categorias_blog c
        LEFT JOIN blog_posts p ON c.id = p.categoria_id
        WHERE c.ativo = 1
        GROUP BY c.id
        ORDER BY c.nome ASC
    ";

    $categories = query($sql, []);

    apiSuccess(['categories' => $categories]);
}

/**
 * Obter categoria por ID
 */
function getCategoryById(int $id): void {
    $category = fetchById('categorias_blog', $id);

    if (!$category) {
        apiError('Categoria nao encontrada', 404);
    }

    // Contar posts
    $count = countRows('blog_posts', 'categoria_id = ?', [$id]);
    $category['total_posts'] = $count;

    apiSuccess(['category' => $category]);
}

/**
 * Criar categoria
 */
function createCategory(): void {
    $data = getRequestBody();

    // Validar campos obrigatorios
    $errors = validateRequired($data, ['nome']);
    if (!empty($errors)) {
        apiError('Dados invalidos', 400, $errors);
    }

    // Gerar slug se nao informado
    $slug = !empty($data['slug']) ? $data['slug'] : slugify($data['nome']);

    // Verificar se slug ja existe
    $existing = fetchOne('categorias_blog', 'slug = ?', [$slug]);
    if ($existing) {
        // Retornar categoria existente ao inves de erro (util para import)
        apiSuccess(['category' => $existing, 'created' => false], 'Categoria ja existe');
    }

    // Preparar dados
    $categoryData = [
        'nome' => $data['nome'],
        'slug' => $slug,
        'descricao' => $data['descricao'] ?? null,
        'cor' => $data['cor'] ?? '#FFBA00',
        'ativo' => $data['ativo'] ?? 1
    ];

    // Inserir
    $id = insert('categorias_blog', $categoryData);

    // Buscar categoria criada
    $category = fetchById('categorias_blog', $id);

    apiSuccess(['category' => $category, 'created' => true], 'Categoria criada com sucesso', 201);
}

/**
 * Atualizar categoria
 */
function updateCategory(int $id): void {
    // Verificar se existe
    $category = fetchById('categorias_blog', $id);
    if (!$category) {
        apiError('Categoria nao encontrada', 404);
    }

    $data = getRequestBody();

    // Preparar dados para update
    $updateData = [];

    if (isset($data['nome'])) {
        $updateData['nome'] = $data['nome'];
    }

    if (isset($data['slug'])) {
        // Verificar se slug ja existe (exceto a propria categoria)
        $existing = fetchOne('categorias_blog', 'slug = ? AND id != ?', [$data['slug'], $id]);
        if ($existing) {
            apiError('Slug ja existe', 400);
        }
        $updateData['slug'] = $data['slug'];
    }

    if (isset($data['descricao'])) {
        $updateData['descricao'] = $data['descricao'];
    }

    if (isset($data['cor'])) {
        $updateData['cor'] = $data['cor'];
    }

    if (isset($data['ativo'])) {
        $updateData['ativo'] = $data['ativo'];
    }

    if (empty($updateData)) {
        apiError('Nenhum dado para atualizar', 400);
    }

    // Atualizar
    update('categorias_blog', $updateData, 'id = ?', [$id]);

    // Buscar categoria atualizada
    $category = fetchById('categorias_blog', $id);

    apiSuccess(['category' => $category], 'Categoria atualizada com sucesso');
}

/**
 * Deletar categoria
 */
function deleteCategory(int $id): void {
    // Verificar se existe
    $category = fetchById('categorias_blog', $id);
    if (!$category) {
        apiError('Categoria nao encontrada', 404);
    }

    // Verificar se tem posts vinculados
    $count = countRows('blog_posts', 'categoria_id = ?', [$id]);
    if ($count > 0) {
        apiError("Nao e possivel deletar. Categoria possui {$count} post(s) vinculado(s).", 400);
    }

    // Deletar categoria
    delete('categorias_blog', 'id = ?', [$id]);

    apiSuccess(null, 'Categoria deletada com sucesso');
}

/**
 * Buscar ou criar categoria pelo nome
 * Util para importacao
 */
function findOrCreateCategory(string $name): int {
    $slug = slugify($name);

    // Buscar existente
    $existing = fetchOne('categorias_blog', 'slug = ?', [$slug]);
    if ($existing) {
        return $existing['id'];
    }

    // Criar nova
    return insert('categorias_blog', [
        'nome' => $name,
        'slug' => $slug,
        'ativo' => 1
    ]);
}
