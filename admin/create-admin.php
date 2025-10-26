<?php
session_start();

/**
 * Script pour créer un utilisateur admin
 * SÉCURISÉ : accessible uniquement aux administrateurs connectés
 */

// Vérification sécurité : seuls les admins connectés peuvent accéder
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=access_denied');
    exit;
}

require_once '../php/db-config.php';

$success = '';
$error = '';

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        // Validation mot de passe (CNIL : 12 caractères, 1 majuscule, 1 caractère spécial)
        require_once '../php/admin-functions.php';
        $passwordValidation = validatePassword($password);

        if (!$passwordValidation['valid']) {
            $error = $passwordValidation['error'];
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

// Vérifier combien d'admin existent déjà et récupérer la liste
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
    $admin_count = $stmt->fetchColumn();

    // Récupérer tous les utilisateurs pour affichage
    $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM admin_users ORDER BY created_at DESC");
    $existing_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $admin_count = 0;
    $existing_users = [];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs - Administration</title>
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

        /* Styles responsive pour la gestion des utilisateurs */
        .existing-users {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #eee;
        }

        .existing-users h2 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .user-card {
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 0.8rem;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: box-shadow 0.2s;
        }

        .user-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            margin-bottom: 0.8rem;
        }

        .user-info strong {
            color: #333;
            font-size: 1.1rem;
        }

        .user-email {
            color: #666;
            font-size: 0.9rem;
            display: block;
            margin-top: 0.2rem;
        }

        .role-badge {
            background: #007bff;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-size: 0.8rem;
            display: inline-block;
            margin-top: 0.4rem;
        }

        .user-actions {
            text-align: center;
        }

        .btn-reset-password {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.7rem 1.2rem;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            transition: all 0.3s ease;
            min-width: 200px;
            text-align: center;
        }

        .btn-reset-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            text-decoration: none;
            color: white;
        }

        /* Media queries pour le responsive */
        @media (max-width: 768px) {
            .create-container {
                max-width: 95%;
                padding: 1.5rem;
                margin: 1rem;
            }

            .user-card {
                padding: 0.8rem;
            }

            .user-info strong {
                font-size: 1rem;
            }

            .user-email {
                font-size: 0.85rem;
            }

            .btn-reset-password {
                padding: 0.8rem 1rem;
                font-size: 0.85rem;
                min-width: 180px;
            }
        }

        @media (max-width: 480px) {
            .create-container {
                padding: 1rem;
            }

            .existing-users h2 {
                font-size: 1.2rem;
            }

            .user-card {
                padding: 0.7rem;
            }

            .btn-reset-password {
                padding: 0.7rem 0.8rem;
                font-size: 0.8rem;
                min-width: 160px;
                word-wrap: break-word;
            }

            .user-info strong {
                font-size: 0.95rem;
            }

            .user-email {
                font-size: 0.8rem;
            }

            .role-badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }
        }
    </style>
</head>

<body>
    <div class="create-container">
        <div class="create-header">
            <h1>👥 Gestion des Utilisateurs</h1>
            <p style="color: #666; margin-bottom: 2rem;">
                <strong>Connecté en tant que :</strong> <?= htmlspecialchars($_SESSION['admin_username']) ?>
                | <a href="dashboard.php" style="color: #007bff;">← Retour au Dashboard</a>
            </p>
            <p>Ajouter un nouvel administrateur au système</p>
        </div>

        <?php if ($admin_count > 0): ?>
            <div class="info">
                📊 <strong>Statistiques :</strong> <?= $admin_count ?> administrateur(s) dans le système.
                <br>💡 <strong>Conseil :</strong> Limitez le nombre d'administrateurs pour la sécurité.
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
                        placeholder="12 caractères min., 1 maj,1 spécial"
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

        <!-- Section : Utilisateurs existants -->
        <?php if (count($existing_users) > 0): ?>
            <div class="existing-users">
                <h2>👥 Utilisateurs existants</h2>

                <div class="users-list">
                    <?php foreach ($existing_users as $user): ?>
                        <div class="user-card">
                            <div class="user-info">
                                <strong><?= htmlspecialchars($user['username']) ?></strong>
                                <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                                <span class="role-badge">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </div>
                            <div class="user-actions">
                                <a href="reset-user-password.php?user_id=<?= $user['id'] ?>"
                                    class="btn-reset-password">
                                    🔐 Réinitialiser mot de passe
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>


</body>

</html>