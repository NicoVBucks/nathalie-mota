/**
 * Thème Nathalie Mota — scripts globaux.
 *
 * Deux comportements disponibles sur tout le site : le menu mobile et la
 * modale de contact. Le JavaScript se contente d'ajouter ou de retirer des
 * classes : toute l'animation est portée par le CSS.
 */
(function () {
	'use strict';

	/**
	 * Menu mobile : le bouton "burger" ouvre le panneau plein écran.
	 */
	function initialiserMenu() {
		var bouton = document.querySelector('.js-menu-toggle');
		var nav = document.querySelector('.main-nav');

		if (!bouton || !nav) {
			return;
		}

		function fermerMenu() {
			nav.classList.remove('is-open');
			bouton.setAttribute('aria-expanded', 'false');
			bouton.setAttribute('aria-label', 'Ouvrir le menu');
			document.body.classList.remove('is-modal-open');
		}

		function basculerMenu() {
			if (nav.classList.contains('is-open')) {
				fermerMenu();
				return;
			}

			nav.classList.add('is-open');
			bouton.setAttribute('aria-expanded', 'true');
			bouton.setAttribute('aria-label', 'Fermer le menu');
			document.body.classList.add('is-modal-open');
		}

		bouton.addEventListener('click', basculerMenu);

		// Clic sur un lien du menu : on referme le panneau. Indispensable pour
		// "Contact", qui ouvre la modale par-dessus sans changer de page.
		nav.addEventListener('click', function (evenement) {
			if (evenement.target.closest('a')) {
				fermerMenu();
			}
		});

		document.addEventListener('keydown', function (evenement) {
			if (evenement.key === 'Escape' && nav.classList.contains('is-open')) {
				fermerMenu();
			}
		});

		// Passage en desktop alors que le menu est ouvert : le panneau n'existe
		// plus, mais le défilement du corps de page resterait bloqué. On écoute
		// le franchissement du seuil, et non chaque pixel de redimensionnement ;
		// la requête est mot pour mot celle de style.css.
		var seuilMobile = window.matchMedia('(max-width: 780px)');

		seuilMobile.addEventListener('change', function (evenement) {
			if (!evenement.matches) {
				fermerMenu();
			}
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initialiserMenu();

		var modal = document.getElementById('contact-modal');
		if (!modal) {
			return;
		}

		var lastFocused = null;

		function openModal(trigger) {
			lastFocused = trigger || document.activeElement;

			// Préremplissage du champ "réf. photo" (utilisé à partir de l'étape 3).
			// Le déclencheur peut porter un attribut data-photo-ref.
			if (trigger) {
				var ref = trigger.getAttribute('data-photo-ref');
				var refField = modal.querySelector('input[name="ref-photo"]');
				if (ref && refField) {
					refField.value = ref;
				}
			}

			modal.classList.add('is-open');
			modal.setAttribute('aria-hidden', 'false');
			document.body.classList.add('is-modal-open');

			// Focus sur le premier champ ou le bouton de fermeture.
			var firstField = modal.querySelector('input, textarea, button');
			if (firstField) {
				firstField.focus();
			}
		}

		function closeModal() {
			modal.classList.remove('is-open');
			modal.setAttribute('aria-hidden', 'true');
			document.body.classList.remove('is-modal-open');
			if (lastFocused) {
				lastFocused.focus();
			}
		}

		// Ouverture : tout élément .js-open-contact ou lien href="#contact".
		document.addEventListener('click', function (e) {
			var opener = e.target.closest('.js-open-contact, a[href="#contact"]');
			if (opener) {
				e.preventDefault();
				openModal(opener);
				return;
			}

			// Fermeture : bouton dédié.
			if (e.target.closest('.js-close-contact')) {
				e.preventDefault();
				closeModal();
				return;
			}

			// Fermeture : clic sur l'overlay (en dehors du panneau).
			if (e.target === modal) {
				closeModal();
			}
		});

		// Fermeture : touche Échap.
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && modal.classList.contains('is-open')) {
				closeModal();
			}
		});
	});
})();
