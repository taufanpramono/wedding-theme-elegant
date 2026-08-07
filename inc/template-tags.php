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

	$names      = wew_get_option( 'groom_name' ) . ' & ' . wew_get_option( 'bride_name' );
	$music_url  = wew_get_option( 'music_url' );
	$guest_name = '';

	if ( '' === $music_url ) {
		$music_files = glob( get_template_directory() . '/assets/music/*.{mp3,m4a,ogg,wav}', GLOB_BRACE );

		if ( ! empty( $music_files ) ) {
			$music_url = get_template_directory_uri() . '/assets/music/' . rawurlencode( basename( $music_files[0] ) );
		}
	}

	if ( isset( $_GET['to'] ) ) {
		$guest_name = sanitize_text_field( wp_unslash( $_GET['to'] ) );
	} elseif ( isset( $_GET['kepada'] ) ) {
		$guest_name = sanitize_text_field( wp_unslash( $_GET['kepada'] ) );
	}

	if ( '' === $guest_name ) {
		$guest_name = __( '(Nama Penerima Undangan)', 'wedding-elegant-wedding' );
	}
	?>
	<div class="wew-preloader" data-wew-preloader role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Buka undangan pernikahan', 'wedding-elegant-wedding' ); ?>">
		<div class="wew-preloader-stage">
			<div class="wew-preloader-envelope" aria-hidden="true">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/1_preloader/envelopes.png' ); ?>" alt="">
				<p>
					<span><?php esc_html_e( 'Still', 'wedding-elegant-wedding' ); ?></span>
					<span><?php esc_html_e( 'We Become', 'wedding-elegant-wedding' ); ?></span>
					<em><strong>8</strong> &ldquo;<?php esc_html_e( 'Eight', 'wedding-elegant-wedding' ); ?>&rdquo; <?php esc_html_e( 'to', 'wedding-elegant-wedding' ); ?><br>&infin; &ldquo;<?php esc_html_e( 'Infinity', 'wedding-elegant-wedding' ); ?>&rdquo;</em>
				</p>
			</div>
			<div class="wew-preloader-content">
				<p class="wew-preloader-kicker"><?php esc_html_e( 'The', 'wedding-elegant-wedding' ); ?> <strong><?php esc_html_e( 'Wedding', 'wedding-elegant-wedding' ); ?></strong> <?php esc_html_e( 'of', 'wedding-elegant-wedding' ); ?></p>
				<h2><?php echo esc_html( $names ); ?></h2>
				<p class="wew-preloader-guest">
					<span><?php esc_html_e( 'Kepada YTH.', 'wedding-elegant-wedding' ); ?></span>
					<strong><?php echo esc_html( $guest_name ); ?></strong>
				</p>
				<button class="wew-open-invitation" type="button" data-wew-open-invitation>
					<span aria-hidden="true">✉</span>
					<?php esc_html_e( 'Buka Undangan', 'wedding-elegant-wedding' ); ?>
				</button>
			</div>
			<img class="wew-preloader-flower" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/1_preloader/flower.png' ); ?>" alt="" aria-hidden="true">
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
