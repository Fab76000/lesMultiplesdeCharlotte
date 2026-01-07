<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médiation Artistique & Culturelle | Charlotte Goupil - Ateliers d'écriture et création</title>
    <meta name="description" content="Découvrez les ateliers de médiation artistique et culturelle de Charlotte Goupil. Écriture, théâtre, arts plastiques pour tous publics. Accompagnement bienveillant et créatif en Normandie et région parisienne.">
    <link rel="stylesheet" href="bootstrap.min.css">
    <?php
    $timestamp = time(); // Plus fiable que date pour éviter le cache
    $css_files = ['style', 'header', 'mediation', 'footer'];
    // Détection automatique de l'environnement
    $isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
    $basePath = $isLocal ? '' : '/';

    // Chargement direct des feuilles de style
    foreach ($css_files as $file) {
        echo '<link rel="stylesheet" type="text/css" href="' . $basePath . $file . '.min.css?v=' . $timestamp . '">';
    }
    ?>
    <link href='https://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet' preload>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <main>
        <section class="mediation">
            <h2 class="mediation-h2">Médiation</h2>
            <p class="alinea">Médiatrice Artistique & Culturelle, artiste pluridisciplinaire travaillant principalement sur
                les régions Nord (Paris et Bruxelles compris) et Ouest (Normandie et Bretagne) ; je vous propose des ateliers d’écritures plurielles
                (textes, théâtres, plastiques) par un accompagnement doux et coloré dans votre création
                personnelle.
                Pour une (re)découverte de l’identité créatrice de chacun, j’adapte ma démarche
                aux différents publics et à leurs demandes : <br><br>
            <ul>
                <li>Une ambiance chaleureuse et un dialogue bienveillant, interculturel et intergénérationnel</li>
                <li>Un mieux-être et une sensibilisation par l’art à la dynamique de groupe</li>
            </ul>
            <p class="alinea">Au travers de mes diverses expériences et stages de formation visant à trouver ma voie
                professionnelle via la pratique artistique, j’ai eu la chance de découvrir le.s métier.s de médiatrice
                culturelle (en galerie d'art, musée, parcs patrimoniaux…) puis de médiatrice artistique en relation
                d’aide.<br>
                Distinguons tout d’abord cette dernière, la médiation artistique « en relation d’aide », de la médiation
                culturelle car celle-ci peut permettre un accompagnement thérapeutique des personnes et /ou des
                groupes.<br> Cependant, ayant traversé de front les deux pratiques, je dirais que j’ai pu me forger une
                expérience de formation et professionnelle peu commune, qui m’ont amené à presque être ma propre
                créatrice de mon métier.<br>
                Ainsi, pour repréciser en conclusion : la médiation artistique en relation d’aide peut amplement, elle
                aussi,
                tout comme la médiation culturelle, être à visée sociale par sa démarche, favorisant l’intégration dans la
                société des personnes en difficulté. En plus d’une visée sociale, la médiation artistique a en commun
                avec la médiation culturelle un rôle
                pédagogique : comme cette dernière, elle a
                recours à l’apprentissage informel, puisqu’elle
                utilise la méthode de l’apprentissage par
                expérience.<br></p>
            <p class="alinea">
                Depuis novembre 2018, je suis certifiée de la
                formation professionnelle de l’Inécat (Paris) en
                tant que médiatrice artistique en relation
                d’aide, titre au RNCP, Niveau II.
                Illustrations de la diversité des médiums que je
                propose aux publics de tous genres, capacités,
                âges et horizons : écriture, théâtre, chant,
                conte…</p>
            <div class="grid-wrap">
                <div class="grid-items">
                    <div class="grid-item">
                        <figure>
                            <picture>
                                <source srcset="images/moqueurs-faiseurs.webp" type="image/webp">
                                <img class="img-moqueurs-faiseurs" src="images/moqueurs-faiseurs.jpg" alt="Illustration de moqueurs-faiseurs" loading="lazy">
                            </picture>
                            <figcaption class="figcaption-moqueurs-faiseurs">© LN Devique/Charlotte Goupil</figcaption>
                        </figure>
                    </div>
                </div>
            </div>

        </section>
        <div class="grid-wrap">
            <div class="grid-items">
                <div class="grid-item">
                    <figure>
                        <picture>
                            <source srcset="images/mediation-PierreOlingue-23300.webp" type="image/webp">
                            <img src="images/mediation-PierreOlingue-23300.jpg" width="684" height="454" alt="" loading="lazy">
                        </picture>
                        <figcaption>© Pierre Olingue</figcaption>
                    </figure>
                </div>
                <div class="grid-item">
                    <figure>
                        <picture>
                            <source srcset="images/mediation-ThierryDujardin-2510.webp" type="image/webp">
                            <img src="images/mediation-ThierryDujardin-2510.jpg" width="684" height="454" alt="" loading="lazy">
                        </picture>
                        <figcaption>© Thierry Dujardin</figcaption>
                    </figure>
                </div>
                <div class="grid-item">
                    <figure>
                        <picture>
                            <source srcset="images/mediation-ThierryDujardin-2520.webp" type="image/webp">
                            <img src="images/mediation-ThierryDujardin-2520.jpg" width="453" height="302" alt="" loading="lazy">
                        </picture>
                        <figcaption>© Thierry Dujardin</figcaption>
                    </figure>
                </div>
                <div class="grid-item">
                    <figure>
                        <picture>
                            <source srcset="images/mediation-MathieuOlingue-MarionMotte-7294.webp" type="image/webp">
                            <img src="images/mediation-MathieuOlingue-MarionMotte-7294.jpg" width="453" height="302" alt="" loading="lazy">
                        </picture>
                        <figcaption>© Mathieu Olingue</figcaption>
                    </figure>
                </div>
                <div class="grid-item">
                    <figure>
                        <picture>
                            <source srcset="images/mediation-ThierryDujardin-2525.webp" type="image/webp">
                            <img src="images/mediation-ThierryDujardin-2525.jpeg" width="453" height="302" alt="" loading="lazy">
                        </picture>
                        <figcaption>© Thierry Dujardin</figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </main>
    <?php include_once 'footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js" defer></script>
    <script src="js/script.min.js" defer></script>
</body>

</html>