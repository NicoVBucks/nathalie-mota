<?php
/**
 * Page infos d'une photo (CPT "photo").
 *
 * Zone de contenu (infos à gauche, photo à droite), barre d'interactions
 * (contact + navigation), puis les photos apparentées de la même catégorie.
 */

get_header();

while ( have_posts() ) :
	the_post();

	$post_id = get_the_ID();

	// Champs personnalisés (SCF) et taxonomies de la photo courante.
	$reference = get_field( 'reference' );
	$type      = get_field( 'type' );
	$categorie = get_the_terms( $post_id, 'categorie' );
	$format    = get_the_terms( $post_id, 'format' );

	$categorie_nom = $categorie ? $categorie[0]->name : '';
	$categorie_id  = $categorie ? $categorie[0]->term_id : 0;
	$format_nom    = $format ? $format[0]->name : '';
	$annee         = get_the_date( 'Y' );

	// Photo précédente / suivante dans la galerie, triées par date de prise de vue.
	$precedente = get_previous_post();
	$suivante   = get_next_post();
	?>

	<article class="photo-single">

		<div class="photo-single__content">

			<!-- Infos (gauche, alignées en bas) -->
			<div class="photo-single__info">
				<h1 class="photo-single__title"><?php the_title(); ?></h1>

				<ul class="photo-single__meta">
					<li><span class="label">Référence</span> : <?php echo esc_html( $reference ); ?></li>
					<li><span class="label">Catégorie</span> : <?php echo esc_html( $categorie_nom ); ?></li>
					<li><span class="label">Format</span> : <?php echo esc_html( $format_nom ); ?></li>
					<li><span class="label">Type</span> : <?php echo esc_html( $type ); ?></li>
					<li><span class="label">Année</span> : <?php echo esc_html( $annee ); ?></li>
				</ul>
			</div>

			<!-- Photo au format natif (droite) -->
			<div class="photo-single__media">
				<?php
				echo get_the_post_thumbnail(
					$post_id,
					'photo_large',
					array(
						'class'   => 'photo-single__img',
						'alt'     => get_the_title(),
						'loading' => 'eager',
					)
				);
				?>
			</div>

		</div>

		<!-- Barre d'interactions : contact à gauche, navigation à droite -->
		<div class="photo-single__interactions">

			<div class="photo-single__contact">
				<span class="photo-single__contact-text">Cette photo vous intéresse ?</span>
				<a href="#contact" class="photo-single__contact-btn js-open-contact"
					data-photo-ref="<?php echo esc_attr( $reference ); ?>">Contact</a>
			</div>

			<?php
			// Miniature affichée au repos : la suivante si elle existe, sinon la
			// précédente (cas de la dernière photo de la galerie).
			$defaut_prec = $suivante ? '' : 'is-default';
			$defaut_suiv = $suivante ? 'is-default' : '';

			// Les flèches précèdent l'aperçu dans le DOM : leur survol pilote la
			// miniature via le sélecteur CSS "~". La grille (style.css) rétablit
			// l'aperçu au-dessus des flèches, conformément à la maquette.
			?>
			<nav class="photo-single__nav" aria-label="Navigation entre les photos">

				<?php if ( $precedente ) : ?>
					<a class="photo-single__nav-arrow photo-single__nav-arrow--prec" href="<?php echo esc_url( get_permalink( $precedente ) ); ?>" aria-label="Photo précédente">
						<?php get_template_part( 'template-parts/fleche', null, array( 'sens' => 'prec', 'classe' => 'photo-single__nav-icon' ) ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $suivante ) : ?>
					<a class="photo-single__nav-arrow photo-single__nav-arrow--suiv" href="<?php echo esc_url( get_permalink( $suivante ) ); ?>" aria-label="Photo suivante">
						<?php get_template_part( 'template-parts/fleche', null, array( 'sens' => 'suiv', 'classe' => 'photo-single__nav-icon' ) ); ?>
					</a>
				<?php endif; ?>

				<?php // Aperçu décoratif : la navigation reste assurée par les liens ci-dessus. ?>
				<div class="photo-single__nav-preview" aria-hidden="true">
					<?php if ( $precedente ) : ?>
						<span class="photo-single__nav-thumb photo-single__nav-thumb--prec <?php echo esc_attr( $defaut_prec ); ?>">
							<?php echo get_the_post_thumbnail( $precedente->ID, 'photo_nav', array( 'loading' => 'lazy' ) ); ?>
						</span>
					<?php endif; ?>
					<?php if ( $suivante ) : ?>
						<span class="photo-single__nav-thumb photo-single__nav-thumb--suiv <?php echo esc_attr( $defaut_suiv ); ?>">
							<?php echo get_the_post_thumbnail( $suivante->ID, 'photo_nav', array( 'loading' => 'lazy' ) ); ?>
						</span>
					<?php endif; ?>
				</div>
			</nav>
		</div>

		<?php
		// Photos apparentées : 2 photos de la même catégorie, la courante exclue.
		$apparentees = new WP_Query(
			array(
				'post_type'      => 'photo',
				'posts_per_page' => 2,
				'post__not_in'   => array( $post_id ),
				'orderby'        => 'rand',
				'tax_query'      => array(
					array(
						'taxonomy' => 'categorie',
						'field'    => 'term_id',
						'terms'    => $categorie_id,
					),
				),
			)
		);

		if ( $apparentees->have_posts() ) : ?>
			<section class="related-photos">
				<p class="related-photos__label">Vous aimerez aussi</p>
				<div class="related-photos__grid">
					<?php
					while ( $apparentees->have_posts() ) :
						$apparentees->the_post();
						get_template_part( 'template-parts/photo-block' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>

	</article>

<?php
endwhile;

get_footer();
