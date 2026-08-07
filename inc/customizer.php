<?php
/**
 * Customizer settings for the wedding invitation content.
 *
 * @package Wedding_Elegant_Wedding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default theme content.
 *
 * @return array<string,string>
 */
function wew_defaults() {
	return array(
		'bride_name'    => 'Isti',
		'groom_name'    => 'Bagus',
		'event_intro'   => 'Dengan penuh sukacita, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dan memberikan doa restu pada hari bahagia kami.',
		'event_date'    => '2026-12-12T09:00',
		'venue_name'    => 'Nama Venue',
		'venue_address' => 'Alamat lengkap venue pernikahan',
		'maps_url'      => '',
		'rsvp_url'      => '#rsvp',
		'whatsapp'      => '',
		'music_url'     => '',
		'preloader'     => '1',
		'hashtag'       => '#WeddingElegant',
		'story_title'   => 'Dua hati, satu langkah',
		'story_body'    => 'Setiap perjalanan punya cara indahnya sendiri untuk mempertemukan dua hati. Hari ini kami memulai babak baru bersama keluarga dan orang-orang terkasih.',
	);
}

/**
 * Read a theme option with default fallback.
 *
 * @param string $key Option key without the wew_ prefix.
 * @return string
 */
function wew_get_option( $key ) {
	$defaults = wew_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return get_theme_mod( 'wew_' . $key, $default );
}

/**
 * Sanitize URLs while still allowing in-page anchors.
 *
 * @param string $value Raw value.
 * @return string
 */
function wew_sanitize_url_or_anchor( $value ) {
	$value = trim( $value );

	if ( '' === $value ) {
		return '';
	}

	if ( 0 === strpos( $value, '#' ) ) {
		return '#' . sanitize_title( substr( $value, 1 ) );
	}

	return esc_url_raw( $value );
}

add_action( 'customize_register', 'wew_customize_register' );
/**
 * Register Customizer fields.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function wew_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'wew_wedding',
		array(
			'title'       => __( 'Wedding Details', 'wedding-elegant-wedding' ),
			'description' => __( 'Atur nama mempelai, tanggal acara, lokasi, dan tombol RSVP.', 'wedding-elegant-wedding' ),
			'priority'    => 30,
		)
	);

	$fields = array(
		'bride_name'    => array(
			'label'    => __( 'Nama Mempelai 1', 'wedding-elegant-wedding' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'groom_name'    => array(
			'label'    => __( 'Nama Mempelai 2', 'wedding-elegant-wedding' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'event_intro'   => array(
			'label'    => __( 'Kalimat Pembuka', 'wedding-elegant-wedding' ),
			'type'     => 'textarea',
			'sanitize' => 'sanitize_textarea_field',
		),
		'event_date'    => array(
			'label'    => __( 'Tanggal dan Jam Acara', 'wedding-elegant-wedding' ),
			'type'     => 'datetime-local',
			'sanitize' => 'sanitize_text_field',
		),
		'venue_name'    => array(
			'label'    => __( 'Nama Venue', 'wedding-elegant-wedding' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'venue_address' => array(
			'label'    => __( 'Alamat Venue', 'wedding-elegant-wedding' ),
			'type'     => 'textarea',
			'sanitize' => 'sanitize_textarea_field',
		),
		'maps_url'      => array(
			'label'    => __( 'Link Google Maps', 'wedding-elegant-wedding' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'rsvp_url'      => array(
			'label'    => __( 'Link RSVP', 'wedding-elegant-wedding' ),
			'type'     => 'text',
			'sanitize' => 'wew_sanitize_url_or_anchor',
		),
		'whatsapp'      => array(
			'label'    => __( 'Nomor WhatsApp RSVP', 'wedding-elegant-wedding' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'music_url'     => array(
			'label'    => __( 'URL Lagu Pernikahan', 'wedding-elegant-wedding' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'preloader'     => array(
			'label'    => __( 'Tampilkan Preloader', 'wedding-elegant-wedding' ),
			'type'     => 'checkbox',
			'sanitize' => 'wew_sanitize_checkbox',
		),
		'hashtag'       => array(
			'label'    => __( 'Hashtag', 'wedding-elegant-wedding' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'story_title'   => array(
			'label'    => __( 'Judul Cerita', 'wedding-elegant-wedding' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'story_body'    => array(
			'label'    => __( 'Isi Cerita Singkat', 'wedding-elegant-wedding' ),
			'type'     => 'textarea',
			'sanitize' => 'sanitize_textarea_field',
		),
	);

	$defaults = wew_defaults();

	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			'wew_' . $id,
			array(
				'default'           => $defaults[ $id ],
				'sanitize_callback' => $field['sanitize'],
			)
		);

		$wp_customize->add_control(
			'wew_' . $id,
			array(
				'label'   => $field['label'],
				'section' => 'wew_wedding',
				'type'    => $field['type'],
			)
		);
	}
}

/**
 * Sanitize checkbox values.
 *
 * @param mixed $checked Raw value.
 * @return string
 */
function wew_sanitize_checkbox( $checked ) {
	return ! empty( $checked ) ? '1' : '0';
}
