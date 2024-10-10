<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Charlotte Goupil | Artiste multidisciplinaire - Spectacles et Ateliers</title>
	<meta name="description" content="Découvrez Charlotte Goupil, artiste aux multiples facettes : comédienne, chanteuse, slameuse. Spectacles vivants, lectures, contes et ateliers de médiation artistique en Normandie.">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<?php
	$date = date("Y-m-d-h-i-s");
	echo '<link rel="stylesheet" type="text/css" href="stylesmq.min.css?uid=' . $date . '">';
	?>
	<link href='https://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet'>
</head>

<body>
	<?php include 'header.php'; ?>
	<section id="Hero">
		<div class="Bio">
			<figure>
				<picture>
					<source srcset="images/Charlotte_Narcisse.webp" type="image/webp">
					<img src="images/Charlotte_Narcisse.jpg" alt="" loading="lazy">
				</picture>
				<figcaption>© Steeve Narcisse</figcaption>
			</figure>
		</div>
		</div>
		<h2 id="presentation">Charlotte Goupil</h2>
		<div class="presentation">
			<p>Après un bac Littéraire option théâtre au Lycée Jeanne d'arc de Rouen, elle suit les
				conseils de sa professeure Annie Franscisi et s'inscrit en Licence d'Arts
				du Spectacle de Caen. En 2ème année de Licence, elle part à Londres
				pour suivre une année Erasmus en "English and Drama School". <br> De
				retour en Normandie, elle poursuit sa formation sur le jeu de l'acteur
				au sein des classes du comédien de l' Actéa, "compagnie dans la Cité
				". Elle reprend par la suite un Master de recherche théâtrale qu'elle
				finance en posant en tant que modèle à l'école régionale des Beaux-
				Arts de Caen, ce qui lui redonne le goût aux arts plastiques et lui fait
				quitter la fac.
				Via une équivalence de Licence, elle entre à l'ésam (école supèrieure
				des arts et médias de Caen-Cherbourg) en 2ème année, ce qui lui
				permet d'établir un échange bilatéral entre cette école et une école
				d'art contemporain à Bruxelles. <br>Au sein de cette autre école, l'ERG
				(Ecole de Recherche Graphique), elle se forme à l'installation performance,
				option principale de son échange.
				Peu après, elle se forme au jeu face caméra et radiophonique via
				diverses expériences audiovisuelles pour des associations (Mutation
				Production), des écoles (IAD, INRACI), ou des maisons de productions
				(AJCV, production du Trésor...). Stagiaire scénario grâce au festival
				Offshort (ex festival OFF du festival du film Grolandais de Quend), elle
				réalise avec l'aide du réalisateur scénariste Christophe Hermans et du
				chef opérateur Frederic Noirhomme le court- métrage intitulé « La
				théorie de l'autruche » adaptant le scénario de ce stage. <br>
				De retour en France en 2013, elle voyage entre la Normandie et Paris
				pour suivre une formation de médiation artistique en relation d'aide au
				sein de l'Inécat, école d'art-thérapie.<br>
				En 2015, elle rencontre deux femmes passionnées de musique et de scène,
				avec qui elle décide de monter une structure associative de spectacles
				vivants et d'ateliers de médiations (artistiques et culturelles) :
				Corrél'Arts. Au cours de cette même année, elle décide de se lancer
				dans la création de spectacles de lecture à voix haute, contes et clown
				(Stage avec la Youle Cie; Rencontre avec Charlie Clé, auteure et
				conteuse; scène ouverte du Théâtre du Présent...). En 2016, dans le
				cadre de son tour de chant intitulé "Autour des Elles d'Allain" sur les
				thèmes et personnages féminins du répertoire du chanteur-poète
				d'Allain Leprest, Charlotte rencontre son pianiste actuel Alexandre
				Rasse, de son nom de scène Alex Rasse, avec qui elle tournera
				plusieurs années et sur plusieurs scènes.<br>
				Présidente du festival Chants d'Elles, le festival des voix de
				femmes, des éditions 2020, 2021 et 2022, elle continue son travail de transmission des oeuvres d'autrui :
				en particulier de la création artistique portée par des femmes et en
				direction de publics tout à fait divers.
				Enfin, suite à son travail sur le répertoire d'Allain Leprest où elle était
				déjà accompagnée par Alex Rasse , Charlotte devient ChaNoé pour revenir
				aujourd'hui avec une forme slamée musicale.<br> À partir de textes
				personnels évoquant l'art, les rencontres et les luttes, ChaNoé,
				toujours accompagnée de son acolyte de création, Alex Rasse, fait
				naître à nos yeux et nos oreilles, un travail de recherche brute et vive
				de mots passionnés.
			</p>
		</div>

	</section>
	<div class="container mt-4">
		<div class="row">
			<div class="form-9 col-xl-8 col-lg-8 col-md-12 col-sm-12 offset-xl-2">
				<h2>Formulaire de contact</h2>
				<form id="contact-form" method="post" action="contact.php" role="form">
					<div class="controls">
						<p id="MessageEssentiel">Laissez-moi un message <span class="essentiel">"essentiel et sans nuages"</span> à
							larchotterenard@protonmail.com</p>
						<div class="row">
							<div class="col-md-6">
								<label for="firstname"><span class="red">*</span>Prénom</label>
								<input id="firstname" type="text" name="firstname" class="form-control" placeholder="Veuillez entre votre prénom">
								<p class="comments"></p>
							</div>
							<div class="col-md-6">
								<label for="name"><span class="red">*</span>Nom</label>
								<input id="name" type="text" name="name" class="form-control" placeholder="Veuillez entrer votre nom" autocomplete="on">
								<p class="comments"></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label for="email"><span class="red">*</span>Email</label>
								<input id="email" type="email" name="email" class="form-control" placeholder="Veuillez entrer votre email" autocomplete="on">
								<p class="comments"></p>
							</div>
							<div class="col-md-6">
								<label for="phone"><span class="red">*</span>Tél</label>
								<input id="phone" type="phone" name="phone" class="form-control" placeholder="Veuillez entrer votre numéro de téléphone" autocomplete="on">
								<p class="comments"></p>
							</div>
							<div class="col-md-12">
								<label for="subject"><span class="red">*</span>Sujet</label>
								<select id="subject" name="subject" class="form-control">
									<option value="">Quel est le sujet de votre message ?</option>
									<option value="Je voudrais plus d'infos sur tes services">Je voudrais plus d'infos sur tes services</option>
									<option value="J'aimerais recevoir un devis">J'aimerais recevoir un devis</option>
									<option value="Autre message du site">Autre</option>
								</select>
								<p class="comments"></p>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col-md-12">
								<label for="message"><span class="red">*</span>Message</label>
								<textarea id="message" name="message" class="form-control" placeholder="Votre Message" rows="8"></textarea>
								<p class="comments mb-4"></p>
							</div>
						</div>
						<label for="data-consent"></label>
						<input type="checkbox" id="data-consent" name="data-consent">
						En cochant cette case et en soumettant ce formulaire, vous acceptez que vos données soient utilisées pour vous recontacter dans le cadre de votre demande indiquée. Aucun autre traitement ne sera effectué avec vos informations.
						<p class="comments mb-4"></p>
						<div class="row">
							<div class="col-md-12 text-center">
								<input type="submit" class="button-form" value="Envoyer">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<p class="red"><strong>* Ces informations sont requises</strong></p>
							</div>
						</div>
					</div>
				</form>
				<div id="Logo" class="col-auto">
					<a href="https://www.facebook.com/larchotte.goupil" title="Lien vers ma page Facebook"><img src="images/logo-facebook.png" alt="logo Facebook" class="logo"></a>
					<a href="https://www.instagram.com/chafoxil/" title="Lien vers ma page Instagram"><img src="images/instagram-Logo-PNG-Transparent-Background-download.png" alt="logo Instagram" class="logo"></a>
				</div>
			</div>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" defer></script>
	<script src="http://multiples-charlotte.fabienneberges.com/js/script.min.js" defer></script>
	<div id="cookie-consent-banner">
		<img src="images/cookie.webp" alt="Cookie" class="cookie-corner-left">
		<h2>Nous utilisons des cookies</h2>
		<img src="images/cookie.webp" alt="Cookie" class="cookie-corner-right">
		<p>Nous utilisons des cookies pour améliorer votre expérience sur notre site.
			Ces cookies nous permettent de collecter des données techniques, telles que vos données de navigation, afin d'analyser et d'optimiser notre site. Voici comment nous utilisons vos données :</p>
		<p>Vous pouvez également consulter notre <a href="politiqueConfidentialite.php" style="color: #007BFF;">politique de confidentialité</a> pour plus d'informations sur la gestion de vos données.</p>
		<button id="accept-cookies">Accepter</button>
		<button id="decline-cookies">Refuser&nbsp</button>
	</div>
	<?php include 'footer.php'; ?>

</body>

</html>