<?php
/**
 * Theme setup: supports, assets, pattern categories and block template part helpers.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

function print_pretty( $var ) {
	echo '<pre>';
	print_r( $var );
	echo '</pre>';
}


function log_pretty( $var ) {
	error_log( print_r( $var, true ) );
}
/**
 * Registers theme supports.
 */
function fram_setup() {
	load_theme_textdomain( 'fram', get_template_directory() . '/languages' );

	// Header/footer are block template parts editable in Appearance → Design.
	add_theme_support( 'block-template-parts' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'width'       => 330,
			'height'      => 105,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );

	// Icon font and component styles in the editor canvas.
	add_editor_style( array( 'assets/vendor/fontawesome/css/all.min.css', 'style.css' ) );
}
add_action( 'after_setup_theme', 'fram_setup' );

/**
 * Returns the version for asset cache busting: the theme version plus the newest modification
 * time of style.css and assets/js. The server sends no cache headers, so browsers keep files
 * by heuristics and would miss edits made while the theme version stays the same.
 *
 * @return string
 */
function fram_version() {
	static $version = null;

	if ( null === $version ) {
		$dir     = get_template_directory();
		$files   = array_merge( array( $dir . '/style.css' ), glob( $dir . '/assets/js/*.js' ) ?: array() );
		$version = wp_get_theme( get_template() )->get( 'Version' ) . '.' . max( array_map( 'filemtime', $files ) );
	}

	return $version;
}

/**
 * Enqueues front-end assets: Font Awesome, style.css (component styles) and theme.js.
 */
