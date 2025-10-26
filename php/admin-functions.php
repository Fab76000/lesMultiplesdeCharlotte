<?php

/**
 * Fonctions utilitaires pour la gestion des administrateurs
 */

/**
 * Vérifier si un cookie admin est valide
 * @param string $cookie_value Valeur du cookie à vérifier
 * @return bool True si le cookie correspond à un admin valide
 */
function isValidAdminCookie($cookie_value) {
    if (!$cookie_value) return false;

    try {
        require_once __DIR__ . '/db-config.php';

        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si le cookie correspond à un admin existant
        $stmt = $pdo->query("SELECT username, id FROM admin_users WHERE role = 'admin'");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($admins as $admin) {
            $expected_cookie = hash('sha256', $admin['username'] . $admin['id'] . 'admin_recognition_salt');
            if ($cookie_value === $expected_cookie) {
                return true;
            }
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Vérifier si l'utilisateur actuel est un administrateur reconnu
 * @return bool True si admin connecté OU cookie admin valide
 */
function isRecognizedAdmin() {
    // Si connecté en tant qu'admin
    if (
        isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] &&
        isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin'
    ) {
        return true;
    }

    // Si cookie admin valide (admin déconnecté mais reconnu)
    if (isset($_COOKIE['admin_recognized'])) {
        return isValidAdminCookie($_COOKIE['admin_recognized']);
    }

    return false;
}

/**
 * Valider la force d'un mot de passe selon les critères CNIL
 * @param string $password Le mot de passe à valider
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validatePassword($password) {
    // Minimum 12 caractères
    if (strlen($password) < 12) {
        return [
            'valid' => false,
            'error' => 'Le mot de passe doit faire au moins 12 caractères (exigence CNIL).'
        ];
    }

    // Au moins 1 majuscule
    if (!preg_match('/[A-Z]/', $password)) {
        return [
            'valid' => false,
            'error' => 'Le mot de passe doit contenir au moins 1 majuscule.'
        ];
    }

    // Au moins 1 caractère spécial
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
        return [
            'valid' => false,
            'error' => 'Le mot de passe doit contenir au moins 1 caractère spécial (!@#$%^&*()_+-=[]{}|;:,.<>?).'
        ];
    }

    return ['valid' => true, 'error' => null];
}
