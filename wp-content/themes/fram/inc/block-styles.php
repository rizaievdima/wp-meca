<?php
/**
 * Block styles shown in the editor's "Styles" panel. Their CSS is in style.css.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the theme block styles.
 */
function fram_register_block_styles() {
	$styles = array(
		'site-header'     => array( __( 'Header-linje', 'fram' ), array( 'core/group' ) ),
		'section-navy'    => array( __( 'Seksjon navy', 'fram' ), array( 'core/group', 'core/columns', 'core/column' ) ),
		'section-mist'    => array( __( 'Seksjon dis', 'fram' ), array( 'core/group', 'core/columns', 'core/column' ) ),
		'section-light'   => array( __( 'Seksjon lys', 'fram' ), array( 'core/group', 'core/columns', 'core/column' ) ),
		'card'            => array( __( 'Kort', 'fram' ), array( 'core/column', 'core/group' ) ),
		'slider'          => array( __( 'Slider', 'fram' ), array( 'core/group' ) ),
		'slider-dots'     => array( __( 'Slider (dots)', 'fram' ), array( 'core/group' ) ),
		'about-box'       => array( __( 'About box', 'fram' ), array( 'core/column' ) ),
		'form-panel'      => array( __( 'Skjemapanel', 'fram' ), array( 'core/group' ) ),
		'title-underline' => array( __( 'Understreket tittel', 'fram' ), array( 'core/heading', 'core/post-title', 'core/query-title' ) ),
		'display'         => array( __( 'Display', 'fram' ), array( 'core/heading', 'core/paragraph' ) ),
		'icon-mail'       => array( __( 'Ikon: e-post', 'fram' ), array( 'core/paragraph' ) ),
		'icon-phone'      => array( __( 'Ikon: telefon', 'fram' ), array( 'core/paragraph' ) ),
		'icon-clock'      => array( __( 'Ikon: klokke', 'fram' ), array( 'core/paragraph' ) ),
		'icon-facebook'   => array( __( 'Ikon: Facebook', 'fram' ), array( 'core/paragraph' ) ),
		'icon-info'       => array( __( 'Ikon: info', 'fram' ), array( 'core/paragraph' ) ),
		'icon-location'   => array( __( 'Ikon: kart', 'fram' ), array( 'core/paragraph' ) ),
		'inline-image-right' => array( __( 'Inline image right', 'fram' ), array( 'core/paragraph' ) ),
		'four-up'         => array( __( 'Fire kolonner', 'fram' ), array( 'core/columns' ) ),
		'to-top'          => array( __( 'Til toppen', 'fram' ), array( 'core/button' ) ),

	);

	foreach ( $styles as $name => list( $label, $block_types ) ) {
		foreach ( $block_types as $block_type ) {
			register_block_style(
				$block_type,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', 'fram_register_block_styles' );