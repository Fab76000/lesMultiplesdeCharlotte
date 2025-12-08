<?php
require_once 'php/db-config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pagination
    $page = max(1, intval($_GET['page'] ?? 1));
    $per_page = 6;
    $offset = ($page - 1) * $per_page;

    // Compter les articles publiés
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'published'");
    $total_articles = $stmt->fetchColumn();
    $total_pages = ceil($total_articles / $per_page);

    // Récupérer les articles publiés (avec LIMIT et OFFSET sécurisés)
    $per_page = intval($per_page);
    $offset = intval($offset);
    $stmt = $pdo->query("
        SELECT id, title, content, excerpt, featured_image, created_at 
        FROM articles 
        WHERE status = 'published' 
        ORDER BY created_at DESC 
        LIMIT $per_page OFFSET $offset
    ");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $articles = [];
    $error = 'Erreur de connexion à la base de données.';
}

// Fonction pour convertir le Markdown simple en HTML
function simpleMarkdownToHtml($text) {
    // Titres
    $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
    $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $text);

    // Gras et italique
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);

    // Liens
    $text = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2" target="_blank">$1</a>', $text);

    // Images
    $text = preg_replace('/!\[(.+?)\]\((.+?)\)/', '<img src="$2" alt="$1" class="article-image">', $text);

    // Listes
    $text = preg_replace('/^- (.+)$/m', '<li>$1</li>', $text);
    $text = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $text);

    // Paragraphes
    $text = preg_replace('/\n\n/', '</p><p>', $text);
    $text = '<p>' . $text . '</p>';

    // Nettoyer les paragraphes vides et mal formés
    $text = preg_replace('/<p><\/p>/', '', $text);
    $text = preg_replace('/<p>(<h[1-6]>.*<\/h[1-6]>)<\/p>/', '$1', $text);
    $text = preg_replace('/<p>(<ul>.*<\/ul>)<\/p>/s', '$1', $text);

    return $text;
}

// Fonction pour extraire les premiers mots d'un texte
function getExcerpt($content, $length = 150) {
    $text = strip_tags($content);
    return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Charlotte Goupil</title>
    <meta name="description" content="Découvrez les réflexions et actualités de Charlotte Goupil, chanteuse, comédienne et médiatrice culturelle.">

    <link rel="stylesheet" href="header.min.css">
    <link rel="stylesheet" href="footer.min.css">
    <link rel="stylesheet" href="style.min.css">

    <!-- Styles spécifiques au blog -->
    <style>
        .blog-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .blog-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .blog-header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .blog-header p {
            font-size: 1.2rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .article-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .article-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .article-content {
            padding: 1.5rem;
        }

        .article-date {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .article-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .article-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .article-title a:hover {
            color: #667eea;
        }

        .article-excerpt {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .read-more {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .read-more:hover {
            color: #764ba2;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin: 3rem 0;
        }

        .pagination a,
        .pagination span {
            padding: 0.8rem 1.2rem;
            border: 2px solid #667eea;
            text-decoration: none;
            color: #667eea;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: #667eea;
            color: white;
        }

        .pagination .current {
            background: #667eea;
            color: white;
        }

        .no-articles {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .no-articles h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .blog-container {
                padding: 0 1rem;
            }

            .blog-header h1 {
                font-size: 2rem;
            }

            .articles-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .article-card {
                margin: 0 auto;
                max-width: 400px;
            }

            .pagination {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .pagination a,
            .pagination span {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Styles pour le contenu d'article complet */
        .full-article {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .full-article h1,
        .full-article h2,
        .full-article h3 {
            color: #2c3e50;
            margin: 1.5rem 0 1rem 0;
        }

        .full-article h1 {
            font-size: 2.2rem;
            border-bottom: 3px solid #667eea;
            padding-bottom: 0.5rem;
        }

        .full-article h2 {
            font-size: 1.6rem;
        }

        .full-article h3 {
            font-size: 1.3rem;
        }

        .full-article p {
            line-height: 1.8;
            margin-bottom: 1.2rem;
            color: #444;
        }

        .full-article ul {
            margin: 1rem 0;
            padding-left: 2rem;
        }

        .full-article li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }

        .full-article .article-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .back-to-blog {
            display: inline-block;
            margin-bottom: 2rem;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .back-to-blog:hover {
            color: #764ba2;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <main class="blog-container">
        <?php if (isset($_GET['article']) && is_numeric($_GET['article'])): ?>
            <?php
            // Affichage d'un article complet
            $article_id = intval($_GET['article']);
            $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND status = 'published'");
            $stmt->execute([$article_id]);
            $article = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($article):
            ?>
                <a href="blog.php" class="back-to-blog">← Retour au blog</a>

                <article class="full-article">
                    <?php if ($article['featured_image']): ?>
                        <img src="<?= htmlspecialchars($article['featured_image']) ?>"
                            alt="<?= htmlspecialchars($article['title']) ?>"
                            class="article-image">
                    <?php endif; ?>

                    <div class="article-date">
                        Publié le <?= date('j F Y', strtotime($article['created_at'])) ?>
                    </div>

                    <div class="article-body">
                        <?= simpleMarkdownToHtml($article['content']) ?>
                    </div>
                </article>
            <?php else: ?>
                <div class="error-message">
                    Article non trouvé ou non publié.
                </div>
                <div style="text-align: center;">
                    <a href="blog.php" class="back-to-blog">← Retour au blog</a>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Liste des articles -->
            <header class="blog-header">
                <h1>📝 Mon Blog</h1>
                <p>Découvrez mes réflexions, actualités et coulisses entre chant, théâtre et médiation culturelle</p>
            </header>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php elseif (empty($articles)): ?>
                <div class="no-articles">
                    <h2>Aucun article pour le moment</h2>
                    <p>Le blog sera bientôt alimenté avec de nouveaux contenus !</p>
                </div>
            <?php else: ?>
                <div class="articles-grid">
                    <?php foreach ($articles as $article): ?>
                        <article class="article-card">
                            <?php if ($article['featured_image']): ?>
                                <img src="<?= htmlspecialchars($article['featured_image']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>"
                                    class="article-image">
                            <?php else: ?>
                                <div class="article-image">
                                    📝
                                </div>
                            <?php endif; ?>

                            <div class="article-content">
                                <div class="article-date">
                                    <?= date('j F Y', strtotime($article['created_at'])) ?>
                                </div>

                                <h2 class="article-title">
                                    <a href="blog.php?article=<?= $article['id'] ?>">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </h2>

                                <div class="article-excerpt">
                                    <?= htmlspecialchars($article['excerpt'] ?: getExcerpt($article['content'])) ?>
                                </div>

                                <a href="blog.php?article=<?= $article['id'] ?>" class="read-more">
                                    Lire la suite →
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <nav class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="blog.php?page=<?= $page - 1 ?>">‹ Précédent</a>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="blog.php?page=<?= $i ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="blog.php?page=<?= $page + 1 ?>">Suivant ›</a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>