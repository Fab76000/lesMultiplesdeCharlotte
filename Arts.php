<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spectacles de Charlotte Goupil | Chanson, Slam et Lecture théâtralisée</title>
    <meta name="description" content="Découvrez les spectacles de Charlotte Goupil : 'Autour des Elles d&#39;Allain', hommage à Leprest, 'VIVENTRE', création slam, et 'L&#39;Argent', lecture théâtralisée. Art pluridisciplinaire en Normandie.">
    <?php
    $date = date("Y-m-d-h-i-s");
    echo '<link rel="stylesheet" type="text/css" href="style.min.css?uid=' . $date . '" rel="preload" as="style" defer>';
    echo '<link rel="stylesheet" type="text/css" href="header.min.css?uid=' . $date . '"  rel="preload"  as="style" defer>';
    echo '<link rel="stylesheet" type="text/css" href="footer.min.css?uid=' . $date . '"  rel="preload"  as="style" defer>';
    echo '<link rel="stylesheet" type="text/css" href="arts.min.css?uid=' . $date . '"  rel="preload" as="style" defer>';
    ?>
    <link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet" defer preload>
</head>

<body>
    <span id="titreSpectacles"></span>

    <?php include_once 'header.php'; ?>
    <main>
        <h2 class="Arts-h2">Arts</h2>
        <h2 class="spectacles-h2">Spectacles</h2>
        <span id="Spectacles"></span>
        <section id="Bulles">
            <figure id="Chanoe" class="photo">
                <picture>
                    <source srcset="images/couverture_album_Les_elles_d_alain.webp" type="image/webp">
                    <img src="images/couverture_album_Les_elles_d_alain.jpg" width="460"
                        height="460" alt="Couverture de l'album 'Les Elles d'Alain'" loading="lazy">
                </picture>
                <figcaption>© LN Devique</figcaption>
                <div class="text-overlay"></div>
                <div class="not-authorized-overlay">
                    <div class="text">
                        <h2>AUTOUR DES ELLES D'ALLAIN</h2>
                        <p>Autour des Elles d'Allain prend la forme d'un réel spectacle où chant,
                            jeu et interprétation pianistique dialoguent.
                        </p>
                    </div>
                    <div class="containerArts">
                        <a href="#allain">
                            <span class="span-allain">En savoir plus</span>
                        </a>
                    </div>
                </div>
            </figure>
            <figure id="Charlotte" class="photo">
                <picture>
                    <source srcset="images/Viventre.webp" type="image/webp">
                    <img src="images/Viventre.jpg" width="460"
                        height="460" alt="" loading="lazy">
                </picture>
                <figcaption>© Angélique Gourié</figcaption>
                <div class="text-overlay">
                </div>
                <div class="not-authorized-overlay">
                    <div class="text">
                        <h2>VIVENTRE</h2>
                        <p>Sur des textes personnels évoquant l'art, les rencontres et les luttes,
                            VIVENTRE est une création sonore, visuelle et textuelle.<br>
                        </p>
                    </div>
                    <div class="containerArts">
                        <a href="#viventre">
                            <span class="span-viv">En savoir plus</span>
                        </a>
                    </div>
                </div>
            </figure>
            <figure id="Larchotte" class="photo">
                <picture>
                    <source srcset="images/Charlotte_voile_rose.webp" type="image/webp">
                    <img src="images/Charlotte_voile_rose.jpg" width="460"
                        height="460" alt="Portrait de Charlotte avec un voile rose" loading="lazy">
                </picture>
                <figcaption>© Paris-Normandie</figcaption>
                <div class="text-overlay">
                </div>
                <div class="not-authorized-overlay">
                    <div class="text">
                        <h2>L'ARGENT</h2>
                        <p>Lecture théâtralisée d'après une nouvelle de Marie Desplechin sur le thème de "l'Argent,
                            la maille, le flouze, la tune, le blé...
                        </p>
                    </div>
                    <div class="containerArts">
                        <a href="#Argent">
                            <span>En savoir plus </span>
                        </a>
                    </div>
                </div>
            </figure>
        </section>
        <section id="Arts" class="container">
            <span id="allain"></span>
            <article id="Allain" class="spectacles">
                <h2>Autour des Elles d'Allain</h2>
                <h3>Hommage à Allain Leprest</h3>
                <p>Après plusieurs résidences, dont la dernière prévue au printemps 2021 pour une création lumière
                    et ajouts d'éléments scéniques, Autour des <span class="Elles">Elles</span> d'Allain
                    prend dès lors la forme d'un réel spectacle où chant, jeu et interprétation pianistique
                    dialoguent.<br><br>
                    En effet, la formation de comédienne de Charlotte Goupil, son expérience de mise en scène et son oreille
                    musicale lui donnent
                    à entendre les propositions de l'improvisateur de jazz qu'est Alexandre Rasse
                    comme de justes illustrations des émotions écrites par Allain Leprest,
                    et tendent à faire renaître les personnages hauts en couleur qu'évoquent ses textes.<br><br>
                    Ainsi,après plusieurs années, le duo choisit à présent d'affiner son répertoire à travers une trentaine
                    de titres,
                    toujours à propos des figures et thèmes féminin de Leprest, agrémentant le tout par quelques titres de
                    création d'auteur.e.s normand.e.s,
                    le lien étant fait par le compositeur, Etienne Goupil, un des musiciens des premières heures du regretté
                    chanteur-poète...
                    d'où "Autour des Elles"
                <p style="margin-bottom: -100px;"><a style="color:var(--red-color);" href="http://morglaz.over-blog.com/2018/12/autour-des-elles-d-allain-charlotte-goupil-au-paris.html">Article sur Autour des Elles d'Allain</a></p>
                <a href="#Bulles" style="display:flex; justify-content: end; margin-top:150px;"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16" alt="Flèche retour vers le haut de la page">
                        <path fill-rule=" evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                    </svg></a>
            </article>
            <span id="viventre"></span>
            <article id="Viventre" class="spectacles">
                <h2>VIVENTRE</h2>
                <p>Sur des textes personnels évoquant l'art, les rencontres et les luttes, VIVENTRE est une création sonore,
                    visuelle et textuelle.
                    Pièce pluridisciplinaire (un Slam'dit) toujours en mouvement, parfois en image, où la musique joue un
                    rôle d'évocation flâneuse,
                    tout autant que les mots.
                </p>
                <p> Dispositif de vidéo pour projection d'oeuvres contemporaines pendant le récital slamé.
                    Revue de presse et médias :
                    Retours de spectateur.ice.s : <br>
                    <span style="font-style: italic;"> "Merci pour cette création, ma curiosité a été comblée. J'ai apprécié la subtilité de votre poésie, sa
                        musicalité : les mots, jeux de mots et tout l'ensemble. Bravo. Bonne continuation pour vos futurs
                        projets 2022" </span> <br>
                    <strong>Pour commander votre EP, c'est via larchotterenard@protonmail.com !</strong>
                </p>
                <a href="#Bulles" style="display:flex; justify-content: end; margin-top:200px;"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                        <path fill-rule=" evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                    </svg></a>
            </article>
            <section class="mosaique">
                <div>
                    <a href="images/Viventre-19.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Viventre-petite-19.webp" type="image/webp">
                                <img src="images/Viventre-petite-19.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Cédric Dominas</figcaption>
                        </figure>
                    </a>
                </div>
                <div>
                    <a href="images/Viventre-22.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Viventre-petite-22.webp" type="image/webp">
                                <img src="images/Viventre-22.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Cédric Dominas</figcaption>
                        </figure>
                    </a>
                </div>
                <div>
                    <a href="images/Viventre-25.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Viventre-25.webp" type="image/webp">
                                <img src="images/Viventre-petite-25.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Cédric Dominas</figcaption>
                        </figure>
                    </a>
                </div>
                <div>
                    <a href="images/Viventre-31.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Viventre-petite-31.webp" type="image/webp">
                                <img src="images/Viventre-petite-31.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Cédric Dominas</figcaption>
                        </figure>
                    </a>
                </div>
                <div>
                    <a href="images/Viventre-36.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Viventre-petite-36.webp" type="image/webp">
                                <img src="images/Viventre-petite-36.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Cédric Dominas</figcaption>
                        </figure>
                    </a>
                </div>
            </section>
            <article id="Argent" class="spectacles">
                <h2>L'Argent</h2>
                <p>Lecture théâtralisée d'après une nouvelle de Marie Desplechin sur le thème de "l'Argent", la maille, le
                    flouze, la tune, le blé...
                    De quelques bas de laine, du troc, du vol, des gros sous, ou encore de la manche,
                    chacun des membres d'une même famille vont vous livrer tout à tour leur rapport
                    au nerf de la guerre de notre époque.<br>
                    Un moyen, une nécessité ou un but ?...<br><br>
                    Nous allons suivre, du fait de leur invitation au mariage de la jeune Virginie, le discours de quatre
                    grandes personnes, frères et soeurs
                    (Edward, Bonnie, Franz et Sylvia), et de leurs enfants (Dimitri, Ernesto, Virginie et Fortunée) à propos
                    de leur relation, de fait, à cause ou malgré...l'argent.
                </p>
                <p>Pour voir la captation du spectacle : </p>
                <p><iframe class="iframe-argent" width="350" height="350" src="https://www.youtube.com/embed/4QuITiqTIxk" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe></p>
                <a href="#Bulles" style="display:flex; justify-content: end; margin-top:100px;"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16" alt="Flèche retour vers le haut de la page">
                        <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                    </svg></a>
            </article>
            <section class="mosaique">
                <div>
                    <a href="images/2019_L'argent.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/2019_L'argent_petite.webp" type="image/webp">
                                <img src="images/2019_L'argent_petite.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Collectif 'Et maintenant (?)'</figcaption>
                        </figure>
                    </a>
                </div>
                <div>
                    <a href="images/Argent_4123.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Argent_4123_petite.webp" type="image/webp">
                                <img src="images/Argent_4123_petite.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Collectif 'Et maintenant (?)'</figcaption>
                        </figure>
                    </a>
                </div>
                <div>
                    <a href="images/Argent_4150.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Argent_4150_petite.webp" type="image/webp">
                                <img src="images/Argent_4150_petite.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Collectif 'Et maintenant (?)'</figcaption>
                        </figure>
                    </a>
                </div>
                <div>
                    <a href="images/Argent_4165_.jpg">
                        <figure>
                            <picture>
                                <source srcset="images/Argent_4165_petite.webp" type="image/webp">
                                <img src="images/Argent_4165_petite.jpg" width="250" height="260" alt="" loading="lazy">
                            </picture>
                            <figcaption>© Collectif 'Et maintenant (?)'</figcaption>
                        </figure>
                    </a>
                </div>
            </section>
            <span id="titreMusique"></span>
        </section>
        <section class="musique">
            <h2 class="musique-h2">Musique</h2>
            <p>Si ce qu'on raconte de ma mythologie familiale est que ma mère changeait mes langes sur la
                scène du Bateau Ivre (ancien cabaret rouennais aujourd'hui disparu), je ne suis pas devenue musicienne professionnelle tout de suite.
                <br>Ma vocation artistique fut en effet premièrement le théâtre.
                Mais d'un père compositeur et pianiste de scène dans sa jeunesse, même si je ne sais pas
                lire la musique, j'ai sûrement reçu une oreille musicale de ce bain familial.
            </p>
            <div class="container-iframes">
                <div class="grid-container">
                    <div class="grid-item">
                        <div class="video-wrapper" data-video-id="293555303" data-provider="vimeo">
                            <picture>
                                <source srcset="https://i.vimeocdn.com/video/730231567-52dac924fec8210c3f2813075b619487eef954615ed505460005fe4dc2608ac4-d_640.webp" type="image/webp">
                                <img src="https://i.vimeocdn.com/video/730231567-52dac924fec8210c3f2813075b619487eef954615ed505460005fe4dc2608ac4-d_640.jpg" width="560" height="315" alt="" class="thumbnail" loading="lazy">
                            </picture>
                            <button aria-label="Lancer la vidéo" title="Lancer la vidéo" class="play-button">
                                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                                    <path class="st0" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                        fill="#f00"></path>
                                    <path d="M 45,24 27,14 27,34 Z" fill="#fff"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="video-wrapper" data-video-id="JC6vuz2HncE" data-provider="youtube">
                            <picture>
                                <source srcset="images/miniature1.webp" type="image/webp">
                                <img src="images/miniature1.jpg" width="560" height="315" alt="" class="thumbnail" loading="lazy">
                            </picture>
                            <button aria-label="Lancer la vidéo" title="Lancer la vidéo" class="play-button">
                                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                                    <path class="st0" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                        fill="#f00"></path>
                                    <path d="M 45,24 27,14 27,34 Z" fill="#fff"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="video-wrapper" data-video-id="IWingOXoCv8" data-provider="youtube">
                            <picture>
                                <source srcset="images/miniature2.webp" type="image/webp">
                                <img src="images/miniature2.jpg" width="560" height="315" alt="" class="thumbnail" loading="lazy">
                            </picture>
                            <button aria-label="Lancer la video" title="Lancer la video" class="play-button">
                                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                                    <path class="st0" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                        fill="#f00"></path>
                                    <path d="M 45,24 27,14 27,34 Z" fill="#fff"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="video-wrapper" data-video-id="VLplFrMCfwE" data-provider="youtube">
                            <picture>
                                <source srcset="images/miniature3.webp" type="image/webp">
                                <img src="images/miniature3.jpg" width="560" height="315" alt="" class="thumbnail" loading="lazy">
                            </picture>
                            <button aria-label="Lancer la video" title="Lancer la video" class="play-button">
                                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                                    <path class="st0" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                        fill="#f00"></path>
                                    <path d="M 45,24 27,14 27,34 Z" fill="#fff"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="video-wrapper" data-video-id="1ENzQan2JhU" data-provider="youtube">
                            <picture>
                                <source srcset="images/miniature4.webp" type="image/webp">
                                <img src="images/miniature4.jpg" width="560" height="315" alt="" class="thumbnail" loading="lazy">
                            </picture>
                            <button aria-label="Lancer la video" title="Lancer la video" class="play-button">
                                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                                    <path class="st0" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                        fill="#f00"></path>
                                    <path d="M 45,24 27,14 27,34 Z" fill="#fff"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="video-wrapper" data-video-id="RklwZVsxQnY" data-provider="youtube">
                            <picture>
                                <source srcset="images/miniature5.webp" type="image/webp">
                                <img src="images/miniature5.jpg" width="560" height="315" alt="" class="thumbnail" loading="lazy">
                            </picture>
                            <button aria-label="Lancer la video" title="Lancer la video" class="play-button">
                                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                                    <path class="st0" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                        fill="#f00"></path>
                                    <path d="M 45,24 27,14 27,34 Z" fill="#fff"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="video-wrapper" data-video-id="Te_6mXfvRKY" data-provider="youtube">
                            <picture>
                                <source srcset="images/miniature6.webp" type="image/webp">
                                <img src="images/miniature6.jpg" width="560" height="315" alt="" class="thumbnail" loading="lazy">
                            </picture>
                            <button aria-label="Lancer la video" title="Lancer la video" class="play-button">
                                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                                    <path class="st0" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                        fill="#f00"></path>
                                    <path d="M 45,24 27,14 27,34 Z" fill="#fff"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="container-links-p">
                    <p>Si vous souhaitez voir plus de vidéos, cliquez sur les liens suivants: </p>
                </div>
                <div class="container-links-ul">
                    <ul class="music-links">
                        <li><a href="https://vimeo.com/753899801" aria-label="Video Sur La Moustache de Georges">"Sur La Moustache de Georges"</a></li>
                        <li><a href="https://www.flickr.com/photos/festivalchantsdelles/albums/72157720181824030/" aria-label=" Video Festival chants d'Elles">Festival chants d'Elles</a></li>
                        <li><a href="https://www.youtube.com/watch?v=ctshwwMDoiA" aria-label="Video Il neige">Il neige</a></li>
                        <li><a href="https://www.youtube.com/watch?v=al5KVGetoXg" aria-label="Video Concert hommage à Allain Leprest - Bruxelles">Concert hommage à Allain Leprest - Bruxelles</a></li>
                        <li><a href="https://www.youtube.com/watch?v=mL48Xt1dUkY&t=156s" aria-label="Video Récital hONdiCAP sur Leprest">Récital hONdiCAP sur Leprest</a></li>
                        <li><a href="https://www.youtube.com/watch?v=uvk5VtPmDCw" aria-label="Video L'horloger">L'horloger</a></li>
                        <li><a href="https://www.youtube.com/watch?v=ZFGkrxvuomk" aria-label="Video 'Le sud' avec Zabou & Martial">"Le sud" avec Zabou & Martial - El Camino</a></li>
                        <li><a href="https://www.youtube.com/watch?v=eBX4y7A8gZ0" aria-label="Video Une Valse pour Rien">"Une Valse pour Rien" avec C.Goupil & R.Jéhanne</a></li>
                        <li><a href="https://www.youtube.com/watch?v=j6Lz2ASfwnM&list=PLA3VSx-wfHVkrFeWYSAJCi7zc3WgYCIp6&index=3" aria-label="Video Concert inauguration de la place Leprest">Concert inauguration de la place Leprest - Mont Saint Aignan</a></li>
                        <li><a href="https://www.youtube.com/watch?v=B6TKhf3UAos&list=PLA3VSx-wfHVkrFeWYSAJCi7zc3WgYCIp6&index=4" aria-label="Video Autour des Elles d'Allain">"Autour des Elles d'Allain" - Extraits</a></li>
                    </ul>
                </div>
            </div>
            <span id="titreEcriture"></span>
        </section>
        <section class="ecriture">
            <h2 class="ecriture-h2">Écriture</h2>
            <p>
                "Si j'écris c'est que passé, avenir et parfois présent, m'envahissent de leurs pensées abruptes et
                naissantes à chaque seconde que la Nature fait.
                Cependant, un certain Einstein dit qu'il n'est ni espace, ni temps, il n'est que mouvement.<br> Eh bien
                voilà,
                si j'écris (proses, vers en rimes, réflexion philo ou métaphy...low), parfois en musique depuis quelques
                temps, c'est pour suivre le mouvement de la pensée : l'universelle ou la mienne. <br>
                J'ai donc suivi, à l'Institut National d'Expression de Création et d'Art-Thérapie où j'ai pu valider un
                certification de médiation artistique en relation d'aide, entre 2014 et 2018, des modules autour de
                l'écriture de soi.
                Ainsi, dès 2014, je me suis permise, d'abord en milieu amical, de mener quelques ateliers d'écriture
                créative. Puis, à partir de 2016, de me professionnaliser peu à peu dans cette pratique au sein
                d'établissements culturels ou médico-sociaux.<br>
                Enfin, inspirée par la chanson dans tous les sens du terme ; celle de nos vies et celle que j'écoute sur des
                CDs, ou plus récemment sur toutes bonnes plateformes qui se respectent,
                je suis aujourd'hui en recherche constante de l'expérience des mots dans leur rencontre avec les sons, la
                musicalité d'eux-mêmes, mais aussi bien sûr de quelques notes que ma voix engendrerait …
                pour, peut-être, bientôt, écrire mes propres morceaux et de belles chansons ?"
            </p>
            <h3 class="ecrits-h3">Mes écrits</h3>
            <a href="http://dmjlarchotte.blogspot.com/?view=magazine" aria-label="Lien vers le blog de Charlotte">Mon blog</a>
            <div>
                <p class="ecritures-description">À propos de mes écritures</p>
            </div>
            <ul>
                <li><a class="aEcriture" href="https://www.facebook.com/ZingSOU/videos/atelier-d%C3%A9criture-n2-avec-charlotte-goupil%EF%B8%8Fmercredi-27-d%C3%A9cembre-19h-21hsur-inscr/1955378794477299/" aria-label="Lien vers la vidéo de l'atelier d'écriture de Charlotte n°1">Atelier
                        d'écriture avec Charlotte Goupil
                    </a>
                </li>
                <li><a href="https://leblogdudoigtdansloeil.wordpress.com/tag/charlotte-goupil/">Le blog du doigt dans
                        l'oeil</a></li>
                <li><a href="https://www.proarti.fr/collect/project/viventre-du-slamdit-1/0">Viventre du slamdit</a></li>
            </ul>
            <div>
                <p class="ecritures-description">Mes engagements à la Factorie, Maison de la poésie normande:</p>
            </div>
            <ul>
                <li><a class="aEcriture" href="https://zh-cn.facebook.com/lafactoriemaisondepoesie/videos/po%C3%A8me-%C3%A0-crier-par-les-fen%C3%AAtres-couvre-feu/513972332580916/">
                        À crier par les fenêtres</a>
                </li>
                <li><a class="aBottom" href="https://france3-regions.francetvinfo.fr/normandie/eure/louviers/culture-domicile-poesie-moyen-s-evader-confinement-1812920.html" aria-label="Lien vers l'article de France 3 Normandie">S'évader
                        du confinement avec la poésie</a>
                </li>
            </ul>
            <div>
                <p class="ecritures-description">Quelques exemples d'ateliers d'ecriture</p>
            </div>
            <ul>
                <li><a class="aEcriture" href="https://www.youtube.com/watch?v=VLplFrMCfwE" aria-label="Lien vers l'interview de Charlotte Goupil">
                        Une interview de Charlotte Goupil</a>
                </li>
                <li><a class=" aBottom" href="https://www.facebook.com/ZingSOU/videos/atelier-d%C3%A9criture-n1-avec-charlotte-goupil%EF%B8%8Fl%C3%A9nah-comslingerie-on-ne-sen-lace-jam/1954323744582804/" aria-label="Lien vers la vidéo de l'atelier d'écriture de Charlotte n°2">
                        Atelier d'écriture avec Charlotte Goupil
                    </a>
                </li>
            </ul>
        </section>
    </main>
    <?php include_once 'footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" defer></script>
    <script nonce="{random}" src="https://charlottegoupil.fr/js/script.min.js" defer></script>
    <script nonce="<?php echo $nonce; ?>">
        document.addEventListener('DOMContentLoaded', function() {
            const videoWrappers = document.querySelectorAll('.video-wrapper');

            videoWrappers.forEach(wrapper => {
                wrapper.addEventListener('click', function() {
                    const videoId = this.getAttribute('data-video-id');
                    const provider = this.getAttribute('data-provider');
                    let iframeSrc;

                    if (provider === 'youtube') {
                        iframeSrc = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                    } else if (provider === 'vimeo') {
                        iframeSrc = `https://player.vimeo.com/video/${videoId}?autoplay=1`;
                    } else {
                        console.error('Unknown provider:', provider);
                        return;
                    }
                    const iframe = document.createElement('iframe');
                    iframe.setAttribute('width', '100%');
                    iframe.setAttribute('height', '100%');
                    iframe.setAttribute('src', iframeSrc);
                    iframe.setAttribute('title', 'Video player');
                    iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                    iframe.setAttribute('allowfullscreen', true);
                    iframe.style.position = 'absolute';
                    iframe.setAttribute('loading', 'lazy');
                    this.innerHTML = '';
                    this.appendChild(iframe);
                });
            });
        });
    </script>
</body>

</html>