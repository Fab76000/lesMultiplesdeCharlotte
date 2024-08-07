<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Les multiples de Charlotte Goupil/Arts </title>
    <?php
    $date = date("Y-m-d-h-i-s");
    echo '<link rel="stylesheet" type="text/css" href="stylesmq.css?uid=' . $date . '">';
    ?>
    <link href='https://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet'>
    <script src="jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <span id="titreSpectacles"></span>

    <?php include 'header.php'; ?>
    <h1 id="ArtsH1">Arts</h1>
    <h2 id="spectaclesH2">Spectacles</h2>
    <span id="Spectacles"></span>
    <section id="Bulles">
        <div id="Chanoe" class="photo">
            <picture>
                <source srcset="images/couverture_album_Les_elles_d_alain.webp" type="image/webp">
                <source srcset="images/couverture_album_Les_elles_d_alain.jpg" type="image/jpeg">
                <img src="images/couverture_album_Les_elles_d_alain.jpg" alt="Charlotte et Alex" loading="lazy">
            </picture>
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
        </div>
        </div>
        <div id="Charlotte" class="photo">
            <picture>
                <source srcset="images/Viventre.webp" type="image/webp">
                <source srcset="images/Viventre.jpg" type="image/jpeg">
                <img src="images/Viventre.jpg" alt="Viventre" loading="lazy">
            </picture>
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
        </div>
        <div id="Larchotte" class="photo">
            <picture>
                <source srcset="images/Charlotte_voile_rose.webp" type="image/webp">
                <source srcset="images/Charlotte_voile_rose.jpg" type="image/jpeg">
                <img src="images/Charlotte_voile_rose.jpg" alt="Viventre" loading="lazy">
            </picture>
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
        </div>
    </section>
    <section id="Arts" class="container">
        <span id="allain"></span>
        <article id="Allain" class="spectacles">
            <h1>Autour des Elles d'Allain</h1>
            <h2>Hommage à Allain Leprest</h2>
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
            </p>
            <a href="#spectacles" style="display:flex; justify-content: end; margin-top:150px;"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                </svg></a>
        </article>
        <span id="viventre"></span>
        <article id="Viventre" class="spectacles">
            <h1>VIVENTRE</h1>
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
            <a href="#spectacles" style="display:flex; justify-content: end; margin-top:200px;"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                </svg></a>

        </article>
        <section id="Mosaique">
            <div>
                <a href="images/Viventre-18_12_21- 19.jpg">
                    <picture>
                        <source srcset="images/Viventre-18_12_21-petite-19.webp" type="image/webp">
                        <source srcset="images/Viventre-18_12_21-petite-19.jpg" type="image/jpeg">
                        <img src="images/Viventre-18_12_21-petite-19.jpg" alt="Viventre Image Petite" loading="lazy">
                    </picture>
                </a>
            </div>
            <div>
                <a href="images/Viventre-18_12_21- 22.jpg">
                    <picture>
                        <source srcset="images/Viventre-18_12_21 - petite 22.webp" type="image/webp">
                        <source srcset="images/Viventre-18_12_21- 22.jpg" type="image/jpeg">
                        <img src="images/Viventre-18_12_21- 22.jpg" alt="Viventre Image Petite" loading="lazy">
                    </picture>
                </a>
            </div>
            <div>
                <a href="images/Viventre-18_12_21 - 25.jpg">
                    <picture>
                        <source srcset="images/Viventre-18_12_2125.webp" type="image/webp">
                        <source srcset="images/Viventre-18_12_21- petite- 25.jpg" type="image/jpeg">
                        <img src="images/Viventre-18_12_21- petite- 25.jpg" alt="Viventre Image Petite" loading="lazy">
                    </picture>
                </a>
            </div>
            <div>
                <a href="images/Viventre-18_12_21- 31.jpg">
                    <picture>
                        <source srcset="images/Viventre-18_12_21-petite-31.webp" type="image/webp">
                        <source srcset="images/Viventre-18_12_21-petite-31.jpg" type="image/jpeg">
                        <img src="images/Viventre-18_12_21-petite-31.jpg" alt="Viventre Image Petite" loading="lazy">
                    </picture>
                </a>
            </div>
            <div>
                <a href="images/Viventre-18_12_21- 36.jpg">
                    <picture>
                        <source srcset="images/Viventre-18_12_21-petite-36.webp" type="image/webp">
                        <source srcset="images/Viventre-18_12_21 - petite 36.jpg" type="image/jpeg">
                        <img src="images/Viventre-18_12_21 - petite 36.jpg" alt="Viventre Image Petite" loading="lazy">
                    </picture>
                </a>
            </div>
        </section>
        <article id="Argent" class="spectacles">
            <h1>L'Argent</h1>
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
            <p><iframe id="iframeArgent" width="350" height="350" src="https://www.youtube.com/embed/4QuITiqTIxk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe></p>
            <a href="#spectacles" style="display:flex; justify-content: end; margin-top:100px;"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                </svg></a>
        </article>
        <section id="Mosaique1">
            <div>
                <a href="images/2019_L'argent.jpg">
                    <picture>
                        <source srcset="images/2019_L'argent_petite.webp" type="image/webp">
                        <source srcset="images/2019_L'argent_petite.jpg" type="image/jpeg">
                        <img src="images/2019_L'argent_petite.jpg" alt="Photo de l'Argent" loading="lazy">
                    </picture>
                </a>
            </div>
            <div>
                <a href="images/Argent_4123.jpg">
                    <picture>
                        <source srcset="images/Argent_4123_petite.webp" type="image/webp">
                        <source srcset="images/Argent_4123_petite.jpg" type="image/jpeg">
                        <img src="images/Argent_4123_petite.jpg" alt="Photo de l'Argent" loading="lazy">
                    </picture>
                </a>
            </div>
            <div>
                <a href="images/Argent_4150.jpg">
                    <picture>
                        <source srcset="images/Argent_4150_petite.webp" type="image/webp">
                        <source srcset="images/Argent_4150_petite.jpg" type="image/jpeg">
                        <img src="images/Argent_4150_petite.jpg" alt="Photo de l'Argent" loading="lazy">
                    </picture>
                </a>
            </div>
            <div>
                <a href="images/Argent_4165_.jpg">
                    <picture>
                        <source srcset="images/Argent_4165_petite.webp" type="image/webp">
                        <source srcset="images/Argent_4165_petite.jpg" type="image/jpeg">
                        <img src="images/Argent_4165_petite.jpg" alt="Photo de l'Argent" loading="lazy">
                    </picture>
                </a>
            </div>
        </section>
        <span id="titreMusique"></span>
    </section>
    </li>

    <section id="Musique">
        <h2>Musique</h2>
        <p>Si ce qu'on raconte de ma mythologie familiale est que ma mère changeait mes langes sur la
            scène
            du Bateau Ivre (ancien cabaret rouennais aujourd'hui disparu),<br>
            je ne suis pas devenue musicienne professionnelle tout de suite. Ma vocation artistique fut
            en
            effet
            premièrement le théâtre.<br>
            Mais d'un père compositeur et pianiste de scène dans sa jeunesse.. même si je ne sais pas
            lire la musique,<br> j'ai sûrement reçu une oreille musicale de ce bain familial.</p>
        <div id="container-iframes">
            <div class="grid-container">
                <div class="grid-item">
                    <div class="video-wrapper" data-video-id="293555303" data-provider="vimeo">
                        <picture>
                            <source srcset="https://i.vimeocdn.com/video/730231567-52dac924fec8210c3f2813075b619487eef954615ed505460005fe4dc2608ac4-d_640.webp" type="image/webp">
                            <source srcset="https://i.vimeocdn.com/video/730231567-52dac924fec8210c3f2813075b619487eef954615ed505460005fe4dc2608ac4-d_640.jpg" type="image/jpeg">
                            <img src="https://i.vimeocdn.com/video/730231567-52dac924fec8210c3f2813075b619487eef954615ed505460005fe4dc2608ac4-d_640.jpg" alt="Teaser Autour des Elles d’Allain - 2018" class="thumbnail" loading="lazy">
                        </picture>
                        <div class="play-button"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="video-wrapper" data-video-id="JC6vuz2HncE" data-provider="youtube">
                        <picture>
                            <source srcset="images/miniature1.webp" type="image/webp">
                            <source srcset="images/miniature1.jpg" type="image/jpeg">
                            <img src="images/miniature1.jpg" alt="Teaser Viventre" class="thumbnail" loading="lazy">
                        </picture>
                        <div class="play-button"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="video-wrapper" data-video-id="IWingOXoCv8" data-provider="youtube">
                        <picture>
                            <source srcset="images/miniature2.webp" type="image/webp">
                            <source srcset="images/miniature2.jpg" type="image/jpeg">
                            <img src="images/miniature2.jpg" alt="Choupote - extrait du spectacle VIVENTRE du Slam'dit - ChaNoé Goupil & Alex Rasse" class="thumbnail" loading="lazy">
                        </picture>
                        <div class="play-button"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="video-wrapper" data-video-id="VLplFrMCfwE" data-provider="youtube">
                        <picture>
                            <source srcset="images/miniature3.webp" type="image/webp">
                            <source srcset="images/miniature3.jpg" type="image/jpeg">
                            <img src="images/miniature3.jpg" alt="Interview de Charlotte Goupil par Emmanuel Gouache" class="thumbnail" loading="lazy">
                        </picture>
                        <div class="play-button"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="video-wrapper" data-video-id="1ENzQan2JhU" data-provider="youtube">
                        <picture>
                            <source srcset="images/miniature4.webp" type="image/webp">
                            <source srcset="images/miniature4.jpg" type="image/jpeg">
                            <img src="images/miniature4.jpg" alt="Le rêve" class="thumbnail" loading="lazy">
                        </picture>
                        <div class="play-button"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="video-wrapper" data-video-id="RklwZVsxQnY" data-provider="youtube">
                        <picture>
                            <source srcset="images/miniature5.webp" type="image/webp">
                            <source srcset="images/miniature5.jpg" type="image/jpeg">
                            <img src="images/miniature5.jpg" alt="Leprest à l'improvsite" class="thumbnail" loading="lazy">
                        </picture>
                        <div class="play-button"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="video-wrapper" data-video-id="Te_6mXfvRKY" data-provider="youtube">
                        <picture>
                            <source srcset="images/miniature6.webp" type="image/webp">
                            <source srcset="images/miniature6.jpg" type="image/jpeg">
                            <img src="images/miniature6.jpg" alt="Teaser Autour des Elles d'Allain 2016" class="thumbnail" loading="lazy">
                        </picture>
                        <div class="play-button"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="container-links">
            <div id="container-links-p">
                <p>Si vous souhaitez voir plus de vidéos, cliquez sur les liens suivants: </p>
            </div>
            <div id="container-links-ul">
                <ul class="music-links">
                    <li><a href="https://www.youtube.com/watch?v=ctshwwMDoiA" loading="lazy">Il neige</a></li>
                    <li><a href="https://www.youtube.com/watch?v=al5KVGetoXg" loading="lazy">Concert hommage à Allain Leprest - Bruxelles</a></li>
                    <li><a href="https://www.youtube.com/watch?v=mL48Xt1dUkY&t=156s" loading="lazy">Récital hONdiCAP sur Leprest</a></li>
                    <li><a href="https://www.youtube.com/watch?v=uvk5VtPmDCw" loading="lazy">L'horloger</a></li>
                    <li><a href="https://www.youtube.com/watch?v=ZFGkrxvuomk" loading="lazy">"Le sud" avec Zabou & Martial - El Camino</a></li>
                    <li><a href="https://www.youtube.com/watch?v=eBX4y7A8gZ0" loading="lazy">"Une Valse pour Rien" C.Goupil & R.Jéhanne</a></li>
                    <li><a href="https://www.youtube.com/watch?v=j6Lz2ASfwnM&list=PLA3VSx-wfHVkrFeWYSAJCi7zc3WgYCIp6&index=3" loading="lazy">Concert inauguration place Leprest - Mont Saint Aignan</a></li>
                    <li><a href="https://www.youtube.com/watch?v=B6TKhf3UAos&list=PLA3VSx-wfHVkrFeWYSAJCi7zc3WgYCIp6&index=4" loading="lazy">Autour des Elles d'Allain Extraits</a></li>
                </ul>
            </div>
        </div>
        </div>
        <span id="titreEcriture"></span>
    </section>
    <section id="Ecriture">
        <h2 id="EcritureH2">Ecriture</h2>

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
        <h3 id="mesEcritsH3">Mes écrits</h3>
        <a href="http://dmjlarchotte.blogspot.com/?view=magazine">Mon blog</a>
        <div>
            <p class="mesEcritures">À propos de mes écritures</p>
        </div>
        <ul>
            <li><a class="aEcriture" href=" https://www.facebook.com/ZingSOU/videos/atelier-d%C3%A9criture-n2-avec-charlotte-goupil%EF%B8%8Fmercredi-27-d%C3%A9cembre-19h-21hsur-inscr/1955378794477299/">Atelier
                    d'écriture avec Charlotte Goupil
                </a>
            </li>
            <li><a href="https://leblogdudoigtdansloeil.wordpress.com/tag/charlotte-goupil/">Le blog du doigt dans
                    l'oeil</a></li>
            <li><a href="https://www.agri-culture.fr/article/culturel-festival/les-artistes-normands-se-pressent-au-festival-55">
                    Les artistes normands se pressent au festival 55
                </a>
            </li>
            <li><a href=" https://www.proarti.fr/collect/project/viventre-du-slamdit-1/0">Viventre du slamdit</a></li>
        </ul>
        <div>
            <p class="mesEcritures">Mes engagements à la Factorie, Maison de la poésie normande:</p>
        </div>
        <ul>
            <li><a class="aEcriture" href="https://zh-cn.facebook.com/lafactoriemaisondepoesie/videos/po%C3%A8me-%C3%A0-crier-par-les-fen%C3%AAtres-couvre-feu/513972332580916/">
                    À crier par les fenêtres</a>
            </li>
            <li><a class="aBottom" href=" https://france3-regions.francetvinfo.fr/normandie/eure/louviers/culture-domicile-poesie-moyen-s-evader-confinement-1812920.html">S'évader
                    du confinement avec la poésie</a>
            </li>
        </ul>
    </section>
    <footer></footer>
    <script src="js/script.js" defer></script>
    <script>
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
                    iframe.setAttribute('width', '560');
                    iframe.setAttribute('height', '315');
                    iframe.setAttribute('src', iframeSrc);
                    iframe.setAttribute('title', 'Video player');
                    iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                    iframe.setAttribute('allowfullscreen', true);
                    iframe.setAttribute('loading', 'lazy');
                    this.innerHTML = '';
                    this.appendChild(iframe);
                });
            });
        });
    </script>
</body>

</html>