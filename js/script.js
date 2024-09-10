
$(document).ready(function () {

    if (localStorage.getItem('cookiesChoice') === null) {
        $('#cookie-consent-banner').show();
    }
    function handleCookieChoice(accepted) {
        // Enregistrer le choix
        localStorage.setItem('cookiesChoice', accepted ? 'accepted' : 'declined');

        // Cacher le bandeau
        $('#cookie-consent-banner').hide();

        if (accepted) {
            // Si accepté, définir les cookies
            const firstname = $('#firstname').val() || '';
            const name = $('#name').val() || '';
            const email = $('#email').val() || '';
            const phone = $('#phone').val() || '';

            const expiration = new Date();
            expiration.setTime(expiration.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 jours

            document.cookie = `user_firstname=${firstname}; expires=${expiration.toUTCString()}; path=/`;
            document.cookie = `user_name=${name}; expires=${expiration.toUTCString()}; path=/`;
            document.cookie = `user_email=${email}; expires=${expiration.toUTCString()}; path=/`;
            document.cookie = `user_phone=${phone}; expires=${expiration.toUTCString()}; path=/`;

            console.log("Cookies acceptés et définis");
        } else {
            // Si refusé, supprimer les cookies existants
            document.cookie = "user_firstname=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "user_name=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "user_email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "user_phone=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

            console.log("Cookies refusés et supprimés");
        }
    }

    // Gérer le clic sur le bouton "Accepter"
    $('#accept-cookies').click(function () {
        handleCookieChoice(true);
    });

    // Gérer le clic sur le bouton "Refuser"
    $('#decline-cookies').click(function () {
        handleCookieChoice(false);
    });

    // Vérifier et afficher la bannière au chargement de la page si nécessaire

    function highlightNames() {
        const colorsOfNames = {
            "Charlotte Goupil": "#741D34",
            "Fabienne Bergès": "#1D7461",
            "Éditeur": "#741D34",
            "Développeur": "#1D7461",
            "https://multiples-charlotte.fr": "#741D34",
        };
        const mainContent = $("#mentionsLegales, #politiqueConfidentialite");
        let content = mainContent.html();
        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }
        // Remplacer les noms en dehors des balises <h3>
        content = content.replace(
            /(<h3>.*?<\/h3>)|([^<>]+)(?=<(?!\/h3))/g,
            function (match, h3Content, textContent) {
                if (h3Content) {
                    // Si c'est le contenu d'une balise <h3>, le laisser tel quel
                    return h3Content;
                } else if (textContent) {
                    // Pour le texte en dehors des balises <h3>, appliquer la coloration
                    for (const [name, color] of Object.entries(colorsOfNames)) {
                        const regex = new RegExp(escapeRegExp(name), 'g');
                        textContent = textContent.replace(regex, `<span style="color: ${color}; font-weight: bold">${name}</span>`);
                    }
                    return textContent;
                }
                return match;
            }
        );
        mainContent.html(content);
    }
    // Vérifiez si l'URL correspond à la page spécifique
    if (window.location.pathname === "/Multiples_Charlotte/MentionsLegales.php" || window.location.pathname === "/Multiples_Charlotte/PolitiqueConfidentialite.php") {
        highlightNames();
    }
}),

    $(".not-authorized-overlay").hide();

$("#Bulles .photo").hover(
    function () {
        $(this).find(".not-authorized-overlay").stop(true, true).fadeIn(300);
    },
    function () {
        $(this).find(".not-authorized-overlay").stop(true, true).fadeOut(100);
    }
);

