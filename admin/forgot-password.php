<?php
session_start();

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: dashboard.php');
    exit;
}

require_once '../php/db-config.php';
require_once '../php/email-config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = '';
$error = '';

if ($_POST) {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Veuillez saisir votre adresse email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } else {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Chercher l'utilisateur
            $stmt = $pdo->prepare("SELECT id, username, full_name, email FROM admin_users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Générer un token sécurisé
                $token = bin2hex(random_bytes(32));
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Supprimer les anciens tokens non utilisés de cet utilisateur
                $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE user_id = ? AND used = FALSE");
                $stmt->execute([$user['id']]);

                // Insérer le nouveau token
                $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$user['id'], $token, $expires_at]);

                // Envoyer l'email avec PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Configuration SMTP
                    $isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

                    if ($isLocal) {
                        // Local : serveur SMTP local (ou désactivé)
                        $mail->isSMTP();
                        $mail->Host = 'localhost';
                        $mail->SMTPAuth = false;
                        $mail->Port = 25;
                    } else {
                        // Production O2switch
                        $mail->isSMTP();
                        $mail->Host = EMAIL_HOST;
                        $mail->SMTPAuth = true;
                        $mail->Username = EMAIL_USERNAME;
                        $mail->Password = EMAIL_PASSWORD;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = EMAIL_PORT;
                    }

                    $mail->CharSet = 'UTF-8';
                    $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
                    $mail->addAddress($email, $user['full_name']);

                    $reset_link = SITE_URL . "/admin/reset-password.php?token=" . $token;

                    $mail->isHTML(true);
                    $mail->Subject = 'Réinitialisation de votre mot de passe';
                    $mail->Body = "
                        <h2>Bonjour {$user['full_name']},</h2>
                        <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                        <p>Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe :</p>
                        <p><a href='{$reset_link}' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Réinitialiser mon mot de passe</a></p>
                        <p>Ou copiez ce lien dans votre navigateur :<br>{$reset_link}</p>
                        <p><strong>Ce lien est valide pendant 1 heure.</strong></p>
                        <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
                        <hr>
                        <p style='color: #666; font-size: 0.9em;'>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                    ";
                    $mail->AltBody = "Bonjour {$user['full_name']},\n\nVous avez demandé la réinitialisation de votre mot de passe.\n\nCopiez ce lien dans votre navigateur :\n{$reset_link}\n\nCe lien est valide pendant 1 heure.\n\nSi vous n'avez pas demandé cette réinitialisation, ignorez cet email.";

                    $mail->send();
                    $success = "Un email de réinitialisation a été envoyé à votre adresse. Vérifiez votre boîte de réception.";
                } catch (Exception $e) {
                    $error = "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
                }
            } else {

                $success = "Si cette adresse email existe dans notre système, vous recevrez un email de réinitialisation.";
            }
        } catch (PDOException $e) {
            $error = 'Erreur de base de données.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Administration</title>
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

        .forgot-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .forgot-header h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .forgot-header p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
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
    </style>
</head>

<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <h1>🔐 Mot de passe oublié</h1>
            <p>Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        </div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Adresse email :</label>
                    <input type="email" id="email" name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required autocomplete="email"
                        placeholder="votre-email@exemple.com">
                </div>

                <button type="submit" class="btn-submit">
                    Envoyer le lien de réinitialisation
                </button>
            </form>
        <?php endif; ?>

        <div class="back-link">
            <a href="login.php">← Retour à la connexion</a>
        </div>
    </div>
</body>

</html>