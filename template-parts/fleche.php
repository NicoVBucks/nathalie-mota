<?php
/**
 * Icône flèche de navigation.
 *
 * Le même tracé sert à la navigation de la page infos et à celle de la
 * lightbox. Il est centralisé ici pour n'exister qu'à un seul endroit.
 *
 * Arguments attendus :
 * - sens   : "prec" (vers la gauche) ou "suiv" (vers la droite)
 * - classe : classe CSS posée sur le <svg>
 */

$sens   = $args['sens'];
$classe = $args['classe'];

// Tracés relevés sur la maquette.
$traces = array(
	'prec' => 'M0.292893 6.65691C-0.0976311 7.04743 -0.0976311 7.6806 0.292893 8.07112L6.65685 14.4351C7.04738 14.8256 7.68054 14.8256 8.07107 14.4351C8.46159 14.0446 8.46159 13.4114 8.07107 13.0209L2.41421 7.36401L8.07107 1.70716C8.46159 1.31664 8.46159 0.68347 8.07107 0.292946C7.68054 -0.0975785 7.04738 -0.0975785 6.65685 0.292946L0.292893 6.65691ZM35 8.36401C35.5523 8.36401 36 7.9163 36 7.36401C36 6.81173 35.5523 6.36401 35 6.36401V8.36401ZM1 8.36401L35 8.36401V6.36401L1 6.36401L1 8.36401Z',
	'suiv' => 'M35.7071 8.07112C36.0976 7.6806 36.0976 7.04743 35.7071 6.65691L29.3431 0.292946C28.9526 -0.0975785 28.3195 -0.0975785 27.9289 0.292946C27.5384 0.68347 27.5384 1.31664 27.9289 1.70716L33.5858 7.36401L27.9289 13.0209C27.5384 13.4114 27.5384 14.0446 27.9289 14.4351C28.3195 14.8256 28.9526 14.8256 29.3431 14.4351L35.7071 8.07112ZM1 6.36401C0.447716 6.36401 0 6.81173 0 7.36401C0 7.9163 0.447716 8.36401 1 8.36401V6.36401ZM35 6.36401L1 6.36401V8.36401L35 8.36401V6.36401Z',
);
?>

<svg class="<?php echo esc_attr( $classe ); ?>" width="36" height="15" viewBox="0 0 36 15" fill="none" aria-hidden="true">
	<path d="<?php echo esc_attr( $traces[ $sens ] ); ?>" fill="currentColor"/>
</svg>
