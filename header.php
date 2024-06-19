
<header id="header">
  <a href="Index.php">
    <h1> Les <span class="green">m</span>ultiples de <span class="red">Charlotte</span></h1>
  </a>
  <hr>
  
</header>


<script>document.addEventListener("DOMContentLoaded", function () {
    var burger = document.getElementById("burger");
    var menu = document.getElementById("menuderoulant");

    burger.addEventListener("click", function () {
      menu.classList.toggle("collapsed");
      burger.classList.toggle("cross");
    });
  });
  </script>


