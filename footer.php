<?php
/**
 * Site footer.
 *
 * @package Wedding_Elegant_Wedding
 */

?>
</main>
<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
	<aside class="wew-footer-widgets" aria-label="<?php esc_attr_e( 'Footer widgets', 'wedding-elegant-wedding' ); ?>">
		<div class="wew-container">
			<?php dynamic_sidebar( 'footer-1' ); ?>
		</div>
	</aside>
<?php endif; ?>
<footer class="wew-site-footer">
	<div class="wew-container">
		<p class="wew-footer-names"><?php echo esc_html( wew_get_option( 'bride_name' ) . ' & ' . wew_get_option( 'groom_name' ) ); ?></p>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'menu_class'     => 'wew-footer-menu',
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 1,
			)
		);
		?>
		<p class="wew-footer-note"><?php esc_html_e( 'Terima kasih atas doa dan kehadiran Anda.', 'wedding-elegant-wedding' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

