<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liens amis | Charlotte Goupil - Artiste et Médiatrice Culturelle</title>
    <meta name="description" content="Découvrez les partenaires et collaborateurs de Charlotte Goupil : Chants d&#39;Elles, Alexandre Rasse, Correl&#39;Arts. Explorez des liens vers des artistes inspirants et des projets culturels en Normandie.">
    <?php
    $date = date("Y-m-d-h-i-s");
    $css_files = ['style', 'header', 'links', 'footer'];

    // Chargement direct des feuilles de style
    foreach ($css_files as $file) {
        echo '<link rel="stylesheet" type="text/css" href="' . $file . '.min.css?uid=' . $date . '">';
    }
    ?>
    <link href='https://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet' preload>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <main>
        <h2 class="links-h2">Liens amis</h2>
        <section id="links" class="links gradient-border">
            <div>
                <a href="images/Charlotte_statue.jpg">
                    <figure class="corner-top-left-image">
                        <picture>
                            <source srcset="images/Charlotte_statue.webp" type="image/webp">
                            <img src="images/Charlotte_statue.jpg" width="100" height="133" alt="" loading="lazy">
                        </picture>
                        <figcaption>© Jean Marc De Pas</figcaption>
                    </figure>
                </a>
            </div>
            <p>Vous trouverez ici quelques liens amis et partenaires de Charlotte Goupil.
                <br><br>
                Ces sites vous enverront vers des sites de partenaires, collaborateur·rice·s, ami·e·s inspiré·e·s et inspirant·e·s.
            </p>
            <br><br>
            <p><strong>Chants d'Elles</strong>, festival des voix de femmes, pour qui Charlotte a été spectatrice, collaboratrice, artiste et même Présidente de l’association.
                <br><br>
                <em><a href="https://www.festivalchantsdelles.org">https://www.festivalchantsdelles.org</a></em>
            </p>
            <figure class="figure-links">
                <picture>
                    <source srcset="images/chantsdelles.webp" type="image/webp">
                    <img src="images/chantsdelles.jpg" width="100" height="133" alt="Logo de l'association Chants d'Elles" loading="lazy">
                </picture>
            </figure>
            <br>
            <p><strong>Alexandre Rasse</strong>, artiste et musicien de talent.
                Il accompagne Charlotte Goupil au piano à travers les années depuis 2016.
                <br><br>
                <em><a href="https://alexandrerasse.fr">https://alexandrerasse.fr</a></em>
            </p>
            <figure class="figure-links">
                <picture>
                    <source srcset="images/ART-Cd-couverture-Alex-Rasse.webp" type="image/webp">
                    <img src="images/ART-Cd-couverture-Alex-Rasse.jpg" width="100" height="133" alt="Couverture album de Alexandre Rasse" loading="lazy">
                </picture>
            </figure>
            <br><br>
            <p><strong>Correl'Arts</strong> est une association créée en 2015,
                née d’amitiés et de passions communes pour la musique, le partage et la transmission.
                <br><br>
                <em><a href="https://correlarts.wixsite.com/Index">https://correlarts.wixsite.com/Index</a></em>
            </p>
            <figure class="figure-links">
                <picture>
                    <source srcset="images/Corelarts.webp" type="image/webp">
                    <img src="images/Corelarts.jpg" width="100" height="133" alt="Logo de l'association Correl'Arts" loading="lazy">
                </picture>
            </figure>
            <br><br>
            <p><strong>Le blog de Léna h. Coms</strong> qui contient un article sur l’atelier d’écriture du 12 août 2018 au Dansoir – Karine Saporta (Ouistreham) sous la direction de Charlotte Goupil, médiatrice culturelle, actrice et chanteuse, lors du vernissage final de l’exposition "Déambule" de Jill Guillais, artiste plasticienne.
                <br><br>
                <em><a href="http://leternelleheureduthe.over-blog.com/2018/08/l-enfance-est-violente-restitution-d-atelier-d-ecriture.html">http://leternelleheureduthe.over-blog.com/2018/08/l-enfance-est-violente-restitution-d-atelier-d-ecriture.html</a></em>
            </p>
            <figure class="figure-links">
                <picture>
                    <source srcset="images/lenahcoms-service-a-the.webp" type="image/webp">
                    <img src="images/lenahcoms-service-a-the.jpg" width="100" height="133" alt="Logo du blog de Léna h. Coms" loading="lazy">
                </picture>
            </figure>
            <br><br>
            <p>Enfin, vous pouvez retrouver le chemin vers mes mots, là aussi &#128521 :
                <br><br>
                <strong>Dans mon jardin, </strong><em><a href="https://dmjlarchotte.blogspot.com/?view=mosaic">https://dmjlarchotte.blogspot.com/?view=mosaic</a></em>
            </p>
            <div>
                <a href="images/Charlotte_statue.jpg">
                    <figure class="corner-bottom-right-image">
                        <picture>
                            <source srcset="images/Charlotte_statue.webp" type="image/webp">
                            <img src="images/Charlotte_statue.jpg" width="100" height="133" alt="" loading="lazy">
                        </picture>
                        <figcaption>© Jean Marc De Pas</figcaption>
                    </figure>
                </a>
            </div>
        </section>
    </main>
    <?php include_once 'footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" defer></script>
</body>

</html>