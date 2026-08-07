<?php
/**
 * Site header.
 *
 * @package Wedding_Elegant_Wedding
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wedding-elegant-wedding' ); ?></a>
<header class="wew-site-header" id="masthead">
	<div class="wew-header-inner">
		<a class="wew-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				printf(
					'<span>%1$s</span><small>%2$s</small>',
					esc_html( wew_get_option( 'bride_name' ) . ' & ' . wew_get_option( 'groom_name' ) ),
					esc_html__( 'Wedding Invitation', 'wedding-elegant-wedding' )
				);
			}
			?>
		</a>
		<button class="wew-nav-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
			<span></span>
			<span></span>
			<span></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'wedding-elegant-wedding' ); ?></span>
		</button>
		<nav class="wew-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'wedding-elegant-wedding' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'wew-menu',
					'container'      => false,
					'fallback_cb'    => 'wew_primary_menu_fallback',
				)
			);
			?>
		</nav>
	</div>
</header>
<main id="primary" class="site-main">

