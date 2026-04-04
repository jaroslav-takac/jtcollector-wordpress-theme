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

	// Menu locations
	register_nav_menus([
		'header_about_menu' => __('Header About Menu', 'jtcollector'),
		'header_info_menu'  => __('Header Info Menu', 'jtcollector'),
		'header_blog_menu'  => __('Header Blog Menu', 'jtcollector'),
		'footer_menu'       => __('Footer Menu', 'jtcollector'),
	]);
}
add_action('after_setup_theme', 'jtcollector_theme_setup');

/**
 * Helper: asset version by filemtime
 */
function jtcollector_asset_version(string $relative_path): string
{
	$absolute_path = get_template_directory() . $relative_path;

	if (file_exists($absolute_path)) {
		return (string) filemtime($absolute_path);
	}

	return wp_get_theme()->get('Version');
}

/**
 * Helper: archive-like Woo pages where filters are used
 */
function jtcollector_is_filter_archive(): bool
{
	return is_shop() || is_product_taxonomy();
}

/**
 * Enqueue theme assets
 */
function jtcollector_enqueue_assets(): void
{
	// MAIN STYLE
	wp_enqueue_style(
		'jtcollector-main-style',
		get_template_directory_uri() . '/assets/css/main.css',
		[],
		jtcollector_asset_version('/assets/css/main.css')
	);

	// HEADER STYLE
	wp_enqueue_style(
		'jtcollector-header-style',
		get_template_directory_uri() . '/assets/css/header.css',
		['jtcollector-main-style'],
		jtcollector_asset_version('/assets/css/header.css')
	);

	// FOOTER STYLE
	wp_enqueue_style(
		'jtcollector-footer-style',
		get_template_directory_uri() . '/assets/css/footer.css',
		['jtcollector-main-style'],
		jtcollector_asset_version('/assets/css/footer.css')
	);

	// PAGE CONTENT STYLE
	wp_enqueue_style(
		'jtcollector-page-content',
		get_template_directory_uri() . '/assets/css/page-content.css',
		['jtcollector-main-style'],
		jtcollector_asset_version('/assets/css/page-content.css')
	);

	// HOME FEATURED PRODUCTS STYLE
	wp_enqueue_style(
		'jtcollector-home-featured-products',
		get_template_directory_uri() . '/assets/css/home-featured-products.css',
		['jtcollector-main-style'],
		jtcollector_asset_version('/assets/css/home-featured-products.css')
	);

	// HOME BLOG STYLE
	wp_enqueue_style(
		'jtcollector-home-blog',
		get_template_directory_uri() . '/assets/css/home-blog.css',
		['jtcollector-main-style'],
		jtcollector_asset_version('/assets/css/home-blog.css')
	);

	// WOOCOMMERCE ARCHIVE STYLE
	wp_enqueue_style(
		'jtcollector-woocommerce',
		get_template_directory_uri() . '/assets/css/woocommerce.css',
		['jtcollector-main-style'],
		jtcollector_asset_version('/assets/css/woocommerce.css')
	);

	// SINGLE PRODUCT STYLE – len na detaile produktu
	if (is_product()) {
		wp_enqueue_style(
			'jtcollector-single-product',
			get_template_directory_uri() . '/assets/css/single-product.css',
			['jtcollector-main-style', 'jtcollector-woocommerce'],
			jtcollector_asset_version('/assets/css/single-product.css')
		);
	}

	// HEADER SCRIPT
	wp_enqueue_script(
		'jtcollector-header-script',
		get_template_directory_uri() . '/assets/js/header.js',
		[],
		jtcollector_asset_version('/assets/js/header.js'),
		true
	);

	// FILTER SCRIPTS – shop + všetky Woo taxonómie
	if (jtcollector_is_filter_archive()) {
		wp_enqueue_script(
			'jt-filter-accordion',
			get_template_directory_uri() . '/assets/js/filter-accordion.js',
			[],
			jtcollector_asset_version('/assets/js/filter-accordion.js'),
			true
		);

		wp_enqueue_script(
			'jt-filter-force-reload',
			get_template_directory_uri() . '/assets/js/filter-force-reload.js',
			[],
			jtcollector_asset_version('/assets/js/filter-force-reload.js'),
			true
		);
	}
}
add_action('wp_enqueue_scripts', 'jtcollector_enqueue_assets');

/**
 * AJAX refresh header cart contents
 */
function jtcollector_header_cart_fragment($fragments)
{
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

/**
 * SINGLE PRODUCT – cleanup
 */
function jtcollector_single_product_cleanup(): void
{
	// Tabs nechceme
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

	// Meta si zobrazujeme manuálne
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

	// Default related products vypneme, zobrazujeme ich manuálne v šablóne
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}
add_action('wp', 'jtcollector_single_product_cleanup');

/**
 * SINGLE PRODUCT – schovať default stock text pri add to cart,
 * aby sa nezobrazoval dvakrát.
 */
function jtcollector_hide_single_product_stock_html($html, $product)
{
	if (is_product()) {
		return '';
	}

	return $html;
}
add_filter('woocommerce_get_stock_html', 'jtcollector_hide_single_product_stock_html', 10, 2);

/**
 * RELATED PRODUCTS NA SINGLE – sklad + cena v jednom riadku
 */
if (!function_exists('jtcollector_single_related_price_stock_wrap_open')) {
	function jtcollector_single_related_price_stock_wrap_open()
	{
		if (!is_product()) {
			return;
		}

		echo '<div class="jt-product-card__price-stock">';
	}
}

if (!function_exists('jtcollector_single_related_product_stock_inline')) {
	function jtcollector_single_related_product_stock_inline()
	{
		if (!is_product()) {
			return;
		}

		global $product;

		if (!$product || !is_a($product, 'WC_Product')) {
			return;
		}

		$stock_quantity = $product->get_stock_quantity();

		if ($product->managing_stock() && $stock_quantity !== null && $stock_quantity > 0) {
			echo '<span class="jt-stock-inline">Skladom ' . esc_html($stock_quantity) . ' ks</span>';
		}
	}
}

if (!function_exists('jtcollector_single_related_price_stock_wrap_close')) {
	function jtcollector_single_related_price_stock_wrap_close()
	{
		if (!is_product()) {
			return;
		}

		echo '</div>';
	}
}

/**
 * SINGLE PRODUCT – prepnúť related products price output
 */
function jtcollector_single_related_products_hooks(): void
{
	if (!is_product()) {
		return;
	}

	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

	add_action('woocommerce_after_shop_loop_item_title', 'jtcollector_single_related_price_stock_wrap_open', 9);
	add_action('woocommerce_after_shop_loop_item_title', 'jtcollector_single_related_product_stock_inline', 10);
	add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 11);
	add_action('woocommerce_after_shop_loop_item_title', 'jtcollector_single_related_price_stock_wrap_close', 12);
}

/**
 * Vráti URL wishlistu z YITH Wishlist pluginu.
 */
if (!function_exists('jtcollector_get_wishlist_url')) {
	function jtcollector_get_wishlist_url() {
		if (function_exists('YITH_WCWL')) {
			$wishlist = YITH_WCWL();

			if ($wishlist && method_exists($wishlist, 'get_wishlist_url')) {
				return $wishlist->get_wishlist_url();
			}
		}

		return home_url('/');
	}
}

add_action('wp', 'jtcollector_single_related_products_hooks');