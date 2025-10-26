<?php

/**
 * Configuration alternative de base de données avec MySQLi
 * Utiliser cette version si PDO MySQL ne fonctionne pas
 */

// Configuration selon l'environnement
$isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if ($isLocal) {
    // Configuration locale (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'charlotte_blog');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
} else {
    // Configuration production
    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'charlotte_blog');
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
    define('DB_PASS', $_ENV['DB_PASS'] ?? '');
    define('DB_CHARSET', 'utf8mb4');
}

/**
 * Classe wrapper pour gérer PDO ou MySQLi
 */
class DatabaseConnection {
    private $connection;
    private $use_pdo;

    public function __construct() {
        $this->use_pdo = class_exists('PDO') && in_array('mysql', PDO::getAvailableDrivers());

        if ($this->use_pdo) {
            $this->connectPDO();
        } else {
            $this->connectMySQLi();
        }
    }

    private function connectPDO() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion PDO: " . $e->getMessage());
        }
    }

    private function connectMySQLi() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_error) {
            throw new Exception("Erreur de connexion MySQLi: " . $this->connection->connect_error);
        }

        $this->connection->set_charset(DB_CHARSET);
    }

    public function query($sql, $params = []) {
        if ($this->use_pdo) {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } else {
            // Pour MySQLi, on prépare la requête différemment
            $stmt = $this->connection->prepare($sql);
            if (!empty($params)) {
                $types = str_repeat('s', count($params)); // Tout en string pour simplifier
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            return $stmt;
        }
    }

    public function fetchAll($stmt) {
        if ($this->use_pdo) {
            return $stmt->fetchAll();
        } else {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function fetchOne($stmt) {
        if ($this->use_pdo) {
            return $stmt->fetch();
        } else {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
    }

    public function lastInsertId() {
        if ($this->use_pdo) {
            return $this->connection->lastInsertId();
        } else {
            return $this->connection->insert_id;
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function isPDO() {
        return $this->use_pdo;
    }
}

// Fonction globale pour obtenir une connexion
function getDB() {
    static $db = null;
    if ($db === null) {
        $db = new DatabaseConnection();
    }
    return $db;
}
