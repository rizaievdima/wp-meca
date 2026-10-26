<?php
/**
 * Front page: full-width block content of the static front page ("Hjem").
 *
 * Falls back to the post list when Settings → Reading shows latest posts.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

if ( 'page' !== get_option( 'show_on_front' ) ) {
	require get_theme_file_path( 'index.php' );
	return;
}

get_template_part( 'page-templates/full-width' );
