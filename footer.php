<?php
/**
 * Pied de page : fermeture du <main>, footer et appel de la modale de contact.
 * La modale est incluse ici car elle est appelable depuis n'importe quelle page.
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

<?php
// Modale de contact, disponible sur tout le site.
get_template_part( 'template-parts/modal', 'contact' );

wp_footer();
?>
</body>
</html>
