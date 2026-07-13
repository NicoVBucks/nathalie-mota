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

			<nav class="photo-single__nav" aria-label="Navigation entre les photos">
				<?php
				// Aperçu toujours visible : la photo suivante (ou la précédente si on est sur la dernière).
				$apercu = $suivante ? $suivante : $precedente;
				if ( $apercu ) :
				?>
					<div class="photo-single__nav-preview">
						<?php echo get_the_post_thumbnail( $apercu->ID, 'photo_thumbnail' ); ?>
					</div>
				<?php endif; ?>

				<div class="photo-single__nav-arrows">
					<?php if ( $precedente ) : ?>
						<a href="<?php echo esc_url( get_permalink( $precedente ) ); ?>" aria-label="Photo précédente">
							<svg class="photo-single__nav-icon" width="36" height="15" viewBox="0 0 36 15" fill="none" aria-hidden="true">
								<path d="M0.292893 6.65691C-0.0976311 7.04743 -0.0976311 7.6806 0.292893 8.07112L6.65685 14.4351C7.04738 14.8256 7.68054 14.8256 8.07107 14.4351C8.46159 14.0446 8.46159 13.4114 8.07107 13.0209L2.41421 7.36401L8.07107 1.70716C8.46159 1.31664 8.46159 0.68347 8.07107 0.292946C7.68054 -0.0975785 7.04738 -0.0975785 6.65685 0.292946L0.292893 6.65691ZM35 8.36401C35.5523 8.36401 36 7.9163 36 7.36401C36 6.81173 35.5523 6.36401 35 6.36401V8.36401ZM1 8.36401L35 8.36401V6.36401L1 6.36401L1 8.36401Z" fill="currentColor"/>
							</svg>
						</a>
					<?php endif; ?>
					<?php if ( $suivante ) : ?>
						<a href="<?php echo esc_url( get_permalink( $suivante ) ); ?>" aria-label="Photo suivante">
							<svg class="photo-single__nav-icon" width="36" height="15" viewBox="0 0 36 15" fill="none" aria-hidden="true">
								<path d="M35.7071 8.07112C36.0976 7.6806 36.0976 7.04743 35.7071 6.65691L29.3431 0.292946C28.9526 -0.0975785 28.3195 -0.0975785 27.9289 0.292946C27.5384 0.68347 27.5384 1.31664 27.9289 1.70716L33.5858 7.36401L27.9289 13.0209C27.5384 13.4114 27.5384 14.0446 27.9289 14.4351C28.3195 14.8256 28.9526 14.8256 29.3431 14.4351L35.7071 8.07112ZM1 6.36401C0.447716 6.36401 0 6.81173 0 7.36401C0 7.9163 0.447716 8.36401 1 8.36401V6.36401ZM35 6.36401L1 6.36401V8.36401L35 8.36401V6.36401Z" fill="currentColor"/>
							</svg>
						</a>
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
