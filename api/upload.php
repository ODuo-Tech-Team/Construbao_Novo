<?php
/**
 * API - Upload de Imagens
 * Suporta multipart/form-data e base64
 */

$method = getMethod();

if ($method !== 'POST') {
    apiError('Metodo nao permitido', 405);
}

// Requer autenticacao
requireApiAuth();

// Verificar tipo de upload
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'multipart/form-data') !== false) {
    handleMultipartUpload();
} elseif (strpos($contentType, 'application/json') !== false) {
    handleBase64Upload();
} else {
    apiError('Content-Type deve ser multipart/form-data ou application/json', 400);
}

/**
 * Upload via multipart/form-data
 */
function handleMultipartUpload(): void {
    if (empty($_FILES['file'])) {
        apiError('Nenhum arquivo enviado. Use o campo "file"', 400);
    }

    $file = $_FILES['file'];
    $folder = $_POST['folder'] ?? 'blog';

    // Validar pasta permitida
    $allowedFolders = ['blog', 'equipamentos', 'postes', 'depoimentos'];
    if (!in_array($folder, $allowedFolders)) {
        apiError('Pasta invalida. Use: ' . implode(', ', $allowedFolders), 400);
    }

    // Fazer upload usando funcao existente
    $path = uploadImage($file, $folder);

    if (!$path) {
        apiError('Erro ao fazer upload. Verifique tipo e tamanho do arquivo.', 400);
    }

    apiSuccess([
        'path' => $path,
        'url' => UPLOAD_URL . $path,
        'folder' => $folder
    ], 'Upload realizado com sucesso', 201);
}

/**
 * Upload via base64 (JSON)
 */
function handleBase64Upload(): void {
    $data = getRequestBody();

    if (empty($data['file'])) {
        apiError('Campo "file" (base64) e obrigatorio', 400);
    }

    $folder = $data['folder'] ?? 'blog';
    $filename = $data['filename'] ?? null;

    // Validar pasta permitida
    $allowedFolders = ['blog', 'equipamentos', 'postes', 'depoimentos'];
    if (!in_array($folder, $allowedFolders)) {
        apiError('Pasta invalida. Use: ' . implode(', ', $allowedFolders), 400);
    }

    // Decodificar base64
    $base64 = $data['file'];

    // Remover prefixo data:image se existir
    if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
        $extension = $matches[1];
        $base64 = substr($base64, strpos($base64, ',') + 1);
    } else {
        $extension = 'jpg';
    }

    $imageData = base64_decode($base64);

    if ($imageData === false) {
        apiError('Base64 invalido', 400);
    }

    // Verificar tamanho
    if (strlen($imageData) > MAX_UPLOAD_SIZE) {
        apiError('Arquivo muito grande. Max: ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB', 400);
    }

    // Gerar nome do arquivo
    if ($filename) {
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($filename, PATHINFO_FILENAME));
        $filename = $filename . '_' . time() . '.' . $extension;
    } else {
        $filename = uniqid() . '_' . time() . '.' . $extension;
    }

    // Criar diretorio se necessario
    $uploadDir = UPLOAD_PATH . $folder . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Salvar arquivo
    $filePath = $uploadDir . $filename;
    if (file_put_contents($filePath, $imageData) === false) {
        apiError('Erro ao salvar arquivo', 500);
    }

    $relativePath = $folder . '/' . $filename;

    apiSuccess([
        'path' => $relativePath,
        'url' => UPLOAD_URL . $relativePath,
        'folder' => $folder
    ], 'Upload realizado com sucesso', 201);
}

/**
 * Download de imagem de URL externa
 * Util para importacao do WordPress
 */
function downloadImage(string $url, string $folder = 'blog'): ?string {
    // Validar URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return null;
    }

    // Baixar imagem
    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'Construbao Import/1.0'
        ]
    ]);

    $imageData = @file_get_contents($url, false, $context);

    if ($imageData === false) {
        return null;
    }

    // Verificar tamanho
    if (strlen($imageData) > MAX_UPLOAD_SIZE) {
        return null;
    }

    // Detectar extensao
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($imageData);

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    if (!isset($extensions[$mimeType])) {
        return null;
    }

    $extension = $extensions[$mimeType];

    // Gerar nome do arquivo
    $filename = uniqid() . '_' . time() . '.' . $extension;

    // Criar diretorio se necessario
    $uploadDir = UPLOAD_PATH . $folder . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Salvar arquivo
    $filePath = $uploadDir . $filename;
    if (file_put_contents($filePath, $imageData) === false) {
        return null;
    }

    return $folder . '/' . $filename;
}
