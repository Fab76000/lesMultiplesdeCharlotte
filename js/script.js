$(document).ready(function () {
    // Vérifier si l'utilisateur a déjà fait un choix de cookies
    if (localStorage.getItem('cookiesChoice') === null) {
        $('#cookie-consent-banner').show();  // Affiche la bannière si aucun choix n'a été fait
    } else {
        $('#cookie-consent-banner').hide();  // Cache la bannière si un choix a été fait
    }

    function handleCookieChoice(accepted) {
        if (typeof accepted !== 'boolean') {
            console.error('handleCookieChoice: accepted must be a boolean');
            return;
        }

        // Enregistrer le choix de l'utilisateur dans le localStorage
        if (localStorage) {
            localStorage.setItem('cookiesChoice', accepted ? 'true' : 'false');
        } else {
            console.error('handleCookieChoice: localStorage is not supported');
            return;
        }

        // Cacher la bannière
        if ($('#cookie-consent-banner')) {
            $('#cookie-consent-banner').hide();
        } else {
            console.error('handleCookieChoice: #cookie-consent-banner is not found');
            return;
        }

        if (accepted) {
            // Si accepté, définir les cookies
            setCookies();
        } else {
            // Si refusé, supprimer les cookies
            deleteCookies();
        }
    }

    // Gestion du clic sur "Accepter"
    $('#accept-cookies').click(function () {
        handleCookieChoice(true);
    });

    // Gestion du clic sur "Refuser"
    $('#decline-cookies').click(function () {
        handleCookieChoice(false);
    });
    // Fonction pour définir les cookies (à adapter côté serveur pour HttpOnly)
    function setCookies() {
        const firstname = $('#firstname').val() || '';
        const name = $('#name').val() || '';
        const email = $('#email').val() || '';
        const phone = $('#phone').val() || '';
        const expiration = new Date();
        expiration.setTime(expiration.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 jours

        document.cookie = `user_firstname=${firstname}; expires=${expiration.toUTCString()}; path=/; secure; SameSite=Strict`;
        document.cookie = `user_name=${name}; expires=${expiration.toUTCString()}; path=/; secure; SameSite=Strict`;
        document.cookie = `user_email=${email}; expires=${expiration.toUTCString()}; path=/; secure; SameSite=Strict`;
        document.cookie = `user_phone=${phone}; expires=${expiration.toUTCString()}; path=/; secure; SameSite=Strict`;
    }

    // Fonction pour supprimer les cookies (ceux gérables côté client)
    function deleteCookies() {
        document.cookie = "user_firstname=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
        document.cookie = "user_name=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
        document.cookie = "user_email=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
        document.cookie = "user_phone=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
    }

    // Validation des champs et soumission du formulaire
    $("#contact-form").submit(function (event) {
        event.preventDefault(); // Empêche l'envoi par défaut du formulaire

        $(".comments").empty(); // Vide les messages d'erreur précédents
        let isValid = true;

        // Vérification des champs
        isValid = validateField($("#firstname"), value => value !== "" && validateNameAndFirstname(value)) && isValid;
        isValid = validateField($("#name"), value => value !== "" && validateNameAndFirstname(value)) && isValid;
        isValid = validateField($("#email"), validateEmail) && isValid;
        isValid = validateField($("#phone"), validatePhone) && isValid;
        isValid = validateField($("#subject"), value => value !== "") && isValid;
        isValid = validateField($("#message"), value => value !== "") && isValid;
        isValid = validateField($("#data-consent"), value => value === true) && isValid;

        // Si tout est valide, envoi du formulaire
        if (isValid) {
            let formData = $("#contact-form").serialize();

            // Ajouter l'état des cookies à formData
            formData += "&cookiesAccepted=" + (localStorage.getItem('cookiesChoice') === 'true');

            $.ajax({
                type: "POST",
                url: "php/contact.php",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.isSuccess) {
                        $("#contact-form").append("<p class='thank-you'>Votre message a bien été envoyé. Merci de m'avoir contacté :)</p>");
                        $("#contact-form")[0].reset();
                        $(".thank-you").delay(3000).fadeOut("slow");

                        if (localStorage.getItem('cookiesChoice') === 'true') {
                            setCookies();
                        }
                    } else {
                        // Affichage des erreurs
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

    // Fonctions de validation
    function validateNameAndFirstname(value) {
        const nameRegex = /^[a-zA-ZÀ-ÿ\s'-]*$/;
        return nameRegex.test(value);
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validatePhone(phone) {
        const phoneRegex = /^[0-9 ]*$/;
        return phoneRegex.test(phone);
    }



    function setCookies() {
        const expiration = new Date();
        expiration.setTime(expiration.getTime() + (7 * 24 * 60 * 60 * 1000)); // 7 jours


        function escapeCookieValue(value) {
            return encodeURIComponent(value).replace(/%20/g, '+'); // Encode et remplace les espaces par des +
        }

        const firstname = escapeCookieValue($("#firstname").val() || '');
        const name = escapeCookieValue($("#name").val() || '');
        const email = escapeCookieValue($("#email").val() || '');
        const phone = escapeCookieValue($("#phone").val() || '');

        document.cookie = `user_firstname=${firstname}; expires=${expiration.toUTCString()}; path=/; Secure; SameSite=Strict`;
        document.cookie = `user_name=${name}; expires=${expiration.toUTCString()}; path=/; Secure; SameSite=Strict`;
        document.cookie = `user_email=${email}; expires=${expiration.toUTCString()}; path=/; Secure; SameSite=Strict`;
        document.cookie = `user_phone=${phone}; expires=${expiration.toUTCString()}; path=/; Secure; SameSite=Strict`;
    }
});
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

});
