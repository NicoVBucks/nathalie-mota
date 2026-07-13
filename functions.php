<?php
/**
 * Fonctions et définitions du thème Nathalie Mota.
 *
 * @package nathaliemota
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Accès direct interdit.
}

if ( ! defined( 'NATHALIEMOTA_VERSION' ) ) {
	// Pensez à incrémenter à chaque mise en production (cache busting).
	define( 'NATHALIEMOTA_VERSION', '0.5.0' );
}

// Identifiant du formulaire Contact Form 7 utilisé dans la modale.
define( 'NATHALIEMOTA_CF7_ID', '87dea4d' );

/**
 * Réglages de base du thème.
 */
function nathaliemota_setup() {
	// Laisse WordPress générer la balise <title>.
	add_theme_support( 'title-tag' );

	// Images à la une : ce sont les photos de Nathalie.
	add_theme_support( 'post-thumbnails' );

	// Marquage HTML5 propre pour les éléments générés par WP.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Flux RSS automatiques.
	add_theme_support( 'automatic-feed-links' );

	// Logo personnalisé (permet à Nathalie d'uploader son logo).
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 40,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Emplacement de menu — le menu est géré dans l'admin, jamais en dur.
	register_nav_menus(
		array(
			'main_menu' => __( 'Menu principal', 'nathaliemota' ),
		)
	);

	// Domaine de traduction.
	load_theme_textdomain( 'nathaliemota', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'nathaliemota_setup' );

/**
 * Chargement des feuilles de style et des scripts.
 * Toutes les ressources passent par wp_enqueue_* (jamais "en dur" dans le HTML).
 */
function nathaliemota_assets() {
	// Polices du styleguide : Space Mono (titres/labels) + Poppins (corps).
	wp_enqueue_style(
		'nathaliemota-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap',
		array(),
		null
	);

	// Feuille de style principale.
	wp_enqueue_style(
		'nathaliemota-main',
		get_stylesheet_uri(),
		array( 'nathaliemota-fonts' ),
		NATHALIEMOTA_VERSION
	);

	// Script principal (modale de contact), utile sur tout le site.
	wp_enqueue_script(
		'nathaliemota-main',
		get_template_directory_uri() . '/js/scripts.js',
		array(),
		NATHALIEMOTA_VERSION,
		true
	);

	// Lightbox : utilisée sur la page d'accueil et sur les pages photo.
	wp_enqueue_script(
		'nathaliemota-lightbox',
		get_template_directory_uri() . '/js/lightbox.js',
		array(),
		NATHALIEMOTA_VERSION,
		true
	);


	// Script du catalogue : chargé uniquement sur la page d'accueil, seule page
	// qui en a besoin (on évite d'envoyer du JavaScript inutile ailleurs).
	if ( is_front_page() ) {
		wp_enqueue_script(
			'nathaliemota-catalogue',
			get_template_directory_uri() . '/js/catalogue.js',
			array(),
			NATHALIEMOTA_VERSION,
			true
		);

		// Transmet au JavaScript l'URL d'appel Ajax et le nonce de sécurité.
		wp_localize_script(
			'nathaliemota-catalogue',
			'nathaliemotaData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'nathaliemota_nonce' ),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_assets' );

/**
 * Optimisation des images (Green Code).
 * On génère des formats sur-mesure pour n'envoyer au navigateur que la taille
 * réellement affichée, et jamais les originaux (très lourds) de Nathalie.
 */
function nathaliemota_image_sizes() {
	// Vignette carrée recadrée pour la grille du catalogue.
	add_image_size( 'photo_thumbnail', 600, 600, true );
	add_image_size( 'photo_thumbnail_2x', 1200, 1200, true ); // écrans Retina
	// Grand format NON recadré pour la page infos — respecte paysage/portrait.
	add_image_size( 'photo_large', 1600, 1600, false );
}
add_action( 'after_setup_theme', 'nathaliemota_image_sizes' );

// Compression JPEG (82 = bon compromis poids/qualité).
add_filter( 'jpeg_quality', function () { return 82; } );
add_filter( 'wp_editor_set_quality', function () { return 82; } );

// Plafonne l'image d'origine stockée (WordPress : 2560 px par défaut).
add_filter( 'big_image_size_threshold', function () { return 2000; } );

/**
 * Construit la requête des photos du catalogue.
 *
 * Cette fonction sert à deux endroits : au premier affichage de la page d'accueil
 * et lors des appels Ajax (filtres, tri, "Charger plus"). En la factorisant ici,
 * la logique de requête n'existe qu'à un seul endroit.
 *
 * @param array $filtres categorie, format, tri, page.
 * @return WP_Query
 */
function nathaliemota_query_photos( $filtres = array() ) {
	$categorie = $filtres['categorie'] ?? '';
	$format    = $filtres['format'] ?? '';
	$tri       = $filtres['tri'] ?? 'DESC'; // par défaut : les plus récentes
	$page      = $filtres['page'] ?? 1;

	$args = array(
		'post_type'      => 'photo',
		'post_status'    => 'publish',
		'posts_per_page' => 8, // 8 photos par défaut (cf. spécifications)
		'paged'          => $page,
		'orderby'        => 'date',
		'order'          => ( 'ASC' === $tri ) ? 'ASC' : 'DESC',
	);

	// Filtres par taxonomie : on n'ajoute la clause que si un filtre est actif.
	$tax_query = array();

	if ( $categorie ) {
		$tax_query[] = array(
			'taxonomy' => 'categorie',
			'field'    => 'slug',
			'terms'    => $categorie,
		);
	}

	if ( $format ) {
		$tax_query[] = array(
			'taxonomy' => 'format',
			'field'    => 'slug',
			'terms'    => $format,
		);
	}

	if ( $tax_query ) {
		// 'AND' : une photo doit satisfaire tous les filtres actifs.
		$tax_query['relation'] = 'AND';
		$args['tax_query']     = $tax_query;
	}

	return new WP_Query( $args );
}

/**
 * Point d'entrée Ajax du catalogue (filtres, tri et "Charger plus").
 *
 * Renvoie le HTML des photos demandées et un booléen indiquant s'il reste des
 * photos à charger. Le JavaScript peut ainsi masquer le bouton "Charger plus"
 * quand on arrive au bout, ce qui évite un appel inutile à l'API (Green Code).
 */
function nathaliemota_filtrer_photos() {
	// Vérification du nonce : la requête vient bien de notre site.
	check_ajax_referer( 'nathaliemota_nonce', 'nonce' );

	$page = absint( $_POST['page'] ?? 1 );

	$photos = nathaliemota_query_photos(
		array(
			'categorie' => sanitize_text_field( wp_unslash( $_POST['categorie'] ?? '' ) ),
			'format'    => sanitize_text_field( wp_unslash( $_POST['format'] ?? '' ) ),
			'tri'       => sanitize_text_field( wp_unslash( $_POST['tri'] ?? 'DESC' ) ),
			'page'      => $page,
		)
	);

	// On met en mémoire tampon le HTML généré par le template-part,
	// pour le renvoyer au JavaScript en une seule fois.
	ob_start();

	while ( $photos->have_posts() ) {
		$photos->the_post();
		get_template_part( 'template-parts/photo-block' );
	}

	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'         => ob_get_clean(),
			'reste_photos' => $page < $photos->max_num_pages,
		)
	);
}
// Les deux hooks : utilisateurs connectés et visiteurs.
add_action( 'wp_ajax_filtrer_photos', 'nathaliemota_filtrer_photos' );
add_action( 'wp_ajax_nopriv_filtrer_photos', 'nathaliemota_filtrer_photos' );

/**
 * Menu de secours affiché tant que le menu "Menu principal" n'a pas été créé
 * dans Apparence > Menus. Ne sert qu'au confort de développement.
 */
function nathaliemota_fallback_menu() {
	echo '<ul id="main-menu" class="menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Accueil', 'nathaliemota' ) . '</a></li>';
	echo '<li><a href="#contact" class="js-open-contact">' . esc_html__( 'Contact', 'nathaliemota' ) . '</a></li>';
	echo '</ul>';
}
