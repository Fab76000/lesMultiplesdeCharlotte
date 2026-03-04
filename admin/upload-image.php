<?php
session_start();

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

header('Content-Type: application/json');

// Vérifier qu'un fichier a été uploadé
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier uploadé ou erreur lors de l\'upload']);
    exit;
}

$file = $_FILES['image'];
$uploadDir = '../images/blog/';

// Créer le dossier s'il n'existe pas
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Vérifier le type MIME
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Type de fichier non autorisé. Formats acceptés : JPG, PNG, GIF, WEBP, SVG']);
    exit;
}

// Vérifier la taille (max 5MB)
$maxSize = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'Le fichier est trop volumineux. Taille maximale : 5MB']);
    exit;
}

// Générer un nom de fichier sécurisé et unique
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = uniqid('blog_', true) . '_' . time() . '.' . $extension;
$filepath = $uploadDir . $filename;

// Déplacer le fichier uploadé
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Retourner l'URL relative de l'image
    $imageUrl = 'images/blog/' . $filename;

    echo json_encode([
        'success' => true,
        'url' => $imageUrl,
        'filename' => $filename,
        'message' => 'Image uploadée avec succès'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du fichier']);
}
