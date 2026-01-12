<?php
/**
 * Script de Importacao WordPress → Construbao
 *
 * Importa posts do blog do WordPress antigo para o novo sistema
 * Suporta RankMath SEO
 *
 * USO:
 *   php import-wordpress.php
 *
 * Ou via navegador (nao recomendado para muitos posts)
 */

// Configuracoes
define('WP_SITE_URL', 'https://construbao.com.br');
define('WP_API_URL', WP_SITE_URL . '/wp-json/wp/v2');
define('POSTS_PER_PAGE', 100);
define('DOWNLOAD_IMAGES', true);
define('DRY_RUN', false); // Se true, nao insere no banco (apenas simula)

// Detectar se esta rodando via CLI ou navegador
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

// Carregar configuracoes do Construbao
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Estatisticas
$stats = [
    'total_found' => 0,
    'imported' => 0,
    'skipped' => 0,
    'errors' => 0,
    'images_downloaded' => 0,
    'categories_created' => 0
];

$errors = [];

// Funcao de log
function logMessage(string $message, string $type = 'info'): void {
    global $isCli;

    $prefix = match($type) {
        'success' => '[OK] ',
        'error' => '[ERRO] ',
        'warning' => '[AVISO] ',
        'info' => '[INFO] ',
        default => ''
    };

    $line = $prefix . $message . "\n";

    if ($isCli) {
        echo $line;
    } else {
        echo $line;
        flush();
        ob_flush();
    }
}

