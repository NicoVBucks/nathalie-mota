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
	// photo_large (1600 px) et non 'full' : le hero est décoratif, servir
	// l'original de Nathalie serait plusieurs mégaoctets pour rien (Green Code).
	$image_hero = get_the_post_thumbnail_url( get_the_ID(), 'photo_large' );
	wp_reset_postdata();
}

/**
 * Prépare la liste des choix d'un filtre à partir d'une taxonomie.
 * Les termes sont lus en base : une nouvelle catégorie créée dans
 * l'administration apparaît donc automatiquement dans le filtre.
 */
function nathaliemota_options_taxonomie( $taxonomie ) {
	$options = array();
	$termes  = get_terms(
		array(
			'taxonomy'   => $taxonomie,
			'hide_empty' => false,
		)
	);

	if ( ! is_wp_error( $termes ) ) {
		foreach ( $termes as $terme ) {
			$options[ $terme->slug ] = $terme->name;
		}
	}

	return $options;
}
?>

<section class="hero" style="background-image: url('<?php echo esc_url( $image_hero ); ?>');">
	<h1 class="hero__titre">Photographe Event</h1>
</section>

<section class="catalogue">

	<div class="filtres">

		<?php
		get_template_part(
			'template-parts/filtre',
			null,
			array(
				'id'      => 'filtre-categorie',
				'libelle' => 'Catégories',
				'options' => nathaliemota_options_taxonomie( 'categorie' ),
			)
		);

		get_template_part(
			'template-parts/filtre',
			null,
			array(
				'id'      => 'filtre-format',
				'libelle' => 'Formats',
				'options' => nathaliemota_options_taxonomie( 'format' ),
			)
		);

		get_template_part(
			'template-parts/filtre',
			null,
			array(
				'id'      => 'filtre-tri',
				'libelle' => 'Trier par',
				'classe'  => 'filtre--tri',
				'options' => array(
					'DESC' => 'Plus récentes',
					'ASC'  => 'Plus anciennes',
				),
			)
		);
		?>

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
