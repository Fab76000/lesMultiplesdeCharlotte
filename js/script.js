// Menu burger
document.addEventListener("DOMContentLoaded", function () {
    var burger = document.getElementById("burger");
    var menu = document.getElementById("menuderoulant");
    if (burger && menu) {
        burger.addEventListener("click", function () {
            menu.classList.toggle("collapsed");
            burger.classList.toggle("cross");
        });
    }
});

$(document).on('DOMContentLoaded', function () {
    // Vérifier si l'utilisateur a déjà fait un choix de cookies
    if (localStorage.getItem('cookiesChoice') === null) {
        $('#cookie-consent-banner').show();  // Affiche la bannière si aucun choix n'a été fait
    } else {
        $('#cookie-consent-banner').hide();  // Cache la bannière si un choix a été fait
    }

    function handleCookieChoice(accepted) {
        try {
            if (typeof accepted !== 'boolean') {
                throw new Error('accepted must be a boolean');
            }

            if (!window.localStorage) {
                throw new Error('localStorage is not supported');
            }

            localStorage.setItem('cookiesChoice', accepted ? 'true' : 'false');

            const banner = $('#cookie-consent-banner');
            if (!banner.length) {
                throw new Error('#cookie-consent-banner is not found');
            }

            banner.hide();

            if (accepted) {
                setCookies();
            } else {
                deleteCookies();
            }
        } catch (error) {
            console.error('handleCookieChoice:', error.message);
        }
    }
    // Gestion du clic sur "Accepter"
    $('#accept-cookies').on('click', function () {
        handleCookieChoice(true);
    });

    $('#decline-cookies').on('click', function () {
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
    function validateField($field, validationFunction) {
        const value = $field.attr('type') === 'checkbox' ? $field.is(":checked") : $field.val().trim();
        const $errorMessage = $field.next(".comments");

        if (validationFunction(value)) {
            $errorMessage.empty(); // Efface le message d'erreur
            return true;
        } else {
            if ($field.attr('type') === 'checkbox') {
                $errorMessage.html("Vous devez accepter les termes et conditions");
            }
            return false;
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
        validateField($(this), value => value === true);
    });

    // Gérer la soumission du formulaire

    $("#contact-form").on('submit', function (event) {
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

        // Affichage des messages d'erreur si nécessaire
        if (!$("#firstname").val().trim()) {
            $("#firstname").next(".comments").html("Merci d'indiquer votre prénom");
        } else if (!validateNameAndFirstname($("#firstname").val().trim())) {
            $("#firstname").next(".comments").html("Le prénom ne doit pas contenir de chiffres");
        }
        if (!$("#name").val().trim()) {
            $("#name").next(".comments").html("Votre nom est aussi nécessaire");
        } else if (!validateNameAndFirstname($("#name").val().trim())) {
            $("#name").next(".comments").html("Le nom ne doit pas contenir de chiffres");
        }
        if (!validateEmail($("#email").val())) {
            $("#email").next(".comments").html("Merci de m'indiquer votre e-mail");
        }
        if (!$("#phone").val().trim()) {
            $("#phone").next(".comments").html("Désolé, vous avez oublié d'indiquer votre numéro de téléphone");
        } else if (!validatePhone($("#phone").val().trim())) {
            $("#phone").next(".comments").html("Le numéro de téléphone doit contenir uniquement des chiffres");
        }
        if (!$("#subject").val().trim()) {
            $("#subject").next(".comments").html("Désolé, vous avez oublié d'indiquer le sujet de votre message");
        }
        if (!$("#message").val().trim()) {
            $("#message").next(".comments").html("Vous avez oublié d'écrire votre message");
        }
        if (!$("#data-consent").is(":checked")) {
            $("#data-consent").next(".comments").html("Vous devez accepter les termes et conditions");
            isValid = false;
        }

        // Envoi du formulaire si tout est valide
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
                        $("#contact-form .thank-you").delay(3000).fadeOut("slow");

                        // Définir les cookies si acceptés
                        if (localStorage.getItem('cookiesChoice') === 'true') {
                            setCookies();
                            console.log("Cookies have been set successfully");
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

/*** Fonction de mise en surbrillance des noms : ne fonctionne plus depuis restructuration html */
/*function highlightNames() {
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
        /(<h2>.*?<\/h2>)|(<h3>.*?<\/h3>)|([^<>]+)(?=<(?!\/h3))/g,
        function (match, h2Content, h3Content, textContent) {
            if (h2Content) {
                // Si c'est le contenu d'une balise <h2>, le laisser tel quel
                return h2Content;
            }
            else if (h3Content) {
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
if (window.location.pathname === "/mentionsLegales.php" || window.location.pathname === "/politiqueConfidentialite.php") {
    highlightNames();
}

});*/



$(".not-authorized-overlay").hide();

$("#Bulles .photo").on("mouseover", function () {
    $(this).find(".not-authorized-overlay").stop(true, true).fadeIn(300);
});

$("#Bulles .photo").on("mouseout", function () {
    $(this).find(".not-authorized-overlay").stop(true, true).fadeOut(100);
});

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
