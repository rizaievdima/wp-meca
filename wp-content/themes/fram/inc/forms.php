<?php
/**
 * Contact Form 7 integration: creates the theme forms once and renders them by slug.
 *
 * Form markup lives in /forms/*.txt and is styled by the "form-panel" block style.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme forms: slug => title, markup file, mail subject, mail body.
 *
 * @return array<string, array<string, string>>
 */
function fram_form_definitions() {
	return array(
		'bestill-time' => array(
			'title'   => __( 'Bestill time', 'fram' ),
			'file'    => 'forms/bestill-time.txt',
			'subject' => '[_site_title] – ' . __( 'timebestilling', 'fram' ) . ': [regnr]',
			'body'    => implode(
				"\n",
				array(
					__( 'Ny timebestilling fra nettsiden', 'fram' ),
					'',
					__( 'Tjenester', 'fram' ) . ': [tjenester]',
					__( 'Ønsket dato', 'fram' ) . ': [dato]',
					__( 'Navn', 'fram' ) . ': [navn]',
					__( 'Telefon', 'fram' ) . ': [telefon]',
					__( 'Registreringsnummer', 'fram' ) . ': [regnr]',
					__( 'E-post', 'fram' ) . ': [epost]',
					'',
					__( 'Melding', 'fram' ) . ':',
					'[melding]',
					'',
					'-- ',
					'[_site_title] ([_site_url])',
				)
			),
		),
		'kontakt'      => array(
			'title'   => __( 'Kontakt', 'fram' ),
			'file'    => 'forms/kontakt.txt',
			'subject' => '[_site_title] – ' . __( 'henvendelse fra', 'fram' ) . ' [navn]',
			'body'    => implode(
				"\n",
				array(
					__( 'Ny henvendelse fra kontaktskjemaet', 'fram' ),
					'',
					__( 'Navn', 'fram' ) . ': [navn]',
					__( 'E-post', 'fram' ) . ': [epost]',
					__( 'Telefon', 'fram' ) . ': [telefon]',
					__( 'Registreringsnummer', 'fram' ) . ': [regnr]',
					__( 'Bilmerke', 'fram' ) . ': [bilmerke]',
					'',
					__( 'Kommentar', 'fram' ) . ':',
					'[melding]',
					'',
					'-- ',
					'[_site_title] ([_site_url])',
				)
			),
		),
	);
}

/**
 * Finds a Contact Form 7 form by post slug.
 *
 * @param string $slug Form slug.
 * @return WPCF7_ContactForm|null
 */
function fram_get_form( $slug ) {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return null;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'name'           => sanitize_title( $slug ),
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
		)
	);

	return $posts ? wpcf7_contact_form( $posts[0] ) : null;
}

/**
 * Creates the theme forms once (a form deleted by the user is not recreated).
 */
function fram_maybe_create_forms() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) || ! current_user_can( 'wpcf7_edit_contact_forms' ) ) {
		return;
	}

	$created = (array) get_option( 'fram_cf7_forms', array() );

	foreach ( fram_form_definitions() as $slug => $definition ) {
		if ( isset( $created[ $slug ] ) ) {
			continue;
		}

		$existing = fram_get_form( $slug );
		if ( $existing ) {
			$created[ $slug ] = $existing->id();
			continue;
		}

		$markup = file_get_contents( get_theme_file_path( $definition['file'] ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
		if ( false === $markup ) {
			continue;
		}

		$form = WPCF7_ContactForm::get_template( array( 'title' => $definition['title'] ) );
		$mail = $form->prop( 'mail' );

		$mail['subject']            = $definition['subject'];
		$mail['body']               = $definition['body'];
		$mail['additional_headers'] = 'Reply-To: [epost]';

		$form->set_properties(
			array(
				'form' => str_replace( '{home}', esc_url( home_url( '/' ) ), $markup ),
				'mail' => $mail,
			)
		);
		$form_id = $form->save();

		if ( $form_id ) {
			wp_update_post(
				array(
					'ID'        => $form_id,
					'post_name' => $slug,
				)
			);
			$created[ $slug ] = $form_id;
		}
	}

	update_option( 'fram_cf7_forms', $created, false );
}
add_action( 'admin_init', 'fram_maybe_create_forms' );

/**
 * Shortcode [fram_form slug="bestill-time"].
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function fram_form_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'slug' => 'bestill-time' ), $atts, 'fram_form' );
	$form = fram_get_form( $atts['slug'] );

	if ( $form ) {
		return do_shortcode( $form->shortcode() );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		return '';
	}

	$message = class_exists( 'WPCF7_ContactForm' )
		/* translators: %s: form slug. */
		? sprintf( __( 'Fant ikke Contact Form 7-skjemaet «%s». Åpne kontrollpanelet én gang for å opprette det, eller lag et skjema med denne slugen.', 'fram' ), $atts['slug'] )
		: __( 'Aktiver Contact Form 7 for å vise skjemaet.', 'fram' );

	return '<p class="fram-form-notice">' . esc_html( $message ) . '</p>';
}
add_shortcode( 'fram_form', 'fram_form_shortcode' );

// The form markup is laid out by CSS; automatic <p>/<br> would break it.
add_filter( 'wpcf7_autop_or_not', '__return_false' );
