<?php
/**
 * EU-kontroll lookup: form shortcode and REST endpoint.
 *
 * The endpoint asks the `fram_eu_kontroll_lookup` filter for data. The theme hooks the
 * Statens vegvesen vehicle data API there when its key is defined in wp-config.php:
 *
 *     define( 'FRAM_VEGVESEN_API_KEY', '…' );
 *
 * Without a key nothing is hooked, and the form sends visitors to the Statens vegvesen
 * lookup page instead.
 *
 * A provider returns `array( 'deadline' => 'YYYY-MM-DD' )`, optionally with 'make', 'regnr',
 * 'vin', 'registered' and 'last_approved', or a WP_Error.
 *
 * The endpoint only serves the site's own pages: browser requests from other origins are
 * refused before the API is called and get no CORS headers.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Statens vegvesen page where visitors can look up the EU-kontroll deadline themselves.
 *
 * @return string
 */
function fram_eu_kontroll_fallback_url() {
	return (string) apply_filters(
		'fram_eu_kontroll_fallback_url',
		'https://www.vegvesen.no/kjoretoy/kjop-og-salg/kjoretoyopplysninger/sjekk-kjoretoyopplysninger/'
	);
}

/**
 * Where the "Bestill time" button under a result leads.
 *
 * @return string
 */
function fram_eu_kontroll_booking_url() {
	return (string) apply_filters( 'fram_eu_kontroll_booking_url', home_url( '/#bestill-time' ) );
}

/**
 * Normalizes a Norwegian registration number: upper case, no spaces or dashes.
 *
 * @param string $value Raw input.
 * @return string
 */
function fram_normalize_regnr( $value ) {
	$value = mb_strtoupper( (string) $value, 'UTF-8' );
	return (string) preg_replace( '/[\s\-]+/u', '', $value );
}

/**
 * Whether a value is a plausible registration number once normalized.
 *
 * @param string $value Raw input.
 * @return bool
 */
function fram_eu_kontroll_valid_regnr( $value ) {
	return 1 === preg_match( '/^[A-ZÆØÅ0-9]{2,9}$/u', fram_normalize_regnr( $value ) );
}

/**
 * Whether a lookup provider is connected.
 *
 * @return bool
 */
function fram_eu_kontroll_is_configured() {
	return (bool) has_filter( 'fram_eu_kontroll_lookup' );
}

/**
 * Formats an API date (YYYY-MM-DD) as DD.MM.YYYY.
 *
 * @param string $date Date from the API.
 * @return string
 */
function fram_eu_kontroll_date( $date ) {
	$parsed = DateTimeImmutable::createFromFormat( '!Y-m-d', (string) $date );
	return $parsed ? $parsed->format( 'd.m.Y' ) : (string) $date;
}

/**
 * Rows of the result table (page and booking mail), in this order; empty values are left out.
 *
 * @param array $result Lookup provider result.
 * @return array<int, array{key: string, label: string, value: string}>
 */
function fram_eu_kontroll_rows( array $result ) {
	$labels = array(
		'make'          => __( 'Merke', 'fram' ),
		'regnr'         => __( 'Reg. nummer', 'fram' ),
		'vin'           => __( 'Understellsnummer', 'fram' ),
		'registered'    => __( 'Reg. dato', 'fram' ),
		'last_approved' => __( 'Sist godkjent', 'fram' ),
		'deadline'      => __( 'Frist neste godkjenning', 'fram' ),
	);
	$dates  = array( 'registered', 'last_approved', 'deadline' );

	$rows = array();
	foreach ( $labels as $key => $label ) {
		$value = sanitize_text_field( (string) ( $result[ $key ] ?? '' ) );
		if ( '' !== $value ) {
			$rows[] = array(
				'key'   => $key,
				'label' => $label,
				'value' => in_array( $key, $dates, true ) ? fram_eu_kontroll_date( $value ) : $value,
			);
		}
	}

	return $rows;
}

/**
 * Registers the front-end script.
 */
