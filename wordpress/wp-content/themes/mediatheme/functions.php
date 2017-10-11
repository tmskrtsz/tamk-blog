<?php
/**
 * Mediatheme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mediatheme
 */

if ( ! function_exists( 'mediatheme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mediatheme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Mediatheme, use a find and replace
		 * to change 'mediatheme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mediatheme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'mediatheme' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'mediatheme_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'mediatheme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mediatheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mediatheme_content_width', 640 );
}
add_action( 'after_setup_theme', 'mediatheme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mediatheme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mediatheme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'mediatheme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mediatheme_widgets_init' );

function mediatheme_search_widget() {
	register_sidebar( array (
		'name'			=> esc_html__( 'Search', 'mediatheme' ),
		'id'			=> 'search',
		'description'	=> esc_html__( 'The search page.', 'mediatheme' ),
		'before_widget' => '',
		'after_widget'  => '<small>' . esc_html__( 'Keywords, phrases, tags and other terms', 'mediatheme') . '</small>',
	) );
}
add_action ( 'widgets_init', 'mediatheme_search_widget' );
/**
 * Custom scripts
 */
// Index featured image width and height
function wpdocs_theme_setup() {
    add_image_size( 'custom-image-thumb', 368, 222, true );
}
add_action( 'after_setup_theme', 'wpdocs_theme_setup' );

// A function that limits the excerpt's length
function get_limited_excerpt($limit) {
	return wp_trim_words(get_the_excerpt(), $limit, '');
}
add_filter( 'jetpack_development_mode', '__return_true' );

function return_get_icon($icon) {
	return get_template_directory_uri() . '/dist/images/' . $icon . '.svg';
}
add_filter('get_icon','return_get_icon');

add_theme_support( 'post-formats', array( 'video', 'gallery', 'chat', ) );

function get_gradient() {
	$gradients = array(
		'#fbc2eb, #a6c1ee',
		'#fbc2eb, #a6c1ee',
		'#d4fc79, #96e6a1',
		'#a1c4fd, #c2e9fb',
		'#fa709a, #fee140',
	);

	$gradient = $gradients[mt_rand(0, count($gradients) - 1)]; 

	return esc_html(sprintf('background-image:linear-gradient(-35deg, %s);', $gradient));
}
add_filter('the_post', 'get_gradient');
/**
 * Enqueue scripts and styles.
 */
function mediatheme_scripts() {
	wp_enqueue_style( 'mediatheme-style', get_template_directory_uri() . '/dist/main.bundle.css' );

	wp_enqueue_script( 'mediatheme-bundle', get_template_directory_uri() . '/dist/main.bundle.js', array( 'jquery' ), '1', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
		wp_enqueue_script( 'mediatheme-bundle-single', get_template_directory_uri() . '/dist/single.bundle.js', array( 'jquery' ), '1', true );
	}
}
add_action( 'wp_enqueue_scripts', 'mediatheme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

