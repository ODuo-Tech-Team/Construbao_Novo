<?php
/**
 * Classe de Conexão com Banco de Dados
 * Singleton Pattern com PDO
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (DEV_MODE) {
                die("Erro de conexão: " . $e->getMessage());
            } else {
                die("Erro ao conectar com o banco de dados.");
            }
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

    // Prevenir clonagem
    private function __clone() {}

    // Prevenir deserialização
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Função helper para obter conexão PDO
 */
function db(): PDO {
    return Database::getInstance()->getConnection();
}

/**
 * Funções CRUD genéricas
 */

function fetchAll(string $table, string $where = '1=1', array $params = [], string $orderBy = 'id DESC'): array {
    $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$orderBy}";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetchOne(string $table, string $where, array $params = []): ?array {
    $sql = "SELECT * FROM {$table} WHERE {$where} LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result ?: null;
}

function fetchById(string $table, int $id): ?array {
    return fetchOne($table, 'id = ?', [$id]);
}

function insert(string $table, array $data): int {
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
    $stmt = db()->prepare($sql);
    $stmt->execute(array_values($data));

    return (int) db()->lastInsertId();
}

function update(string $table, array $data, string $where, array $whereParams = []): int {
    $set = implode(' = ?, ', array_keys($data)) . ' = ?';

    $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
    $stmt = db()->prepare($sql);
    $stmt->execute(array_merge(array_values($data), $whereParams));

    return $stmt->rowCount();
}

function delete(string $table, string $where, array $params = []): int {
    $sql = "DELETE FROM {$table} WHERE {$where}";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt->rowCount();
}

function countRows(string $table, string $where = '1=1', array $params = []): int {
    $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$where}";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetch()['total'];
}

/**
 * Executa uma query SQL customizada
 */
function query(string $sql, array $params = []): array {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
