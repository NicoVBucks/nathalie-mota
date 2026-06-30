/**
 * Thème Nathalie Mota — scripts globaux.
 *
 * Étape 1 : ouverture / fermeture de la modale de contact.
 * (La pagination Ajax du catalogue et la lightbox viendront aux étapes 4 et 5.)
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
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
