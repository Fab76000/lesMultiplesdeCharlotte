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

    // Traitement des actions
    $action = $_GET['action'] ?? 'list';
    $message = '';
    $message_type = '';

    // Supprimer un article
    if ($action === 'delete' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $message = "Article supprimé avec succès.";
        $message_type = "success";
        $action = 'list';
    }

    // Changer le statut d'un article
    if ($action === 'toggle_status' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT status FROM articles WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $current_status = $stmt->fetchColumn();

        $new_status = $current_status === 'published' ? 'draft' : 'published';

        $stmt = $pdo->prepare("UPDATE articles SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $_GET['id']]);

        $message = "Statut de l'article mis à jour : " . ($new_status === 'published' ? 'Publié' : 'Brouillon');
        $message_type = "success";
        $action = 'list';
    }

    // Récupérer les articles avec pagination et recherche
    $search = $_GET['search'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    $page = max(1, intval($_GET['page'] ?? 1));
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    $where_conditions = [];
    $params = [];

    if ($search) {
        $where_conditions[] = "(title LIKE ? OR content LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($status_filter) {
        $where_conditions[] = "status = ?";
        $params[] = $status_filter;
    }

    $where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    // Compter le total pour la pagination
    $count_sql = "SELECT COUNT(*) FROM articles $where_clause";
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_articles = $stmt->fetchColumn();
    $total_pages = ceil($total_articles / $per_page);

    // Récupérer les articles
    $sql = "SELECT id, title, status, created_at, updated_at 
            FROM articles 
            $where_clause 
            ORDER BY created_at DESC 
            LIMIT $per_page OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Statistiques rapides
    $stats = [];
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM articles GROUP BY status");
    while ($row = $stmt->fetch()) {
        $stats[$row['status']] = $row['count'];
    }
} catch (PDOException $e) {
    $error = 'Erreur de connexion à la base de données: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - Administration</title>
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

        .admin-nav a:hover,
        .admin-nav a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1200px;
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

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .stats-bar {
            background: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .filters-row {
            display: flex;
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .articles-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-published {
            background: #d4edda;
            color: #155724;
        }

        .status-draft {
            background: #fff3cd;
            color: #856404;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
        }

        .pagination a:hover {
            background: #f8f9fa;
        }

        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
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

        .no-articles {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .user-info {
            color: white;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <header class="admin-header">
        <h1>🎨 Administration - Charlotte Goupil</h1>
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
            Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong>
        </div>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php else: ?>

            <div class="page-header">
                <h2>📝 Gestion des Articles</h2>
                <a href="article-form.php" class="btn btn-primary">
                    ➕ Nouvel Article
                </a>
            </div>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number"><?= $stats['published'] ?? 0 ?></div>
                    <div class="stat-label">Publiés</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $stats['draft'] ?? 0 ?></div>
                    <div class="stat-label">Brouillons</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $total_articles ?></div>
                    <div class="stat-label">Total</div>
                </div>
            </div>

            <div class="filters">
                <form method="GET" class="filters-row">
                    <div class="form-group">
                        <label for="search">Rechercher</label>
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Titre ou contenu..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Statut</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">Tous les statuts</option>
                            <option value="published" <?= $status_filter === 'published' ? 'selected' : '' ?>>Publiés</option>
                            <option value="draft" <?= $status_filter === 'draft' ? 'selected' : '' ?>>Brouillons</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
                    </div>
                    <?php if ($search || $status_filter): ?>
                        <div class="form-group">
                            <a href="manage-articles.php" class="btn btn-secondary">❌ Reset</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="articles-table">
                <?php if (empty($articles)): ?>
                    <div class="no-articles">
                        <?php if ($search || $status_filter): ?>
                            <h3>Aucun article trouvé</h3>
                            <p>Aucun article ne correspond à vos critères de recherche.</p>
                            <a href="manage-articles.php" class="btn btn-secondary">Voir tous les articles</a>
                        <?php else: ?>
                            <h3>Aucun article</h3>
                            <p>Vous n'avez pas encore créé d'article.</p>
                            <p>Utilisez le bouton "➕ Nouvel Article" ci-dessus pour commencer.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Modifié le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($article['title']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $article['status'] ?>">
                                            <?= $article['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($article['created_at'])) ?></td>
                                    <td>
                                        <?= $article['updated_at'] ? date('d/m/Y H:i', strtotime($article['updated_at'])) : '-' ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="article-form.php?action=edit&id=<?= $article['id'] ?>"
                                                class="btn btn-sm btn-primary" title="Modifier">
                                                ✏️
                                            </a>
                                            <a href="?action=toggle_status&id=<?= $article['id'] ?>"
                                                class="btn btn-sm <?= $article['status'] === 'published' ? 'btn-warning' : 'btn-success' ?>"
                                                title="<?= $article['status'] === 'published' ? 'Mettre en brouillon' : 'Publier' ?>"
                                                onclick="return confirm('Changer le statut de cet article ?')">
                                                <?= $article['status'] === 'published' ? '📝' : '🚀' ?>
                                            </a>
                                            <a href="?action=delete&id=<?= $article['id'] ?>"
                                                class="btn btn-sm btn-danger" title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                🗑️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>">‹ Précédent</a>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="current"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>">Suivant ›</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>
</body>

</html>