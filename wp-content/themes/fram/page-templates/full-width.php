<?php
/**
 * Template Name: Full bredde uten tittel
 * Template Post Type: page
 *
 * Block content only, sections can go edge to edge. Used for the front page,
 * contact and landing pages.
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
		<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<?php
			the_content();
			fram_cta();
			?>
		</div>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
