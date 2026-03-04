<?php
session_start();

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

require_once '../php/db-config.php';
require_once '../php/blog-functions.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Migration automatique : Vérifier et ajouter la colonne featured_image_height si nécessaire
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM articles LIKE 'featured_image_height'");
        $columnExists = $stmt->fetch();

        if (!$columnExists) {
            $pdo->exec("ALTER TABLE articles ADD COLUMN featured_image_height INT DEFAULT 300 COMMENT 'Hauteur en pixels de l\'image mise en avant'");
        }
    } catch (PDOException $e) {
        // Ignorer l'erreur si la colonne existe déjà ou autre problème mineur
    }

    $action = $_GET['action'] ?? 'create';
    $article_id = $_GET['id'] ?? null;
    $article = null;
    $message = '';
    $message_type = '';

    // Récupérer toutes les catégories pour le formulaire
    $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer l'article pour édition
    if ($action === 'edit' && $article_id) {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            header('Location: manage-articles.php');
            exit;
        }
    }

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $featured_image = trim($_POST['featured_image'] ?? '');
        $featured_image_height = !empty($_POST['featured_image_height']) ? (int)$_POST['featured_image_height'] : 300;
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

        $errors = [];

        // Gestion de l'upload de l'image mise en avant
        if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../images/blog/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['featured_image_file']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = 'featured_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['featured_image_file']['tmp_name'], $filePath)) {
                    $featured_image = 'images/blog/' . $fileName;
                }
            }
        }

        // Validation
        if (empty($title)) {
            $errors[] = "Le titre est obligatoire.";
        }

        if (empty($content)) {
            $errors[] = "Le contenu est obligatoire.";
        }

        if (!in_array($status, ['draft', 'published'])) {
            $status = 'draft';
        }

        // Générer automatiquement l'extrait s'il est vide
        if (empty($excerpt) && !empty($content)) {
            $excerpt = generateExcerpt($content, 200);
        }

        // Générer le slug unique à partir du titre
        $slug = generateUniqueSlug($title, $article_id);

        if (empty($errors)) {
            try {
                if ($action === 'edit' && $article_id) {
                    // Mise à jour
                    $stmt = $pdo->prepare("
                        UPDATE articles 
                        SET title = ?, slug = ?, content = ?, excerpt = ?, status = ?, featured_image = ?, featured_image_height = ?, category_id = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$title, $slug, $content, $excerpt, $status, $featured_image, $featured_image_height, $category_id, $article_id]);

                    $message = "Article mis à jour avec succès.";
                    $message_type = "success";
                } else {
                    // Création
                    $stmt = $pdo->prepare("
                        INSERT INTO articles (title, slug, content, excerpt, status, featured_image, featured_image_height, category_id, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
                    ");
                    $stmt->execute([$title, $slug, $content, $excerpt, $status, $featured_image, $featured_image_height, $category_id]);

                    $article_id = $pdo->lastInsertId();
                    $message = "Article créé avec succès.";
                    $message_type = "success";

                    // Rediriger vers l'édition du nouvel article
                    header("Location: article-form.php?action=edit&id=$article_id&success=1");
                    exit;
                }

                // Recharger l'article mis à jour
                if ($article_id) {
                    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
                    $stmt->execute([$article_id]);
                    $article = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            } catch (PDOException $e) {
                $errors[] = "Erreur lors de la sauvegarde: " . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $message = implode('<br>', $errors);
            $message_type = "error";
        }
    }

    // Message de succès depuis la redirection
    if (isset($_GET['success'])) {
        $message = "Article créé avec succès.";
        $message_type = "success";
    }
} catch (PDOException $e) {
    $error = 'Erreur de connexion à la base de données: ' . $e->getMessage();
}

// Valeurs par défaut pour le formulaire
$form_data = [
    'title' => $article['title'] ?? ($_POST['title'] ?? ''),
    'content' => $article['content'] ?? ($_POST['content'] ?? ''),
    'excerpt' => $article['excerpt'] ?? ($_POST['excerpt'] ?? ''),
    'status' => $article['status'] ?? ($_POST['status'] ?? 'draft'),
    'featured_image' => $article['featured_image'] ?? ($_POST['featured_image'] ?? ''),
    'featured_image_height' => $article['featured_image_height'] ?? ($_POST['featured_image_height'] ?? 300),
    'category_id' => $article['category_id'] ?? ($_POST['category_id'] ?? ''),
];

$page_title = $action === 'edit' ? 'Modifier l\'article' : 'Nouvel article';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Administration</title>
    <link rel="stylesheet" href="css/admin-style.min.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h2 {
            color: #333;
            font-size: 1.5rem;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-row {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            flex: 1;
        }

        .form-group.full-width {
            flex: 0 0 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .editor-toolbar {
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            padding: 0.5rem;
            background: #f8f9fa;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .editor-btn {
            padding: 0.4rem 0.8rem;
            border: 1px solid #ddd;
            background: white;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: background 0.2s;
        }

        .editor-btn:hover {
            background: #e9ecef;
        }

        .content-editor {
            border-radius: 0 0 5px 5px;
            border-top: none;
            min-height: 400px;
            font-family: 'Georgia', serif;
            line-height: 1.6;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-help {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.3rem;
        }

        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .status-draft .status-indicator {
            background: #ffc107;
        }

        .status-published .status-indicator {
            background: #28a745;
        }

        .user-info {
            color: white;
            font-size: 0.9rem;
        }

        .meta-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            color: #666;
        }

        .btn-upload-image {
            padding: 0.5rem 1rem;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }

        .btn-upload-image:hover {
            background: #0b5ed7;
        }

        .btn-upload-image:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <header class="admin-header">
        <h1>🎨 Administration<br><small style="font-size: 0.75em; font-weight: normal; display: block; text-align: center; margin-left: 0.75rem;"><?= htmlspecialchars($_SESSION['admin_full_name'] ?? $_SESSION['admin_username']) ?></small></h1>
        <nav class="admin-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage-articles.php" class="active">Articles</a>
            <a href="categories.php">Catégories</a>
            <a href="create-admin.php">👥 Utilisateurs</a>
            <a href="../index.php" target="_blank">🏠 Site principal</a>
            <a href="../blog.php" target="_blank">📖 Voir le blog</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
        <div class="user-info">
            Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['admin_full_name'] ?? $_SESSION['admin_username']) ?></strong>
        </div>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php else: ?>

            <div class="page-header">
                <h2><?= $page_title ?></h2>
                <a href="manage-articles.php" class="btn btn-secondary">
                    ← Retour aux articles
                </a>
            </div>

            <?php if ($article && $action === 'edit'): ?>
                <div class="meta-info">
                    <strong>Informations:</strong><br>
                    Créé le: <?= date('d/m/Y à H:i', strtotime($article['created_at'])) ?><br>
                    <?php if ($article['updated_at']): ?>
                        Dernière modification: <?= date('d/m/Y à H:i', strtotime($article['updated_at'])) ?><br>
                    <?php endif; ?>
                    ID: #<?= $article['id'] ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" id="articleForm" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Titre de l'article *</label>
                            <input type="text" id="title" name="title" class="form-control"
                                value="<?= htmlspecialchars($form_data['title']) ?>" required
                                placeholder="Entrez le titre de votre article">
                        </div>
                        <div class="form-group">
                            <label for="status">Statut</label>
                            <select id="status" name="status" class="form-control">
                                <option value="draft" <?= $form_data['status'] === 'draft' ? 'selected' : '' ?>>
                                    <span class="status-indicator"></span>Brouillon
                                </option>
                                <option value="published" <?= $form_data['status'] === 'published' ? 'selected' : '' ?>>
                                    <span class="status-indicator"></span>Publié
                                </option>
                            </select>
                            <div class="form-help">
                                Les brouillons ne sont pas visibles sur le site public
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id">Catégorie</label>
                            <select id="category_id" name="category_id" class="form-control">
                                <option value="">Aucune catégorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"
                                        <?= ($form_data['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-help">
                                Associez cet article à une catégorie pour mieux l'organiser
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="excerpt">Extrait</label>
                            <textarea id="excerpt" name="excerpt" class="form-control" rows="3"
                                placeholder="Résumé de l'article (optionnel - sera généré automatiquement si vide)"><?= htmlspecialchars($form_data['excerpt']) ?></textarea>
                            <div class="form-help">
                                Résumé affiché dans la liste des articles et pour le référencement
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="featured_image">Image mise en avant</label>

                            <!-- Aperçu de l'image -->
                            <?php if (!empty($form_data['featured_image'])): ?>
                                <div id="featuredImagePreview" style="margin-bottom: 10px;">
                                    <img src="../<?= htmlspecialchars($form_data['featured_image']) ?>" alt="Image mise en avant" style="max-width: 300px; max-height: 200px; border-radius: 5px; border: 2px solid #ddd;">
                                    <button type="button" onclick="removeFeaturedImage()" style="display: block; margin-top: 5px; padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">🗑️ Supprimer l'image</button>
                                </div>
                            <?php else: ?>
                                <div id="featuredImagePreview" style="margin-bottom: 10px; display: none;">
                                    <img id="previewImg" src="" alt="Aperçu" style="max-width: 300px; max-height: 200px; border: 2px solid #ddd;">
                                    <button type="button" onclick="removeFeaturedImage()" style="display: block; margin-top: 5px; padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">🗑️ Supprimer l'image</button>
                                </div>
                            <?php endif; ?>

                            <!-- Bouton pour uploader une image locale -->
                            <div style="margin-bottom: 10px;">
                                <button type="button" class="btn-upload-featured" onclick="document.getElementById('featuredImageFile').click()" style="padding: 8px 16px; background: #198754; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    Choisir une image locale
                                </button>
                                <input type="file" id="featuredImageFile" name="featured_image_file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" style="display: none;">
                            </div>

                            <div style="text-align: center; margin: 10px 0; color: #6c757d;">ou</div>

                            <!-- Champ URL conservé -->
                            <input type="url" id="featured_image" name="featured_image" class="form-control"
                                value="<?= htmlspecialchars($form_data['featured_image']) ?>"
                                placeholder="https://exemple.com/image.jpg">
                            <div class="form-help">
                                Uploadez une image locale ou entrez l'URL d'une image en ligne (optionnel)
                            </div>

                            <!-- Champ pour la hauteur de l'image -->
                            <div style="margin-top: 15px;">
                                <label for="featured_image_height" style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    Hauteur de l'image (en pixels)
                                </label>
                                <input type="number" id="featured_image_height" name="featured_image_height"
                                    class="form-control"
                                    value="<?= htmlspecialchars($form_data['featured_image_height']) ?>"
                                    min="100" max="800" step="10"
                                    placeholder="300">
                                <div class="form-help">
                                    Hauteur maximum de l'image en pixels (défaut: 300px). Suggestions: Petite (200-250), Moyenne (300-400), Grande (500-600)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="content">Contenu de l'article *</label>

                            <div class="markdown-help">
                                <h4 style="margin-bottom: 10px; color: #333;">🎨 Guide Markdown (copiez-collez les exemples)</h4>
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; font-size: 0.85rem;">
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px;">
                                        <div>
                                            <strong style="color: #d63384;">📝 Titres (ESPACE obligatoire) :</strong><br>
                                            <code style="background: white; padding: 2px 4px; border-radius: 3px; display: block; margin: 3px 0; border-left: 3px solid #d63384;">## Mon titre principal</code>
                                            <code style="background: white; padding: 2px 4px; border-radius: 3px; display: block; margin: 3px 0; border-left: 3px solid #d63384;">### Mon sous-titre</code>
                                            <small style="color: #d63384;">⚠️ N'oubliez pas l'espace après # !</small>
                                        </div>
                                        <div>
                                            <strong style="color: #0d6efd;">✨ Mise en forme :</strong><br>
                                            <code style="background: white; padding: 2px 4px; border-radius: 3px; display: block; margin: 3px 0;">**texte gras**</code>
                                            <code style="background: white; padding: 2px 4px; border-radius: 3px; display: block; margin: 3px 0;">*texte italique*</code>
                                        </div>
                                        <div>
                                            <strong style="color: #198754;">🔗 Liens et listes :</strong><br>
                                            <code style="background: white; padding: 2px 4px; border-radius: 3px; display: block; margin: 3px 0;">[texte du lien](url)</code>
                                            <code style="background: white; padding: 2px 4px; border-radius: 3px; display: block; margin: 3px 0;">- Élément de liste</code>
                                        </div>
                                        <div>
                                            <strong style="color: #fd7e14;">🖼️ Images :</strong><br>
                                            <code style="background: white; padding: 2px 4px; border-radius: 3px; display: block; margin: 3px 0;">![description](url-image)</code>
                                        </div>
                                    </div>
                                    <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;">
                                        <strong style="color: #664d03;">💡 Astuce :</strong> <span style="color: #664d03;">Les titres doivent être sur une ligne séparée pour fonctionner !</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton pour ajouter une image -->
                            <div style="margin-bottom: 10px;">
                                <button type="button" class="btn-upload-image" onclick="document.getElementById('imageUpload').click()">
                                    📎 Ajouter une image
                                </button>
                                <input type="file" id="imageUpload" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" style="display: none;">
                                <span id="uploadStatus" style="margin-left: 10px; font-size: 0.9em;"></span>
                            </div>

                            <textarea id="content" name="content" class="form-control content-editor" required
                                placeholder="Écrivez votre article ici...

Exemples de formatage Markdown :

## Titre principal
### Sous-titre

Vous pouvez écrire du **texte gras** et du *texte italique*.

- Élément de liste 1
- Élément de liste 2

[Lien vers un site](https://exemple.com)

![Description d'une image](https://exemple.com/image.jpg)"><?= htmlspecialchars($form_data['content']) ?></textarea>

                            <div class="form-help">
                                Vous pouvez utiliser le Markdown pour la mise en forme. Exemples:<br>
                                <strong>**texte gras**</strong>, <em>*texte italique*</em>, ## Titre niveau 2, ### Titre niveau 3
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <?= $action === 'edit' ? '💾 Mettre à jour' : '💾 Créer l\'article' ?>
                            </button>


                        </div>

                        <div>
                            <a href="manage-articles.php" class="btn btn-secondary">Annuler</a>
                        </div>
                    </div>
                </form>


            </div>

        <?php endif; ?>
    </div>

    <script>
        // Fonctions d'aide pour l'éditeur Markdown
        function formatText(command) {
            try {
                const textarea = document.getElementById('content');
                if (!textarea) return;

                const start = textarea.selectionStart || 0;
                const end = textarea.selectionEnd || 0;
                const selectedText = textarea.value.substring(start, end);

                let replacement = '';
                let cursorOffset = 0;

                if (command === 'bold') {
                    if (selectedText) {
                        replacement = `**${selectedText}**`;
                        cursorOffset = replacement.length;
                    } else {
                        replacement = '**texte gras**';
                        cursorOffset = 2; // Placer le curseur après **
                    }
                } else if (command === 'italic') {
                    if (selectedText) {
                        replacement = `*${selectedText}*`;
                        cursorOffset = replacement.length;
                    } else {
                        replacement = '*texte italique*';
                        cursorOffset = 1; // Placer le curseur après *
                    }
                }

                if (replacement) {
                    textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
                    textarea.focus();

                    if (selectedText) {
                        textarea.setSelectionRange(start + cursorOffset, start + cursorOffset);
                    } else {
                        // Sélectionner le texte placeholder pour le remplacer facilement
                        const placeholderStart = start + (command === 'bold' ? 2 : 1);
                        const placeholderEnd = start + replacement.length - (command === 'bold' ? 2 : 1);
                        textarea.setSelectionRange(placeholderStart, placeholderEnd);
                    }
                }
            } catch (error) {
                console.error('Erreur formatText:', error);
            }
        }

        function insertText(text) {
            try {
                const textarea = document.getElementById('content');
                if (!textarea) return;

                const start = textarea.selectionStart || 0;
                const end = textarea.selectionEnd || 0;

                textarea.value = textarea.value.substring(0, start) + text + textarea.value.substring(end);
                textarea.focus();
                textarea.setSelectionRange(start + text.length, start + text.length);
            } catch (error) {
                console.error('Erreur insertText:', error);
            }
        }

        // Gestion de l'upload d'images
        document.addEventListener('DOMContentLoaded', function() {
            const imageUpload = document.getElementById('imageUpload');
            const uploadBtn = document.querySelector('.btn-upload-image');
            const uploadStatus = document.getElementById('uploadStatus');

            if (imageUpload) {
                imageUpload.addEventListener('change', async function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    // Vérifier le type de fichier
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                    if (!allowedTypes.includes(file.type)) {
                        uploadStatus.textContent = '❌ Type de fichier non autorisé';
                        uploadStatus.style.color = '#dc3545';
                        return;
                    }

                    // Vérifier la taille (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        uploadStatus.textContent = '❌ Fichier trop volumineux (max 5MB)';
                        uploadStatus.style.color = '#dc3545';
                        return;
                    }

                    // Afficher le statut de chargement
                    uploadStatus.textContent = '⏳ Upload en cours...';
                    uploadStatus.style.color = '#0d6efd';
                    uploadBtn.disabled = true;

                    // Créer le FormData
                    const formData = new FormData();
                    formData.append('image', file);

                    try {
                        const response = await fetch('upload-image.php', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Demander la hauteur souhaitée
                            const heightPrompt = prompt(
                                'Quelle hauteur souhaitez-vous pour cette image ?\n\n' +
                                '• Tapez un nombre en pixels (ex: 300)\n' +
                                '• Ou laissez vide pour la taille automatique\n\n' +
                                'Tailles suggérées :\n' +
                                '- Petite : 200-250px\n' +
                                '- Moyenne : 300-400px\n' +
                                '- Grande : 500-600px',
                                '300'
                            );

                            let imageMarkdown;
                            const altText = file.name.replace(/\.\w+$/, '');

                            if (heightPrompt && !isNaN(heightPrompt) && heightPrompt.trim() !== '') {
                                // Image avec hauteur personnalisée
                                const height = parseInt(heightPrompt);
                                imageMarkdown = `\n<img src="${result.url}" alt="${altText}" style="max-height: ${height}px; width: auto; height: auto;">\n`;
                            } else {
                                // Image en taille automatique (Markdown standard)
                                imageMarkdown = `\n![${altText}](${result.url})\n`;
                            }

                            insertText(imageMarkdown);

                            uploadStatus.textContent = '✅ Image ajoutée !';
                            uploadStatus.style.color = '#28a745';

                            // Réinitialiser après 3 secondes
                            setTimeout(() => {
                                uploadStatus.textContent = '';
                                imageUpload.value = '';
                            }, 3000);
                        } else {
                            uploadStatus.textContent = '❌ ' + result.message;
                            uploadStatus.style.color = '#dc3545';
                        }
                    } catch (error) {
                        console.error('Erreur upload:', error);
                        uploadStatus.textContent = '❌ Erreur lors de l\'upload';
                        uploadStatus.style.color = '#dc3545';
                    } finally {
                        uploadBtn.disabled = false;
                    }
                });
            }
        });

        // Génération automatique de l'extrait
        let excerptTimeout;
        let userEditedExcerpt = false;

        function autoGenerateExcerpt() {
            const contentField = document.getElementById('content');
            const excerptField = document.getElementById('excerpt');

            if (!contentField || !excerptField) return;

            // Si l'utilisateur a modifié manuellement l'extrait, ne pas écraser
            if (userEditedExcerpt && excerptField.value.trim() !== '') return;

            clearTimeout(excerptTimeout);
            excerptTimeout = setTimeout(() => {
                let content = contentField.value.trim();

                if (!content) {
                    if (!userEditedExcerpt) excerptField.value = '';
                    return;
                }

                // Supprimer les symboles markdown
                content = content.replace(/^#{1,6}\s+/gm, ''); // Titres
                content = content.replace(/\*\*(.+?)\*\*/g, '$1'); // Gras
                content = content.replace(/\*(.+?)\*/g, '$1'); // Italique
                content = content.replace(/\[(.+?)\]\(.+?\)/g, '$1'); // Liens
                content = content.replace(/!\[.*?\]\(.+?\)/g, ''); // Images
                content = content.replace(/^[\-\*]\s+/gm, ''); // Listes
                content = content.replace(/`(.+?)`/g, '$1'); // Code inline
                content = content.replace(/\s+/g, ' '); // Espaces multiples
                content = content.trim();

                // Tronquer à 200 caractères au mot entier
                const maxLength = 200;
                if (content.length > maxLength) {
                    content = content.substring(0, maxLength);
                    const lastSpace = content.lastIndexOf(' ');
                    if (lastSpace !== -1) {
                        content = content.substring(0, lastSpace);
                    }
                    content += '...';
                }

                excerptField.value = content;
            }, 500); // Délai de 500ms pour éviter trop de calculs
        }

        // Auto-sauvegarde en brouillon (optionnel)
        let saveTimeout;

        function autoSave() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                console.log('Auto-save triggered');
            }, 30000);
        }

        // Gestion de l'image mise en avant
        function removeFeaturedImage() {
            document.getElementById('featured_image').value = '';
            document.getElementById('featuredImageFile').value = '';
            const preview = document.getElementById('featuredImagePreview');
            if (preview) {
                preview.style.display = 'none';
            }
        }

        // Prévisualisation de l'image mise en avant lors de la sélection
        const featuredImageFile = document.getElementById('featuredImageFile');
        if (featuredImageFile) {
            featuredImageFile.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.getElementById('featuredImagePreview');
                        const previewImg = document.getElementById('previewImg');
                        if (preview && previewImg) {
                            previewImg.src = event.target.result;
                            preview.style.display = 'block';
                        }
                    };
                    reader.readAsDataURL(file);

                    // Vider le champ URL si une image locale est choisie
                    document.getElementById('featured_image').value = '';
                }
            });
        }

        // Initialisation des gestionnaires d'événements
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-sauvegarde
            const titleField = document.getElementById('title');
            const contentField = document.getElementById('content');
            const excerptField = document.getElementById('excerpt');

            if (titleField) titleField.addEventListener('input', autoSave);
            if (contentField) {
                contentField.addEventListener('input', autoSave);
                // Génération automatique de l'extrait pendant la saisie du contenu
                contentField.addEventListener('input', autoGenerateExcerpt);
            }

            // Détecter si l'utilisateur modifie manuellement l'extrait
            if (excerptField) {
                excerptField.addEventListener('input', function() {
                    userEditedExcerpt = true;
                });
            }

            // Gestionnaires pour les boutons de l'éditeur
            const editorButtons = document.querySelectorAll('.editor-btn');
            editorButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const action = this.getAttribute('data-action');
                    const text = this.getAttribute('data-text');

                    if (action) {
                        // Boutons de formatage (gras, italique)
                        formatText(action);
                    } else if (text) {
                        // Boutons d'insertion (titres, listes, liens, images)
                        insertText(text);
                    }
                });
            });

            console.log('Éditeur Markdown initialisé');
        });
    </script>
</body>

</html>