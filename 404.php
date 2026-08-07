<?php
/**
 * 404 template.
 *
 * @package Wedding_Elegant_Wedding
 */

get_header();
?>

<section class="wew-page-hero">
	<div class="wew-container">
		<p class="wew-kicker"><?php esc_html_e( '404', 'wedding-elegant-wedding' ); ?></p>
		<h1><?php esc_html_e( 'Halaman tidak ditemukan', 'wedding-elegant-wedding' ); ?></h1>
	</div>
</section>
<section class="wew-section">
	<div class="wew-container wew-narrow">
		<p><?php esc_html_e( 'Konten yang Anda cari mungkin sudah dipindahkan atau belum tersedia.', 'wedding-elegant-wedding' ); ?></p>
		<a class="wew-button wew-button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Kembali ke Home', 'wedding-elegant-wedding' ); ?></a>
	</div>
</section>

<?php
get_footer();

