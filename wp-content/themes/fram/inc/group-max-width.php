<?php
/**
 * Max width for the Group block: "Max width" in the editor's Dimensions panel
 * (assets/js/group-max-width.js) and an inline max-width on the front end.
 *
 * Core has no dimensions.maxWidth block support, so supports.dimensions.maxWidth does nothing.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads the control in the block editor.
 */
function fram_group_max_width_editor_assets() {
	wp_enqueue_script(
		'fram-group-max-width',
		get_theme_file_uri( 'assets/js/group-max-width.js' ),
		array( 'wp-hooks', 'wp-compose', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		fram_version()
	);
}
add_action( 'enqueue_block_editor_assets', 'fram_group_max_width_editor_assets' );

/**
 * Adds the max width to the Group block's inline style.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function fram_render_group_max_width( $block_content, $block ) {
	$max_width = (string) ( $block['attrs']['framMaxWidth'] ?? '' );

	// One value only: safecss_filter_attr() would keep any other declaration after a ";".
	$css = ( '' === $max_width || str_contains( $max_width, ';' ) ) ? '' : safecss_filter_attr( 'max-width:' . $max_width );
	if ( '' === $css ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag() ) {
		$style = rtrim( (string) $tags->get_attribute( 'style' ), '; ' );
		$tags->set_attribute( 'style', ( '' === $style ? '' : $style . ';' ) . $css );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/group', 'fram_render_group_max_width', 10, 2 );
