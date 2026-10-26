<?php
/**
 * Post list for the main query, rendered as a Query block (inherit) so block styles apply.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

$fram_no_results = esc_html__( 'Fant ingenting her. Prøv å søke.', 'fram' );
$fram_more       = esc_html__( 'Les mer', 'fram' );
$fram_search     = esc_html__( 'Søk', 'fram' );

fram_blocks(
	<<<HTML
<!-- wp:query {"queryId":1,"query":{"inherit":true},"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"280px"},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
		<!-- wp:group {"className":"is-style-card","layout":{"type":"default"}} -->
		<div class="wp-block-group is-style-card">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"396/250"} /-->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","left":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
				<!-- wp:post-title {"level":2,"isLink":true,"style":{"typography":{"textTransform":"none","fontWeight":"700"}},"fontSize":"large"} /-->
				<!-- wp:post-date /-->
				<!-- wp:post-excerpt {"moreText":"{$fram_more}","excerptLength":24} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center">{$fram_no_results}</p>
		<!-- /wp:paragraph -->
		<!-- wp:search {"label":"{$fram_search}","showLabel":false,"buttonText":"{$fram_search}","align":"center","width":480,"widthUnit":"px"} /-->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
HTML
);
