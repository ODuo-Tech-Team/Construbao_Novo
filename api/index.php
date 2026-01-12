<?php
/**
 * API - Router Principal
 * Roteia requisicoes para os endpoints corretos
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$method = getMethod();
$path = getPath();

// Roteamento
switch (true) {
    // Blog Posts
    case preg_match('#^/blog/posts/?$#', $path):
        require __DIR__ . '/blog/posts.php';
        break;

    case preg_match('#^/blog/posts/(\d+)$#', $path, $matches):
        $_GET['id'] = $matches[1];
        require __DIR__ . '/blog/posts.php';
        break;

    case preg_match('#^/blog/posts/slug/([a-z0-9-]+)$#', $path, $matches):
        $_GET['slug'] = $matches[1];
        require __DIR__ . '/blog/posts.php';
        break;

    // Blog Categories
    case preg_match('#^/blog/categories/?$#', $path):
        require __DIR__ . '/blog/categories.php';
        break;

    case preg_match('#^/blog/categories/(\d+)$#', $path, $matches):
        $_GET['id'] = $matches[1];
        require __DIR__ . '/blog/categories.php';
        break;

    // Upload
    case preg_match('#^/upload/?$#', $path):
        require __DIR__ . '/upload.php';
        break;

    // Health check
    case $path === '/' || $path === '':
        apiSuccess([
            'api' => 'Construbao API',
            'version' => '1.0',
            'status' => 'online',
            'endpoints' => [
                'GET /api/blog/posts' => 'Listar posts',
                'GET /api/blog/posts/{id}' => 'Obter post por ID',
                'GET /api/blog/posts/slug/{slug}' => 'Obter post por slug',
                'POST /api/blog/posts' => 'Criar post',
                'PUT /api/blog/posts/{id}' => 'Atualizar post',
                'DELETE /api/blog/posts/{id}' => 'Deletar post',
                'GET /api/blog/categories' => 'Listar categorias',
                'POST /api/blog/categories' => 'Criar categoria',
                'POST /api/upload' => 'Upload de imagem'
            ]
        ]);
        break;

    default:
        apiError('Endpoint nao encontrado', 404);
}
