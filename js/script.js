
$(document).ready(function () {
    function highlightNames() {
        const colorsOfNames = {
            "Charlotte Goupil": "#741D34",
            "Fabienne Bergès": "#1D7461"
        };

        const mainContent = $("#mentionsLegales, #politiqueConfidentialite");
        let content = mainContent.html();

        for (const [name, color] of Object.entries(colorsOfNames)) {
            const regex = new RegExp(name, 'g');
            content = content.replace(regex, `<span style="color: ${color}; font-weight: bold">${name}</span>`);
        }
        mainContent.html(content);
    }

    // Vérifiez si l'URL correspond à la page spécifique
    if (window.location.pathname === "/Multiples_Charlotte/MentionsLegales.php" || window.location.pathname === "/Multiples_Charlotte/PolitiqueConfidentialite.php") {

        highlightNames();
    }
    $(".not-authorized-overlay").hide(), $("#Bulles .photo").hover((function () { $(this).find(".not-authorized-overlay").toggle(200) }))
}),
    $((function () {
        $(".navbar a ").on("click", (function (t) { t.preventDefault(); var o = this.hash; $("body,html").animate({ scrollTop: $(o).offset().top }, 850, (function () { window.location.hash = o })) })), $("#myNavbar a").on("click", (function () { $("#myNavbar").collapse("hide") })),
            $("#contact-form").submit(function (event) {
                event.preventDefault(); // Empêche l'envoi par défaut du formulaire
                $(".comments").empty(); // Vide les messages d'erreur précédents
                let isValid = true;

                // Vérification des champs
                if ($("#firstname").val().trim() === "") {
                    $("#firstname").next(".comments").html("Merci de m'indiquer votre prénom");
                    isValid = false;
                }
                if ($("#name").val().trim() === "") {
                    $("#name").next(".comments").html("Votre nom est aussi nécessaire");
                    isValid = false;
                }
                if (!validateEmail($("#email").val())) {
                    $("#email").next(".comments").html("Votre email n'est pas valide");
                    isValid = false;
                }
                if (!validatePhone($("#phone").val())) {
                    $("#phone").next(".comments").html("Seuls les chiffres sont utilisables.");
                    isValid = false;
                }
                if ($("#subject").val().trim() === "") {
                    $("#subject").next(".comments").html("Désolé, vous avez oublié d'indiquer le sujet de votre message");
                    isValid = false;
                }
                if ($("#message").val().trim() === "") {
                    $("#message").next(".comments").html("Vous avez oublié d'écrire votre message");
                    isValid = false;
                }

                // Si tous les champs sont valides, vérifier le consentement
                if (isValid) {
                    if (!$("#data-consent").is(":checked")) {
                        $("#data-consent").next(".comments").html("Vous devez accepter les termes et conditions");
                        isValid = false;
                    }
                }

                // Envoi du formulaire si tout est valide
                if (isValid) {
                    let formData = $("#contact-form").serialize(); // Sérialise les données du formulaire
                    $.ajax({
                        type: "POST",
                        url: "php/contact.php",
                        data: formData,
                        dataType: "json",
                        success: function (response) {
                            if (response.isSuccess) {
                                $("#contact-form").append("<p class='thank-you'>Votre message a bien été envoyé. Merci de m'avoir contacté :)</p>");
                                $("#contact-form")[0].reset();
                                $("#contact-form .thank-you").delay(3000).fadeOut("slow") // Réinitialise le formulaire
                            } else {
                                // Affichage des messages d'erreur renvoyés par le serveur
                                $("#firstname").next(".comments").html(response.firstnameError);
                                $("#name").next(".comments").html(response.nameError);
                                $("#email").next(".comments").html(response.emailError);
                                $("#phone").next(".comments").html(response.phoneError);
                                $("#subject").next(".comments").html(response.subjectError);
                                $("#message").next(".comments").html(response.messageError);
                                $("#data-consent").next(".comments").html(response.consentError);
                            }
                        }
                    });
                }
            });

        // Fonction pour valider l'email
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Fonction pour valider le téléphone
        function validatePhone(phone) {
            const re = /^[0-9 ]*$/;
            return re.test(phone);
        }
    }));






