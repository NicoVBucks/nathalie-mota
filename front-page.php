<?php
/**
 * Page d'accueil : hero, filtres et catalogue de photos.
 *
 * WordPress choisit automatiquement ce template pour la page d'accueil.
 */

get_header();

// --- Hero -------------------------------------------------------------------
// L'image est choisie par Nathalie elle-même, via le champ SCF "image_hero"
// posé sur la page d'accueil. Elle peut donc changer la photo d'ouverture de
// son site sans développeur.
$image_hero = '';
$choix_hero = get_field( 'image_hero' );

// Selon son réglage, le champ renvoie un tableau ou un identifiant : on se
// ramène à l'identifiant pour demander nous-mêmes la taille voulue.
if ( is_array( $choix_hero ) ) {
	$choix_hero = $choix_hero['ID'] ?? 0;
}

if ( $choix_hero ) {
	// photo_large (1600 px) et non l'original : le hero est décoratif, servir
	// le fichier d'origine serait plusieurs mégaoctets pour rien (Green Code).
	$image_hero = wp_get_attachment_image_url( $choix_hero, 'photo_large' );
}

// Repli tant qu'aucune image n'a été choisie : la photo paysage la plus
// récente. Un tri par date plutôt qu'aléatoire, pour que la page reste
// identique d'une visite à l'autre — donc cachable par l'hébergeur.
if ( ! $image_hero ) {
	$photo_hero = new WP_Query(
		array(
			'post_type'      => 'photo',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'format',
					'field'    => 'slug',
					'terms'    => 'paysage',
				),
			),
		)
	);

	if ( $photo_hero->have_posts() ) {
		$photo_hero->the_post();
		$image_hero = get_the_post_thumbnail_url( get_the_ID(), 'photo_large' );
		wp_reset_postdata();
	}
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
