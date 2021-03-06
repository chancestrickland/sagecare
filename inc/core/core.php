<?php
/**
 * Core setup, site hooks and filters.
 *
 * @package sagecare
 */

namespace Chancelight\Sagecare\Core;

/**
 * Set up theme defaults and register supported WordPress features.
 */
function setup() {
	$n = function( $function ) {
		$function = __NAMESPACE__ . "\\$function";
		if ( function_exists( $function ) ) {
			return $function;
		}
	};

	add_action( 'after_setup_theme',         $n( 'i18n' ) );
	add_action( 'after_setup_theme',         $n( 'theme_setup' ) );
	add_action( 'wp_enqueue_scripts',        $n( 'scripts' ) );
	add_action( 'wp_enqueue_scripts',        $n( 'styles' ) );
	add_action( 'widgets_init',              $n( 'widgets' ) );
	//add_filter( 'acf/fields/google_map/api', $n( 'acf_map_api' ) );
}

/**
 * Makes Theme available for translation.
 *
 * Translations can be added to the /languages directory.
 * If you're building a theme based on "sagecare", change the
 * filename of '/languages/sagecare.pot' to the name of your project.
 */
function i18n() {
	load_theme_textdomain( 'sagecare', SAGECARE_PATH . '/languages' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function theme_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	] );
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Additional theme support for woocommerce 3.0.+
	// Uncomment as needed.
	//add_theme_support( 'wc-product-gallery-zoom' );
	//add_theme_support( 'wc-product-gallery-lightbox' );
	//add_theme_support( 'wc-product-gallery-slider' );

	// Add featured image sizes.
	// Sizes are optimized and cropped for landscape aspect ratio and
	// optimized for HiDPI displays on 'small' and 'medium' screen sizes.
	add_image_size( 'featured-small', 640, 9999 );
	add_image_size( 'featured-medium', 1280, 9999 );
	add_image_size( 'featured-large', 1440, 9999 );
	add_image_size( 'featured-xlarge', 1920, 9999 );

	// Load editor stylesheet.
	add_editor_style( SAGECARE_TEMPLATE_URL . '/dist/css/editor.min.css' );

	// Register nav menus.
	register_nav_menus( [
		'main-navigation'      => __( 'Main Navigation', 'sagecare' ),
		'secondary-navigation' => __( 'Secondary Navigation', 'sagecare' ),
		'footer-navigation'    => __( 'Footer Navigation', 'sagecare' ),
		'social'               => __( 'Social Navigation', 'sagecare' ),
	] );
}

/**
 * Enqueue scripts for front-end.
 */
function scripts() {

	// Deregister the jquery version bundled with WordPress.
	wp_deregister_script( 'jquery' );

	// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
	wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', [], '3.2.1', false );

	// Deregister the jquery-migrate version bundled with WordPress.
	wp_deregister_script( 'jquery-migrate' );

	// CDN hosted jQuery migrate for compatibility with jQuery 3.x.
	wp_register_script( 'jquery-migrate', '//code.jquery.com/jquery-migrate-3.0.1.min.js', [ 'jquery' ], '3.0.1', false );
	wp_enqueue_script( 'jquery-migrate' );

	// Frontend JS.
	wp_register_script( 'frontend', SAGECARE_TEMPLATE_URL . '/dist/js/frontend.min.js', [], SAGECARE_VERSION, true );
	wp_localize_script( 'frontend', 'csAjax', [
		'ajaxUrl' => esc_url( admin_url( 'admin-ajax.php' ) ),
	] );
	wp_enqueue_script( 'frontend' );

	// Add the comment-reply library on pages where it is necessary.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

/**
 * Enqueue styles for front-end.
 */
function styles() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Work+Sans:300,400,600,70', [], SAGECARE_VERSION );
	wp_enqueue_style( 'frontend', SAGECARE_TEMPLATE_URL . '/dist/css/frontend.min.css', [], filemtime( SAGECARE_PATH . 'dist/css/frontend.min.css' ) );
}

/**
 * Register widget areas.
 */
function widgets() {

	register_sidebar( [
		'name'          => 'Main sidebar',
		'id'            => 'main-sidebar',
		'before_widget' => '<div class="sidebar__wrapper">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="sidebar__heading">',
		'after_title'   => '</h3>',
	] );
}

/**
 * Return API key for use in ACF filter.
 *
 * @param  string $api Google Maps API key.
 * @return array       API array with key added.
 */
function acf_map_api( $api ) {
	$api['key'] = 'API_KEY_GOES_HERE';

	return $api;
}

// Add ACF options page.
if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page( [
		'page_title' => __( 'Theme Settings', 'sagecare' ),
		'menu_title' => __( 'Theme Settings', 'sagecare' ),
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect'   => false,
	] );
}
