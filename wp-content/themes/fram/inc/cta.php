<?php
/**
 * CTA panel: shortcut buttons with hexagon icons from Theme Settings → Global Settings
 * (ACF repeater "cta_items").
 *
 * Placement: the shortcode [fram_cta] prints the panel where it stands in the content. Without it,
 * pages with "Show CTA" checked (field group "Page settings") get the panel right after the first
 * "Slider" Group, or at the end of the content when the page has no slider. The panel is printed
 * once per page. From 782px it is fixed to the right edge and shrinks to its hexagons on scroll
 * (assets/js/theme.js); below that it sits in the flow where it was placed.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the panel is placed automatically: "Show CTA" is checked and the content has no [fram_cta].
 *
 * @return bool
 */
function fram_cta_auto() {
	if ( ! function_exists( 'get_field' ) || ! is_singular() ) {
		return false;
	}

	$post = get_queried_object();
	if ( $post instanceof WP_Post && has_shortcode( $post->post_content, 'fram_cta' ) ) {
		return false;
	}

	$settings = get_field( 'page_settings', $post );

	return ! empty( $settings['show_cta'] );
}

/**
 * Returns the CTA panel markup, once per request.
 *
 * @return string
 */
function fram_get_cta() {
	static $printed = false;

	if ( $printed || ! function_exists( 'get_field' ) ) {
		return '';
	}

	$items = '';
	foreach ( get_field( 'cta_items', 'option' ) ?: array() as $row ) {
		$item = $row['cta_item'] ?? array();
		$text = trim( (string) ( $item['text'] ?? '' ) );
		$link = trim( (string) ( $item['link'] ?? '' ) );
		if ( '' === $text || '' === $link ) {
			continue;
		}

		$color = sanitize_hex_color( $item['color'] ?? '' );
		$icon  = $item['icon'] ?? '';
		// The field returns a URL; also accept the array format.
		$icon = is_array( $icon ) ? ( $icon['url'] ?? '' ) : $icon;

		$items .= sprintf(
			'<li class="fram-cta__item"%1$s><a class="fram-cta__link" href="%2$s"><span class="fram-cta__hex" aria-hidden="true">%3$s</span>%4$s</a></li>',
			$color ? ' style="--cta-color:' . esc_attr( $color ) . '"' : '',
			esc_url( $link ),
			$icon ? '<img src="' . esc_url( $icon ) . '" alt="" width="36" height="36" decoding="async" />' : '',
			esc_html( $text )
		);
	}

	if ( '' === $items ) {
		return '';
	}

	$printed = true;

	return '<nav class="fram-cta" aria-label="' . esc_attr__( 'Snarveier', 'fram' ) . '"><ul class="fram-cta__list">' . $items . '</ul></nav>';
}

/**
 * Shortcode [fram_cta]: the panel at this place in the content.
 *
 * @return string
 */
function fram_cta_shortcode() {
	return fram_get_cta();
}
add_shortcode( 'fram_cta', 'fram_cta_shortcode' );

/**
 * Prints the panel at the end of the content when it is placed automatically and the page
 * has no slider to follow.
 */
function fram_cta() {
	if ( fram_cta_auto() ) {
		echo fram_get_cta(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in fram_get_cta().
	}
}

/**
 * Appends the automatically placed panel to the first "Slider" Group of the page content.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function fram_cta_after_slider( $block_content, $block ) {
	// Only the main content: not excerpts, widgets or template parts.
	if ( ! in_the_loop() || ! doing_filter( 'the_content' ) ) {
		return $block_content;
	}

	$classes = explode( ' ', $block['attrs']['className'] ?? '' );
	if ( ! in_array( 'is-style-slider', $classes, true ) || ! fram_cta_auto() ) {
		return $block_content;
	}

	return $block_content . fram_get_cta();
}
add_filter( 'render_block_core/group', 'fram_cta_after_slider', 20, 2 );