function fram_eu_kontroll_register_script() {
	wp_register_script(
		'fram-eu-kontroll',
		get_theme_file_uri( 'assets/js/eu-kontroll.js' ),
		array(),
		fram_version(),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'init', 'fram_eu_kontroll_register_script' );

/**
 * Shortcode [fram_eu_kontroll].
 *
 * @return string
 */
function fram_eu_kontroll_shortcode() {
	wp_enqueue_script( 'fram-eu-kontroll' );

	$input_id = wp_unique_id( 'fram-eu-regnr-' );

	ob_start();
	?>
<form class="fram-eu-kontroll" novalidate data-endpoint="<?php echo esc_url( rest_url( 'fram/v1/eu-kontroll' ) ); ?>"
    data-fallback="<?php echo esc_url( fram_eu_kontroll_fallback_url() ); ?>"
    data-booking-url="<?php echo esc_url( fram_eu_kontroll_booking_url() ); ?>"
    data-configured="<?php echo fram_eu_kontroll_is_configured() ? '1' : '0'; ?>"
    data-msg-empty="<?php esc_attr_e( 'Skriv inn registreringsnummeret.', 'fram' ); ?>"
    data-msg-invalid="<?php esc_attr_e( 'Sjekk registreringsnummeret.', 'fram' ); ?>"
    data-msg-loading="<?php esc_attr_e( 'Søker …', 'fram' ); ?>"
    data-msg-title="<?php esc_attr_e( 'Resultat', 'fram' ); ?>"
    data-msg-booking="<?php esc_attr_e( 'Bestill time', 'fram' ); ?>"
    data-msg-result="<?php esc_attr_e( 'Neste frist for EU-kontroll: %s', 'fram' ); ?>"
    data-msg-fallback="<?php esc_attr_e( 'Søket åpnes hos Statens vegvesen.', 'fram' ); ?>"
    data-msg-error="<?php esc_attr_e( 'Fant ikke fristen. Prøv igjen senere.', 'fram' ); ?>">
    <label class="screen-reader-text"
        for="<?php echo esc_attr( $input_id ); ?>"><?php esc_html_e( 'Registreringsnummer', 'fram' ); ?></label>
    <input id="<?php echo esc_attr( $input_id ); ?>" name="regnr" type="text" inputmode="text" autocomplete="off"
        maxlength="12" required placeholder="<?php esc_attr_e( 'Reg. nummer *', 'fram' ); ?>">
    <button type="submit" class="wp-element-button"><?php esc_html_e( 'Søk nå', 'fram' ); ?></button>
    <div class="fram-eu-kontroll__result" aria-live="polite"></div>
</form>
<?php
	return (string) ob_get_clean();
}
add_shortcode( 'fram_eu_kontroll', 'fram_eu_kontroll_shortcode' );

/**
 * Loads the lookup script with the booking form as well: after "Bestill time" under a result
 * it ticks the EU-kontroll service and fills in the registration number.
 *
 * @param string       $output Shortcode output.
 * @param string       $tag    Shortcode name.
 * @param array|string $attr   Shortcode attributes.
 * @return string
 */
function fram_eu_kontroll_booking_script( $output, $tag, $attr ) {
	if ( 'fram_form' === $tag && 'bestill-time' === ( is_array( $attr ) && isset( $attr['slug'] ) ? $attr['slug'] : 'bestill-time' ) ) {
		wp_enqueue_script( 'fram-eu-kontroll' );
	}

	return $output;
}
add_filter( 'do_shortcode_tag', 'fram_eu_kontroll_booking_script', 10, 3 );

/**
 * Whether a request comes from the site itself. Browsers send no Origin header with same-origin
 * GET requests, and the site's own origin when they do.
 *
 * @param WP_REST_Request $request Request.
 * @return bool
 */
function fram_eu_kontroll_is_own_origin( WP_REST_Request $request ) {
	$origin = $request->get_header( 'origin' );
	if ( ! $origin ) {
		return true;
	}

	$allowed = array();
	foreach ( array( home_url(), site_url() ) as $url ) {
		$parts     = wp_parse_url( $url );
		$allowed[] = strtolower( $parts['scheme'] . '://' . $parts['host'] . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) );
	}

	return in_array( strtolower( untrailingslashit( $origin ) ), $allowed, true );
}

/**
 * Permission callback: open to visitors, closed to other origins.
 *
 * @param WP_REST_Request $request Request.
 * @return true|WP_Error
 */
