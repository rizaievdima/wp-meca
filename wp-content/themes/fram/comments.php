<?php
/**
 * Comments list and form.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area wp-block-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="wp-block-heading">
			<?php
			printf(
				/* translators: %s: number of comments. */
				esc_html( _n( '%s kommentar', '%s kommentarer', get_comments_number(), 'fram' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol class="wp-block-comment-template">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php
		the_comments_pagination();
	endif;

	comment_form();
	?>
</section>