// Funcao para fazer requisicoes a API do WordPress
function wpApiRequest(string $endpoint, array $params = []): ?array {
    $url = WP_API_URL . $endpoint;

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    logMessage("Requisicao: " . $url);

    $context = stream_context_create([
        'http' => [
            'timeout' => 60,
            'user_agent' => 'Construbao Import Script/1.0',
            'ignore_errors' => true,
            'header' => "Accept: application/json\r\n"
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        logMessage("Erro: file_get_contents retornou false", 'error');
        return null;
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        logMessage("Erro JSON: " . json_last_error_msg(), 'error');
        return null;
    }

    return $data;
}

// Funcao para buscar ou criar categoria
function findOrCreateCategory(string $name): ?int {
    global $stats;

    if (empty($name)) {
        return null;
    }

    $slug = slugify($name);

    // Buscar existente
    $existing = fetchOne('categorias_blog', 'slug = ?', [$slug]);
    if ($existing) {
        return $existing['id'];
    }

    // Criar nova
    $id = insert('categorias_blog', [
        'nome' => $name,
        'slug' => $slug,
        'ativo' => 1
    ]);

    $stats['categories_created']++;
    logMessage("Categoria criada: {$name}", 'success');

    return $id;
}

// Funcao para baixar imagem
function downloadImage(string $url): ?string {
    global $stats;

    if (empty($url) || !DOWNLOAD_IMAGES) {
        return null;
    }

    // Validar URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return null;
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'Construbao Import/1.0'
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    $imageData = @file_get_contents($url, false, $context);

    if ($imageData === false) {
        logMessage("Erro ao baixar imagem: {$url}", 'warning');
        return null;
    }

    // Verificar tamanho
    if (strlen($imageData) > MAX_UPLOAD_SIZE) {
        logMessage("Imagem muito grande: {$url}", 'warning');
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
        logMessage("Tipo de imagem nao suportado: {$mimeType}", 'warning');
        return null;
    }

    $extension = $extensions[$mimeType];

    // Gerar nome do arquivo
    $filename = uniqid() . '_' . time() . '.' . $extension;

    // Criar diretorio se necessario
    $uploadDir = UPLOAD_PATH . 'blog/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Salvar arquivo
    $filePath = $uploadDir . $filename;
    if (file_put_contents($filePath, $imageData) === false) {
        logMessage("Erro ao salvar imagem: {$filePath}", 'warning');
        return null;
    }

    $stats['images_downloaded']++;
    return 'blog/' . $filename;
}

// Funcao para mapear status do WordPress para Construbao
function mapStatus(string $wpStatus): string {
    return match($wpStatus) {
        'publish' => 'publicado',
        'draft' => 'rascunho',
        'pending' => 'rascunho',
        'future' => 'agendado',
        default => 'rascunho'
    };
}

// Funcao para limpar HTML
function cleanHtml(string $html): string {
    // Remover tags HTML mas manter quebras de linha
    $text = strip_tags($html);
    // Decodificar entidades HTML
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    // Limpar espacos extras
    $text = trim(preg_replace('/\s+/', ' ', $text));
    return $text;
}

// Funcao para importar um post
function importPost(array $wpPost): bool {
    global $stats, $errors;

    $title = cleanHtml($wpPost['title']['rendered'] ?? '');
    $slug = $wpPost['slug'] ?? '';

    if (empty($title) || empty($slug)) {
        $errors[] = "Post sem titulo ou slug";
        $stats['errors']++;
        return false;
    }

    // Verificar se ja existe
    $existing = fetchOne('blog_posts', 'slug = ?', [$slug]);
    if ($existing) {
        logMessage("Post ja existe: {$title}", 'warning');
        $stats['skipped']++;
        return false;
    }

    // Extrair dados
    $content = $wpPost['content']['rendered'] ?? '';
    $excerpt = cleanHtml($wpPost['excerpt']['rendered'] ?? '');
    $status = mapStatus($wpPost['status'] ?? 'draft');
    $date = $wpPost['date'] ?? null;

    // Extrair categoria (primeira categoria)
    $categoryName = null;
    if (!empty($wpPost['_embedded']['wp:term'][0][0]['name'])) {
        $categoryName = $wpPost['_embedded']['wp:term'][0][0]['name'];
    }

    // Extrair imagem destacada
    $featuredImageUrl = null;
    if (!empty($wpPost['_embedded']['wp:featuredmedia'][0]['source_url'])) {
        $featuredImageUrl = $wpPost['_embedded']['wp:featuredmedia'][0]['source_url'];
    }

    // Extrair campos SEO do RankMath (se disponiveis via meta)
    $focusKeyword = $wpPost['rank_math_focus_keyword'] ?? $wpPost['meta']['rank_math_focus_keyword'] ?? null;
    $metaTitle = $wpPost['rank_math_title'] ?? $wpPost['meta']['rank_math_title'] ?? null;
    $metaDescription = $wpPost['rank_math_description'] ?? $wpPost['meta']['rank_math_description'] ?? null;

    // Processar
    $categoryId = $categoryName ? findOrCreateCategory($categoryName) : null;
    $imagePath = $featuredImageUrl ? downloadImage($featuredImageUrl) : null;

    // Preparar dados
    $postData = [
        'titulo' => $title,
        'slug' => $slug,
        'conteudo' => $content,
        'resumo' => substr($excerpt, 0, 300) ?: null,
        'categoria_id' => $categoryId,
        'autor_id' => 1, // Admin padrao
        'imagem_destaque' => $imagePath,
        'focus_keyword' => $focusKeyword,
        'meta_title' => $metaTitle ? substr($metaTitle, 0, 70) : null,
        'meta_description' => $metaDescription ? substr($metaDescription, 0, 160) : null,
        'status' => $status,
        'publicado_em' => $date ? date('Y-m-d H:i:s', strtotime($date)) : null,
        'views' => 0,
        'seo_score' => 0
    ];

    // Inserir
    if (!DRY_RUN) {
        try {
            insert('blog_posts', $postData);
            $stats['imported']++;
            return true;
        } catch (Exception $e) {
            $errors[] = "Erro ao inserir '{$title}': " . $e->getMessage();
            $stats['errors']++;
            return false;
        }
    } else {
        $stats['imported']++;
        return true;
    }
}

// ============================================================================
// INICIO DA IMPORTACAO
// ============================================================================

logMessage("=================================================");
logMessage("IMPORTACAO WORDPRESS -> CONSTRUBAO");
logMessage("=================================================");
logMessage("");
logMessage("Fonte: " . WP_SITE_URL);
logMessage("Destino: " . SITE_URL);
logMessage("Modo: " . (DRY_RUN ? 'SIMULACAO (dry-run)' : 'PRODUCAO'));
logMessage("Download de imagens: " . (DOWNLOAD_IMAGES ? 'SIM' : 'NAO'));
logMessage("");

// Buscar total de posts
logMessage("Verificando quantidade de posts...");

$firstPage = wpApiRequest('/posts', [
    'per_page' => 1,
    '_embed' => 1
]);

if ($firstPage === null) {
    logMessage("ERRO: Nao foi possivel conectar a API do WordPress", 'error');
    logMessage("Verifique se a URL esta correta: " . WP_API_URL);
    exit(1);
}

// Buscar todos os posts (paginado)
$page = 1;
$allPosts = [];

logMessage("Buscando posts...");

while (true) {
    $posts = wpApiRequest('/posts', [
        'per_page' => POSTS_PER_PAGE,
        'page' => $page,
        '_embed' => 1
    ]);

    // Verificar se a resposta e valida
    if ($posts === null) {
        logMessage("Erro ao buscar pagina {$page}", 'warning');
        break;
    }

    // Se array vazio, terminou
    if (empty($posts)) {
        logMessage("Pagina {$page}: array vazio, finalizando");
        break;
    }

    // Verificar se e um erro da API (tem campo 'code')
    if (isset($posts['code']) && isset($posts['message'])) {
        logMessage("Erro API: {$posts['code']} - {$posts['message']}", 'error');
        break;
    }

    // Contar posts validos
    $count = 0;
    foreach ($posts as $post) {
        if (is_array($post) && isset($post['id'])) {
            $allPosts[] = $post;
            $count++;
        }
    }

    logMessage("Pagina {$page}: {$count} posts encontrados");

    if (count($posts) < POSTS_PER_PAGE) {
        break;
    }

    $page++;
}

$stats['total_found'] = count($allPosts);
logMessage("");
logMessage("Total de posts encontrados: " . $stats['total_found']);
logMessage("");

if ($stats['total_found'] === 0) {
    logMessage("Nenhum post encontrado para importar.", 'warning');
    exit(0);
}

// Importar cada post
logMessage("Iniciando importacao...");
logMessage("-------------------------------------------------");

$num = 0;
foreach ($allPosts as $wpPost) {
    $num++;

    // Validar que o post e um array valido
    if (!is_array($wpPost)) {
        logMessage("Post {$num} invalido (nao e array), pulando...", 'warning');
        $stats['errors']++;
        continue;
    }

    // Verificar se tem os campos necessarios
    if (!isset($wpPost['title']) || !isset($wpPost['slug'])) {
        logMessage("Post {$num} sem titulo ou slug, pulando...", 'warning');
        $stats['errors']++;
        continue;
    }

    $title = cleanHtml($wpPost['title']['rendered'] ?? 'Sem titulo');
    $title = substr($title, 0, 50) . (strlen($title) > 50 ? '...' : '');

    echo "[{$num}/{$stats['total_found']}] \"{$title}\" ... ";

    if (importPost($wpPost)) {
        echo "OK\n";
    } else {
        echo "PULADO\n";
    }
}

// Relatorio final
logMessage("");
logMessage("=================================================");
logMessage("RELATORIO FINAL");
logMessage("=================================================");
logMessage("Total encontrados: " . $stats['total_found']);
logMessage("Importados: " . $stats['imported'], 'success');
logMessage("Pulados (ja existiam): " . $stats['skipped'], 'warning');
logMessage("Erros: " . $stats['errors'], $stats['errors'] > 0 ? 'error' : 'info');
logMessage("Imagens baixadas: " . $stats['images_downloaded']);
logMessage("Categorias criadas: " . $stats['categories_created']);
logMessage("");

if (!empty($errors)) {
    logMessage("ERROS DETALHADOS:", 'error');
    foreach ($errors as $error) {
        logMessage("  - " . $error, 'error');
    }
}

if (DRY_RUN) {
    logMessage("");
    logMessage(">>> MODO SIMULACAO - Nenhum dado foi inserido no banco <<<", 'warning');
    logMessage(">>> Para executar de verdade, altere DRY_RUN para false <<<", 'warning');
}

logMessage("");
logMessage("Importacao concluida!");
