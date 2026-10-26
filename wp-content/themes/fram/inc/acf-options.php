<?php
/**
 * ACF options pages: Theme Settings with sub pages.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the options pages (requires ACF Pro).
 */
function fram_acf_options_pages() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	// The parent menu item opens its first sub page.
	$parent = acf_add_options_page(
		array(
			'page_title' => __( 'Theme Settings', 'fram' ),
			'menu_title' => __( 'Theme Settings', 'fram' ),
			'redirect'   => true,
		)
	);
	acf_add_options_sub_page(
		array(
			'page_title'  => __( 'Global Settings', 'fram' ),
			'menu_title'  => __( 'Global Settings', 'fram' ),
			'parent_slug' => $parent['menu_slug'],
		)
	);
	acf_add_options_sub_page(
		array(
			'page_title'  => __( 'Footer Settings', 'fram' ),
			'menu_title'  => __( 'Footer Settings', 'fram' ),
			'parent_slug' => $parent['menu_slug'],
		)
	);
	
}
add_action( 'acf/init', 'fram_acf_options_pages' );