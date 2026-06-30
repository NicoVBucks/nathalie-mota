<?php
/**
 * Template d'affichage d'un contenu seul.
 *
 * Version minimale (dupliquée d'un template standard) qui sera intégrée
 * pour de bon à l'étape 3 (page infos d'une photo).
 *
 * @package nathaliemota
 */

get_header();
?>

<article class="container" style="padding-block: clamp(2rem, 8vh, 5rem);">
	<?php
	while ( have_posts() ) :
		the_post();

		echo '<h1 class="entry-title">' . esc_html( get_the_title() ) . '</h1>';

		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'large' );
		}

		the_content();

	endwhile;
	?>
</article>

<?php
get_footer();
