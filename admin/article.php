<?php
// Inclure la configuration de base de données
require_once 'php/db-config.php';

$article = null;
$error = null;

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $article_id = intval($_GET['id'] ?? 0);

    if ($article_id) {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND status = 'published'");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$article) {
        $error = "Article introuvable.";
    }
} catch (PDOException $e) {
    $error = 'Erreur de chargement de l\'article.';
}

// Fonction simple pour convertir le Markdown de base
function simpleMarkdown($text) {
    // Titres
    $text = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $text);
    $text = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $text);

    // Gras et italique
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);

    // Liens
    $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank">$1</a>', $text);

    // Images
    $text = preg_replace('/!\[([^\]]*)\]\(([^)]+)\)/', '<img src="$2" alt="$1" style="max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0;">', $text);

    // Listes
    $text = preg_replace('/^- (.*$)/m', '<li>$1</li>', $text);
    $text = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $text);

    // Paragraphes
    $text = nl2br($text);

    return $text;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $article ? htmlspecialchars($article['title']) . ' - ' : '' ?>Charlotte Goupil</title>

    <?php if ($article): ?>
        <meta name="description" content="<?= htmlspecialchars(strip_tags($article['excerpt'] ?: substr($article['content'], 0, 160))) ?>">
    <?php endif; ?>

    <!-- Styles existants -->
    <link rel="stylesheet" href="header.min.css">
    <link rel="stylesheet" href="style.min.css">
    <link rel="stylesheet" href="footer.min.css">

    <style>
        /* Structure pour sticky footer */
        html {
            height: 100%;
        }

        body {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .article-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .article-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #667eea;
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
        }

        .article-title {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .article-meta {
            color: #7f8c8d;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .article-featured-image {
            width: 100%;
            max-width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .article-content {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            line-height: 1.8;
            font-size: 1.1rem;
            color: #2c3e50;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3 {
            color: #2c3e50;
            margin: 2rem 0 1rem 0;
            line-height: 1.3;
        }

        .article-content h1 {
            font-size: 2rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }

        .article-content h2 {
            font-size: 1.5rem;
            color: #667eea;
        }

        .article-content h3 {
            font-size: 1.2rem;
            color: #764ba2;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .article-content em {
            color: #5d6d7e;
            font-style: italic;
        }

        .article-content a {
            color: #667eea;
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: border-color 0.3s ease;
        }

        .article-content a:hover {
            border-bottom-color: #667eea;
        }

        .article-content ul {
            margin: 1.5rem 0;
            padding-left: 2rem;
        }

        .article-content li {
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .article-footer {
            text-align: center;
            margin: 3rem 0;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .share-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .share-btn {
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        .share-btn:hover {
            opacity: 0.9;
        }

        .share-btn.facebook {
            background: #3b5998;
        }

        .share-btn.twitter {
            background: #1da1f2;
        }

        .share-btn.linkedin {
            background: #0077b5;
        }

        .error-message {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            margin: 2rem 0;
        }

        .error-message h2 {
            margin-bottom: 1rem;
            color: #c53030;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .article-container {
                padding: 0 1rem;
            }

            .article-title {
                font-size: 2rem;
            }

            .article-content {
                padding: 2rem 1.5rem;
            }

            .share-buttons {
                flex-wrap: wrap;
            }
        }

        /* Intégration avec le design existant */
        main {
            min-height: calc(100vh - 200px);
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="article-container">
            <a href="blog.php" class="back-link">
                ← Retour au blog
            </a>

            <?php if ($error): ?>
                <div class="error-message">
                    <h2>Article introuvable</h2>
                    <p><?= htmlspecialchars($error) ?></p>
                    <a href="blog.php">Retour au blog</a>
                </div>
            <?php else: ?>
                <article>
                    <header class="article-header">
                        <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
                        <div class="article-meta">
                            Publié le <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                            <?php if ($article['updated_at'] && $article['updated_at'] !== $article['created_at']): ?>
                                • Modifié le <?= date('d/m/Y', strtotime($article['updated_at'])) ?>
                            <?php endif; ?>
                        </div>
                    </header>

                    <?php if (!empty($article['featured_image'])): ?>
                        <img src="<?= htmlspecialchars($article['featured_image']) ?>"
                            alt="<?= htmlspecialchars($article['title']) ?>"
                            class="article-featured-image">
                    <?php endif; ?>

                    <div class="article-content">
                        <?= simpleMarkdown($article['content']) ?>
                    </div>
                </article>

                <div class="article-footer">
                    <h3>Partager cet article</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>"
                            target="_blank" class="share-btn facebook">Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($article['title']) ?>"
                            target="_blank" class="share-btn twitter">Twitter</a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>"
                            target="_blank" class="share-btn linkedin">LinkedIn</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="js/script.min.js"></script>
</body>

</html>