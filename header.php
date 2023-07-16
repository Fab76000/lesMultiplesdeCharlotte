
<header id="header">
  <a href="Index.php">
    <h1> Les <span class="green">m</span>ultiples de <span class="red">Charlotte</span></h1>
  </a>
  <hr>
  <nav>
    <div id="burger" class="burger">
      <div class="burger-line"></div>
      <div class="burger-line"></div>
      <div class="burger-line"></div>
    </div>
    <ul id="menuderoulant" class="menu">
      <li>
        <a href="Arts.php">Arts</a>
        <ul class="sousmenu">
          <li><a href="Arts.php">Spectacles</a></li>
          <li><a href="Arts.php#Musique">Musique</a></li>
          <li><a href="Arts.php#Ecriture">Ecriture</a></li>
        </ul>
      </li>
      <li><a class="ColoRed" href="Mediation.php">Médiation</a></li>
      <li><a href="#">Liens amis</a></li>
    </ul>
  </nav>
</header>


<script>document.addEventListener("DOMContentLoaded", function () {
    var burger = document.getElementById("burger");
    var menu = document.getElementById("menuderoulant");

    burger.addEventListener("click", function () {
      menu.classList.toggle("collapsed");
      burger.classList.toggle("cross");
    });
  });</script>


