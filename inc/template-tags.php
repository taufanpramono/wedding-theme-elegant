<?php
/**
 * Template helpers.
 *
 * @package Wedding_Elegant_Wedding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get event timestamp in the site timezone.
 *
 * @return int
 */
function wew_event_timestamp() {
	$raw      = wew_get_option( 'event_date' );
	$timezone = wp_timezone();

	try {
		$date = new DateTimeImmutable( $raw, $timezone );
	} catch ( Exception $exception ) {
		$date = new DateTimeImmutable( 'now', $timezone );
	}

	return $date->getTimestamp();
}

/**
 * Get event date in ISO 8601 format for JavaScript.
 *
 * @return string
 */
function wew_event_iso() {
	return wp_date( DATE_ATOM, wew_event_timestamp() );
}

/**
 * Get human-readable event date.
 *
 * @return string
 */
function wew_event_date_label() {
	return wp_date( 'l, j F Y', wew_event_timestamp() );
}

/**
 * Get human-readable event time.
 *
 * @return string
 */
function wew_event_time_label() {
	return wp_date( 'H.i T', wew_event_timestamp() );
}

/**
 * Build an RSVP link from WhatsApp or the RSVP URL.
 *
 * @return string
 */
function wew_rsvp_link() {
	$whatsapp = preg_replace( '/\D+/', '', wew_get_option( 'whatsapp' ) );

	if ( ! empty( $whatsapp ) ) {
		$message = sprintf(
			/* translators: 1: bride name, 2: groom name. */
			__( 'Halo, saya ingin konfirmasi kehadiran untuk pernikahan %1$s dan %2$s.', 'wedding-elegant-wedding' ),
			wew_get_option( 'bride_name' ),
			wew_get_option( 'groom_name' )
		);

		return 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode( $message );
	}

	return wew_get_option( 'rsvp_url' );
}

/**
 * Primary navigation fallback.
 */
function wew_primary_menu_fallback() {
	$items = array(
		'#home'    => __( 'Home', 'wedding-elegant-wedding' ),
		'#story'   => __( 'Story', 'wedding-elegant-wedding' ),
		'#details' => __( 'Detail', 'wedding-elegant-wedding' ),
		'#gallery' => __( 'Gallery', 'wedding-elegant-wedding' ),
		'#rsvp'    => __( 'RSVP', 'wedding-elegant-wedding' ),
	);

	echo '<ul id="primary-menu" class="wew-menu">';
	foreach ( $items as $url => $label ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Render the post thumbnail or a soft placeholder.
 *
 * @param string $size Image size.
 */
function wew_post_thumbnail( $size = 'large' ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( $size );
		return;
	}

	echo '<div class="wew-thumb-placeholder" aria-hidden="true"></div>';
}

add_filter( 'body_class', 'wew_body_classes' );
/**
 * Add body classes used by interactive wedding elements.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function wew_body_classes( $classes ) {
	if ( '1' === wew_get_option( 'preloader' ) ) {
		$classes[] = 'wew-preloader-visible';
	}

	return $classes;
}

/**
 * Render the wedding invitation preloader.
 */
function wew_render_preloader() {
	if ( '1' !== wew_get_option( 'preloader' ) ) {
		return;
	}

	$names     = wew_get_option( 'bride_name' ) . ' & ' . wew_get_option( 'groom_name' );
	$music_url = wew_get_option( 'music_url' );
	?>
	<div class="wew-preloader" data-wew-preloader role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Buka undangan pernikahan', 'wedding-elegant-wedding' ); ?>">
		<div class="wew-preloader-card">
			<div class="wew-preloader-ornament" aria-hidden="true">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<p class="wew-kicker"><?php esc_html_e( 'Wedding Invitation', 'wedding-elegant-wedding' ); ?></p>
			<h2><?php echo esc_html( $names ); ?></h2>
			<p><?php esc_html_e( 'Satu hari indah, satu cerita baru, dan doa hangat dari orang-orang tersayang.', 'wedding-elegant-wedding' ); ?></p>
			<button class="wew-open-invitation" type="button" data-wew-open-invitation>
				<?php esc_html_e( 'Buka Undangan', 'wedding-elegant-wedding' ); ?>
			</button>
		</div>
	</div>
	<?php if ( ! empty( $music_url ) ) : ?>
		<audio data-wew-audio src="<?php echo esc_url( $music_url ); ?>" preload="auto" loop></audio>
		<button class="wew-music-toggle" type="button" data-wew-music-toggle hidden aria-pressed="true">
			<span data-wew-music-label><?php esc_html_e( 'Pause Musik', 'wedding-elegant-wedding' ); ?></span>
		</button>
	<?php endif; ?>
	<?php
}
