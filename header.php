<?php
/**
 * En-tête du thème : ouverture du document, logo et navigation.
 *
 * @package nathaliemota
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Aller au contenu', 'nathaliemota' ); ?></a>

<header class="site-header">
	<div class="container site-header__inner">

		<?php // Logo : texte stylé par défaut (léger / accessible). Remplaçable par le logo Figma via Apparence > Personnaliser. ?>
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		<?php endif; ?>

		<?php // Bouton du menu mobile : masqué en desktop, il pilote le panneau ci-dessous. ?>
		<button type="button" class="menu-burger js-menu-toggle"
			aria-controls="main-menu" aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'nathaliemota' ); ?>">
			<span class="menu-burger__barres" aria-hidden="true"></span>
		</button>

		<nav class="main-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'nathaliemota' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'main_menu',
					'container'      => false,
					'menu_id'        => 'main-menu',
					'menu_class'     => 'menu',
					'depth'          => 1,
					'fallback_cb'    => 'nathaliemota_fallback_menu',
				)
			);
			?>
		</nav>

	</div>
</header>

<main id="main" class="site-main">
