<?php
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
session_start();

// Génération du token CSRF
if (!isset($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<?php
// Vérification du CSRF lors de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
		die('Jeton CSRF manquant');
	}

	if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
		die('Erreur CSRF');
	}

	// Régénérer le jeton pour la prochaine requête
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Charlotte Goupil | Artiste multidisciplinaire - Spectacles et Ateliers</title>
	<link rel="icon" type="image/png" href="images/favicon.png">
	<meta name="description" content="Découvrez Charlotte Goupil, artiste aux multiples facettes : comédienne, chanteuse, slameuse. Spectacles vivants, lectures, contes et ateliers de médiation artistique en Normandie.">
	<!-- Chargement de Bootstrap -->
	<link rel="stylesheet" href="bootstrap.min.css">
	<!-- Chargement de Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet">
	<?php
	$date = date("Y-m-d-h-i-s");
	$css_files = ['style', 'header', 'bio', 'footer'];
	foreach ($css_files as $file) {
		echo '<link rel="stylesheet" type="text/css" href="' . $file . '.min.css?uid=' . $date . '">';
	}
	?>

	<!-- Chargement de Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet">
</head>

<body>
	<?php include_once 'header.php'; ?>
	<main>
		<section class="Hero">
			<article class="Bio">
				<figure class="bio-figure" style="width:500px; height:480px; margin:0 auto; position:relative;">
					<picture>
						<source srcset="images/Charlotte_Narcisse_small_500.webp" type="image/webp" media="(max-width: 674px)">
						<source srcset="images/Charlotte_Narcisse_small.webp" type="image/webp">
						<source srcset="images/Charlotte_Narcisse_small.jpg" type="image/jpeg">
						<img src="images/Charlotte_Narcisse_small.webp" srcset="images/Charlotte_Narcisse_500_small.webp 500w, images/Charlotte_Narcisse.webp 1200w" width="500" height="480" alt="Portrait de Charlotte Goupil" style="display:block;">
					</picture>
					<figcaption class="bio-figcaption" style="position:absolute;bottom:0;right:0;width:100%;color:rgba(255,255,255,0.9);padding:10px;min-height:32px;box-sizing:border-box;">© Steeve Narcisse</figcaption>
				</figure>
			</article>
			<h2 class="presentation stable-title" style="min-height:2.8em;">Charlotte Goupil, artiste normande et sans frontières</h2>
			<div class="presentation-bio stable-bio" style="min-height:320px;">
				<p>Charlotte est une artiste normande, de naissance, de par sa formation mais aussi via son parcours professionnel. Après un bac Littéraire option théâtre au Lycée Jeanne d'arc de Rouen,
					elle suit les conseils de sa professeure Annie Franscisi et s'inscrit en 'Arts du Spectacle’, à la faculté de Caen.
					En 2ème année de Licence, elle part à Londres pour suivre une année Erasmus en English and Drama school.
					Touchée par la culture cosmopolite, elle est prise du virus du voyage.
					De retour en Normandie, elle poursuit sa formation sur le jeu de l'acteur au sein des classes du comédien de l'Actéa, compagnie dans la Cité.
					Elle suit ensuite un Master recherche en théâtre qu'elle finance en posant en tant que modèle à l'école régionale des Beaux-Arts de Caen.
				</p>
				<h3>Une formation multiple</h3>
				<p>Son travail de modèle académique lui redonne goût aux arts plastiques et lui fait quitter la fac de lettres et
					le théâtre. Via une équivalence de crédits universitaires, elle entre alors à l'ésam
					(école supérieure des arts et médias de Caen-Cherbourg) en 2ème année du cursus Arts. </p>
				<h3>Capitale(s)</h3>
				<p>Ce pont du champ universitaire littéraire à celui des arts plastiques, lui permet aussi de voyager à nouveau en établissant un échange entre les beaux-arts de Caen et une école d'art contemporain, l'E.R.G (Ecole de Recherche Graphique), à...Bruxelles</p>
				<p>Au sein de cette nouvelle école, elle se forme à l'installation performance, option principale de son échange, mais aussi au jeu face caméra et radiophonique via diverses expériences audiovisuelles pour des associations (Mutation Production), écoles (IAD, INRACI), ou maisons de productions (AJCV, production du Trésor...) : la Belgique étant l’un des hauts-lieux de tournages cinématographiques européens.</p>
				<p>De retour en France plus tard, elle voyage entre la Normandie et Paris pour suivre une formation de « médiation artistique en relation d'aide » au sein de l'Inécat, école d'art-
					thérapie. Persuadée que la pratique artistique et/ou la réception d’une œuvre, quelle qu’elle soit, peut transcender les problématiques d’une personne en difficulté et améliorer sa relation au monde</p>
				<h3>Premières et nouvelles écritures</h3>
				<p>Stagiaire scénario grâce au festival Offshort (ex-festival off du festival du film Grolandais de Quend), elle réalise avec l'aide du réalisateur-scénariste Christophe Hermans et du chef opérateur Frederic Noirhomme, le court-métrage intitulé « La théorie de l'autruche » adaptation cinématographique du scénario de ce stage, dont le thème était... frontières !</p>
				<p>Par la suite, apres son travail de reprises du répertoire d'Allain Leprest où elle était déjà accompagnée par Alex Rasse, Charlotte devient ChaNoé sur une forme slamée musicale et visuelle très personnelle. À partir de textes intimes évoquant l'art, les rencontres et les luttes, ChaNoé, fait naître à nos yeux et nos oreilles, un travail de recherche brute et vive de mots passionnés : le projet « Viventre ».</p>
				<h3>Back to the... Rouen !</h3>
				<p>En 2015, elle rencontre deux femmes passionnées de musique et de scène, avec qui elle décide de monter une structure associative de spectacles vivants et d'ateliers de médiations (artistiques et culturelles) : Corrél'Arts. Au cours de cette même année, elle décide de se lancer dans la création de spectacles de lecture à voix haute, contes et clown (Stage avec la Youle Cie; Rencontre avec Charlie Clé, auteure et conteuse; scène ouverte du Théâtre du Présent...). En 2016, dans le cadre de son tour de chant intitulé "Autour des Elles d'Allain" sur les thèmes et personnages féminins du répertoire du chanteur-poète d'Allain Leprest, Charlotte rencontre son pianiste actuel Alexandre Rasse, avec qui elle tournera plusieurs années et sur plusieurs scènes.</p>
				<p>Présidente du festival Chants d'Elles, le festival des voix de femmes, des éditions 2020, 2021 et 2022, elle continue son travail de transmission des œuvres d'autrui : en particulier de la création artistique portée par des femmes et en direction de publics tout à fait divers.</p>
			</div>
		</section>
		<div class="container mt-4">
			<div class="row">
				<div class="form-9 col-xl-8 col-lg-8 col-md-12 col-sm-12 offset-xl-2">
					<h2 class="form-h2">Formulaire de contact</h2>
					<form id="contact-form" method="post" action="php/contact.php" role="form">
						<input type="hidden" name="csrf_token" value="e10b05cf776731f6e94a7261925338dbf4d1e2e12b3cb2b453cb53964cb6ed16">
						<div class="controls">
							<p class="MessageEssentiel">Laissez-moi un message <span class="essentiel">"essentiel et sans nuages"</span></p>
							<div class="row">
								<div class="col-md-6">
									<label for="firstname"><span class="red">*</span>Prénom</label>
									<input id="firstname" type="text" name="firstname" class="form-control" placeholder="Prénom" autocomplete="on" maxlength="20">
									<p class="comments"></p>
								</div>
								<div class="col-md-6">
									<label for="name"><span class="red">*</span>Nom</label>
									<input id="name" type="text" name="name" class="form-control" placeholder="Nom" autocomplete="on" maxlength="20">
									<p class="comments"></p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label for="email"><span class="red">*</span>Email</label>
									<input id="email" type="email" name="email" class="form-control" placeholder="E-mail" autocomplete="on" maxlength="50">
									<p class="comments"></p>
								</div>
								<div class="col-md-6">
									<label for="phone"><span class="red">*</span>Tél</label>
									<input id="phone" type="phone" name="phone" class="form-control" placeholder="Numéro de téléphone" autocomplete="on" maxlength="10">
									<p class="comments"></p>
								</div>
								<div class="col-md-12 col-sm-12">
									<label for="subject"><span class="red">*</span>Sujet</label>
									<select id="subject" name="subject" class="form-control">
										<option value="">Choisir une option</option>
										<option value="Demande d'infos">Demande d'infos</option>
										<option value="Demande de devis">Demande de devis</option>
										<option value="Autre">Autre</option>
									</select>
									<p class="comments"></p>
								</div>
							</div>
							<div class="row mt-4">
								<div class="col-md-12">
									<label for="message"><span class="red">*</span>Message</label>
									<textarea id="message" name="message" class="form-control" placeholder="Votre Message" rows="8" maxlength="1000"></textarea>
									<p class="comments mb-4"></p>
								</div>
							</div>
							<label for="data-consent"></label>
							<input type="checkbox" id="data-consent" name="data-consent">
							En cochant cette case et en soumettant ce formulaire, vous acceptez que vos données soient utilisées pour vous recontacter dans le cadre de votre demande indiquée. Aucun autre traitement ne sera effectué avec vos informations.
							<p class="comments mb-4"></p>
							<div class="row">
								<div class="col-md-12 text-center">
									<button type="submit" class="button-form" aria-label="Soumettre le formulaire" value="Envoyer un message">Envoyer</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<p class="red"><strong>* Ces informations sont requises</strong></p>
								</div>
							</div>
						</div>
					</form>

					<div class="Logo col-auto">
						<a href="https://piaille.fr/@LesMultiples2Charlotte" aria-label="Lien vers ma page Mastodon" title="Lien vers ma page Mastodon">
							<picture>
								<source srcset="images/Mastodon-logo.webp" type="image/webp" media="(max-width: 674px)">
								<source srcset="images/Mastodon-logo.png" type="image/png">
								<img class="lazy-image logo" src="images/Mastodon-logo.png" alt="Logo Mastodon" width="162" height="174" loading="lazy">
							</picture>
						</a>
						<a href="https://www.instagram.com/chafoxil/" aria-label="Lien vers ma page Instagram" title="Lien vers ma page Instagram">
							<picture>
								<source srcset="images/instagram-Logo-PNG-Transparent-Background-download_500.webp" type="image/webp" media="(max-width: 674px)">
								<source srcset="images/instagram-Logo-PNG-Transparent-Background-download.png" type="image/png">
								<img class="lazy-image logo" data-src="images/instagram-Logo-PNG-Transparent-Background-download_500.png" alt="Logo Instagram" width="162" height="174" loading="lazy">
							</picture>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div id="cookie-consent-banner" role="dialog" aria-modal="true" tabindex="0" aria-labelledby="cookie-banner-title" aria-describedby="cookie-banner-desc">
			<img src="images/cookie_small.webp" data-src="images/cookie_small.webp" class="lazy-image cookie-corner-left" width="70" height="70" alt="Dessin d'un cookie" loading="lazy">
			<h2 id="cookie-banner-title">Nous utilisons des cookies</h2>
			<img src="images/cookie_small.webp" data-src="images/cookie_small.webp" class="lazy-image cookie-corner-right" width="70" height="70" alt="Dessin d'un cookie" loading="lazy">
			<p id="cookie-banner-desc">Nous utilisons des cookies pour améliorer votre expérience sur notre site.
				Ces cookies nous permettent de collecter des données techniques, telles que vos données de navigation, afin d'analyser et d'optimiser notre site. Voici comment nous utilisons vos données :</p>
			<p>Vous pouvez également consulter notre <a href="politiqueConfidentialite.php">politique de confidentialité</a> pour plus d'informations sur la gestion de vos données.</p>
			<button id="accept-cookies" type="button" value="Accepter les cookies" aria-label="Accepter les cookies">Accepter les cookies</button>
			<button id="decline-cookies" type="button" value="Refuser les cookies" aria-label="Refuser les cookies">Refuser les cookies</button>
		</div>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" rel="preload" as="script" defer></script>
	<script src="js/bootstrap.bundle.min.js" defer></script>
	<script src="https://charlottegoupil.fr/js/script.min.js" defer></script>

	<script nonce="<?php echo $nonce; ?>">
		window.addEventListener('load', function() {
			var images = document.querySelectorAll('.lazy-image');
			images.forEach(function(image) {
				var src = image.getAttribute('data-src');
				if (src) image.src = src;
			});
		});
	</script>
	<?php include_once 'footer.php'; ?>
</body>

</html>