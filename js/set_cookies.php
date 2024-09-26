<?php
// Démarrer la session
session_start();

// Vérifier si le consentement aux cookies a été donné
if (isset($_POST['cookiesAccepted']) && $_POST['cookiesAccepted'] === 'true') {
    // Définir les cookies de manière sécurisée avec HttpOnly et Secure
    setcookie("user_firstname", $_POST['firstname'], [
        'expires' => time() + (7 * 24 * 60 * 60),  // Expiration dans 7 jours
        'path' => '/',                              // Accessible sur tout le site
        'secure' => true,                           // Seulement via HTTPS
        'httponly' => true,                         // Non accessible via JavaScript
        'samesite' => 'Strict'                      // Protection contre les requêtes intersites
    ]);

    setcookie("user_name", $_POST['name'], [
        'expires' => time() + (7 * 24 * 60 * 60),
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    setcookie("user_email", $_POST['email'], [
        'expires' => time() + (7 * 24 * 60 * 60),
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    setcookie("user_phone", $_POST['phone'], [
        'expires' => time() + (7 * 24 * 60 * 60),
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    echo json_encode(['success' => true, 'message' => 'Cookies set successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'User declined cookies']);
}
