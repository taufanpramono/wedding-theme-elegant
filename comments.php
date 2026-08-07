<?php
/**
 * Comments template.
 *
 * @package Wedding_Elegant_Wedding
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area wew-container wew-narrow">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			printf(
				esc_html(
					_nx(
						'One comment',
						'%1$s comments',
						get_comments_number(),
						'comments title',
						'wedding-elegant-wedding'
					)
				),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	if ( ! comments_open() && get_comments_number() ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Komentar ditutup.', 'wedding-elegant-wedding' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>

