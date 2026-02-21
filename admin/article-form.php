<?php
session_start();

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

require_once '../php/db-config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

        $errors = [];

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
            $excerpt = substr(strip_tags($content), 0, 200) . '...';
        }

        // Générer le slug à partir du titre
        function generateSlug($title, $pdo, $current_id = null) {
            // Nettoyer et convertir le titre en slug
            $slug = strtolower($title);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');

            // Vérifier l'unicité
            $base_slug = $slug;
            $counter = 1;

            while (true) {
                $check_sql = "SELECT id FROM articles WHERE slug = ?";
                $params = [$slug];

                if ($current_id) {
                    $check_sql .= " AND id != ?";
                    $params[] = $current_id;
                }

                $stmt = $pdo->prepare($check_sql);
                $stmt->execute($params);

                if (!$stmt->fetchColumn()) {
                    break; // Slug disponible
                }

                $slug = $base_slug . '-' . $counter;
                $counter++;
            }

            return $slug;
        }

        $slug = generateSlug($title, $pdo, $article_id);

        if (empty($errors)) {
            try {
                if ($action === 'edit' && $article_id) {
                    // Mise à jour
                    $stmt = $pdo->prepare("
                        UPDATE articles 
                        SET title = ?, slug = ?, content = ?, excerpt = ?, status = ?, featured_image = ?, category_id = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$title, $slug, $content, $excerpt, $status, $featured_image, $category_id, $article_id]);

                    $message = "Article mis à jour avec succès.";
                    $message_type = "success";
                } else {
                    // Création
                    $stmt = $pdo->prepare("
                        INSERT INTO articles (title, slug, content, excerpt, status, featured_image, category_id, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                    ");
                    $stmt->execute([$title, $slug, $content, $excerpt, $status, $featured_image, $category_id]);

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
                <form method="POST" id="articleForm">
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
                            <input type="url" id="featured_image" name="featured_image" class="form-control"
                                value="<?= htmlspecialchars($form_data['featured_image']) ?>"
                                placeholder="https://exemple.com/image.jpg">
                            <div class="form-help">
                                URL de l'image principale de l'article (optionnel)
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

        // Auto-sauvegarde en brouillon (optionnel)
        let saveTimeout;

        function autoSave() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                console.log('Auto-save triggered');
            }, 30000);
        }

        // Initialisation des gestionnaires d'événements
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-sauvegarde
            const titleField = document.getElementById('title');
            const contentField = document.getElementById('content');

            if (titleField) titleField.addEventListener('input', autoSave);
            if (contentField) contentField.addEventListener('input', autoSave);

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