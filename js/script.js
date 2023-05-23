//Permet le changement de plusieurs images au clic
/*$(document).ready(function () {

	$('#Bulles .photo').click(function () {
		const overlay = $(this).find('.not-authorized-overlay')
		overlay.toggle()
	})

});*/

$(document).ready(function () {

	$('#Bulles .photo').hover(function () {
		const overlay = $(this).find('.not-authorized-overlay')
		setTimeout(() => { ; overlay.toggle(); }, 250)
	})

});

// Récupérer les éléments HTML nécessaires
/*const photos = document.querySelectorAll('.photo');
const overlays = document.querySelectorAll('.overlay');

// Fonction pour retourner l'image et afficher le texte
function flipPhoto() {
	// Récupérer l'overlay de l'image survolée
	const overlay = this.querySelector('.overlay');
	// Changer la classe pour déclencher l'animation CSS
	overlay.classList.toggle('flip');
}

// Écouter l'événement de survol sur chaque photo
photos.forEach(photo => photo.addEventListener('mouseover', flipPhoto));
photos.forEach(photo => photo.addEventListener('mouseout', flipPhoto));*/


//Permet d'aller progressivement dans une section du menu

$(function () {

	$(".navbar a ").on("click", function (event) {

		event.preventDefault();
		var hash = this.hash;

		$('body,html').animate({ scrollTop: $(hash).offset().top }, 850, function () { window.location.hash = hash; })

	});

	$("#myNavbar a").on("click", function () {

		$("#myNavbar").collapse("hide");
	});

	//Formulaire de contact avec message d'erreurs

	// Fonction JavaScript pour l'envoi du formulaire

	$('#contact-form').submit(function (e) {
		e.preventDefault();
		$('.comments').empty();
		let postdata = $('#contact-form').serialize();

		$.ajax({
			type: 'POST',
			url: 'php/contact.php',
			data: postdata,
			dataType: 'json',
			success: function (result) {


				if (result.isSuccess) {

					$("#contact-form").append("<p class='thank-you'>Votre message a bien été envoyé. Merci de m'avoir contacté :)</p>");
					$("#contact-form")[0].reset();
				}

				else {

					$("#firstname + .comments").html(result.firstnameError);

					$("#name + .comments").html(result.nameError);

					$("#email + .comments").html(result.emailError);

					$("#phone + .comments").html(result.phoneError);

					$("#message + .comments").html(result.messageError);

				}
			}


		});
	});
});



