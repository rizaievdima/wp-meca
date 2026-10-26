<?php
/**
 * Page: underlined H1 and block content.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="wp-block-group site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="wp-block-group has-global-padding is-layout-constrained">
			<?php fram_blocks( '<!-- wp:post-title {"textAlign":"center","level":1,"className":"is-style-title-underline"} /-->' ); ?>
		</div>
		<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<?php
			the_content();
			fram_cta();
			wp_link_pages();
			?>
		</div>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>
</main>
<?php
get_footer();
