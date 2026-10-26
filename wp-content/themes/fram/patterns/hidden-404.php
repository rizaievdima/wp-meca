<?php
/**
 * Title: 404
 * Slug: fram/hidden-404
 * Inserter: no
 * Description: Content of the 404 page.
 *
 * @package Fram
 */

?>
<!-- wp:heading {"textAlign":"center","level":1,"className":"is-style-title-underline"} -->
<h1 class="wp-block-heading has-text-align-center is-style-title-underline"><?php esc_html_e( 'Siden finnes ikke', 'fram' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'Lenken kan være feil, eller siden er flyttet. Prøv å søke eller gå til forsiden.', 'fram' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"<?php echo esc_attr__( 'Søk', 'fram' ); ?>","showLabel":false,"buttonText":"<?php echo esc_attr__( 'Søk', 'fram' ); ?>","align":"center","width":480,"widthUnit":"px"} /-->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Til forsiden', 'fram' ); ?></a></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
