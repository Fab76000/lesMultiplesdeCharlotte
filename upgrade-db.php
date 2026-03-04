<?php

/**
 * Script de migration pour ajouter la colonne featured_image_height en production
 * À exécuter une seule fois via : https://charlottegoupil.fr/upgrade-db.php
 */

// Mot de passe temporaire pour sécuriser l'accès
$upgrade_password = 'Charlotte2024!Upgrade'; // Changez ce mot de passe !

// Vérification du mot de passe
if (!isset($_GET['password']) || $_GET['password'] !== $upgrade_password) {
    http_response_code(403);
    die('Accès refusé. Mot de passe requis : upgrade-db.php?password=VOTRE_MOT_DE_PASSE');
}

require_once 'php/db-config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>🔄 Migration de la base de données</h2>";

    // Vérifier si la colonne existe déjà
    $stmt = $pdo->query("SHOW COLUMNS FROM articles LIKE 'featured_image_height'");
    $columnExists = $stmt->fetch();

    if ($columnExists) {
        echo "<p style='color: orange;'>⚠️ La colonne 'featured_image_height' existe déjà dans la table 'articles'.</p>";
        echo "<p>✅ Aucune action nécessaire !</p>";
    } else {
        // Ajouter la colonne
        $sql = "ALTER TABLE articles 
                ADD COLUMN featured_image_height INT DEFAULT 300 
                COMMENT 'Hauteur en pixels de l\'image mise en avant'";

        $pdo->exec($sql);

        echo "<p style='color: green;'>✅ Colonne 'featured_image_height' ajoutée avec succès !</p>";
        echo "<p>Valeur par défaut : 300px</p>";
    }

    echo "<hr>";
    echo "<h3>📊 Structure de la table 'articles' :</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Défaut</th></tr>";

    $stmt = $pdo->query("SHOW COLUMNS FROM articles");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<hr>";
    echo "<p style='margin-top: 20px;'><a href='blog.php'>→ Voir le blog</a> | <a href='admin/dashboard.php'>→ Tableau de bord admin</a></p>";
    echo "<p style='color: red; font-weight: bold;'>⚠️ IMPORTANT : Supprimez ce fichier après utilisation pour des raisons de sécurité !</p>";
    echo "<p><em>Commande : rm upgrade-db.php (via SSH/FTP)</em></p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
