<?php
/**
 * Template par défaut / page d'accueil.
 *
 * Étape 1 : on vérifie que le header, une zone de contenu et le footer fonctionnent.
 * Le vrai hero, les filtres et le catalogue seront intégrés à l'étape 4.
 *
 * @package nathaliemota
 */

get_header();
?>

<section class="placeholder-hero container">
	<h1><?php bloginfo( 'name' ); ?></h1>
	<p><?php esc_html_e( 'Photographe événementiel — site en cours de construction.', 'nathaliemota' ); ?></p>
</section>

<section class="container" style="padding-bottom: 3rem;">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	endif;
	?>
</section>

<?php
get_footer();
