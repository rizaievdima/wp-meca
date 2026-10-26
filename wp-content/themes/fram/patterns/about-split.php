<?php
/**
 * Title: About: text and photo
 * Slug: fram/about-split
 * Categories: fram, about
 * Description: Light text box with a slanted edge over a large photo (Column style "About box"). For a slider, group the photo with more Images and pick the Group style "Slider (dots)".
 * Viewport Width: 1400
 *
 * @package Fram
 */

?>
<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"0","left":"0"},"margin":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--70);margin-bottom:var(--wp--preset--spacing--70)"><!-- wp:column {"width":"40%","className":"is-style-about-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|30","bottom":"var:preset|spacing|50","left":"clamp(20px, 7vw, 100px)"}}}} -->
<div class="wp-block-column is-style-about-box" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--50);padding-left:clamp(20px, 7vw, 100px);flex-basis:40%"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Om verkstedet vårt', 'fram' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size"><?php esc_html_e( 'Fortell kort hvem dere er: hvor lenge verkstedet har eksistert, hva dere er gode på og hvorfor kundene kommer tilbake.', 'fram' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)"><!-- wp:button {"style":{"typography":{"textTransform":"uppercase"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/om-oss/' ) ); ?>" style="text-transform:uppercase"><?php esc_html_e( 'Les mer', 'fram' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"60%","style":{"spacing":{"padding":{"top":"22px"}}}} -->
<div class="wp-block-column" style="padding-top:22px;flex-basis:60%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/photo-placeholder.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
