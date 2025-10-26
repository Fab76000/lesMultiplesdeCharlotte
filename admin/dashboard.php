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

    // Statistiques rapides
    $stats = [];

    // Compter les articles
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
    $stats['total_articles'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'published'");
    $stats['published_articles'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'draft'");
    $stats['draft_articles'] = $stmt->fetchColumn();

    // Compter les catégories
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $stats['total_categories'] = $stmt->fetchColumn();

    // Derniers articles
    $stmt = $pdo->query("
        SELECT a.id, a.title, a.status, a.created_at, c.name as category_name
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        ORDER BY a.created_at DESC 
        LIMIT 5
    ");
    $recent_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Erreur de connexion à la base de données.';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administration</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #666;
            font-size: 0.9rem;
        }

        .content-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .section-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h2 {
            color: #333;
            font-size: 1.2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
        }

        .articles-list {
            padding: 1.5rem;
        }

        .article-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .article-item:last-child {
            border-bottom: none;
        }

        .article-info h3 {
            color: #333;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .article-meta {
            color: #666;
            font-size: 0.8rem;
        }

        .status {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status.published {
            background: #d4edda;
            color: #155724;
        }

        .status.draft {
            background: #fff3cd;
            color: #856404;
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
            <a href="manage-articles.php">Articles</a>
            <a href="categories.php">Catégories</a>
            <a href="../index.php" target="_blank">🏠 Site principal</a>
            <a href="../blog.php" target="_blank">📖 Voir le blog</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
        <div class="user-info">
            Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong>
        </div>
    </header>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?= $stats['total_articles'] ?></h3>
                <p>Articles total</p>
            </div>
            <div class="stat-card">
                <h3><?= $stats['published_articles'] ?></h3>
                <p>Articles publiés</p>
            </div>
            <div class="stat-card">
                <h3><?= $stats['draft_articles'] ?></h3>
                <p>Brouillons</p>
            </div>
            <div class="stat-card">
                <h3><?= $stats['total_categories'] ?></h3>
                <p>Catégories</p>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2>📝 Articles récents</h2>
                <a href="manage-articles.php" class="btn-primary">Gérer les articles</a>
            </div>
            <div class="articles-list">
                <?php if (empty($recent_articles)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">
                        Aucun article pour le moment.
                        <a href="manage-articles.php?action=new">Créer le premier article</a>
                    </p>
                <?php else: ?>
                    <?php foreach ($recent_articles as $article): ?>
                        <div class="article-item">
                            <div class="article-info">
                                <h3><?= htmlspecialchars($article['title']) ?></h3>
                                <div class="article-meta">
                                    <?= $article['category_name'] ?? 'Sans catégorie' ?> •
                                    <?= date('d/m/Y H:i', strtotime($article['created_at'])) ?>
                                </div>
                            </div>
                            <span class="status <?= $article['status'] ?>">
                                <?= $article['status'] == 'published' ? 'Publié' : 'Brouillon' ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>