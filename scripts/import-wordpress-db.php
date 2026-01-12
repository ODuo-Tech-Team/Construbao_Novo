<?php
/**
 * Script de Importacao DIRETA do Banco WordPress -> Construbao
 *
 * Conecta diretamente no banco MySQL do WordPress e migra os posts
 *
 * USO via CLI:
 *   php import-wordpress-db.php
 */

// ==============================================================================
// CONFIGURACOES DO WORDPRESS - PREENCHA AQUI
// ==============================================================================
define('WP_DB_HOST', 'localhost');           // Host do banco WordPress
define('WP_DB_NAME', 'educas86_wp645');    // Nome do banco WordPress
define('WP_DB_USER', 'educas86_wp645p');          // Usuario do banco WordPress
define('WP_DB_PASS', 'D!lSpA.861');            // Senha do banco WordPress
define('WP_TABLE_PREFIX', 'wp_');            // Prefixo das tabelas (geralmente wp_)
define('WP_SITE_URL', 'https://construbao.com.br'); // URL do site WordPress (para imagens)

// Opcoes
define('DOWNLOAD_IMAGES', true);  // Baixar imagens destacadas
define('DRY_RUN', false);         // Se true, apenas simula (nao insere no banco)

// ==============================================================================
// NAO MODIFICAR ABAIXO DESTA LINHA
// ==============================================================================

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
    $prefix = match($type) {
        'success' => '[OK] ',
        'error' => '[ERRO] ',
        'warning' => '[AVISO] ',
        'info' => '[INFO] ',
        default => ''
    };
    echo $prefix . $message . "\n";
    flush();
}

// Conectar ao banco WordPress
function connectWordPress(): ?PDO {
    try {
        $dsn = "mysql:host=" . WP_DB_HOST . ";dbname=" . WP_DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, WP_DB_USER, WP_DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        logMessage("Erro ao conectar no banco WordPress: " . $e->getMessage(), 'error');
        return null;
    }
}

// Buscar meta do post WordPress
function getPostMeta(PDO $wpDb, int $postId, string $metaKey): ?string {
    $prefix = WP_TABLE_PREFIX;
    $stmt = $wpDb->prepare("SELECT meta_value FROM {$prefix}postmeta WHERE post_id = ? AND meta_key = ? LIMIT 1");
    $stmt->execute([$postId, $metaKey]);
    $result = $stmt->fetch();
    return $result ? $result['meta_value'] : null;
}

// Buscar imagem destacada do WordPress
function getFeaturedImage(PDO $wpDb, int $postId): ?string {
    $prefix = WP_TABLE_PREFIX;

    // Buscar ID da imagem destacada
    $thumbnailId = getPostMeta($wpDb, $postId, '_thumbnail_id');
    if (!$thumbnailId) {
        return null;
    }

    // Buscar URL da imagem
    $stmt = $wpDb->prepare("SELECT guid FROM {$prefix}posts WHERE ID = ? AND post_type = 'attachment'");
    $stmt->execute([$thumbnailId]);
    $result = $stmt->fetch();

    return $result ? $result['guid'] : null;
}

// Buscar categoria do post WordPress
function getPostCategory(PDO $wpDb, int $postId): ?string {
    $prefix = WP_TABLE_PREFIX;

    $sql = "SELECT t.name
            FROM {$prefix}terms t
            INNER JOIN {$prefix}term_taxonomy tt ON t.term_id = tt.term_id
            INNER JOIN {$prefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
            WHERE tr.object_id = ? AND tt.taxonomy = 'category'
            LIMIT 1";

    $stmt = $wpDb->prepare($sql);
    $stmt->execute([$postId]);
    $result = $stmt->fetch();

    return $result ? $result['name'] : null;
}

// Buscar ou criar categoria no Construbao
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
    if (!DRY_RUN) {
        $id = insert('categorias_blog', [
            'nome' => $name,
            'slug' => $slug,
            'ativo' => 1
        ]);
        $stats['categories_created']++;
        logMessage("Categoria criada: {$name}", 'success');
        return $id;
    }

    return null;
}

// Baixar imagem
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

// Mapear status do WordPress
function mapStatus(string $wpStatus): string {
    return match($wpStatus) {
        'publish' => 'publicado',
        'draft' => 'rascunho',
        'pending' => 'rascunho',
        'future' => 'agendado',
        default => 'rascunho'
    };
}

// ==============================================================================
// INICIO DA IMPORTACAO
// ==============================================================================

