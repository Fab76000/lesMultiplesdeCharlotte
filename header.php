<header id="header">
  <a href="index.php">
    <h1> Les <span class="green">m</span>ultiples de <span class="red">Charlotte</span></h1>
  </a>
  <hr>
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

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var burger = document.getElementById("burger");
    var menu = document.getElementById("menuderoulant");
    burger.addEventListener("click", function() {
      menu.classList.toggle("collapsed");
      burger.classList.toggle("cross");
    });
  });
</script>