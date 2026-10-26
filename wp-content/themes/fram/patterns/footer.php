<?php
/**
 * Title: Footer
 * Slug: fram/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Navy footer: company details, contacts, opening hours, partner badges, privacy link, back-to-top button.
 *
 * @package Fram
 */

$fram_assets = get_theme_file_uri( 'assets' );
?>
<!-- wp:group {"align":"full","className":"is-style-section-navy","style":{"spacing":{"padding":{"top":"5.4em","bottom":"3em"},"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"1250px"}} -->
<div class="wp-block-group alignfull is-style-section-navy" style="margin-top:0;margin-bottom:0;padding-top:5.4em;padding-bottom:3em">
	<!-- wp:columns {"className":"is-style-four-up","style":{"spacing":{"blockGap":{"top":"2rem","left":"2rem"}}}} -->
	<div class="wp-block-columns is-style-four-up">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:site-logo {"width":222,"shouldSyncIcon":false} /-->

			<!-- wp:paragraph -->
			<p><strong><?php esc_html_e( 'Bilverksted AS', 'fram' ); ?></strong><br><?php esc_html_e( 'Eksempelveien 1', 'fram' ); ?><br><?php esc_html_e( '0000 Stedet', 'fram' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:paragraph {"className":"is-style-icon-mail"} -->
			<p class="is-style-icon-mail"><a href="mailto:post@example.no">post@example.no</a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"is-style-icon-phone","style":{"typography":{"fontWeight":"700","lineHeight":"1.2"}},"fontSize":"medium"} -->
			<p class="is-style-icon-phone has-medium-font-size" style="font-weight:700;line-height:1.2"><a href="tel:+4700000000">00&nbsp;00&nbsp;00&nbsp;00</a><br><?php esc_html_e( 'Vakt tlf:', 'fram' ); ?> <a href="tel:+4700000001">000&nbsp;00&nbsp;001</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:paragraph {"className":"is-style-icon-clock","style":{"typography":{"lineHeight":"1.2"}}} -->
			<p class="is-style-icon-clock" style="line-height:1.2"><strong><?php esc_html_e( 'Åpningstider:', 'fram' ); ?></strong><br><?php esc_html_e( 'Mandag–fredag', 'fram' ); ?><br>07:00–16:30<br><?php esc_html_e( 'Sommeråpent juli:', 'fram' ); ?><br>07:00–16:00</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:image {"height":"86px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( $fram_assets . '/images/partner-1.svg' ); ?>" alt="<?php esc_attr_e( 'Godkjent verksted', 'fram' ); ?>" style="height:86px"/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"height":"86px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( $fram_assets . '/images/partner-2.svg' ); ?>" alt="<?php esc_attr_e( 'Miljøsertifisert', 'fram' ); ?>" style="height:86px"/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"height":"86px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( $fram_assets . '/images/partner-3.svg' ); ?>" alt="<?php esc_attr_e( 'Kjedepartner', 'fram' ); ?>" style="height:86px"/></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"className":"is-style-icon-info"} -->
		<p class="is-style-icon-info"><a href="<?php echo esc_url( home_url( '/personvernerklaering/' ) ); ?>"><?php esc_html_e( 'Personvernerklæring', 'fram' ); ?></a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-to-top"} -->
		<div class="wp-block-button is-style-to-top"><a class="wp-block-button__link wp-element-button" href="#top"><?php esc_html_e( 'Til toppen', 'fram' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
