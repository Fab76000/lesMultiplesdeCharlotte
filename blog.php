<?php
require_once __DIR__ . '/php/db-config.php';
require_once __DIR__ . '/php/blog-functions.php';
require_once __DIR__ . '/vendor/autoload.php';

// Initialiser Parsedown pour le Markdown
$Parsedown = new Parsedown();

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
        SELECT id, title, content, excerpt, featured_image, featured_image_height, created_at 
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

// Fonction pour extraire les premiers mots d'un texte
function getExcerpt($content, $length = 150) {
    $text = strip_tags($content);
    $text = stripMarkdown($text);
    return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
}

// Inclure le header AVANT le HTML pour les headers de sécurité
include 'header.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Charlotte Goupil</title>
    <meta name="description" content="Découvrez les réflexions et actualités de Charlotte Goupil, chanteuse, comédienne et médiatrice culturelle.">

    <!-- Styles existants du site -->
    <?php
    $timestamp = time();
    $css_files = ['style', 'header', 'blog-style', 'footer'];
    $isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
    $basePath = $isLocal ? '' : '/';

    foreach ($css_files as $file) {
        echo '<link rel="stylesheet" type="text/css" href="' . $basePath . $file . '.min.css?v=' . $timestamp . '">';
    }
    ?>
</head>

<body>
    <main class="blog-container" style="flex: 1; padding-top: 2rem;">
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
                        <?php
                        $imageHeight = !empty($article['featured_image_height']) ? (int)$article['featured_image_height'] : 300;
                        ?>
                        <img src="<?= htmlspecialchars($article['featured_image']) ?>"
                            alt="<?= htmlspecialchars($article['title']) ?>"
                            class="blog-article-image"
                            style="max-height: <?= $imageHeight ?>px; width: 100%; height: auto; object-fit: contain; display: block;">
                    <?php endif; ?>

                    <div class="article-date">
                        Publié le <?= date('j F Y', strtotime($article['created_at'])) ?>
                    </div>

                    <div class="article-body">
                        <?= $Parsedown->text($article['content']) ?>
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
                                <?php
                                $imageHeight = !empty($article['featured_image_height']) ? (int)$article['featured_image_height'] : 300;
                                ?>
                                <img src="<?= htmlspecialchars($article['featured_image']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>"
                                    class="blog-article-image"
                                    style="max-height: <?= $imageHeight ?>px; width: 100%; height: auto; object-fit: contain; display: block;">
                            <?php else: ?>
                                <div class="blog-article-image">
                                    ✍️
                                </div>
                            <?php endif; ?> <div class="article-content">
                                <div class="article-date">
                                    <?= date('j F Y', strtotime($article['created_at'])) ?>
                                </div>

                                <h2 class="article-title">
                                    <a href="blog.php?article=<?= $article['id'] ?>">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </h2>

                                <div class="article-excerpt">
                                    <?php
                                    $excerpt_text = $article['excerpt'] ?: getExcerpt($article['content']);
                                    // Nettoyer les symboles markdown du résumé
                                    $excerpt_text = stripMarkdown($excerpt_text);
                                    echo htmlspecialchars($excerpt_text);
                                    ?>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/script.min.js" defer></script>
</body>

</html>