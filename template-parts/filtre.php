<?php
/**
 * Filtre du catalogue : liste déroulante personnalisée.
 *
 * Les <option> d'un <select> natif ne peuvent pas être stylées (surlignement
 * rouge, coins arrondis...). On conserve donc un vrai <select>, masqué, qui
 * porte la valeur et reste lu par le JavaScript Ajax et par les lecteurs
 * d'écran ; par-dessus, on affiche une liste que l'on peut styler librement.
 *
 * Arguments attendus :
 * - id      : identifiant du select (ex. "filtre-categorie")
 * - libelle : texte affiché tant qu'aucun choix n'est fait (ex. "Catégories")
 * - options : tableau valeur => texte
 * - classe  : classe supplémentaire éventuelle
 */

$id      = $args['id'];
$libelle = $args['libelle'];
$options = $args['options'];
$classe  = $args['classe'] ?? '';
?>

<div class="filtre <?php echo esc_attr( $classe ); ?>" data-filtre>

	<?php // Le select réel : masqué visuellement, mais toujours présent. ?>
	<select name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" class="filtre__select">
		<option value=""><?php echo esc_html( $libelle ); ?></option>
		<?php foreach ( $options as $valeur => $texte ) : ?>
			<option value="<?php echo esc_attr( $valeur ); ?>"><?php echo esc_html( $texte ); ?></option>
		<?php endforeach; ?>
	</select>

	<?php // La surcouche visuelle, pilotée par le JavaScript. ?>
	<button type="button" class="filtre__bouton" aria-haspopup="listbox" aria-expanded="false">
		<span class="filtre__valeur"><?php echo esc_html( $libelle ); ?></span>
		<svg class="filtre__chevron" width="12" height="8" viewBox="0 0 12 8" fill="none" aria-hidden="true">
			<path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
		</svg>
	</button>

	<ul class="filtre__liste" role="listbox" hidden>
		<?php foreach ( $options as $valeur => $texte ) : ?>
			<li class="filtre__option" role="option" data-valeur="<?php echo esc_attr( $valeur ); ?>">
				<?php echo esc_html( $texte ); ?>
			</li>
		<?php endforeach; ?>
	</ul>

</div>
