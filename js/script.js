//Permet le changement de plusieurs images au clic$(document).ready(function () {


$(document).ready(function () {
	// Initial hiding of overlays
	$('.not-authorized-overlay').hide();
	$('#Bulles .photo').hover(
		function () {
			$(this).find('.not-authorized-overlay').toggle(200);
		}
	);
});


//Permet d'aller progressivement dans une section du menu

$(function () {
	$(".navbar a ").on("click", function (event) {
		event.preventDefault();
		var hash = this.hash;
		$('body,html').animate({ scrollTop: $(hash).offset().top }, 850, function () {
			window.location.hash = hash;
		})
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
					$("#subject + .comments").html(result.subjectError);
					$("#message + .comments").html(result.messageError);
				}
			}
		});
	});
});



