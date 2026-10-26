<?php
/**
 * Document head and the "header" block template part.
 *
 * Block styles enqueued while rendering after wp_head() are moved into the
 * head by core (wp_hoist_late_printed_styles, WP 6.9+).
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php do_action('rb_uc_after_head'); ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php do_action('rb_uc_after_body'); ?>
    <?php wp_body_open(); ?>
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Hopp til innhold', 'fram' ); ?></a>
    <div class="wp-site-blocks" id="top">
        <?php fram_template_part( 'header', 'header' ); ?>