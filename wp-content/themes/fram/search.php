<?php
/**
 * Search results.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="wp-block-group site-main">
	<div class="wp-block-group has-global-padding is-layout-constrained" style="padding-bottom:var(--wp--preset--spacing--60)">
		<?php
		$fram_search = esc_html__( 'Søk', 'fram' );
		fram_blocks(
			'<!-- wp:query-title {"type":"search","textAlign":"center","className":"is-style-title-underline"} /-->'
			. '<!-- wp:search {"label":"' . $fram_search . '","showLabel":false,"buttonText":"' . $fram_search . '","align":"center","width":480,"widthUnit":"px","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} /-->'
		);
		get_template_part( 'template-parts/loop' );
		?>
	</div>
</main>
<?php
get_footer();
