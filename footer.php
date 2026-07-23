<?php
/**
 * Pied de page : fermeture du <main>, footer, lightbox et modale de contact.
 *
 * La lightbox et la modale sont incluses ici car elles sont appelables depuis
 * n'importe quelle page du site.
 *
 * @package nathaliemota
 */
?>
</main><!-- #main -->

<footer class="site-footer">
	<div class="container site-footer__inner">
		<?php
		// Page "Mentions légales" : créée plus tard. On pointe dessus si elle existe.
		$mentions = get_page_by_path( 'mentions-legales' );
		$privacy  = get_option( 'wp_page_for_privacy_policy' );
		?>

		<a href="<?php echo esc_url( $mentions ? get_permalink( $mentions ) : '#' ); ?>">
			<?php esc_html_e( 'Mentions légales', 'nathaliemota' ); ?>
		</a>

		<a href="<?php echo esc_url( $privacy ? get_permalink( $privacy ) : '#' ); ?>">
			<?php esc_html_e( 'Vie privée', 'nathaliemota' ); ?>
		</a>

		<span><?php esc_html_e( 'Tous droits réservés', 'nathaliemota' ); ?></span>
	</div>
</footer>

<!-- Lightbox : présente sur toutes les pages qui affichent des photos.
     Elle est remplie par le JavaScript au moment de l'ouverture. -->
<div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-hidden="true"
	aria-label="Photo en plein écran">

	<button type="button" class="lightbox__fermer js-close-lightbox" aria-label="Fermer">
		&times;
	</button>

	<button type="button" class="lightbox__nav lightbox__nav--prec js-lightbox-prec">
		<?php get_template_part( 'template-parts/fleche', null, array( 'sens' => 'prec', 'classe' => 'lightbox__nav-icon' ) ); ?>
		<span>Précédente</span>
	</button>

	<figure class="lightbox__contenu">
		<img src="" alt="" class="lightbox__image" id="lightbox-image">
		<figcaption class="lightbox__legende">
			<span class="lightbox__reference" id="lightbox-reference"></span>
			<span class="lightbox__categorie" id="lightbox-categorie"></span>
		</figcaption>
	</figure>

	<button type="button" class="lightbox__nav lightbox__nav--suiv js-lightbox-suiv">
		<span>Suivante</span>
		<?php get_template_part( 'template-parts/fleche', null, array( 'sens' => 'suiv', 'classe' => 'lightbox__nav-icon' ) ); ?>
	</button>

</div>

<?php
// Modale de contact, disponible sur tout le site.
get_template_part( 'template-parts/modal', 'contact' );

wp_footer();
?>
</body>
</html>
