<?php
/**
 * Template des pages.
 *
 * Sert notamment à la page "À propos", qui ne contient que le header,
 * le footer et un contenu vide rempli par Nathalie via l'éditeur WordPress.
 *
 * @package nathaliemota
 */

get_header();
?>

<article class="container" style="padding-block: clamp(2rem, 8vh, 5rem);">
	<?php
	while ( have_posts() ) :
		the_post();

		if ( ! is_front_page() && get_the_title() ) {
			echo '<h1 class="page-title">' . esc_html( get_the_title() ) . '</h1>';
		}

		the_content();

	endwhile;
	?>
</article>

<?php
get_footer();
