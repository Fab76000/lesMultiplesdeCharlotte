<?php
require_once '../php/db-config.php';

$success = '';
$error = '';

if ($_POST && isset($_POST['add_role'])) {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ajouter la colonne role
        $pdo->exec("
            ALTER TABLE admin_users 
            ADD COLUMN role ENUM('admin', 'editor', 'viewer') DEFAULT 'admin' 
            AFTER password_hash
        ");

        // Mettre à jour tous les utilisateurs existants pour qu'ils soient admin
        $pdo->exec("UPDATE admin_users SET role = 'admin' WHERE role IS NULL");

        $success = "Colonne 'role' ajoutée avec succès ! Tous les utilisateurs existants sont maintenant 'admin'.";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            $success = "La colonne 'role' existe déjà !";
        } else {
            $error = 'Erreur : ' . $e->getMessage();
        }
    }
}

// Vérifier si la colonne existe
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $stmt = $pdo->query("DESCRIBE admin_users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $hasRole = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'role') {
            $hasRole = true;
            break;
        }
    }
} catch (PDOException $e) {
    $error = 'Erreur de connexion : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour Table Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }

        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }

        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #1e7e34;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔧 Mise à jour Table admin_users</h1>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <h2>📋 Structure actuelle de la table</h2>
        <table>
            <tr>
                <th>Colonne</th>
                <th>Type</th>
                <th>Null</th>
                <th>Default</th>
            </tr>
            <?php foreach ($columns as $column): ?>
                <tr>
                    <td><?= htmlspecialchars($column['Field']) ?></td>
                    <td><?= htmlspecialchars($column['Type']) ?></td>
                    <td><?= htmlspecialchars($column['Null']) ?></td>
                    <td><?= htmlspecialchars($column['Default']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (!$hasRole): ?>
            <div class="warning">
                <h3>⚠️ Colonne 'role' manquante</h3>
                <p>La colonne 'role' est importante pour gérer les niveaux d'accès administrateur.</p>
                <p><strong>Rôles proposés :</strong></p>
                <ul>
                    <li><strong>admin</strong> : Accès complet (créer, modifier, supprimer articles + gérer utilisateurs)</li>
                    <li><strong>editor</strong> : Gérer articles uniquement</li>
                    <li><strong>viewer</strong> : Lecture seule</li>
                </ul>

                <form method="POST">
                    <input type="hidden" name="add_role" value="1">
                    <button type="submit" class="btn btn-success">
                        ➕ Ajouter la colonne 'role'
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="success">
                <h3>✅ Colonne 'role' présente</h3>
                <p>La table est correctement configurée avec la gestion des rôles.</p>
            </div>
        <?php endif; ?>

        <div class="info">
            <h3>📝 Prochaines étapes</h3>
            <ul>
                <li><a href="create-admin.php">Créer un utilisateur admin</a></li>
                <li><a href="login.php">Se connecter à l'administration</a></li>
                <li><a href="../index.php">Retour au site</a></li>
            </ul>
        </div>
    </div>
</body>

</html>