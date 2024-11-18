<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales | Charlotte Goupil - Artiste et Médiatrice Culturelle</title>
    <meta name="description" content="Informations légales sur le site de Charlotte Goupil, artiste et médiatrice culturelle. Éditeur, développeur, hébergement, propriété intellectuelle et protection des données personnelles.">
    <?php
    $date = date("Y-m-d-h-i-s");
    echo '<link rel="stylesheet" type="text/css" href="stylesmq.min.css?uid=' . $date . '">';
    ?>
    <link href='https://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet'>
</head>

<body id="">
    <?php include 'header.php'; ?>
    <main>
        <section id="mentionsLegales">
            <h2>Mentions légales</h2>
            <h3>Éditeur du site</h3>
            <p>Charlotte Goupil,
                22 rue de l'ancienne prison
                76000 Rouen, France <br>
                Email : charlotte.transcourt@gmail.com
            </p>
            <h3>Développeur du site</h3>
            <p>Fabienne Bergès,
                84 rue Méridienne
                76100 Rouen, France <br>
                Email : fabienne_berges@yahoo.fr
            </p>
            <h3>Hébergeur</h3>
            <p>SAS OVH
                2 rue Kellermann - BP 80157
                59100 Roubaix, France <br>
                www.ovh.com <br>
                Fax : 03 20 20 09 58 <br>
                Email : support@ovh.com</p>
            <h3>Responsabilités</h3>
            <p>Le présent site a été développé par Fabienne Bergès, agissant en qualité de développeur et prestataire technique. L'édition et la direction de la publication du site sont assurées par Charlotte Goupil (charlotte.transcourt@gmail.com), qui est responsable du traitement des données personnelles collectées sur ce site.</p>
            <h3>Propriété intellectuelle</h3>
            <p>L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques. Toute reproduction totale ou partielle de ce site ou de son contenu, par quelque procédé que ce soit, sans autorisation expresse de Mmes Charlotte Goupil et Fabienne Bergès ou des artistes et spectateurs présents dans les vidéos ou photographies, est interdite et constituerait une contrefaçon sanctionnée par les articles L 335-2 et suivants du Code de la propriété intellectuelle.</p>
            <h3>Protection des données personnelles</h3>
            <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un droit d'accès, de rectification et de suppression des données vous concernant. Pour exercer ces droits ou pour toute question sur le traitement de vos données, vous pouvez contacter Charlotte Goupil à l'adresse email : charlotte.transcourt@gmail.com</p>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" defer></script>
    <script src="https://multiples-charlotte.fabienneberges.com/js/script.min.js" defer></script>
    <script>
        function highlightNames() {
            const colorsOfNames = {
                "Charlotte Goupil": "#741D34",
                "Fabienne Bergès": "#1D7461",
                "Éditeur": "#741D34",
                "Développeur": "#1D7461",
                "https://multiples-charlotte.fr": "#741D34",
            };
            const mainContent = $("#mentionsLegales");

            if (mainContent.length === 0) {
                return;
            }
            mainContent.find('p, strong').each(function() {
                let textContent = $(this).html();
                for (const [name, color] of Object.entries(colorsOfNames)) {
                    const regex = new RegExp(escapeRegExp(name), 'g');
                    textContent = textContent.replace(regex, `<span style="color: ${color}; font-weight: bold">${name}</span>`);
                }
                $(this).html(textContent);
            });
        }

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }
        $(document).ready(function() {
            if (window.location.pathname.includes("mentionsLegales.php")) {
                highlightNames();
            } else {
                console.log("Pas sur la bonne page, fonction non appelée");
            }
        });
    </script>
</body>

</html>