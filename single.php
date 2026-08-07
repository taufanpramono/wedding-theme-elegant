<?php
/**
 * Single post template.
 *
 * @package Wedding_Elegant_Wedding
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'wew-content-page' ); ?>>
		<header class="wew-page-hero">
			<div class="wew-container">
				<p class="wew-kicker"><?php echo esc_html( get_the_date() ); ?></p>
				<h1><?php the_title(); ?></h1>
			</div>
		</header>
		<div class="wew-section">
			<div class="wew-container wew-entry-content">
				<?php
				if ( has_post_thumbnail() ) {
					echo '<div class="wew-featured-image">';
					the_post_thumbnail( 'large' );
					echo '</div>';
				}

				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wedding-elegant-wedding' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>
			<div class="wew-container wew-post-nav">
				<?php the_post_navigation(); ?>
			</div>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();

