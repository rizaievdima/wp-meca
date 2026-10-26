<?php
/**
 * 404: content comes from the "hidden-404" pattern.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="wp-block-group site-main">
	<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
		<?php fram_blocks( '<!-- wp:pattern {"slug":"fram/hidden-404"} /-->' ); ?>
	</div>
</main>
<?php
get_footer();
