<?php
/**
 * Front page wedding invitation.
 *
 * @package Wedding_Elegant_Wedding
 */

get_header();

$bride      = wew_get_option( 'bride_name' );
$groom      = wew_get_option( 'groom_name' );
$hero_image = file_exists( get_template_directory() . '/assets/images/wedding-hero.png' )
	? get_template_directory_uri() . '/assets/images/wedding-hero.png'
	: get_template_directory_uri() . '/assets/images/1_preloader/background.jpg';
$rsvp_link  = wew_rsvp_link();
$maps_url   = wew_get_option( 'maps_url' );
$maps_link  = $maps_url ? $maps_url : '#rsvp';
?>

<div class="wew-invitation-shell">
	<section class="wew-cover" id="home">
		<figure class="wew-cover-photo">
			<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Dekorasi pernikahan elegan', 'wedding-elegant-wedding' ); ?>">
			<figcaption>
				<span><?php echo esc_html( $bride ); ?></span>
				<span><?php echo esc_html( $groom ); ?></span>
			</figcaption>
		</figure>
		<div class="wew-cover-copy">
			<p><?php echo esc_html( wew_get_option( 'event_intro' ) ); ?></p>
			<time datetime="<?php echo esc_attr( wew_event_iso() ); ?>"><?php echo esc_html( wp_date( 'd.m.Y', wew_event_timestamp() ) ); ?></time>
		</div>
	</section>

	<section class="wew-location-panel" id="details">
		<div class="wew-strip-heading">
			<span><?php esc_html_e( 'Lokasi', 'wedding-elegant-wedding' ); ?></span>
			<span><?php echo esc_html( wew_get_option( 'venue_name' ) ); ?></span>
			<a href="<?php echo esc_url( $maps_link ); ?>" <?php echo $maps_url ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> aria-label="<?php esc_attr_e( 'Buka lokasi acara', 'wedding-elegant-wedding' ); ?>">&#8594;</a>
		</div>
		<div class="wew-location-grid">
			<article>
				<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Area acara pernikahan', 'wedding-elegant-wedding' ); ?>">
				<span>01</span>
				<p><?php echo esc_html( wew_get_option( 'venue_address' ) ); ?></p>
			</article>
			<article>
				<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Meja resepsi pernikahan', 'wedding-elegant-wedding' ); ?>">
				<span>02</span>
				<p><?php esc_html_e( 'Resepsi hangat bersama keluarga dan sahabat.', 'wedding-elegant-wedding' ); ?></p>
			</article>
		</div>
		<div class="wew-directions">
			<h2><?php esc_html_e( 'Lokasi | Cara Menuju', 'wedding-elegant-wedding' ); ?></h2>
			<a class="wew-button wew-button-outline" href="<?php echo esc_url( $maps_link ); ?>" <?php echo $maps_url ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>><?php esc_html_e( 'Lokasi', 'wedding-elegant-wedding' ); ?></a>
			<span class="wew-stamp"><?php esc_html_e( 'maps', 'wedding-elegant-wedding' ); ?></span>
		</div>
	</section>

	<section class="wew-brown-panel" id="story">
		<div class="wew-brown-title">
			<p><?php esc_html_e( 'Matur Nuwun', 'wedding-elegant-wedding' ); ?></p>
			<h2><?php echo esc_html( $bride . ' & ' . $groom ); ?></h2>
			<span><?php esc_html_e( 'Por Favor', 'wedding-elegant-wedding' ); ?></span>
		</div>
		<div class="wew-request-grid">
			<article>
				<span>01</span>
				<h3><?php esc_html_e( 'Saran Lagu', 'wedding-elegant-wedding' ); ?></h3>
				<p><?php esc_html_e( 'Kirim lagu favorit untuk menemani pesta kecil kami.', 'wedding-elegant-wedding' ); ?></p>
			</article>
			<article>
				<span>02</span>
				<h3><?php esc_html_e( 'Doa Restu', 'wedding-elegant-wedding' ); ?></h3>
				<p><?php echo esc_html( wew_get_option( 'story_body' ) ); ?></p>
			</article>
		</div>
		<span class="wew-stamp wew-stamp-light"><?php esc_html_e( 'love', 'wedding-elegant-wedding' ); ?></span>
	</section>

	<section class="wew-menu-panel">
		<div class="wew-stripes" aria-hidden="true"></div>
		<div class="wew-silver-tray">
			<div class="wew-menu-card">
				<h2><?php esc_html_e( 'Menu', 'wedding-elegant-wedding' ); ?></h2>
				<p><strong><?php esc_html_e( 'Resepsi', 'wedding-elegant-wedding' ); ?></strong><br><?php esc_html_e( 'Hidangan hangat, dessert manis, dan minuman pilihan.', 'wedding-elegant-wedding' ); ?></p>
				<p><strong><?php esc_html_e( 'Cerita', 'wedding-elegant-wedding' ); ?></strong><br><?php echo esc_html( wew_get_option( 'story_title' ) ); ?></p>
				<p><strong><?php esc_html_e( 'Hadiah', 'wedding-elegant-wedding' ); ?></strong><br><?php esc_html_e( 'Doa Anda adalah hadiah terbaik untuk kami.', 'wedding-elegant-wedding' ); ?></p>
			</div>
		</div>
	</section>

	<section class="wew-photo-panel" id="gallery">
		<figure>
			<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Gallery pernikahan', 'wedding-elegant-wedding' ); ?>">
			<figcaption><?php esc_html_e( 'Galeri de Fotos', 'wedding-elegant-wedding' ); ?></figcaption>
		</figure>
		<p><?php esc_html_e( 'Se habilitara album completo para que subas tus fotos del evento.', 'wedding-elegant-wedding' ); ?></p>
		<a class="wew-button wew-button-outline" href="#gallery"><?php esc_html_e( 'Fotos', 'wedding-elegant-wedding' ); ?></a>
	</section>

	<section class="wew-dress-code">
		<h2><?php esc_html_e( 'Dress Code', 'wedding-elegant-wedding' ); ?></h2>
		<p><?php esc_html_e( 'Elegan | Natural | Earth tone', 'wedding-elegant-wedding' ); ?></p>
		<div class="wew-polaroid-stack" aria-hidden="true">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div>
	</section>

	<section class="wew-save-date" id="rsvp">
		<h2><?php esc_html_e( 'Guarda la Fecha', 'wedding-elegant-wedding' ); ?></h2>
		<a class="wew-button wew-button-outline" href="<?php echo esc_url( $rsvp_link ); ?>"><?php esc_html_e( 'Agenda la Fecha', 'wedding-elegant-wedding' ); ?></a>
		<p>
			<time datetime="<?php echo esc_attr( wew_event_iso() ); ?>"><?php echo esc_html( wew_event_date_label() ); ?></time>
			<span><?php echo esc_html( wew_event_time_label() ); ?></span>
		</p>
		<div class="wew-envelope">
			<a href="<?php echo esc_url( $rsvp_link ); ?>"><?php esc_html_e( 'Confirma la Asistencia', 'wedding-elegant-wedding' ); ?></a>
		</div>
		<div class="wew-countdown" data-countdown="<?php echo esc_attr( wew_event_iso() ); ?>" aria-label="<?php esc_attr_e( 'Countdown acara', 'wedding-elegant-wedding' ); ?>">
			<div><strong data-days>00</strong><span><?php esc_html_e( 'Hari', 'wedding-elegant-wedding' ); ?></span></div>
			<div><strong data-hours>00</strong><span><?php esc_html_e( 'Jam', 'wedding-elegant-wedding' ); ?></span></div>
			<div><strong data-minutes>00</strong><span><?php esc_html_e( 'Menit', 'wedding-elegant-wedding' ); ?></span></div>
			<div><strong data-seconds>00</strong><span><?php esc_html_e( 'Detik', 'wedding-elegant-wedding' ); ?></span></div>
		</div>
	</section>
</div>

<?php
get_footer();
