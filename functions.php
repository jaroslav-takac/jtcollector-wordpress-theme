<?php
/**
 * Theme functions and definitions
 *
 * @package JTCollector
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme setup
 */
function jtcollector_theme_setup(): void
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('custom-logo');

	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	]);

	// WooCommerce support
	add_theme_support('woocommerce');

	// MENU LOCATIONS
	register_nav_menus([
		'header_about_menu' => __('Header About Menu', 'jtcollector'),
		'header_info_menu'  => __('Header Info Menu', 'jtcollector'),
		'header_blog_menu'  => __('Header Blog Menu', 'jtcollector'),
		'footer_menu'       => __('Footer Menu', 'jtcollector'),
	]);
}
add_action('after_setup_theme', 'jtcollector_theme_setup');


/**
 * Enqueue theme assets
 */
function jtcollector_enqueue_assets(): void
{
	$theme_version = wp_get_theme()->get('Version');

	// MAIN STYLE
	wp_enqueue_style(
		'jtcollector-main-style',
		get_template_directory_uri() . '/assets/css/main.css',
		[],
		$theme_version
	);

	// HEADER STYLE
	wp_enqueue_style(
		'jtcollector-header-style',
		get_template_directory_uri() . '/assets/css/header.css',
		['jtcollector-main-style'],
		filemtime(get_template_directory() . '/assets/css/header.css')
	);

	// FOOTER STYLE
	wp_enqueue_style(
		'jt-footer-style',
		get_template_directory_uri() . '/assets/css/footer.css',
		[],
		filemtime(get_template_directory() . '/assets/css/footer.css')
	);

	// HOME FEATURED PRODUCTS STYLE
	wp_enqueue_style(
		'jtcollector-home-featured-products',
		get_template_directory_uri() . '/assets/css/home-featured-products.css',
		['jtcollector-main-style'],
		filemtime(get_template_directory() . '/assets/css/home-featured-products.css')
	);

	// WOOCOMMERCE STYLE
	wp_enqueue_style(
		'jtcollector-woocommerce',
		get_template_directory_uri() . '/assets/css/woocommerce.css',
		['jtcollector-main-style'],
		filemtime(get_template_directory() . '/assets/css/woocommerce.css')
	);

	// PAGE CONTENT STYLE
	wp_enqueue_style(
		'jtcollector-page-content',
		get_template_directory_uri() . '/assets/css/page-content.css',
		['jtcollector-main-style'],
		filemtime(get_template_directory() . '/assets/css/page-content.css')
	);

	// HEADER SCRIPT (prepínanie panelov)
	wp_enqueue_script(
		'jtcollector-header-script',
		get_template_directory_uri() . '/assets/js/header.js',
		[],
		filemtime(get_template_directory() . '/assets/js/header.js'),
		true
	);
}
add_action('wp_enqueue_scripts', 'jtcollector_enqueue_assets');

/**
 * AJAX refresh header cart contents
 */
function jtcollector_header_cart_fragment($fragments) {
	ob_start();
	?>
	<span class="site-header__cart-text js-header-cart-text">
		<?php echo WC()->cart->get_cart_contents_count(); ?> ks /
		<?php echo wp_kses_post(WC()->cart->get_cart_total()); ?>
	</span>
	<?php
	$fragments['.js-header-cart-text'] = ob_get_clean();

	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'jtcollector_header_cart_fragment');