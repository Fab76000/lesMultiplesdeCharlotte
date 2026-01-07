<?php
require_once __DIR__ . '/php/db-config.php';

// Vérifier qu'un ID d'article est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    if (!headers_sent()) {
        header('Location: blog.php');
    }
    exit;
}

$article_id = intval($_GET['id']);

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer l'article
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND status = 'published'");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        if (!headers_sent()) {
            header('Location: blog.php');
        }
        exit;
    }

    // Récupérer l'article précédent et suivant
    $stmt = $pdo->prepare("
        SELECT id, title FROM articles 
        WHERE status = 'published' AND created_at > ? 
        ORDER BY created_at ASC 
        LIMIT 1
    ");
    $stmt->execute([$article['created_at']]);
    $next_article = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT id, title FROM articles 
        WHERE status = 'published' AND created_at < ? 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$article['created_at']]);
    $prev_article = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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
    $text = preg_replace('/!\[(.+?)\]\((.+?)\)/', '<img src="$2" alt="$1" class="blog-article-image">', $text);

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

// Inclure le header AVANT le HTML pour les headers de sécurité
include 'header.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - Charlotte Goupil</title>
    <meta name="description" content="<?= htmlspecialchars($article['excerpt'] ?: substr(strip_tags($article['content']), 0, 150)) ?>">

    <!-- Styles existants du site -->
    <?php
    $timestamp = time();
    $css_files = ['style', 'header', 'blog-style', 'footer'];
    $isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
    $basePath = $isLocal ? '' : '/';

    foreach ($css_files as $file) {
        if ($file === 'blog-style') {
            echo '<link rel="stylesheet" type="text/css" href="' . $basePath . $file . '.css?v=' . $timestamp . '">';
        } else {
            echo '<link rel="stylesheet" type="text/css" href="' . $basePath . $file . '.min.css?v=' . $timestamp . '">';
        }
    }
    ?>
</head>

<body>
    <main class="blog-container">
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
            <div style="text-align: center;">
                <a href="blog.php" class="back-to-blog">← Retour au blog</a>
            </div>
        <?php else: ?>
            <a href="blog.php" class="back-to-blog">← Retour au blog</a>

            <article class="full-article">
                <?php if ($article['featured_image']): ?>
                    <img src="<?= htmlspecialchars($article['featured_image']) ?>"
                        alt="<?= htmlspecialchars($article['title']) ?>"
                        class="blog-article-image">
                <?php endif; ?>

                <div class="article-date">
                    Publié le <?= date('j F Y', strtotime($article['created_at'])) ?>
                </div>

                <div class="article-body">
                    <?= simpleMarkdownToHtml($article['content']) ?>
                </div>
            </article>

            <!-- Navigation entre articles -->
            <nav style="display: flex; justify-content: space-between; margin: 2rem 0; flex-wrap: wrap; gap: 1rem;">
                <?php if ($prev_article): ?>
                    <a href="article.php?id=<?= $prev_article['id'] ?>" class="back-to-blog">
                        ← <?= htmlspecialchars($prev_article['title']) ?>
                    </a>
                <?php else: ?>
                    <div></div>
                <?php endif; ?>

                <?php if ($next_article): ?>
                    <a href="article.php?id=<?= $next_article['id'] ?>" class="back-to-blog">
                        <?= htmlspecialchars($next_article['title']) ?> →
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
    <script src="js/script.min.js" defer></script>
</body>

</html>