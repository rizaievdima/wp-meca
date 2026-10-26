<?php
/**
 * Clickable Group block: a "Link" toolbar button in the editor (assets/js/group-link.js)
 * and a link over the whole box on the front end. Its CSS is in style.css.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads the toolbar button in the block editor.
 */
function fram_group_link_editor_assets() {
	wp_enqueue_script(
		'fram-group-link',
		get_theme_file_uri( 'assets/js/group-link.js' ),
		array( 'wp-hooks', 'wp-compose', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-primitives', 'wp-i18n' ),
		fram_version()
	);
}
add_action( 'enqueue_block_editor_assets', 'fram_group_link_editor_assets' );

/**
 * Adds the link to a Group block that has a URL.
 *
 * Wrapping the box in <a> would nest links (buttons, links in the text), so the link is
 * the last child, stretched over the box; links inside the box stay on top of it.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function fram_render_group_link( $block_content, $block ) {
	$url = esc_url( $block['attrs']['framLinkUrl'] ?? '' );
	$end = strrpos( $block_content, '</' );
	if ( '' === $url || false === $end ) {
		return $block_content;
	}

	// Accessible name: the first heading in the box, otherwise its text.
	$label = preg_match( '/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $block_content, $heading ) ? $heading[1] : $block_content;
	$label = wp_trim_words( wp_strip_all_tags( $label ), 12 );
	if ( '' === $label ) {
		$label = __( 'Les mer', 'fram' );
	}

	$link = sprintf(
		'<a class="fram-group-link" href="%1$s"%2$s><span class="screen-reader-text">%3$s</span></a>',
		$url,
		empty( $block['attrs']['framLinkNewTab'] ) ? '' : ' target="_blank" rel="noopener"',
		esc_html( $label )
	);
	$block_content = substr_replace( $block_content, $link, $end, 0 );

	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag() ) {
		$tags->add_class( 'has-fram-link' );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/group', 'fram_render_group_link', 10, 2 );
