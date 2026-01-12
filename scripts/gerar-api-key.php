<?php
/**
 * Script para gerar API Key para um usuario
 *
 * USO via CLI:
 *   php gerar-api-key.php [email]
 *
 * Exemplo:
 *   php gerar-api-key.php admin@construbao.com.br
 *
 * Ou acesse via navegador (depois delete o arquivo!)
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<pre style='font-family: monospace; background: #1a1a2e; color: #eee; padding: 20px;'>";
}

echo "=================================================\n";
echo "GERADOR DE API KEY - CONSTRUBAO\n";
echo "=================================================\n\n";

// Verificar se coluna api_key existe
try {
    $result = query("SHOW COLUMNS FROM usuarios LIKE 'api_key'");
    if (empty($result)) {
        echo "[INFO] Adicionando coluna api_key na tabela usuarios...\n";
        db()->exec("ALTER TABLE usuarios ADD COLUMN api_key VARCHAR(64) NULL UNIQUE");
        db()->exec("ALTER TABLE usuarios ADD INDEX idx_api_key (api_key)");
        echo "[OK] Coluna api_key adicionada com sucesso!\n\n";
    }
} catch (Exception $e) {
    echo "[AVISO] " . $e->getMessage() . "\n\n";
}

// Obter email do usuario
$email = null;

if ($isCli && isset($argv[1])) {
    $email = $argv[1];
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
}

if (!$email) {
    // Listar usuarios disponiveis
    echo "Usuarios disponiveis:\n";
    echo "---------------------\n";

    $users = fetchAll('usuarios', '1=1', [], 'id ASC');
    foreach ($users as $user) {
        $hasKey = !empty($user['api_key']) ? ' [TEM API KEY]' : '';
        echo "  - {$user['email']} ({$user['nome']}){$hasKey}\n";
    }

    echo "\n";

    if ($isCli) {
        echo "USO: php gerar-api-key.php [email]\n";
        echo "Exemplo: php gerar-api-key.php admin@construbao.com.br\n";
    } else {
        echo "USO: gerar-api-key.php?email=[email]\n";
        echo "Exemplo: gerar-api-key.php?email=admin@construbao.com.br\n";
    }

    if (!$isCli) {
        echo "</pre>";
    }
    exit;
}

// Buscar usuario
$user = fetchOne('usuarios', 'email = ?', [$email]);

if (!$user) {
    echo "[ERRO] Usuario nao encontrado: {$email}\n";
    exit(1);
}

echo "Usuario encontrado: {$user['nome']} ({$user['email']})\n\n";

// Verificar se ja tem API Key
if (!empty($user['api_key'])) {
    echo "[AVISO] Este usuario ja possui uma API Key:\n";
    echo "  {$user['api_key']}\n\n";
    echo "Deseja gerar uma nova? Isso invalidara a anterior.\n";

    if ($isCli) {
        echo "Digite 'sim' para continuar: ";
        $handle = fopen("php://stdin", "r");
        $line = trim(fgets($handle));
        fclose($handle);

        if (strtolower($line) !== 'sim') {
            echo "Operacao cancelada.\n";
            exit(0);
        }
    } elseif (!isset($_GET['force'])) {
        echo "\nAdicione &force=1 na URL para gerar nova chave.\n";
        echo "</pre>";
        exit;
    }
}

// Gerar nova API Key
$apiKey = bin2hex(random_bytes(32));

// Atualizar no banco
update('usuarios', ['api_key' => $apiKey], 'id = ?', [$user['id']]);

echo "=================================================\n";
echo "API KEY GERADA COM SUCESSO!\n";
echo "=================================================\n\n";
echo "Usuario: {$user['nome']} ({$user['email']})\n";
echo "API Key: {$apiKey}\n\n";
echo "=================================================\n";
echo "COMO USAR:\n";
echo "=================================================\n\n";
echo "Adicione o header em suas requisicoes:\n\n";
echo "  X-API-Key: {$apiKey}\n\n";
echo "Ou via Authorization:\n\n";
echo "  Authorization: Bearer {$apiKey}\n\n";
echo "Exemplo com curl:\n\n";
echo "  curl -H \"X-API-Key: {$apiKey}\" \\\n";
echo "       https://seu-site.com/api/blog/posts\n\n";

if (!$isCli) {
    echo "</pre>";
    echo "<p style='color: red; font-weight: bold;'>IMPORTANTE: Delete este arquivo apos usar!</p>";
}

echo "IMPORTANTE: Guarde esta chave em local seguro!\n";
