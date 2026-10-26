<?php
/**
 * Title: Header
 * Slug: fram/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 * Description: Navy header: logo, contact line with partner badges, main navigation.
 *
 * @package Fram
 */

$fram_assets = get_theme_file_uri( 'assets' );
?>
<!-- wp:group {"align":"full","className":"is-style-site-header","style":{"spacing":{"padding":{"top":"0","right":"var:preset|spacing|30","bottom":"0","left":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"primary","textColor":"base","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull is-style-site-header has-base-color has-primary-background-color has-text-color has-background" style="padding-top:0;padding-right:var(--wp--preset--spacing--30);padding-bottom:0;padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:site-logo {"width":330,"shouldSyncIcon":false,"className":"site-header__logo"} /-->

	<!-- wp:group {"className":"site-header__right","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"right"}} -->
	<div class="wp-block-group site-header__right">
		<!-- wp:group {"className":"site-header__top","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
		<div class="wp-block-group site-header__top">
			<!-- wp:paragraph {"className":"is-style-icon-mail","style":{"typography":{"fontWeight":"700"}},"fontSize":"small"} -->
			<p class="is-style-icon-mail has-small-font-size" style="font-weight:700"><a href="mailto:post@example.no">post@example.no</a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"is-style-icon-phone","style":{"typography":{"fontWeight":"700"}},"fontSize":"small"} -->
			<p class="is-style-icon-phone has-small-font-size" style="font-weight:700"><a href="tel:+4700000000">00&nbsp;00&nbsp;00&nbsp;00</a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"is-style-icon-clock","style":{"typography":{"fontWeight":"700"}},"fontSize":"small"} -->
			<p class="is-style-icon-clock has-small-font-size" style="font-weight:700"><a href="<?php echo esc_url( home_url( '/kontakt-oss/#apningstider' ) ); ?>"><?php esc_html_e( 'Åpningstider', 'fram' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"is-style-icon-facebook","style":{"typography":{"fontWeight":"700"}},"fontSize":"small"} -->
			<p class="is-style-icon-facebook has-small-font-size" style="font-weight:700"><a href="https://www.facebook.com/">Facebook</a></p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"className":"site-header__badges","style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group site-header__badges">
				<!-- wp:image {"height":"45px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( $fram_assets . '/images/partner-1.svg' ); ?>" alt="<?php esc_attr_e( 'Godkjent verksted', 'fram' ); ?>" style="height:45px"/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"height":"45px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( $fram_assets . '/images/partner-2.svg' ); ?>" alt="<?php esc_attr_e( 'Miljøsertifisert', 'fram' ); ?>" style="height:45px"/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"height":"45px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( $fram_assets . '/images/partner-3.svg' ); ?>" alt="<?php esc_attr_e( 'Kjedepartner', 'fram' ); ?>" style="height:45px"/></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:navigation {"textColor":"base","overlayBackgroundColor":"primary","overlayTextColor":"base","className":"site-header__nav","style":{"spacing":{"blockGap":"0"}},"fontSize":"medium","layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} -->
			<!-- wp:navigation-link {"label":"Hjem","url":"<?php echo esc_url( home_url( '/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<!-- wp:navigation-link {"label":"Om oss","url":"<?php echo esc_url( home_url( '/om-oss/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<!-- wp:navigation-submenu {"label":"Våre tjenester","url":"<?php echo esc_url( home_url( '/tjenester/' ) ); ?>","kind":"custom"} -->
				<!-- wp:navigation-link {"label":"Bilverksted","url":"<?php echo esc_url( home_url( '/tjenester/bilverksted/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"EU-kontroll","url":"<?php echo esc_url( home_url( '/tjenester/eu-kontroll/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Dekk og dekkhotell","url":"<?php echo esc_url( home_url( '/tjenester/dekk/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Bilglass","url":"<?php echo esc_url( home_url( '/tjenester/bilglass/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Bilskade og lakk","url":"<?php echo esc_url( home_url( '/tjenester/bilskade-og-lakk/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Bobil","url":"<?php echo esc_url( home_url( '/tjenester/bobil/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Bilservice","url":"<?php echo esc_url( home_url( '/tjenester/bilservice/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Elbil-verksted","url":"<?php echo esc_url( home_url( '/tjenester/elbil/' ) ); ?>","kind":"custom"} /-->
			<!-- /wp:navigation-submenu -->
			<!-- wp:navigation-link {"label":"Bestill time","url":"<?php echo esc_url( home_url( '/#bestill-time' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<!-- wp:navigation-link {"label":"Kontakt oss","url":"<?php echo esc_url( home_url( '/kontakt-oss/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
		<!-- /wp:navigation -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
