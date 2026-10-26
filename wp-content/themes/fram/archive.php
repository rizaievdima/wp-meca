<?php
/**
 * Archives: categories, tags, dates, authors.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="wp-block-group site-main">
	<div class="wp-block-group has-global-padding is-layout-constrained" style="padding-bottom:var(--wp--preset--spacing--60)">
		<?php
		fram_blocks(
			'<!-- wp:query-title {"type":"archive","textAlign":"center","showPrefix":false,"className":"is-style-title-underline"} /-->'
			. '<!-- wp:term-description {"textAlign":"center"} /-->'
		);
		get_template_part( 'template-parts/loop' );
		?>
	</div>
</main>
<?php
get_footer();
