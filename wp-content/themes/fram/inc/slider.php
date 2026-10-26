<?php
/**
 * Group styles "Slider" and "Slider (dots)": every block inside the Group is a slide, turned into
 * a Slick carousel on the front end (assets/js/slider.js). "Slider" crossfades with arrows, its
 * slides are Groups with the photo as background image and a heading; "Slider (dots)" slides
 * sideways with dots, its slides are Images. A single slide stays a static block, without Slick.
 * Slick 1.8.1 is self-hosted in assets/vendor/slick. Its CSS is in style.css.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers Slick and the slider script; they load only on pages with a slider.
 */
function fram_slider_register_assets() {
	wp_register_style( 'fram-slick', get_theme_file_uri( 'assets/vendor/slick/slick.css' ), array(), '1.8.1' );

	wp_register_script(
		'fram-slick',
		get_theme_file_uri( 'assets/vendor/slick/slick.min.js' ),
		array( 'jquery' ),
		'1.8.1',
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_register_script(
		'fram-slider',
		get_theme_file_uri( 'assets/js/slider.js' ),
		array( 'fram-slick' ),
		fram_version(),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'init', 'fram_slider_register_assets' );

/**
 * Loads the slider assets for a Group with a slider style and at least two slides,
 * and passes the arrow and dot labels.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function fram_render_slider( $block_content, $block ) {
	// Also matches is-style-slider-dots.
	if ( ! str_contains( $block['attrs']['className'] ?? '', 'is-style-slider' ) || count( $block['innerBlocks'] ) < 2 ) {
		return $block_content;
	}

	wp_enqueue_style( 'fram-slick' );
	wp_enqueue_script( 'fram-slider' );

	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag() ) {
		$tags->set_attribute( 'data-label-prev', __( 'Forrige bilde', 'fram' ) );
		$tags->set_attribute( 'data-label-next', __( 'Neste bilde', 'fram' ) );
		/* translators: %d: slide number. */
		$tags->set_attribute( 'data-label-dot', __( 'Bilde %d', 'fram' ) );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/group', 'fram_render_slider', 10, 2 );
