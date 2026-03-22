<?php
/**
 * Custom WooCommerce archive template
 * (pre stránky kategórií HOKEJ/FUTBAL/MMA...)
 *
 * @package JTCollector
 */

defined('ABSPATH') || exit;

get_header();
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

					if (class_exists('WC_Widget_Price_Filter')) {
						the_widget('WC_Widget_Price_Filter');
					}
					?>
				</div>
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
get_footer();