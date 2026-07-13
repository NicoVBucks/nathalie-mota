<?php
/**
 * Page d'accueil : hero, filtres et catalogue de photos.
 *
 * WordPress choisit automatiquement ce template pour la page d'accueil.
 */

get_header();

// --- Hero -------------------------------------------------------------------
// Une photo au hasard parmi les photos au format paysage, pour que le hero
// change à chaque visite sans avoir à coder une image en dur.
$photo_hero = new WP_Query(
	array(
		'post_type'      => 'photo',
		'posts_per_page' => 1,
		'orderby'        => 'rand',
		'tax_query'      => array(
			array(
				'taxonomy' => 'format',
				'field'    => 'slug',
				'terms'    => 'paysage',
			),
		),
	)
);

$image_hero = '';
if ( $photo_hero->have_posts() ) {
	$photo_hero->the_post();
	$image_hero = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	wp_reset_postdata();
}
?>

<section class="hero" style="background-image: url('<?php echo esc_url( $image_hero ); ?>');">
	<h1 class="hero__titre">Photographe Event</h1>
</section>

<section class="catalogue">

	<!-- Filtres : les listes sont remplies dynamiquement depuis les taxonomies,
	     pour que les nouveaux termes créés en back-office apparaissent seuls. -->
	<div class="filtres">

		<?php
		// Le libellé du filtre ("Catégories") est l'option par défaut du select,
		// conformément à la maquette : il n'y a pas d'étiquette au-dessus.
		wp_dropdown_categories(
			array(
				'taxonomy'        => 'categorie',
				'name'            => 'filtre-categorie',
				'id'              => 'filtre-categorie',
				'show_option_all' => 'Catégories',
				'value_field'     => 'slug',
				'hide_empty'      => false,
				'class'           => 'filtres__select',
			)
		);

		wp_dropdown_categories(
			array(
				'taxonomy'        => 'format',
				'name'            => 'filtre-format',
				'id'              => 'filtre-format',
				'show_option_all' => 'Formats',
				'value_field'     => 'slug',
				'hide_empty'      => false,
				'class'           => 'filtres__select',
			)
		);
		?>

		<select name="filtre-tri" id="filtre-tri" class="filtres__select filtres__select--tri">
			<option value="">Trier par</option>
			<option value="DESC">Plus récentes</option>
			<option value="ASC">Plus anciennes</option>
		</select>

	</div>

	<!-- Grille des photos : 8 au chargement, puis 8 de plus à chaque clic. -->
	<div class="catalogue__grille" id="catalogue-grille">
		<?php
		$photos = nathaliemota_query_photos();

		while ( $photos->have_posts() ) :
			$photos->the_post();
			get_template_part( 'template-parts/photo-block' );
		endwhile;

		wp_reset_postdata();
		?>
	</div>

	<?php
	// Le bouton n'est affiché que s'il reste effectivement des photos à charger.
	if ( $photos->max_num_pages > 1 ) :
		?>
		<div class="catalogue__plus">
			<button type="button" class="bouton" id="charger-plus">Charger plus</button>
		</div>
	<?php endif; ?>

</section>

<?php
get_footer();
