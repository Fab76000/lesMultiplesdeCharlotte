<?php
session_start();

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

require_once '../php/db-config.php';

$success = '';
$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Traitement des actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                $error = 'Le nom de la catégorie est requis.';
            } elseif (empty($slug)) {
                $error = 'Le slug est requis.';
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
                $stmt->execute([$slug]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'Ce slug existe déjà.';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $slug, $description]);
                    $success = 'Catégorie ajoutée avec succès !';
                }
            }
        } elseif ($action === 'edit') {
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($id <= 0) {
                $error = 'ID de catégorie invalide.';
            } elseif (empty($name) || empty($slug)) {
                $error = 'Le nom et le slug sont requis.';
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND id != ?");
                $stmt->execute([$slug, $id]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'Ce slug existe déjà.';
                } else {
                    $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
                    $stmt->execute([$name, $slug, $description, $id]);
                    $success = 'Catégorie modifiée avec succès !';
                }
            }
        } elseif ($action === 'delete') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
                if ($stmt->execute([$id])) {
                    header('Location: categories.php?deleted=1');
                    exit;
                }
            }
        }
    }

    $stmt = $pdo->query("SELECT c.*, COUNT(a.id) as article_count FROM categories c LEFT JOIN articles a ON c.id = a.category_id GROUP BY c.id ORDER BY c.name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $editing_category = null;
    if (isset($_GET['edit'])) {
        $edit_id = intval($_GET['edit']);
        if ($edit_id > 0) {
            $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$edit_id]);
            $editing_category = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
} catch (PDOException $e) {
    $error = 'Erreur : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            font-size: 1.5rem;
        }

        .admin-nav {
            display: flex;
            gap: 1rem;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .admin-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .content-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .section-header h2 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .form-container {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-edit {
            background: #28a745;
            color: white;
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
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

        .categories-table {
            width: 100%;
            border-collapse: collapse;
        }

        .categories-table th,
        .categories-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .categories-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .badge {
            background: #007bff;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }
    </style>
</head>

<body>
    <header class="admin-header">
        <h1>🏷️ Gestion des Catégories</h1>
        <nav class="admin-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage-articles.php">Articles</a>
            <a href="categories.php">Catégories</a>
            <a href="create-admin.php">👥 Utilisateurs</a>
            <a href="../index.php" target="_blank">🏠 Site principal</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>

    <div class="container">
        <div class="content-section">
            <div class="section-header">
                <h2><?= $editing_category ? '✏️ Modifier une catégorie' : '➕ Ajouter une catégorie' ?></h2>
            </div>
            <div class="form-container">
                <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="action" value="<?= $editing_category ? 'edit' : 'add' ?>">
                    <?php if ($editing_category): ?><input type="hidden" name="id" value="<?= $editing_category['id'] ?>"><?php endif; ?>
                    <div class="form-group">
                        <label for="name">Nom de la catégorie *</label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($editing_category['name'] ?? '') ?>" placeholder="Ex: Médiation">
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug (URL) *</label>
                        <input type="text" id="slug" name="slug" required value="<?= htmlspecialchars($editing_category['slug'] ?? '') ?>" placeholder="Ex: mediation">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Description..."><?= htmlspecialchars($editing_category['description'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= $editing_category ? '💾 Modifier' : '➕ Ajouter' ?></button>
                    <?php if ($editing_category): ?><a href="categories.php" class="btn btn-secondary">Annuler</a><?php endif; ?>
                </form>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2>📋 Catégories existantes</h2>
            </div>
            <table class="categories-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Articles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><strong><?= htmlspecialchars($category['name']) ?></strong></td>
                            <td><code><?= htmlspecialchars($category['slug']) ?></code></td>
                            <td><span class="badge"><?= $category['article_count'] ?></span></td>
                            <td>
                                <div class="actions">
                                    <a href="?edit=<?= $category['id'] ?>" class="btn btn-edit">✏️ Modifier</a>
                                    <?php if ($category['article_count'] == 0): ?>
                                        <button type="button"
                                            class="btn btn-danger btn-trigger-delete"
                                            data-id="<?= $category['id'] ?>"
                                            data-name="<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>">
                                            🗑️ Supprimer
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">⚠️ Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="categories.php">
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer la catégorie :</p>
                        <p class="text-center"><strong class="fs-5 text-primary" id="modal-category-name"></strong></p>

                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="modal-category-id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">🗑️ Supprimer définitivement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../js/admin-categories.js"></script>
</body>

</html>