$(function () {
    $(".navbar a").on("click", function (t) {
        t.preventDefault();
        var o = this.hash;
        $("body,html").animate({ scrollTop: $(o).offset().top }, 850, function () {
            window.location.hash = o;
        });
    });

    $("#myNavbar a").on("click", function () {
        $("#myNavbar").collapse("hide");
    });
    function validateField($field, validationFunction) {
        const value = $field.val().trim();
        const $errorMessage = $field.next(".comments");

        if (validationFunction(value)) {
            $errorMessage.empty(); // Efface le message d'erreur
            return true;
        } else {
            return false; // Le message d'erreur sera affiché lors de la soumission
        }
    }
    // Validation en temps réel pour chaque champ
    $("#firstname, #name").on('input', function () {
        validateField($(this), value => value !== "" && validateNameAndFirstname(value));
    });
    $("#email").on('input', function () {
        validateField($(this), validateEmail);
    });
    $("#phone").on('input', function () {
        validateField($(this), validatePhone);
    });
    $("#subject").on('change', function () {
        validateField($(this), value => value !== "");
    });
    $("#message").on('input', function () {
        validateField($(this), value => value !== "");
    });
    $("#data-consent").on('change', function () {
        validateField($(this), value => $(this).is(":checked"));
    });
    $("#contact-form").submit(function (event) {
        event.preventDefault(); // Empêche l'envoi par défaut du formulaire
        if (localStorage.getItem('cookiesAccepted') !== 'true') {
            alert("Vous devez accepter l'utilisation des cookies pour soumettre le formulaire.");
            return; // Ne soumet pas le formulaire
        }
        $(".comments").empty(); // Vide les messages d'erreur précédents
        let isValid = true;
        // Vérification des champs
        isValid = validateField($("#firstname"), value => value !== "" && validateNameAndFirstname(value)) && isValid;
        isValid = validateField($("#name"), value => value !== "" && validateNameAndFirstname(value)) && isValid;
        isValid = validateField($("#email"), validateEmail) && isValid;
        isValid = validateField($("#phone"), validatePhone) && isValid;
        isValid = validateField($("#subject"), value => value !== "") && isValid;
        isValid = validateField($("#message"), value => value !== "") && isValid;
        isValid = validateField($("#data-consent"), value => $("#data-consent").is(":checked")) && isValid;
        // Affichage des messages d'erreur si nécessaire
        if (!$("#firstname").val().trim()) {
            $("#firstname").next(".comments").html("Merci de m'indiquer votre prénom");
        } else if (!validateNameAndFirstname($("#firstname").val().trim())) {
            $("#firstname").next(".comments").html("Le prénom ne doit pas contenir de chiffres");
        }
        if (!$("#name").val().trim()) {
            $("#name").next(".comments").html("Votre nom est aussi nécessaire");
        } else if (!validateNameAndFirstname($("#name").val().trim())) {
            $("#name").next(".comments").html("Le nom ne doit pas contenir de chiffres");
        }
        if (!validateEmail($("#email").val())) {
            $("#email").next(".comments").html("Votre email n'est pas valide");
        }
        if (!validatePhone($("#phone").val())) {
            $("#phone").next(".comments").html("Seuls les chiffres sont utilisables.");
        }
        if (!$("#subject").val().trim()) {
            $("#subject").next(".comments").html("Désolé, vous avez oublié d'indiquer le sujet de votre message");
        }
        if (!$("#message").val().trim()) {
            $("#message").next(".comments").html("Vous avez oublié d'écrire votre message");
        }
        if (!$("#data-consent").is(":checked")) {
            $("#data-consent").next(".comments").html("Vous devez accepter les termes et conditions");
        }
        // Envoi du formulaire si tout est valide
        if (isValid) {
            let formData = $("#contact-form").serialize();
            $.ajax({
                type: "POST",
                url: "php/contact.php",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.isSuccess) {
                        $("#contact-form").append("<p class='thank-you'>Votre message a bien été envoyé. Merci de m'avoir contacté :)</p>");
                        $("#contact-form")[0].reset();
                        $("#contact-form .thank-you").delay(3000).fadeOut("slow");

                        if (response.cookiesSet) {
                            console.log("Cookies have been set successfully");
                            // Vous pouvez ajouter ici du code pour informer l'utilisateur que ses informations ont été sauvegardées
                        }
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
    function validateNameAndFirstname(value) {
        const nameRegex = /^[a-zA-ZÀ-ÿ\s'-]*$/;  // Inclut les accents, espaces, apostrophes et tirets
        return nameRegex.test(value);
    }
    function validateEmail(email) {
        const nameRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return nameRegex.test(email);
    }
    function validatePhone(phone) {
        const nameRegex = /^[0-9 ]*$/;
        return nameRegex.test(phone);
    }
});






