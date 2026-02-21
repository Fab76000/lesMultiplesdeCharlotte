<?php
session_start();

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: dashboard.php');
    exit;
}

require_once '../php/db-config.php';

$error = '';
if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Chercher l'utilisateur dans admin_users uniquement
            $stmt = $pdo->prepare("
                SELECT id, username, password_hash, email, role, full_name
                FROM admin_users 
                WHERE username = ? OR email = ?
                LIMIT 1
            ");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // DEBUG temporaire
            if (!$user) {
                $error = "Utilisateur '$username' non trouvé dans la base.";
            } else if (!password_verify($password, $user['password_hash'])) {
                $error = "Mot de passe incorrect pour l'utilisateur '" . $user['username'] . "'.";
            } else if ($user && password_verify($password, $user['password_hash'])) {
                // Connexion réussie
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_full_name'] = $user['full_name'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_role'] = $user['role'];

                // Cookie sécurisé pour reconnaître les administrateurs (persiste après déconnexion)
                $cookie_value = hash('sha256', $user['username'] . $user['id'] . 'admin_recognition_salt');
                setcookie('admin_recognized', $cookie_value, [
                    'expires' => time() + (86400 * 365), // 1 an
                    'path' => '/',
                    'secure' => !isset($_SERVER['HTTP_HOST']) || $_SERVER['HTTP_HOST'] !== 'localhost:8090', // HTTPS en production
                    'httponly' => true, // Pas accessible via JavaScript
                    'samesite' => 'Lax' // Protection CSRF
                ]);

                // Mettre à jour la dernière connexion
                $stmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);

                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Erreur de connexion à la base de données.';
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | Charlotte Goupil</title>
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

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 0.9rem;
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

        .btn-login {
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

        .btn-login:hover {
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
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🎨 Administration</h1>
            <p>Espace de gestion du site Charlotte Goupil</p>
        </div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur ou Email :</label>
                <input type="text" id="username" name="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password"
                    required autocomplete="current-password"
                    placeholder="Saisissez votre mot de passe"
                    style="font-family: monospace; font-size: 12px; background: #f8f9fa;">
                <small style="display: block; margin-top: 0.5rem; color: #666; font-size: 0.85rem; line-height: 1.4;">
                    Minimum 12 caractères avec au moins 1 majuscule et 1 caractère spécial (@#$%^&*!?-_=+)
                </small>
            </div>

            <button type="submit" class="btn-login">
                Se connecter
            </button>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="forgot-password.php" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">Mot de passe oublié ?</a>
            </div>
        </form>

        <div class="back-link">
            <a href="../index.php">← Retour au site</a>
        </div>
    </div>
</body>

</html>