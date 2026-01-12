<?php
/**
 * Debug - Verificar API do WordPress
 */

header('Content-Type: text/plain; charset=utf-8');

$wpUrl = 'https://construbao.com.br';
$apiUrl = $wpUrl . '/wp-json/wp/v2/posts?per_page=5&_embed=1';

echo "=================================================\n";
echo "DEBUG API WORDPRESS\n";
echo "=================================================\n\n";

echo "URL: {$apiUrl}\n\n";

// Fazer requisicao
$context = stream_context_create([
    'http' => [
        'timeout' => 60,
        'user_agent' => 'Construbao Import Script/1.0',
        'ignore_errors' => true
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

echo "Fazendo requisicao...\n\n";

$response = @file_get_contents($apiUrl, false, $context);

// Verificar headers da resposta
echo "=== HEADERS DA RESPOSTA ===\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        echo $header . "\n";
    }
}
echo "\n";

// Verificar resposta
echo "=== RESPOSTA (primeiros 2000 chars) ===\n";
if ($response === false) {
    echo "ERRO: Nao foi possivel obter resposta\n";
} else {
    echo substr($response, 0, 2000) . "\n";

    if (strlen($response) > 2000) {
        echo "\n... (resposta truncada, total: " . strlen($response) . " bytes)\n";
    }
}

echo "\n=== ANALISE ===\n";

if ($response === false) {
    echo "- Falha na conexao\n";
    echo "- Possivel: site bloqueando requisicoes ou SSL invalido\n";
} else {
    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "- Resposta NAO e JSON valido\n";
        echo "- Erro JSON: " . json_last_error_msg() . "\n";
        echo "- Possivel: pagina de erro HTML, login required, etc\n";
    } else {
        echo "- Resposta e JSON valido\n";

        if (isset($data['code'])) {
            echo "- ERRO da API: {$data['code']}\n";
            echo "- Mensagem: " . ($data['message'] ?? 'N/A') . "\n";
        } elseif (is_array($data) && !empty($data)) {
            if (isset($data[0]['id'])) {
                echo "- Posts encontrados: " . count($data) . "\n";
                echo "- Primeiro post ID: {$data[0]['id']}\n";
                echo "- Primeiro post titulo: " . ($data[0]['title']['rendered'] ?? 'N/A') . "\n";
                echo "\n*** API FUNCIONANDO CORRETAMENTE ***\n";
            } else {
                echo "- Array retornado mas sem formato de posts\n";
                echo "- Chaves: " . implode(', ', array_keys($data[0] ?? $data)) . "\n";
            }
        } else {
            echo "- Array vazio ou formato inesperado\n";
            echo "- Tipo: " . gettype($data) . "\n";
        }
    }
}

echo "\n=================================================\n";
echo "Teste outras URLs:\n";
echo "- {$wpUrl}/wp-json/ (deve listar namespaces)\n";
echo "- {$wpUrl}/wp-json/wp/v2/ (deve listar endpoints)\n";
echo "=================================================\n";
