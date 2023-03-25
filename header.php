
<?php
echo ' <header> <a href="Index.php">
    <h1> Les <span class="green">m</span>ultiples de <span class="red"> Charlotte</span></h1>
</a>
<hr>
<nav>
    <ul id="menuderoulant">
        <li>
            <a href="Arts.php">Arts</a>
            <ul class="sousmenu">
                <li>
                    <a href="Arts.php#spectacles">Spectacles</a>
                </li>
                <li>
                    <a href="Arts.php#Musique">Musique</a>
                </li>
                <li>
                    <a href="Arts.php#Ecriture">Ecriture</a>
                </li>
            </ul>
        </li>

        <li>
            <a class="ColoRed" href="Mediation.php">Médiation</a>
            <ul class="sousmenu">
                <li>
                    <a class="ColoGreen" href="Mediation.php#atelierArtistique"> Ateliers artistiques</a>
                </li>
                <li>
                    <a class="ColoGreen" href="#"> Médiation en relation d`\'aide</a>
                </li>
                <li>
                    <a class="ColoGreen" href="#"> Médiation culturelle</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Liens amis</a>
        </li>
    </ul>
</nav>
</header>';
?>