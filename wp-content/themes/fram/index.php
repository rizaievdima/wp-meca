<?php
/**
 * Fallback template: blog posts index.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="wp-block-group site-main">
	<div class="wp-block-group has-global-padding is-layout-constrained" style="padding-bottom:var(--wp--preset--spacing--60)">
		<?php
		$fram_title = is_home() && ! is_front_page() ? single_post_title( '', false ) : __( 'Nyheter', 'fram' );
		fram_blocks(
			'<!-- wp:heading {"textAlign":"center","level":1,"className":"is-style-title-underline"} -->'
			. '<h1 class="wp-block-heading has-text-align-center is-style-title-underline">' . esc_html( $fram_title ) . '</h1>'
			. '<!-- /wp:heading -->'
		);
		get_template_part( 'template-parts/loop' );
		?>
	</div>
</main>
<?php
get_footer();
