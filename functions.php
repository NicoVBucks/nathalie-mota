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
	define( 'NATHALIEMOTA_VERSION', '0.1.0' );
}

define( 'NATHALIEMOTA_CF7_ID', '87dea4d' );

/**
 * Réglages de base du thème.
 */
function nathaliemota_setup() {
	// Laisse WordPress générer la balise <title>.
	add_theme_support( 'title-tag' );

	// Images à la une (utilisées pour les photos dès l'étape 2/3).
	add_theme_support( 'post-thumbnails' );

	// Marquage HTML5 propre pour les éléments générés par WP.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Flux RSS automatiques.
	add_theme_support( 'automatic-feed-links' );

	// Logo personnalisé (option : permet à Nathalie d'uploader le logo Figma).
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
 * Chargement des feuilles de style et scripts.
 * Toutes les ressources passent par wp_enqueue_* (jamais "en dur" dans le HTML).
 */
function nathaliemota_assets() {
	// Google Fonts — à confirmer/remplacer par les polices exactes de la maquette.
	wp_enqueue_style(
		'nathaliemota-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,700&display=swap',
		array(),
		null
	);

	// Feuille de style principale (style.css à la racine du thème).
	wp_enqueue_style(
		'nathaliemota-main',
		get_stylesheet_uri(),
		array( 'nathaliemota-fonts' ),
		NATHALIEMOTA_VERSION
	);

	// Script principal (ouverture/fermeture de la modale, etc.), chargé en pied de page.
	wp_enqueue_script(
		'nathaliemota-main',
		get_template_directory_uri() . '/js/scripts.js',
		array(),
		NATHALIEMOTA_VERSION,
		true
	);

	// Données passées au JS (URL Ajax + nonce) — prêtes pour les étapes 4 et 5.
	wp_localize_script(
		'nathaliemota-main',
		'nathaliemotaData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'restUrl' => esc_url_raw( rest_url() ),
			'nonce'   => wp_create_nonce( 'wp_rest' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'nathaliemota_assets' );

/**
 * Optimisation des images (Green Code).
 * On génère des formats sur-mesure pour n'envoyer au navigateur que la taille
 * réellement affichée, et jamais les originaux (très lourds) de Nathalie.
 */
function nathaliemota_image_sizes() {
	// Vignette carrée recadrée pour la grille du catalogue (étape 4).
	add_image_size( 'photo_thumbnail', 600, 600, true );
	add_image_size( 'photo_thumbnail_2x', 1200, 1200, true ); // écrans Retina
	// Grand format NON recadré pour la page infos — respecte paysage/portrait (étape 3).
	add_image_size( 'photo_large', 1600, 1600, false );
}
add_action( 'after_setup_theme', 'nathaliemota_image_sizes' );

// Compression JPEG (82 = bon compromis poids/qualité).
add_filter( 'jpeg_quality', function () { return 82; } );
add_filter( 'wp_editor_set_quality', function () { return 82; } );

// Plafonne l'image d'origine stockée (WordPress : 2560 px par défaut).
add_filter( 'big_image_size_threshold', function () { return 2000; } );

/**
 * Menu de secours affiché tant que le menu "Menu principal" n'a pas
 * été créé dans Apparence > Menus. Ne sert qu'au confort de développement.
 */
function nathaliemota_fallback_menu() {
	echo '<ul id="main-menu" class="menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Accueil', 'nathaliemota' ) . '</a></li>';
	echo '<li><a href="#contact" class="js-open-contact">' . esc_html__( 'Contact', 'nathaliemota' ) . '</a></li>';
	echo '</ul>';
}
