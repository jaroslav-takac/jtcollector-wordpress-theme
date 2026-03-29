<?php
/**
 * Custom WooCommerce archive template
 * (pre stránky kategórií HOKEJ/FUTBAL/MMA...)
 *
 * @package JTCollector
 */

defined('ABSPATH') || exit;

get_header();

/**
 * Otvorí wrapper pre sklad + cenu.
 */
if (!function_exists('jtcollector_archive_price_stock_wrap_open')) {
	function jtcollector_archive_price_stock_wrap_open() {
		echo '<div class="jt-product-card__price-stock">';
	}
}

/**
 * Zobrazí text "Skladom X ks" pred cenou produktu v archívnom výpise.
 */
if (!function_exists('jtcollector_archive_product_stock_inline')) {
	function jtcollector_archive_product_stock_inline() {
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

/**
 * Zavrie wrapper pre sklad + cenu.
 */
if (!function_exists('jtcollector_archive_price_stock_wrap_close')) {
	function jtcollector_archive_price_stock_wrap_close() {
		echo '</div>';
	}
}

/*
 * Default WooCommerce:
 * woocommerce_template_loop_price = priority 10
 *
 * Chceme:
 * 9  -> otvor wrapper
 * 10 -> sklad
 * 11 -> cena
 * 12 -> zavrieť wrapper
 */
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
add_action('woocommerce_after_shop_loop_item_title', 'jtcollector_archive_price_stock_wrap_open', 9);
add_action('woocommerce_after_shop_loop_item_title', 'jtcollector_archive_product_stock_inline', 10);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 11);
add_action('woocommerce_after_shop_loop_item_title', 'jtcollector_archive_price_stock_wrap_close', 12);
?>

<main class="site-main shop-archive-page">
	<div class="site-container">
		<div class="shop-archive-layout">

			<aside class="shop-sidebar">
				<div class="shop-quick-links entry-card">
					<h3><?php woocommerce_page_title(); ?> – rýchly výber</h3>

					<?php
					if (shortcode_exists('yith_wcan_filters')) {
						echo do_shortcode('[yith_wcan_filters slug="draft-preset-2"]');
					}
					?>
				</div>

				<div class="shop-filter-box entry-card">
					<h3>Filtrovať</h3>

					<?php
					if (shortcode_exists('yith_wcan_filters')) {
						echo do_shortcode('[yith_wcan_filters slug="draft-preset"]');
					}
					?>
				</div>

				<?php
				if (class_exists('WC_Widget_Price_Filter')) {
					the_widget(
						'WC_Widget_Price_Filter',
						[
							'title' => 'Filtrovať podľa ceny',
						],
						[
							'before_widget' => '<div class="shop-price-filter-box entry-card widget woocommerce widget_price_filter">',
							'after_widget'  => '</div>',
							'before_title'  => '<h3>',
							'after_title'   => '</h3>',
						]
					);
				}
				?>
			</aside>

			<div class="shop-archive-content entry-card">
				<?php if (woocommerce_product_loop()) : ?>

					<header class="woocommerce-products-header">
						<?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
							<h1 class="woocommerce-products-header__title page-title">
								<?php woocommerce_page_title(); ?>
							</h1>
						<?php endif; ?>

						<?php do_action('woocommerce_archive_description'); ?>
					</header>

					<div class="shop-archive-toolbar">
						<?php
						woocommerce_result_count();
						woocommerce_catalog_ordering();
						?>
					</div>

					<?php
					woocommerce_product_loop_start();

					if (wc_get_loop_prop('total')) {
						while (have_posts()) {
							the_post();

							do_action('woocommerce_shop_loop');

							wc_get_template_part('content', 'product');
						}
					}

					woocommerce_product_loop_end();

					do_action('woocommerce_after_shop_loop');
					?>

				<?php else : ?>

					<?php do_action('woocommerce_no_products_found'); ?>

				<?php endif; ?>
			</div>

		</div>
	</div>
</main>

<?php
remove_action('woocommerce_after_shop_loop_item_title', 'jtcollector_archive_price_stock_wrap_open', 9);
remove_action('woocommerce_after_shop_loop_item_title', 'jtcollector_archive_product_stock_inline', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 11);
remove_action('woocommerce_after_shop_loop_item_title', 'jtcollector_archive_price_stock_wrap_close', 12);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

get_footer();