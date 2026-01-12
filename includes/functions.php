<?php
/**
 * Funções auxiliares do site
 */

require_once __DIR__ . '/config.php';

/**
 * Gera URL amigável (slug)
 */
function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

/**
 * Escapa HTML para prevenir XSS
 */
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Gera token CSRF
 */
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida token CSRF
 */
function validateCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Input hidden com CSRF token
 */
function csrfField(): string {
    return '<input type="hidden" name="_token" value="' . generateCsrfToken() . '">';
}

/**
 * Redireciona para URL
 */
function redirect(string $url): void {
    header("Location: {$url}");
    exit;
}

/**
 * Define mensagem flash
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Obtém e limpa mensagem flash
 */
function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Exibe mensagem flash formatada
 */
function showFlash(): string {
    $flash = getFlash();
    if (!$flash) return '';

    $type = $flash['type'];
    $message = e($flash['message']);

    $classes = [
        'success' => 'alert-success',
        'error' => 'alert-error',
        'warning' => 'alert-warning',
        'info' => 'alert-info'
    ];

    $class = $classes[$type] ?? 'alert-info';

    return "<div class=\"alert {$class}\">{$message}</div>";
}

/**
 * Formata data para exibição
 */
function formatDate(string $date, string $format = 'd/m/Y'): string {
    return date($format, strtotime($date));
}

/**
 * Formata data relativa (há X dias)
 */
function timeAgo(string $datetime): string {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'agora';
    if ($diff < 3600) return floor($diff / 60) . ' min atrás';
    if ($diff < 86400) return floor($diff / 3600) . 'h atrás';
    if ($diff < 2592000) return floor($diff / 86400) . ' dias atrás';
    if ($diff < 31536000) return floor($diff / 2592000) . ' meses atrás';

    return floor($diff / 31536000) . ' anos atrás';
}

/**
 * Trunca texto
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . $suffix;
}

/**
 * Upload de imagem
 */
function uploadImage(array $file, string $folder): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Validar tipo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return null;
    }

    // Validar tamanho
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return null;
    }

    // Gerar nome único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;

    // Criar pasta se não existir
    $uploadDir = UPLOAD_PATH . $folder . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Mover arquivo
    $destination = $uploadDir . $filename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $folder . '/' . $filename;
    }

    return null;
}

/**
 * Remove imagem
 */
function deleteImage(string $path): bool {
    $fullPath = UPLOAD_PATH . $path;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

/**
 * URL da imagem
 */
function imageUrl(string $path): string {
    if (empty($path)) {
        return ASSETS_URL . 'images/placeholder.png';
    }

    // Se for caminho de upload
    if (strpos($path, 'uploads/') === false && strpos($path, 'assets/') === false) {
        return UPLOAD_URL . $path;
    }

    return SITE_URL . '/' . $path;
}

/**
 * Gera link do WhatsApp
 */
function whatsappLink(string $message = null): string {
    return 'https://wa.link/9ohsml';
}

/**
 * Verifica se é requisição AJAX
 */
function isAjax(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Resposta JSON
 */
function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Obtém IP do visitante
 */
function getClientIp(): string {
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            return $_SERVER[$key];
        }
    }
    return 'unknown';
}

/**
 * Valida email
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Limpa entrada de texto
 */
function sanitize(string $input): string {
    return trim(strip_tags($input));
}

/**
 * Obtém variável GET sanitizada
 */
function get(string $key, $default = null) {
    return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
}

/**
 * Obtém variável POST sanitizada
 */
function post(string $key, $default = null) {
    return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
}

/**
 * Decodifica características JSON
 */
function getCaracteristicas($json): array {
    if (empty($json)) return [];
    if (is_array($json)) return $json;
    return json_decode($json, true) ?? [];
}

/**
 * Meta tags para SEO
 */
function metaTags(string $title, string $description = '', string $image = ''): string {
    $siteName = SITE_NAME;
    $siteUrl = SITE_URL;
    $desc = $description ?: SITE_DESCRIPTION;
    $img = $image ?: ASSETS_URL . 'images/logo-construbao.png';

    return <<<HTML
    <title>{$title} | {$siteName}</title>
    <meta name="description" content="{$desc}">
    <meta property="og:title" content="{$title}">
    <meta property="og:description" content="{$desc}">
    <meta property="og:image" content="{$img}">
    <meta property="og:url" content="{$siteUrl}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
HTML;
}
