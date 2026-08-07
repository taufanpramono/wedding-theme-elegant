<?php
/**
 * Main index template.
 *
 * @package Wedding_Elegant_Wedding
 */

get_header();
?>

<section class="wew-page-hero">
	<div class="wew-container">
		<p class="wew-kicker"><?php esc_html_e( 'Journal', 'wedding-elegant-wedding' ); ?></p>
		<h1><?php bloginfo( 'name' ); ?></h1>
	</div>
</section>

<section class="wew-section">
	<div class="wew-container wew-post-list">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'wew-post-card' ); ?>>
					<a class="wew-post-thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php wew_post_thumbnail(); ?>
					</a>
					<div>
						<p class="wew-post-meta"><?php echo esc_html( get_the_date() ); ?></p>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</div>
				</article>
				<?php
			endwhile;
			the_posts_pagination();
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Belum ada konten.', 'wedding-elegant-wedding' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();

