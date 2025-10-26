<?php
require_once '../php/db-config.php';

$success = '';
$error = '';

if ($_POST && isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'] ?? '';

    if (strlen($new_password) >= 12) {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Hasher le nouveau mot de passe
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'Fabienne'");

            if ($stmt->execute([$new_hash])) {
                $success = "Mot de passe mis à jour pour l'utilisateur 'Fabienne' !";
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
        } catch (PDOException $e) {
            $error = 'Erreur : ' . $e->getMessage();
        }
    } else {
        $error = 'Le mot de passe doit faire au moins 12 caractères (recommandation CNIL).';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser Mot de Passe - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 500px;
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

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn:hover {
            background: #0056b3;
        }

        .links {
            text-align: center;
            margin-top: 1rem;
        }

        .links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 0.5rem;
        }

        h1 {
            color: #333;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔑 Réinitialiser Mot de Passe</h1>
        <p><strong>Utilisateur :</strong> Fabienne</p>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <div class="links">
                <a href="login.php">→ Aller à la connexion</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe :</label>
                    <input type="password" id="new_password" name="new_password"
                        placeholder="Minimum 12 caractères (CNIL)"
                        required minlength="12"
                        style="font-family: monospace; background: #f8f9fa;">
                    <small style="color: #666; font-size: 0.8em;">
                        � Mot de passe masqué pour la sécurité
                    </small>
                </div>

                <button type="submit" name="reset_password" class="btn">
                    Mettre à jour le mot de passe
                </button>
            </form>
        <?php endif; ?>

        <div class="links">
            <a href="login.php">← Retour à la connexion</a>
        </div>
    </div>
</body>

</html>