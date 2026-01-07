<?php
// Démarrer la session pour vérifier les droits admin
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Générer un nonce unique pour les scripts inline
$nonce = base64_encode(random_bytes(16));

// Détecter si on est en localhost pour adapter les headers de sécurité
$is_localhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1']) ||
  strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost:') === 0;

// En-têtes de sécurité adaptés pour localhost ou production
if (!headers_sent()) {
  if ($is_localhost) {
    // Configuration allégée pour localhost (développement)
    header(
      "Content-Security-Policy: " .
        "default-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://ajax.googleapis.com https://stackpath.bootstrapcdn.com https://code.jquery.com; " .
        "style-src 'self' 'unsafe-inline' https://stackpath.bootstrapcdn.com https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
        "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
        "img-src 'self' data: https:; " .
        "frame-src 'self' https://www.youtube.com https://player.vimeo.com; " .
        "connect-src 'self';"
    );
  } else {
    // Configuration stricte pour production
    header(
      "Content-Security-Policy: " .
        "upgrade-insecure-requests; " .
        "default-src 'self'; " .
        "script-src 'self' 'nonce-{$nonce}' https://ajax.googleapis.com https://stackpath.bootstrapcdn.com https://charlottegoupil.fr; " .
        "style-src 'self' https://stackpath.bootstrapcdn.com https://fonts.googleapis.com https://cdn.jsdelivr.net 'unsafe-inline'; " .
        "font-src https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
        "img-src 'self' https://i.vimeocdn.com https://i.ytimg.com https:; " .
        "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://*.vimeo.com; " .
        "connect-src 'self' https://*.vimeo.com https://*.vimeocdn.com; " .
        "object-src 'none'; " .
        "base-uri 'none';"
    );
  }
}
?>

<header class="header">
  <a href="index.php">
    <h1 class="main-title"> Les <span class="green">m</span>ultiples de <span class="red">Charlotte</span></h1>
  </a>
  <nav>
    <div class="parent-container">
      <div class="burger" id="burger">
        <div class="burger-line"></div>
        <div class="burger-line"></div>
        <div class="burger-line"></div>
      </div>
    </div>
    <ul id="menuderoulant" class="menu">
      <li>
        <a href="arts.php">Arts</a>
        <ul class="sousmenu">
          <li><a href="arts.php#titreSpectacles">Spectacles</a></li>
          <li><a href="arts.php#titreMusique">Musique</a></li>
          <li><a href="arts.php#titreEcriture">Écriture</a></li>
        </ul>
      </li>
      <li><a class="ColoRed" href="mediation.php">Médiation</a></li>
      <li><a href="blog.php">Blog</a></li>
      <li><a class="ColoRed" href="links.php">Liens amis</a></li>
    </ul>
  </nav>
</header>