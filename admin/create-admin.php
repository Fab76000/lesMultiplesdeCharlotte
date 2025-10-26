<?php

/**
 * Script pour créer un utilisateur admin
 */

require_once '../php/db-config.php';

$success = '';
$error = '';

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        // Validation mot de passe (CNIL : 12 caractères minimum)
        if (strlen($password) < 12) {
            $error = 'Le mot de passe doit faire au moins 12 caractères (recommandation CNIL).';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Adresse email invalide.';
        } else {
            try {
                $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Vérifier si l'utilisateur existe déjà
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);

                if ($stmt->fetchColumn() > 0) {
                    $error = 'Un utilisateur avec ce nom ou cet email existe déjà.';
                } else {
                    // Créer l'utilisateur
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("
                    INSERT INTO admin_users (username, email, password_hash, role) 
                    VALUES (?, ?, ?, 'admin')
                ");

                    if ($stmt->execute([$username, $email, $password_hash])) {
                        $success = 'Utilisateur admin créé avec succès ! Vous pouvez maintenant vous connecter.';
                    } else {
                        $error = 'Erreur lors de la création de l\'utilisateur.';
                    }
                }
            } catch (PDOException $e) {
                $error = 'Erreur de base de données : ' . $e->getMessage();
            }
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}

// Vérifier combien d'admin existent déjà
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
    $admin_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    $admin_count = 0;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Admin - Charlotte Goupil</title>
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

        .create-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .create-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .create-header h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .create-header p {
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

        .btn-create {
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

        .btn-create:hover {
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

        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
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
    </style>
</head>

<body>
    <div class="create-container">
        <div class="create-header">
            <h1>👤 Créer Administrateur</h1>
            <p>Première configuration du système d'administration</p>
        </div>

        <?php if ($admin_count > 0): ?>
            <div class="info">
                Il y a déjà <?= $admin_count ?> administrateur(s) dans le système.
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <div class="links">
                <a href="login.php">→ Aller à la connexion</a>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        required autocomplete="username"
                        placeholder="Ex: admin, charlotte, etc.">
                </div>

                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required autocomplete="email"
                        placeholder="votre-email@exemple.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password"
                        required autocomplete="new-password"
                        placeholder="Minimum 12 caractères"
                        minlength="12">
                </div>
                <button type="submit" class="btn-create">
                    Créer l'administrateur
                </button>
            </form>
        <?php endif; ?>

        <div class="links">
            <?php if (!$success): ?>
                <a href="../index.php">← Retour au site</a>
            <?php endif; ?>
        </div>
    </div>


</body>

</html>