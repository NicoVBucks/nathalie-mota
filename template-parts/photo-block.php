<?php
/**
 * Bloc d'affichage d'une photo (carte).
 *
 * Réutilisé dans le catalogue de la page d'accueil et dans la zone des photos
 * apparentées du template single. S'appuie sur la photo courante de la boucle.
 *
 * Au survol : un œil (lien vers la page infos) et une icône plein écran
 * (ouverture de la lightbox).
 */

$categorie     = get_the_terms( get_the_ID(), 'categorie' );
$categorie_nom = $categorie ? $categorie[0]->name : '';
$reference     = get_field( 'reference' );

// URL en grand format : c'est cette image que la lightbox affichera.
$image_large = get_the_post_thumbnail_url( get_the_ID(), 'photo_large' );
?>

<article class="photo-card">

	<a href="<?php the_permalink(); ?>" class="photo-card__link">
		<?php the_post_thumbnail( 'photo_thumbnail', array( 'class' => 'photo-card__img', 'loading' => 'lazy' ) ); ?>
	</a>

	<div class="photo-card__overlay">

		<a href="<?php the_permalink(); ?>" class="photo-card__icon" aria-label="Voir les informations de la photo">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
				<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
			</svg>
		</a>

		<?php // Les données de la photo sont portées par le bouton : le JavaScript
		      // les lit pour remplir la lightbox, sans nouvel appel au serveur. ?>
		<button type="button" class="photo-card__icon js-open-lightbox"
			data-image="<?php echo esc_url( $image_large ); ?>"
			data-reference="<?php echo esc_attr( $reference ); ?>"
			data-categorie="<?php echo esc_attr( $categorie_nom ); ?>"
			data-titre="<?php the_title_attribute(); ?>"
			aria-label="Afficher la photo en plein écran">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
				<path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</button>

	</div>

	<div class="photo-card__caption">
		<span class="photo-card__title"><?php the_title(); ?></span>
		<span class="photo-card__category"><?php echo esc_html( $categorie_nom ); ?></span>
	</div>

</article>
