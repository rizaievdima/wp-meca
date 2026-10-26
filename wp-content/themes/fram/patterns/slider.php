<?php
/**
 * Title: Slider
 * Slug: fram/slider
 * Categories: fram, banner, featured
 * Description: Full-width photo slider: one Group per slide, set the photo in Styles → Background image. Group style "Slider"; a single slide stays static.
 * Viewport Width: 1400
 *
 * @package Fram
 */

?>
<!-- wp:group {"align":"full","className":"is-style-slider","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull is-style-slider" style="margin-top:0;margin-bottom:0"><!-- wp:group {"backgroundColor":"primary","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-primary-background-color has-background"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php echo wp_kses( __( 'Velkommen til<br>Bilverksted AS', 'fram' ), array( 'br' => array() ) ); ?></h1>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"tile-navy","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-tile-navy-background-color has-background"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Service og reparasjon for alle bilmerker', 'fram' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"midnight","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-midnight-background-color has-background"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'EU-kontroll, dekk og felg', 'fram' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
