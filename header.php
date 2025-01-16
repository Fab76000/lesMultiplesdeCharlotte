<?php
$nonce = base64_encode(random_bytes(16));

header(
  "Content-Security-Policy: " .
    "upgrade-insecure-requests; " .
    "default-src 'self'; " .
    "script-src 'self' 'nonce-{$nonce}' https://ajax.googleapis.com https://stackpath.bootstrapcdn.com; " .
    "style-src 'self' https://stackpath.bootstrapcdn.com https://fonts.googleapis.com 'unsafe-inline'; " .
    "font-src https://fonts.gstatic.com; " .
    "img-src 'self' https://i.vimeocdn.com https://i.ytimg.com https:; " .
    "frame-src 'self' https://www.youtube.com https://player.vimeo.com; " .
    "frame-src 'self' https://player.vimeo.com https://*.vimeo.com;" .
    "connect-src 'self' https://*.vimeo.com https://*.vimeocdn.com;" .
    "object-src 'none'; " .
    "base-uri 'none';"
);
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
      <li><a href="links.php">Liens amis</a></li>
    </ul>
  </nav>
</header>
<script nonce="<?php echo $nonce; ?>">
  document.addEventListener("DOMContentLoaded", function() {
    var burger = document.getElementById("burger");
    var menu = document.getElementById("menuderoulant");
    burger.addEventListener("click", function() {
      menu.classList.toggle("collapsed");
      burger.classList.toggle("cross");
    });
  });
</script>