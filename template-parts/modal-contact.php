<?php
/**
 * Template part : modale de contact.
 *
 * Le formulaire est géré par l'extension Contact Form 7.
 * 1. Installez/activez Contact Form 7.
 * 2. Créez un formulaire avec les champs : nom, e-mail, réf. photo (optionnel), message.
 *    Nommez le champ réf. photo "ref-photo" pour pouvoir le préremplir (étape 3).
 * 3. Renseignez l'ID du formulaire ci-dessous (NATHALIEMOTA_CF7_ID) ou collez
 *    directement votre shortcode.
 *
 * @package nathaliemota
 */

// ⚠️ Remplacez par l'ID (ou le hash) du formulaire CF7 créé dans l'admin.
$nathaliemota_cf7_id = defined( 'NATHALIEMOTA_CF7_ID' ) ? NATHALIEMOTA_CF7_ID : '';
?>

<div id="contact-modal" class="contact-modal" role="dialog" aria-modal="true"
	aria-labelledby="contact-modal-title" aria-hidden="true">

	<div class="contact-modal__panel">

		<button type="button" class="contact-modal__close js-close-contact"
			aria-label="<?php esc_attr_e( 'Fermer la fenêtre de contact', 'nathaliemota' ); ?>">
			&times;
		</button>

		<h2 id="contact-modal-title" class="contact-modal__title">
			<?php esc_html_e( 'Contact', 'nathaliemota' ); ?>
		</h2>

		<?php // Bande décorative de la maquette mobile : "CONTACT" répété en
		      // italique, débordant du panneau et coupé aux bords. Purement
		      // visuel (aria-hidden) — le nom accessible reste porté par le <h2>. ?>
		<p class="contact-modal__title-deco" aria-hidden="true">CONTACTCONTACTCONTACTCONTACTCONTACTCONTACTCONTACTCONTACTCONTACT</p>

		<div class="contact-modal__form">
			<?php
			if ( shortcode_exists( 'contact-form-7' ) && $nathaliemota_cf7_id ) {
				echo do_shortcode( '[contact-form-7 id="' . esc_attr( $nathaliemota_cf7_id ) . '" title="Contact"]' );
			} else {
				?>
				<p class="contact-modal__notice">
					<?php esc_html_e( 'Le formulaire de contact apparaîtra ici une fois Contact Form 7 installé et l\'ID du formulaire renseigné dans template-parts/modal-contact.php.', 'nathaliemota' ); ?>
				</p>
				<?php
			}
			?>
		</div>

	</div>
</div>