function fram_eu_kontroll_check_origin( WP_REST_Request $request ) {
	return fram_eu_kontroll_is_own_origin( $request )
		? true
		: new WP_Error( 'fram_eu_forbidden_origin', __( 'Ikke tillatt.', 'fram' ), array( 'status' => 403 ) );
}

/**
 * Removes the CORS headers core sends for any origin (rest_send_cors_headers, priority 10)
 * from lookup responses to other origins.
 *
 * @param bool             $served  Whether the request has been served.
 * @param WP_HTTP_Response $result  Response.
 * @param WP_REST_Request  $request Request.
 * @return bool
 */
function fram_eu_kontroll_cors( $served, $result, $request ) {
	if ( str_starts_with( $request->get_route(), '/fram/v1/eu-kontroll' ) && ! fram_eu_kontroll_is_own_origin( $request ) ) {
		header_remove( 'Access-Control-Allow-Origin' );
		header_remove( 'Access-Control-Allow-Credentials' );
	}

	return $served;
}
add_filter( 'rest_pre_serve_request', 'fram_eu_kontroll_cors', 11, 3 );

/**
 * Registers GET /wp-json/fram/v1/eu-kontroll?regnr=AB12345.
 */
function fram_eu_kontroll_register_route() {
	register_rest_route(
		'fram/v1',
		'/eu-kontroll',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'permission_callback' => 'fram_eu_kontroll_check_origin',
			'callback'            => 'fram_eu_kontroll_rest',
			'args'                => array(
				'regnr' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'fram_normalize_regnr',
					'validate_callback' => 'fram_eu_kontroll_valid_regnr',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'fram_eu_kontroll_register_route' );

/**
 * REST callback.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function fram_eu_kontroll_rest( WP_REST_Request $request ) {
	$regnr = $request['regnr'];

	/**
	 * Looks up the EU-kontroll deadline for a registration number.
	 *
	 * @param array|WP_Error|null $result Null while no provider is connected.
	 * @param string              $regnr  Normalized registration number.
	 */
	$result = apply_filters( 'fram_eu_kontroll_lookup', null, $regnr );

	if ( null === $result ) {
		return rest_ensure_response(
			array(
				'configured'   => false,
				'regnr'        => $regnr,
				'fallback_url' => fram_eu_kontroll_fallback_url(),
			)
		);
	}

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return rest_ensure_response(
		array(
			'configured' => true,
			'regnr'      => $regnr,
			'deadline'   => isset( $result['deadline'] ) ? sanitize_text_field( $result['deadline'] ) : null,
			'rows'       => fram_eu_kontroll_rows( $result ),
		)
	);
}

/**
 * Adds the vehicle data for the submitted registration number to the booking mail to the
 * workshop (form "bestill-time"), before the signature. Plain text, or a table in HTML mails.
 *
 * @param WPCF7_ContactForm $contact_form Form being sent.
 */
function fram_eu_kontroll_booking_mail( $contact_form ) {
	$submission = WPCF7_Submission::get_instance();
	if ( 'bestill-time' !== $contact_form->name() || ! $submission || ! fram_eu_kontroll_is_configured() ) {
		return;
	}

	$regnr = (string) $submission->get_posted_data( 'regnr' );
	if ( '' === trim( $regnr ) ) {
		return;
	}

	$result = fram_eu_kontroll_valid_regnr( $regnr )
		? apply_filters( 'fram_eu_kontroll_lookup', null, fram_normalize_regnr( $regnr ) )
		: new WP_Error( 'fram_eu_not_found', __( 'Fant ikke kjøretøyet. Sjekk registreringsnummeret.', 'fram' ) );
	if ( null === $result ) {
		return;
	}

	$mail  = $contact_form->prop( 'mail' );
	$title = mb_strtoupper( __( 'Neste EU-kontroll', 'fram' ), 'UTF-8' );
	$rows  = is_array( $result ) ? fram_eu_kontroll_rows( $result ) : array();
	$error = is_wp_error( $result ) ? $result->get_error_message() : __( 'Fant ikke fristen.', 'fram' );

	if ( ! empty( $mail['use_html'] ) ) {
		$section = '<h2 style="margin:10px 0 15px;color:#000;font:bold 20px/1.3 Arial,Helvetica,sans-serif;">' . esc_html( $title ) . '</h2>';
		if ( $rows ) {
			$section .= '<table style="width:100%;border-collapse:collapse;color:#000;"><tbody>';
			foreach ( $rows as $i => $row ) {
				$section .= sprintf(
					'<tr%1$s><td style="width:50%%;padding:10px;border:1px solid #e9e9e9;"><b>%2$s</b></td><td style="padding:10px;border:1px solid #e9e9e9;">%3$s</td></tr>',
					$i % 2 ? ' style="background-color:#f8f8f8;"' : '',
					esc_html( $row['label'] ),
					esc_html( $row['value'] )
				);
			}
			$section .= '</tbody></table>';
		} else {
			$section .= '<p>' . esc_html( $error ) . '</p>';
		}
	} else {
		$lines   = $rows ? array_map( static fn( $row ) => $row['label'] . ': ' . $row['value'], $rows ) : array( $error );
		$section = $title . "\n" . implode( "\n", $lines );
	}

	// Before the "-- " signature line when there is one, otherwise at the end.
	$signature    = strrpos( $mail['body'], "\n-- \n" );
	$mail['body'] = false === $signature
		? $mail['body'] . "\n\n" . $section
		: substr_replace( $mail['body'], $section . "\n\n", $signature + 1, 0 );

	$contact_form->set_properties( array( 'mail' => $mail ) );
}
add_action( 'wpcf7_before_send_mail', 'fram_eu_kontroll_booking_mail' );

/**
 * Lookup provider: Statens vegvesen vehicle data API (single lookup by registration number).
 *
 * @param array|WP_Error|null $result Result of an earlier provider.
 * @param string              $regnr  Normalized registration number.
 * @return array|WP_Error
 */
function fram_vegvesen_lookup( $result, $regnr ) {
	if ( null !== $result ) {
		return $result;
	}

	$response = wp_remote_get(
		'https://www.vegvesen.no/ws/no/vegvesen/kjoretoy/felles/datautlevering/enkeltoppslag/kjoretoydata?kjennemerke=' . rawurlencode( $regnr ),
		array(
			'timeout' => 10,
			'headers' => array(
				'SVV-Authorization' => FRAM_VEGVESEN_API_KEY,
				'Accept'            => 'application/json',
			),
		)
	);

	$code = (int) wp_remote_retrieve_response_code( $response );

	// 204 without a body: no vehicle with this registration number. 400: not a valid number
	// for the API (e.g. more than 7 characters).
	if ( 204 === $code || 400 === $code ) {
		return new WP_Error( 'fram_eu_not_found', __( 'Fant ikke kjøretøyet. Sjekk registreringsnummeret.', 'fram' ), array( 'status' => 404 ) );
	}

	$data    = 200 === $code ? json_decode( wp_remote_retrieve_body( $response ), true ) : null;
	$vehicle = $data['kjoretoydataListe'][0] ?? null;
	if ( ! is_array( $vehicle ) ) {
		return new WP_Error( 'fram_eu_unavailable', __( 'Fant ikke fristen. Prøv igjen senere.', 'fram' ), array( 'status' => 502 ) );
	}

	$general = $vehicle['godkjenning']['tekniskGodkjenning']['tekniskeData']['generelt'] ?? array();

	return array(
		'deadline'      => $vehicle['periodiskKjoretoyKontroll']['kontrollfrist'] ?? '',
		'make'          => trim( ( $general['merke'][0]['merke'] ?? '' ) . ' ' . ( $general['handelsbetegnelse'][0] ?? '' ) ),
		'regnr'         => $vehicle['kjoretoyId']['kjennemerke'] ?? $regnr,
		'vin'           => $vehicle['kjoretoyId']['understellsnummer'] ?? '',
		'registered'    => $vehicle['forstegangsregistrering']['registrertForstegangNorgeDato'] ?? '',
		'last_approved' => $vehicle['periodiskKjoretoyKontroll']['sistGodkjent'] ?? '',
	);
}

if ( defined( 'FRAM_VEGVESEN_API_KEY' ) && FRAM_VEGVESEN_API_KEY ) {
	add_filter( 'fram_eu_kontroll_lookup', 'fram_vegvesen_lookup', 10, 2 );
}