function fram_enqueue_assets() {
	// Font Awesome Free, self-hosted (assets/vendor/fontawesome).
	wp_enqueue_style( 'fram-fontawesome', get_theme_file_uri( 'assets/vendor/fontawesome/css/all.min.css' ), array(), '7.3.1' );

	// Print after the global styles, whether they sit in the head or are hoisted there from the footer.
	$deps = array( 'fram-fontawesome' );
	if ( wp_style_is( 'wp-global-styles-placeholder', 'registered' ) ) {
		$deps[] = 'wp-global-styles-placeholder';
	} elseif ( wp_style_is( 'global-styles', 'registered' ) ) {
		$deps[] = 'global-styles';
	}

	wp_enqueue_style( 'fram-style', get_stylesheet_uri(), $deps, fram_version() );

	wp_enqueue_script(
		'fram-theme',
		get_theme_file_uri( 'assets/js/theme.js' ),
		array(),
		fram_version(),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'fram_enqueue_assets', 20 );

/**
 * Always loads jQuery on the front end for plugins' inline scripts that expect it without declaring it
 * (e.g. Risingbear User Consent, which prints from wp_footer at priority 1, before footer scripts),
 * so jQuery has to stay in the head (core default).
 */
function fram_enqueue_jquery() {
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'fram_enqueue_jquery' );

/**
 * Allows SVG uploads for users who may post unfiltered HTML (administrators, editors):
 * an SVG file can carry scripts and is not sanitized.
 *
 * @param array $mimes Allowed mime types, keyed by extension.
 * @return array
 */
function fram_allow_svg_uploads( $mimes ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'fram_allow_svg_uploads' );

/**
 * Stores width and height of SVG attachments (core only measures raster images).
 *
 * Without them the Site Logo block cannot apply its width and wp_get_attachment_image()
 * prints no width/height.
 *
 * @param array $metadata      Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array
 */
function fram_svg_attachment_metadata( $metadata, $attachment_id ) {
	if ( 'image/svg+xml' !== get_post_mime_type( $attachment_id ) ) {
		return $metadata;
	}

	$file = get_attached_file( $attachment_id );
	$svg  = $file && is_readable( $file ) ? simplexml_load_file( $file, 'SimpleXMLElement', LIBXML_NONET ) : false;
	if ( ! $svg ) {
		return $metadata;
	}

	// Pixel width/height attributes, otherwise the viewBox.
	$width  = (float) $svg['width'];
	$height = (float) $svg['height'];
	$box    = preg_split( '/[\s,]+/', trim( (string) $svg['viewBox'] ) );

	if ( ( ! $width || ! $height || ! is_numeric( rtrim( (string) $svg['width'], 'px' ) ) ) && 4 === count( $box ) ) {
		$width  = (float) $box[2];
		$height = (float) $box[3];
	}

	if ( $width > 0 && $height > 0 ) {
		$metadata           = (array) $metadata;
		$metadata['width']  = (int) round( $width );
		$metadata['height'] = (int) round( $height );
		$metadata['file']   = _wp_relative_upload_path( $file );
	}

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'fram_svg_attachment_metadata', 10, 2 );

/**
 * Registers block pattern categories.
 */
function fram_register_pattern_categories() {
	register_block_pattern_category(
		'fram',
		array(
			'label'       => __( 'Fram', 'fram' ),
			'description' => __( 'Seksjoner for verkstedsiden.', 'fram' ),
		)
	);
	register_block_pattern_category(
		'fram-pages',
		array(
			'label'       => __( 'Fram – Sider', 'fram' ),
			'description' => __( 'Ferdige sider.', 'fram' ),
		)
	);
}
add_action( 'init', 'fram_register_pattern_categories' );
/**
 * Adds a width control to the Group block's Dimensions panel.
 *
 * @param array  $args       Block type arguments.
 * @param string $block_type Block name.
 * @return array
 */
function fram_group_width_support( $args, $block_type ) {
	if ( 'core/group' === $block_type ) {
		$args['supports']['dimensions']['width'] = true;
		
	}

	return $args;
}
add_filter( 'register_block_type_args', 'fram_group_width_support', 10, 2 );
/**
 * Renders a block template part (the version saved in the admin, or the theme file).
 *
 * @param string $slug     Template part slug, e.g. 'header'.
 * @param string $tag_name Wrapper element.
 * @return string
 */
function fram_get_template_part( $slug, $tag_name = 'div' ) {
	$attributes = array(
		'slug'    => $slug,
		'theme'   => get_stylesheet(),
		'tagName' => $tag_name,
	);

	return do_blocks( '<!-- wp:template-part ' . wp_json_encode( $attributes ) . ' /-->' );
}

/**
 * Prints a block template part.
 *
 * @param string $slug     Template part slug.
 * @param string $tag_name Wrapper element.
 */
function fram_template_part( $slug, $tag_name = 'div' ) {
	echo fram_get_template_part( $slug, $tag_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup.
}

/**
 * Renders block markup inside a PHP template (styles of block variations are only generated for rendered blocks).
 *
 * @param string $markup Serialized blocks.
 */
function fram_blocks( $markup ) {
	echo do_blocks( $markup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup.
}

/**
 * Shows a placeholder logo until a real one is uploaded (no database writes).
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function fram_site_logo_fallback( $block_content, $block ) {
	log_pretty( $block );
	if ( '' !== trim( $block_content ) || has_custom_logo() ) {
		return $block_content;
	}

	log_pretty( $block );

	$width   = (int) ( $block['attrs']['width'] ?? 330 );
	$classes = trim( 'wp-block-site-logo ' . ( $block['attrs']['className'] ?? '' ) );

	return sprintf(
		'<div class="%1$s"><a href="%2$s" class="custom-logo-link" rel="home"><img src="%3$s" class="custom-logo" alt="%4$s" width="%5$d" style="width:%5$dpx" /></a></div>',
		esc_attr( $classes ),
		esc_url( home_url( '/' ) ),
		esc_url( get_theme_file_uri( 'assets/images/logo-placeholder.svg' ) ),
		esc_attr( get_bloginfo( 'name' ) ),
		$width
	);
}
add_filter( 'render_block_core/site-logo', 'fram_site_logo_fallback', 10, 2 );

/**
 * Marks custom navigation links that point to the current URL as current.
 *
 * Core only does this for links tied to a post ID; the starter menu uses URLs.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function fram_mark_current_nav_link( $block_content, $block ) {
	$url = $block['attrs']['url'] ?? '';
	if ( '' === $url || str_contains( $block_content, 'current-menu-item' ) || str_contains( $url, '#' ) ) {
		return $block_content;
	}

	$link_path    = untrailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	$request_path = untrailingslashit( (string) wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH ) );
	$link_host    = wp_parse_url( $url, PHP_URL_HOST );

	if ( ( $link_host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $link_host ) || $link_path !== $request_path ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag( 'li' ) ) {
		$tags->add_class( 'current-menu-item' );
		if ( $tags->next_tag( 'a' ) ) {
			$tags->set_attribute( 'aria-current', 'page' );
		}
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/navigation-link', 'fram_mark_current_nav_link', 10, 2 );