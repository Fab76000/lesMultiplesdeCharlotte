<?php
session_start();

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: dashboard.php');
    exit;
}

require_once '../php/db-config.php';
require_once '../php/admin-functions.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$valid_token = false;
$user = null;

if (empty($token)) {
    $error = 'Token de réinitialisation manquant.';
} else {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier le token
        $stmt = $pdo->prepare("
            SELECT prt.*, au.username, au.full_name, au.email 
            FROM password_reset_tokens prt
            JOIN admin_users au ON prt.user_id = au.id
            WHERE prt.token = ? AND prt.used = FALSE AND prt.expires_at > NOW()
            LIMIT 1
        ");
        $stmt->execute([$token]);
        $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($token_data) {
            $valid_token = true;
            $user = $token_data;
        } else {
            $error = 'Ce lien de réinitialisation est invalide ou a expiré.';
        }
    } catch (PDOException $e) {
        $error = 'Erreur de base de données.';
    }
}

// Traitement du formulaire
if ($_POST && $valid_token) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {
        // Validation mot de passe
        $passwordValidation = validatePassword($new_password);

        if (!$passwordValidation['valid']) {
            $error = $passwordValidation['error'];
        } else {
            try {
                // Hasher le nouveau mot de passe
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Mettre à jour le mot de passe
                $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$password_hash, $user['user_id']]);

                // Marquer le token comme utilisé
                $stmt = $pdo->prepare("UPDATE password_reset_tokens SET used = TRUE WHERE token = ?");
                $stmt->execute([$token]);

                $success = 'Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.';
                $valid_token = false; // Empêcher une nouvelle soumission
            } catch (PDOException $e) {
                $error = 'Erreur lors de la mise à jour du mot de passe.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Administration</title>
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
            max-width: 450px;
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
            text-align: center;
            border-left: 4px solid #2196f3;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: bold;
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

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .error {
            background: #ffe6e6;
            color: #d8000c;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .back-link {
            text-align: center;
            margin-top: 1rem;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .password-requirements {
            background: #fff3cd;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <div class="reset-header">
            <h1>🔐 Nouveau mot de passe</h1>
            <p>Créez un nouveau mot de passe sécurisé</p>
        </div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <div class="back-link">
                <a href="login.php">→ Se connecter maintenant</a>
            </div>
        <?php elseif ($valid_token): ?>
            <div class="user-info">
                <strong>👤 <?= htmlspecialchars($user['full_name']) ?></strong><br>
                <small><?= htmlspecialchars($user['email']) ?></small>
            </div>

            <div class="password-requirements">
                <strong>⚠️ Exigences du mot de passe :</strong><br>
                • Minimum 12 caractères<br>
                • Au moins 1 majuscule<br>
                • Au moins 1 caractère spécial (@#$%^&*!?-_=+)
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe :</label>
                    <input type="password" id="new_password" name="new_password"
                        required minlength="12"
                        placeholder="Saisissez votre nouveau mot de passe"
                        style="font-family: monospace; background: #f8f9fa;">
                    <small style="display: block; margin-top: 0.5rem; color: #666; font-size: 0.85rem; line-height: 1.4;">
                        Minimum 12 caractères avec au moins 1 majuscule et 1 caractère spécial (@#$%^&*!?-_=+)
                    </small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        required minlength="12"
                        placeholder="Confirmez votre nouveau mot de passe"
                        style="font-family: monospace; background: #f8f9fa;">
                </div>

                <button type="submit" class="btn-submit">
                    Réinitialiser le mot de passe
                </button>
            </form>
        <?php endif; ?>

        <div class="back-link">
            <a href="login.php">← Retour à la connexion</a>
        </div>
    </div>
</body>

</html>