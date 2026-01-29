<?php
// Configuration O2SWITCH - À renommer en "db-config.php" sur le serveur

// ========== PRODUCTION O2SWITCH ==========
define('DB_HOST', 'localhost');
define('DB_NAME', 'REMPLACER_PAR_votreidentifiant_multiples_charlotte');
define('DB_USER', 'REMPLACER_PAR_votreidentifiant_charlotte_user');
define('DB_PASS', 'REMPLACER_PAR_mot_de_passe_fort');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("Erreur de connexion à la BDD : " . $e->getMessage());
    die("Erreur de connexion à la base de données. Veuillez contacter l'administrateur.");
}
