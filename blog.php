<?php
require_once __DIR__ . '/php/db-config.php';

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

    // Récupérer les articles publiés
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
    // Séparer en lignes pour un traitement plus propre
    $lines = explode("\n", $text);
    $result = [];
    $in_list = false;

    foreach ($lines as $line) {
        $line = trim($line);

        if (empty($line)) {
            // Ligne vide - fermer la liste si ouverte
            if ($in_list) {
                $result[] = '</ul>';
                $in_list = false;
            }
            $result[] = '<br>';
            continue;
        }

        // Titres
        if (preg_match('/^### (.+)$/', $line, $matches)) {
            if ($in_list) {
                $result[] = '</ul>';
                $in_list = false;
            }
            $result[] = '<h3>' . $matches[1] . '</h3>';
        } elseif (preg_match('/^## (.+)$/', $line, $matches)) {
            if ($in_list) {
                $result[] = '</ul>';
                $in_list = false;
            }
            $result[] = '<h2>' . $matches[1] . '</h2>';
        } elseif (preg_match('/^# (.+)$/', $line, $matches)) {
            if ($in_list) {
                $result[] = '</ul>';
                $in_list = false;
            }
            $result[] = '<h1>' . $matches[1] . '</h1>';
        }
        // Listes
        elseif (preg_match('/^- (.+)$/', $line, $matches)) {
            if (!$in_list) {
                $result[] = '<ul>';
                $in_list = true;
            }
            // Traiter le gras/italique dans les éléments de liste
            $item = $matches[1];
            $item = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $item);
            $item = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $item);
            $item = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2" target="_blank">$1</a>', $item);
            $result[] = '<li>' . $item . '</li>';
        }
        // Paragraphe normal
        else {
            if ($in_list) {
                $result[] = '</ul>';
                $in_list = false;
            }
            // Traiter le gras/italique/liens/images
            $line = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $line);
            $line = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $line);
            $line = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2" target="_blank">$1</a>', $line);
            $line = preg_replace('/!\[(.+?)\]\((.+?)\)/', '<img src="$2" alt="$1" class="blog-article-image">', $line);
            $result[] = $line;
        }
    }

    // Fermer la liste si elle est encore ouverte
    if ($in_list) {
        $result[] = '</ul>';
    }

    return implode("\n", $result);
}

// Fonction pour extraire les premiers mots d'un texte
function getExcerpt($content, $length = 150) {
    $text = strip_tags($content);
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
                            class="blog-article-image">
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
                                    class="blog-article-image">
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