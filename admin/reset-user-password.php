<?php
session_start();

/**
 * Script pour réinitialiser le mot de passe d'un utilisateur
 * SÉCURISÉ : accessible uniquement aux administrateurs connectés
 */

// Vérification sécurité : seuls les admins connectés peuvent accéder
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=access_denied');
    exit;
}

require_once '../php/db-config.php';
require_once '../php/admin-functions.php';

$success = '';
$error = '';
$user_to_reset = null;

// Récupérer l'ID utilisateur depuis l'URL
$user_id = $_GET['user_id'] ?? null;

if (!$user_id || !is_numeric($user_id)) {
    header('Location: create-admin.php?error=invalid_user');
    exit;
}

// Récupérer les informations de l'utilisateur
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    $stmt = $pdo->prepare("SELECT id, username, email, role FROM admin_users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_to_reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_to_reset) {
        header('Location: create-admin.php?error=user_not_found');
        exit;
    }
} catch (PDOException $e) {
    $error = 'Erreur de base de données : ' . $e->getMessage();
}

// Traitement du formulaire de réinitialisation
if ($_POST && isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'] ?? '';

    // Validation mot de passe (CNIL : 12 caractères, 1 majuscule, 1 caractère spécial)
    $passwordValidation = validatePassword($new_password);

    if (!$passwordValidation['valid']) {
        $error = $passwordValidation['error'];
    } else {
        try {
            // Hasher le nouveau mot de passe
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
            $result = $stmt->execute([$new_hash, $user_id]);

            if ($result && $stmt->rowCount() > 0) {
                $success = "Mot de passe mis à jour avec succès pour l'utilisateur '{$user_to_reset['username']}' !";
            } else {
                $error = "Erreur : Aucune ligne mise à jour - l'utilisateur n'existe pas.";
            }
        } catch (PDOException $e) {
            $error = 'Erreur : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser Mot de Passe - Administration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .reset-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .reset-header h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .reset-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .user-info {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #2196f3;
        }

        .user-info h3 {
            color: #1565c0;
            margin-bottom: 0.5rem;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            border-left: 4px solid #28a745;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            border-left: 4px solid #dc3545;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .btn-reset {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .btn-reset:hover {
            opacity: 0.9;
        }

        .links {
            text-align: center;
            margin-top: 1rem;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            margin: 0 0.5rem;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .security-notice {
            background: #fff3cd;
            color: #856404;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <div class="reset-header">
            <h1>🔐 Réinitialiser Mot de Passe</h1>
            <p style="color: #666; margin-bottom: 1rem;">
                <strong>Administrateur :</strong> <?= htmlspecialchars($_SESSION['admin_full_name'] ?? $_SESSION['admin_username']) ?>
                | <a href="create-admin.php" style="color: #007bff;">← Retour à la gestion utilisateurs</a>
            </p>
        </div>

        <?php if ($user_to_reset): ?>
            <div class="user-info">
                <h3>👤 Utilisateur concerné</h3>
                <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user_to_reset['username']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user_to_reset['email']) ?></p>
                <p><strong>Rôle :</strong> <span style="background: #007bff; color: white; padding: 0.2rem 0.5rem; border-radius: 3px; font-size: 0.8rem;"><?= htmlspecialchars($user_to_reset['role']) ?></span></p>
            </div>

            <div class="security-notice">
                ⚠️ <strong>Sécurité :</strong> Le nouveau mot de passe doit respecter les critères CNIL (12 caractères min., 1 majuscule, 1 caractère spécial).
            </div>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success"><?= htmlspecialchars($success) ?></div>
                <div class="links">
                    <a href="create-admin.php">→ Retour à la gestion utilisateurs</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe :</label>
                        <input type="password" id="new_password" name="new_password"
                            placeholder="12 car. min, 1 majuscule, 1 car. spécial (!@#...)"
                            required minlength="12"
                            style="font-family: monospace; background: #f8f9fa;">
                        <small style="color: #666; font-size: 0.8em;">
                            🔐 Mot de passe masqué pour la sécurité
                        </small>
                    </div>

                    <button type="submit" name="reset_password" class="btn-reset">
                        🔐 Mettre à jour le mot de passe
                    </button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <div class="links">
            <a href="create-admin.php">← Retour à la gestion utilisateurs</a>
            <a href="dashboard.php">🏠 Dashboard</a>
        </div>
    </div>
</body>

</html>