<?php

/**
 * Fonctions utilitaires pour le blog
 */

/**
 * Génère un slug URL-friendly à partir d'un titre
 * @param string $title Le titre de l'article
 * @return string Le slug généré
 */
function generateSlug($title) {
    // Conversion des caractères accentués
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $title);

    // Suppression des caractères spéciaux (garde lettres, chiffres, espaces)
    $slug = preg_replace('/[^a-zA-Z0-9\s]/', '', $slug);

    // Remplacement des espaces par des tirets
    $slug = preg_replace('/\s+/', '-', trim($slug));

    // Conversion en minuscules
    return strtolower($slug);
}

/**
 * Génère un excerpt à partir du contenu complet
 * @param string $content Le contenu complet de l'article
 * @param int $length Longueur maximum de l'excerpt (défaut: 200)
 * @return string L'excerpt généré
 */
function generateExcerpt($content, $length = 200) {
    // Suppression des balises HTML
    $text = strip_tags($content);

    // Troncature propre (coupe au mot entier)
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $lastSpace = strrpos($text, ' ');
        if ($lastSpace !== false) {
            $text = substr($text, 0, $lastSpace);
        }
        $text .= '...';
    }

    return $text;
}

/**
 * Vérifie si un slug existe déjà en base
 * @param string $slug Le slug à vérifier
 * @param int $excludeId ID de l'article à exclure (pour modification)
 * @return bool True si le slug existe déjà
 */
function slugExists($slug, $excludeId = null) {
    global $pdo; // Connexion base de données

    $sql = "SELECT id FROM articles WHERE slug = :slug";
    $params = [':slug' => $slug];

    if ($excludeId) {
        $sql .= " AND id != :exclude_id";
        $params[':exclude_id'] = $excludeId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch() !== false;
}

/**
 * Génère un slug unique (ajoute un numéro si déjà existant)
 * @param string $title Le titre de l'article
 * @param int $excludeId ID de l'article à exclure (pour modification)
 * @return string Le slug unique
 */
function generateUniqueSlug($title, $excludeId = null) {
    $baseSlug = generateSlug($title);
    $slug = $baseSlug;
    $counter = 1;

    // Si le slug existe déjà, ajouter un numéro
    while (slugExists($slug, $excludeId)) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

/**
 * Validation et nettoyage des données d'article
 * @param array $data Données du formulaire
 * @return array Données nettoyées et validées
 */
function validateArticleData($data) {
    $cleaned = [];

    // Titre (obligatoire)
    $cleaned['title'] = trim(htmlspecialchars($data['title'] ?? ''));
    if (empty($cleaned['title'])) {
        throw new InvalidArgumentException('Le titre est obligatoire');
    }

    // Contenu (obligatoire)
    $cleaned['content'] = trim($data['content'] ?? '');
    if (empty($cleaned['content'])) {
        throw new InvalidArgumentException('Le contenu est obligatoire');
    }

    // Excerpt (optionnel, généré automatiquement si vide)
    $cleaned['excerpt'] = trim($data['excerpt'] ?? '');
    if (empty($cleaned['excerpt'])) {
        $cleaned['excerpt'] = generateExcerpt($cleaned['content']);
    }

    // Status (draft par défaut)
    $cleaned['status'] = in_array($data['status'] ?? '', ['draft', 'published'])
        ? $data['status']
        : 'draft';

    // Métadonnées SEO
    $cleaned['meta_description'] = trim(htmlspecialchars($data['meta_description'] ?? ''));
    if (empty($cleaned['meta_description'])) {
        $cleaned['meta_description'] = $cleaned['excerpt'];
    }

    // Tags (optionnel)
    $cleaned['tags'] = trim(htmlspecialchars($data['tags'] ?? ''));

    // Auteur
    $cleaned['author'] = trim(htmlspecialchars($data['author'] ?? 'Charlotte Goupil'));

    return $cleaned;
}

/**
 * Test de la fonction generateSlug
 */
function testGenerateSlug() {
    $tests = [
        "Charlotte présente son nouveau spectacle à Rouen !" => "charlotte-presente-son-nouveau-spectacle-a-rouen",
        "Atelier d'écriture : les émotions en mots" => "atelier-d-ecriture-les-emotions-en-mots",
        "Festival Chants d'Elles 2025 - Bilan" => "festival-chants-d-elles-2025-bilan",
        "Médiation artistique & thérapie" => "mediation-artistique-therapie"
    ];

    echo "<h3>Tests de génération de slugs :</h3>\n";
    foreach ($tests as $title => $expected) {
        $result = generateSlug($title);
        $status = ($result === $expected) ? "✅" : "❌";
        echo "<p>{$status} <strong>{$title}</strong><br>Résultat: <code>{$result}</code></p>\n";
    }
}
