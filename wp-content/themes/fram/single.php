<?php
/**
 * Single post.
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
		<article <?php post_class(); ?>>
			<div class="wp-block-group has-global-padding is-layout-constrained">
				<?php
				fram_blocks(
					'<!-- wp:post-title {"textAlign":"center","level":1,"className":"is-style-title-underline"} /-->'
					. '<!-- wp:post-date {"textAlign":"center"} /-->'
					. '<!-- wp:post-featured-image {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} /-->'
				);
				?>
			</div>
			<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
			<div class="wp-block-group has-global-padding is-layout-constrained" style="padding-bottom:var(--wp--preset--spacing--60)">
				<?php
				the_post_navigation(
					array(
						'prev_text' => '&larr; %title',
						'next_text' => '%title &rarr;',
					)
				);
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
