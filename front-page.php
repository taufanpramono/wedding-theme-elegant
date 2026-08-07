<?php
/**
 * Front page wedding invitation.
 *
 * @package Wedding_Elegant_Wedding
 */

get_header();

$bride      = wew_get_option( 'bride_name' );
$groom      = wew_get_option( 'groom_name' );
$hero_image = get_template_directory_uri() . '/assets/images/wedding-hero.png';
$rsvp_link  = wew_rsvp_link();
?>

<section class="wew-hero" id="home" style="--wew-hero-image: url('<?php echo esc_url( $hero_image ); ?>');">
	<div class="wew-hero-overlay"></div>
	<div class="wew-hero-content">
		<p class="wew-kicker"><?php esc_html_e( 'The Wedding of', 'wedding-elegant-wedding' ); ?></p>
		<h1><?php echo esc_html( $bride ); ?> <span>&amp;</span> <?php echo esc_html( $groom ); ?></h1>
		<p class="wew-date">
			<time datetime="<?php echo esc_attr( wew_event_iso() ); ?>"><?php echo esc_html( wew_event_date_label() ); ?></time>
		</p>
		<div class="wew-hero-actions">
			<a class="wew-button wew-button-primary" href="<?php echo esc_url( $rsvp_link ); ?>"><?php esc_html_e( 'Konfirmasi Hadir', 'wedding-elegant-wedding' ); ?></a>
			<a class="wew-button wew-button-secondary" href="#details"><?php esc_html_e( 'Lihat Detail', 'wedding-elegant-wedding' ); ?></a>
		</div>
	</div>
	<div class="wew-countdown" data-countdown="<?php echo esc_attr( wew_event_iso() ); ?>" aria-label="<?php esc_attr_e( 'Countdown acara', 'wedding-elegant-wedding' ); ?>">
		<div><strong data-days>00</strong><span><?php esc_html_e( 'Hari', 'wedding-elegant-wedding' ); ?></span></div>
		<div><strong data-hours>00</strong><span><?php esc_html_e( 'Jam', 'wedding-elegant-wedding' ); ?></span></div>
		<div><strong data-minutes>00</strong><span><?php esc_html_e( 'Menit', 'wedding-elegant-wedding' ); ?></span></div>
		<div><strong data-seconds>00</strong><span><?php esc_html_e( 'Detik', 'wedding-elegant-wedding' ); ?></span></div>
	</div>
</section>

<section class="wew-section wew-intro">
	<div class="wew-container wew-narrow">
		<p class="wew-kicker"><?php esc_html_e( 'Undangan Pernikahan', 'wedding-elegant-wedding' ); ?></p>
		<h2><?php echo esc_html( $bride . ' & ' . $groom ); ?></h2>
		<p><?php echo esc_html( wew_get_option( 'event_intro' ) ); ?></p>
	</div>
</section>

<section class="wew-section wew-story" id="story">
	<div class="wew-container wew-split">
		<div>
			<p class="wew-kicker"><?php esc_html_e( 'Our Story', 'wedding-elegant-wedding' ); ?></p>
			<h2><?php echo esc_html( wew_get_option( 'story_title' ) ); ?></h2>
		</div>
		<div class="wew-story-copy">
			<p><?php echo esc_html( wew_get_option( 'story_body' ) ); ?></p>
		</div>
	</div>
</section>

<section class="wew-section wew-details" id="details">
	<div class="wew-container">
		<div class="wew-section-heading">
			<p class="wew-kicker"><?php esc_html_e( 'Wedding Day', 'wedding-elegant-wedding' ); ?></p>
			<h2><?php esc_html_e( 'Detail Acara', 'wedding-elegant-wedding' ); ?></h2>
		</div>
		<div class="wew-event-grid">
			<article class="wew-event-card">
				<span><?php esc_html_e( 'Akad', 'wedding-elegant-wedding' ); ?></span>
				<h3><?php echo esc_html( wew_event_date_label() ); ?></h3>
				<p><?php echo esc_html( wew_event_time_label() ); ?></p>
			</article>
			<article class="wew-event-card">
				<span><?php esc_html_e( 'Venue', 'wedding-elegant-wedding' ); ?></span>
				<h3><?php echo esc_html( wew_get_option( 'venue_name' ) ); ?></h3>
				<p><?php echo nl2br( esc_html( wew_get_option( 'venue_address' ) ) ); ?></p>
			</article>
			<article class="wew-event-card">
				<span><?php esc_html_e( 'Resepsi', 'wedding-elegant-wedding' ); ?></span>
				<h3><?php esc_html_e( 'Setelah akad', 'wedding-elegant-wedding' ); ?></h3>
				<p><?php esc_html_e( 'Kami menantikan kehadiran dan doa restu Anda bersama keluarga.', 'wedding-elegant-wedding' ); ?></p>
			</article>
		</div>
		<?php if ( wew_get_option( 'maps_url' ) ) : ?>
			<p class="wew-center">
				<a class="wew-button wew-button-secondary" href="<?php echo esc_url( wew_get_option( 'maps_url' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Buka Lokasi', 'wedding-elegant-wedding' ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</section>

<section class="wew-section wew-gallery" id="gallery">
	<div class="wew-container">
		<div class="wew-section-heading">
			<p class="wew-kicker"><?php esc_html_e( 'Gallery', 'wedding-elegant-wedding' ); ?></p>
			<h2><?php esc_html_e( 'Momen yang Menjadi Kenangan', 'wedding-elegant-wedding' ); ?></h2>
		</div>
		<div class="wew-gallery-grid">
			<figure class="wew-gallery-item wew-gallery-large">
				<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Dekorasi pernikahan elegan', 'wedding-elegant-wedding' ); ?>">
			</figure>
			<figure class="wew-gallery-item wew-floral-card">
				<figcaption><?php esc_html_e( 'Love', 'wedding-elegant-wedding' ); ?></figcaption>
			</figure>
			<figure class="wew-gallery-item wew-note-card">
				<figcaption><?php echo esc_html( wew_get_option( 'hashtag' ) ); ?></figcaption>
			</figure>
		</div>
	</div>
</section>

<section class="wew-section wew-rsvp" id="rsvp">
	<div class="wew-container wew-narrow">
		<p class="wew-kicker"><?php esc_html_e( 'RSVP', 'wedding-elegant-wedding' ); ?></p>
		<h2><?php esc_html_e( 'Konfirmasi Kehadiran', 'wedding-elegant-wedding' ); ?></h2>
		<p><?php esc_html_e( 'Kehadiran Anda akan menjadi kebahagiaan bagi kami dan keluarga.', 'wedding-elegant-wedding' ); ?></p>
		<a class="wew-button wew-button-primary" href="<?php echo esc_url( $rsvp_link ); ?>"><?php esc_html_e( 'Konfirmasi Sekarang', 'wedding-elegant-wedding' ); ?></a>
	</div>
</section>

<?php
get_footer();

