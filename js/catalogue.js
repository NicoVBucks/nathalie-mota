/**
 * Catalogue de la page d'accueil : filtres, tri et pagination "Charger plus".
 *
 * Les trois fonctionnalités reposent sur le même appel Ajax : seul le numéro
 * de page change. Filtrer ou trier repart de la page 1 et remplace la grille ;
 * "Charger plus" demande la page suivante et l'ajoute à la suite.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var grille = document.getElementById('catalogue-grille');
		if (!grille) {
			return; // On n'est pas sur la page d'accueil.
		}

		var selectCategorie = document.getElementById('filtre-categorie');
		var selectFormat = document.getElementById('filtre-format');
		var selectTri = document.getElementById('filtre-tri');
		var boutonPlus = document.getElementById('charger-plus');

		var pageCourante = 1;

		/**
		 * Interroge l'API de WordPress et affiche les photos reçues.
		 *
		 * @param {boolean} ajouter true = ajouter à la suite ("Charger plus"),
		 *                          false = remplacer la grille (filtre ou tri).
		 */
		function chargerPhotos(ajouter) {
			var donnees = new FormData();
			donnees.append('action', 'filtrer_photos');
			donnees.append('nonce', nathaliemotaData.nonce);
			donnees.append('categorie', selectCategorie ? selectCategorie.value : '');
			donnees.append('format', selectFormat ? selectFormat.value : '');
			donnees.append('tri', selectTri ? selectTri.value : 'DESC');
			donnees.append('page', pageCourante);

			fetch(nathaliemotaData.ajaxUrl, {
				method: 'POST',
				body: donnees
			})
				.then(function (reponse) {
					return reponse.json();
				})
				.then(function (resultat) {
					if (!resultat.success) {
						return;
					}

					if (ajouter) {
						grille.insertAdjacentHTML('beforeend', resultat.data.html);
					} else {
						grille.innerHTML = resultat.data.html;
					}

					// On masque le bouton quand il n'y a plus rien à charger :
					// inutile de laisser l'utilisateur déclencher un appel vide.
					if (boutonPlus) {
						boutonPlus.hidden = !resultat.data.reste_photos;
					}
				})
				.catch(function (erreur) {
					console.error('Erreur lors du chargement des photos :', erreur);
				});
		}

		// Filtres et tri : on repart toujours de la première page.
		function auChangementDeFiltre() {
			pageCourante = 1;
			chargerPhotos(false);
		}

		if (selectCategorie) {
			selectCategorie.addEventListener('change', auChangementDeFiltre);
		}
		if (selectFormat) {
			selectFormat.addEventListener('change', auChangementDeFiltre);
		}
		if (selectTri) {
			selectTri.addEventListener('change', auChangementDeFiltre);
		}

		// "Charger plus" : on demande la page suivante et on l'ajoute à la grille.
		if (boutonPlus) {
			boutonPlus.addEventListener('click', function () {
				pageCourante++;
				chargerPhotos(true);
			});
		}
	});
})();
