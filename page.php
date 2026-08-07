<?php
/**
 * Page template.
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
				<p class="wew-kicker"><?php esc_html_e( 'Page', 'wedding-elegant-wedding' ); ?></p>
				<h1><?php the_title(); ?></h1>
			</div>
		</header>
		<div class="wew-section">
			<div class="wew-container wew-entry-content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wedding-elegant-wedding' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();