logMessage("=================================================");
logMessage("IMPORTACAO DIRETA BANCO WORDPRESS -> CONSTRUBAO");
logMessage("=================================================");
logMessage("");
logMessage("Fonte: " . WP_DB_HOST . "/" . WP_DB_NAME);
logMessage("Destino: " . DB_NAME);
logMessage("Modo: " . (DRY_RUN ? 'SIMULACAO (dry-run)' : 'PRODUCAO'));
logMessage("Download de imagens: " . (DOWNLOAD_IMAGES ? 'SIM' : 'NAO'));
logMessage("");

// Conectar ao WordPress
logMessage("Conectando ao banco WordPress...");
$wpDb = connectWordPress();

if (!$wpDb) {
    logMessage("Falha na conexao. Verifique as credenciais.", 'error');
    exit(1);
}

logMessage("Conexao estabelecida!", 'success');
logMessage("");

// Buscar posts do WordPress
$prefix = WP_TABLE_PREFIX;
$sql = "SELECT ID, post_title, post_name, post_content, post_excerpt, post_status, post_date
        FROM {$prefix}posts
        WHERE post_type = 'post'
        AND post_status IN ('publish', 'draft', 'pending')
        ORDER BY post_date DESC";

$stmt = $wpDb->query($sql);
$wpPosts = $stmt->fetchAll();

$stats['total_found'] = count($wpPosts);
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
foreach ($wpPosts as $wpPost) {
    $num++;
    $postId = $wpPost['ID'];
    $title = html_entity_decode($wpPost['post_title'], ENT_QUOTES, 'UTF-8');
    $titleShort = substr($title, 0, 50) . (strlen($title) > 50 ? '...' : '');

    echo "[{$num}/{$stats['total_found']}] \"{$titleShort}\" ... ";

    $slug = $wpPost['post_name'];

    // Verificar se ja existe
    $existing = fetchOne('blog_posts', 'slug = ?', [$slug]);
    if ($existing) {
        echo "PULADO (ja existe)\n";
        $stats['skipped']++;
        continue;
    }

    // Buscar dados adicionais do WordPress
    $categoryName = getPostCategory($wpDb, $postId);
    $featuredImageUrl = getFeaturedImage($wpDb, $postId);

    // Buscar campos SEO do RankMath
    $focusKeyword = getPostMeta($wpDb, $postId, 'rank_math_focus_keyword');
    $metaTitle = getPostMeta($wpDb, $postId, 'rank_math_title');
    $metaDescription = getPostMeta($wpDb, $postId, 'rank_math_description');

    // Se nao tem RankMath, tentar Yoast
    if (!$focusKeyword) {
        $focusKeyword = getPostMeta($wpDb, $postId, '_yoast_wpseo_focuskw');
    }
    if (!$metaTitle) {
        $metaTitle = getPostMeta($wpDb, $postId, '_yoast_wpseo_title');
    }
    if (!$metaDescription) {
        $metaDescription = getPostMeta($wpDb, $postId, '_yoast_wpseo_metadesc');
    }

    // Processar
    $categoryId = $categoryName ? findOrCreateCategory($categoryName) : null;
    $imagePath = $featuredImageUrl ? downloadImage($featuredImageUrl) : null;

    // Limpar excerpt
    $excerpt = strip_tags($wpPost['post_excerpt']);
    $excerpt = html_entity_decode($excerpt, ENT_QUOTES, 'UTF-8');
    $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));

    // Preparar dados
    $postData = [
        'titulo' => $title,
        'slug' => $slug,
        'conteudo' => $wpPost['post_content'],
        'resumo' => $excerpt ? substr($excerpt, 0, 300) : null,
        'categoria_id' => $categoryId,
        'autor_id' => 1, // Admin padrao
        'imagem_destaque' => $imagePath,
        'focus_keyword' => $focusKeyword,
        'meta_title' => $metaTitle ? substr($metaTitle, 0, 70) : null,
        'meta_description' => $metaDescription ? substr($metaDescription, 0, 160) : null,
        'status' => mapStatus($wpPost['post_status']),
        'publicado_em' => $wpPost['post_date'],
        'views' => 0,
        'seo_score' => 0
    ];

    // Inserir
    if (!DRY_RUN) {
        try {
            insert('blog_posts', $postData);
            echo "OK\n";
            $stats['imported']++;
        } catch (Exception $e) {
            echo "ERRO\n";
            $errors[] = "Erro ao inserir '{$title}': " . $e->getMessage();
            $stats['errors']++;
        }
    } else {
        echo "OK (simulado)\n";
        $stats['imported']++;
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
