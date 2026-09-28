<?php
/**
 * ASTRA-UAW theme setup.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

define( 'ASTRA_UAW_DIR', get_template_directory() );
define( 'ASTRA_UAW_URI', get_template_directory_uri() );

require_once ASTRA_UAW_DIR . '/inc/post-types.php';
require_once ASTRA_UAW_DIR . '/inc/meta-boxes.php';
require_once ASTRA_UAW_DIR . '/inc/customizer.php';
require_once ASTRA_UAW_DIR . '/inc/nav-walker.php';
require_once ASTRA_UAW_DIR . '/inc/template-tags.php';
require_once ASTRA_UAW_DIR . '/inc/starter-content.php';

/**
 * Theme supports, menus and image sizes.
 */
function astra_uaw_setup() {
	load_theme_textdomain( 'astra-uaw', ASTRA_UAW_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 298,
			'width'       => 420,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	// Testimonial headshots render at 96px, so 192px keeps them sharp on retina screens.
	add_image_size( 'astra-avatar', 192, 192, true );

	register_nav_menus(
		array(
			'primary'         => __( 'Main menu (header tabs)', 'astra-uaw' ),
			'footer_campaign' => __( 'Footer: Campaign column', 'astra-uaw' ),
			'footer_involved' => __( 'Footer: Get involved column', 'astra-uaw' ),
		)
	);

	// Pages get an excerpt box too (used as the meta description).
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'after_setup_theme', 'astra_uaw_setup' );

/**
 * Styles and scripts.
 */
function astra_uaw_enqueue() {
	wp_enqueue_style(
		'astra-uaw-fonts',
		'https://fonts.googleapis.com/css2?family=Anton&family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'astra-uaw-style',
		get_stylesheet_uri(),
		array( 'astra-uaw-fonts' ),
		(string) filemtime( ASTRA_UAW_DIR . '/style.css' )
	);
	wp_enqueue_script(
		'astra-uaw-script',
		ASTRA_UAW_URI . '/js/script.js',
		array(),
		(string) filemtime( ASTRA_UAW_DIR . '/js/script.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'astra_uaw_enqueue' );

/**
 * Preconnect to Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array
 */
function astra_uaw_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'astra_uaw_resource_hints', 10, 2 );

/**
 * 301-redirect the old static-site .html URLs to their WordPress pages.
 */
function astra_uaw_legacy_redirects() {
	if ( is_admin() || empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$request = wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	if ( ! is_string( $request ) || ! str_ends_with( strtolower( $request ), '.html' ) ) {
		return;
	}

	// Strip the WordPress install path so this works in subdirectory installs too.
	$home_path = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	$relative  = strtolower( ltrim( substr( $request, strlen( rtrim( $home_path, '/' ) ) ), '/' ) );

	$map = array(
		'index.html'        => '/',
		'who-we-are.html'   => '/who-we-are/',
		'international.html' => '/international/',
		'faq.html'          => '/faq/',
		'sign-card.html'    => '/sign-card/',
		'get-involved.html' => '/get-involved/',
		'meet-lincoln.html' => '/meet-lincoln/',
		'about.html'        => '/#about',
		'committee.html'    => '/who-we-are/#committee',
	);

	if ( isset( $map[ $relative ] ) ) {
		wp_safe_redirect( home_url( $map[ $relative ] ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'astra_uaw_legacy_redirects', 1 );

/**
 * Favicon fallback when no Site Icon is set in the Customizer.
 */
function astra_uaw_favicon() {
	if ( ! has_site_icon() ) {
		printf( '<link rel="icon" href="%s" />' . "\n", esc_url( ASTRA_UAW_URI . '/assets/favicon.png' ) );
	}
}
add_action( 'wp_head', 'astra_uaw_favicon' );

/**
 * Meta description from the page excerpt, falling back to the site tagline.
 */
function astra_uaw_meta_description() {
	$description = '';
	if ( is_singular() && has_excerpt() ) {
		$description = get_the_excerpt();
	}
	if ( '' === $description ) {
		$description = get_bloginfo( 'description' );
	}
	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( wp_strip_all_tags( $description ) ) );
	}
}
add_action( 'wp_head', 'astra_uaw_meta_description', 1 );

/**
 * The inline "js" class must be on <html> before paint so scroll reveals do
 * not flash, and so visitors without JavaScript still see all content.
 */
function astra_uaw_js_class() {
	echo "<script>document.documentElement.classList.add('js');</script>\n";
}
add_action( 'wp_head', 'astra_uaw_js_class', 0 );

/**
 * "FAQ | ASTRA" style titles, like the original site.
 *
 * @return string
 */
function astra_uaw_title_separator() {
	return '|';
}
add_filter( 'document_title_separator', 'astra_uaw_title_separator' );

/*
 * Show text exactly as typed. WordPress normally swaps straight quotes and
 * apostrophes for curly ones, which changes how the original copy looks.
 */
add_filter( 'run_wptexturize', '__return_false' );

/**
 * Keep the browser's own emoji. WordPress otherwise swaps emoji (the issue
 * icons, the lock, Lincoln's heart) for images that look different.
 */
function astra_uaw_disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'astra_uaw_disable_emoji' );
