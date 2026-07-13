/**
 * Lightbox : affiche une photo en plein écran par-dessus la page.
 *
 * Les données de chaque photo (image, référence, catégorie) sont déjà présentes
 * dans les attributs "data-" des boutons "plein écran". Le JavaScript les lit
 * directement : afficher une photo ou naviguer entre elles ne déclenche donc
 * aucun nouvel appel au serveur.
 *
 * La liste des photos est relue à chaque ouverture, ce qui permet de prendre en
 * compte celles ajoutées par les filtres ou par le bouton "Charger plus".
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var lightbox = document.getElementById('lightbox');
		if (!lightbox) {
			return;
		}

		var image = document.getElementById('lightbox-image');
		var reference = document.getElementById('lightbox-reference');
		var categorie = document.getElementById('lightbox-categorie');
		var boutonPrec = lightbox.querySelector('.js-lightbox-prec');
		var boutonSuiv = lightbox.querySelector('.js-lightbox-suiv');

		var photos = [];       // Les boutons "plein écran" présents sur la page.
		var indexCourant = 0;  // Position de la photo affichée dans cette liste.

		/**
		 * Remplit la lightbox avec la photo située à la position demandée.
		 */
		function afficherPhoto(index) {
			var bouton = photos[index];
			if (!bouton) {
				return;
			}

			indexCourant = index;

			image.src = bouton.dataset.image;
			image.alt = bouton.dataset.titre;
			reference.textContent = bouton.dataset.reference;
			categorie.textContent = bouton.dataset.categorie;

			// Une seule photo affichée : les flèches n'ont plus d'utilité.
			var plusieurs = photos.length > 1;
			boutonPrec.hidden = !plusieurs;
			boutonSuiv.hidden = !plusieurs;
		}

		function ouvrir(bouton) {
			// On relit les photos affichées : la grille a pu changer entre-temps.
			photos = Array.from(document.querySelectorAll('.js-open-lightbox'));

			afficherPhoto(photos.indexOf(bouton));

			lightbox.classList.add('is-open');
			lightbox.setAttribute('aria-hidden', 'false');
			document.body.classList.add('is-modal-open');
		}

		function fermer() {
			lightbox.classList.remove('is-open');
			lightbox.setAttribute('aria-hidden', 'true');
			document.body.classList.remove('is-modal-open');
		}

		// Navigation circulaire : après la dernière photo, on revient à la première.
		function precedente() {
			afficherPhoto((indexCourant - 1 + photos.length) % photos.length);
		}

		function suivante() {
			afficherPhoto((indexCourant + 1) % photos.length);
		}

		// Un seul écouteur sur le document : les photos chargées en Ajax après
		// coup sont prises en compte sans avoir à réattacher quoi que ce soit.
		document.addEventListener('click', function (evenement) {
			var ouverture = evenement.target.closest('.js-open-lightbox');
			if (ouverture) {
				ouvrir(ouverture);
				return;
			}

			if (evenement.target.closest('.js-close-lightbox')) {
				fermer();
				return;
			}

			if (evenement.target.closest('.js-lightbox-prec')) {
				precedente();
				return;
			}

			if (evenement.target.closest('.js-lightbox-suiv')) {
				suivante();
				return;
			}

			// Clic sur le fond noir, en dehors de la photo : on ferme.
			if (evenement.target === lightbox) {
				fermer();
			}
		});

		// Raccourcis clavier, actifs seulement quand la lightbox est ouverte.
		document.addEventListener('keydown', function (evenement) {
			if (!lightbox.classList.contains('is-open')) {
				return;
			}

			if (evenement.key === 'Escape') {
				fermer();
			} else if (evenement.key === 'ArrowLeft') {
				precedente();
			} else if (evenement.key === 'ArrowRight') {
				suivante();
			}
		});
	});
})();
