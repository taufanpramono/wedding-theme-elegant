<?php
/**
 * Theme bootstrap.
 *
 * @package Wedding_Elegant_Wedding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WEW_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'WEW_THEME_SLUG', 'wedding-elegant-wedding' );
define( 'WEW_GITHUB_REPO', 'taufanpramono/wedding-theme-elegant' );
define( 'WEW_GITHUB_BRANCH', 'main' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/github-theme-updater.php';

add_action( 'after_setup_theme', 'wew_setup' );
/**
 * Set up theme defaults and WordPress feature support.
 */
function wew_setup() {
	load_theme_textdomain( 'wedding-elegant-wedding', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'wedding-elegant-wedding' ),
			'footer'  => __( 'Footer Menu', 'wedding-elegant-wedding' ),
		)
	);
}

add_action( 'wp_enqueue_scripts', 'wew_enqueue_assets' );
/**
 * Enqueue frontend assets.
 */
function wew_enqueue_assets() {
	wp_enqueue_style(
		'wew-google-fonts',
		'https://fonts.googleapis.com/css2?family=Charmonman:wght@400;700&display=block',
		array(),
		null
	);

	wp_enqueue_style(
		'wew-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		array( 'wew-google-fonts' ),
		WEW_VERSION
	);

	wp_enqueue_script(
		'wew-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		WEW_VERSION,
		true
	);
}

add_action( 'widgets_init', 'wew_widgets_init' );
/**
 * Register widget areas for standard WordPress pages.
 */
function wew_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'wedding-elegant-wedding' ),
			'id'            => 'footer-1',
			'description'   => __( 'Widgets shown above the footer note.', 'wedding-elegant-wedding' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'after_setup_theme', 'wew_boot_github_updater', 20 );
/**
 * Register the GitHub theme updater.
 */
function wew_boot_github_updater() {
	if ( class_exists( 'WEW_GitHub_Theme_Updater' ) ) {
		new WEW_GitHub_Theme_Updater( WEW_THEME_SLUG, WEW_GITHUB_REPO, WEW_GITHUB_BRANCH );
	}
}
