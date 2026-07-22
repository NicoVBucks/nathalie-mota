/**
 * Listes déroulantes personnalisées des filtres.
 *
 * Chaque filtre contient un vrai <select> masqué : c'est lui qui porte la
 * valeur. Le clic sur une option met le select à jour et déclenche son
 * événement "change" — le script du catalogue continue donc de fonctionner
 * sans aucune modification, et le formulaire reste utilisable sans JavaScript.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var filtres = document.querySelectorAll('[data-filtre]');
		if (!filtres.length) {
			return;
		}

		filtres.forEach(function (filtre) {
			var select = filtre.querySelector('.filtre__select');
			var bouton = filtre.querySelector('.filtre__bouton');
			var valeur = filtre.querySelector('.filtre__valeur');
			var liste = filtre.querySelector('.filtre__liste');
			var options = filtre.querySelectorAll('.filtre__option');

			function ouvrir() {
				fermerTous();
				liste.hidden = false;
				filtre.classList.add('is-open');
				bouton.setAttribute('aria-expanded', 'true');
			}

			function fermer() {
				liste.hidden = true;
				filtre.classList.remove('is-open');
				bouton.setAttribute('aria-expanded', 'false');
			}

			bouton.addEventListener('click', function () {
				if (filtre.classList.contains('is-open')) {
					fermer();
				} else {
					ouvrir();
				}
			});

			options.forEach(function (option) {
				option.addEventListener('click', function () {
					// On met à jour l'affichage...
					valeur.textContent = option.textContent.trim();
					options.forEach(function (autre) {
						autre.classList.remove('is-selected');
					});
					option.classList.add('is-selected');

					// ...puis le select réel, en signalant le changement pour
					// que le catalogue se recharge en Ajax.
					select.value = option.dataset.valeur;
					select.dispatchEvent(new Event('change', { bubbles: true }));

					fermer();
				});
			});
		});

		// Un clic ailleurs sur la page referme les listes ouvertes.
		function fermerTous() {
			filtres.forEach(function (filtre) {
				filtre.querySelector('.filtre__liste').hidden = true;
				filtre.classList.remove('is-open');
				filtre.querySelector('.filtre__bouton').setAttribute('aria-expanded', 'false');
			});
		}

		document.addEventListener('click', function (evenement) {
			if (!evenement.target.closest('[data-filtre]')) {
				fermerTous();
			}
		});

		document.addEventListener('keydown', function (evenement) {
			if (evenement.key === 'Escape') {
				fermerTous();
			}
		});
	});
})();
