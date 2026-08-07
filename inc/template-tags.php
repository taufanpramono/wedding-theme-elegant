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

