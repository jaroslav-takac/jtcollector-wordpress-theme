<?php
defined('ABSPATH') || exit;

get_header();
?>

<main class="site-main single-product-page">
	<div class="site-container">

		<?php while (have_posts()) : the_post(); ?>
			<?php global $product; ?>

			<div id="product-<?php the_ID(); ?>" <?php wc_product_class('jt-single-product', $product); ?>>

				<div class="jt-single-product__card">
					<div class="jt-single-product__layout">

						<!-- ĽAVÝ STĹPEC: GALÉRIA -->
						<div class="jt-single-product__gallery-col">
							<div class="jt-single-product__gallery-frame">

								<div class="jt-single-product__wishlist-float" aria-label="Pridať do obľúbených">
									<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
								</div>

								<?php do_action('woocommerce_before_single_product_summary'); ?>
							</div>
						</div>

						<!-- PRAVÝ STĹPEC: INFO -->
						<div class="jt-single-product__summary-col">

							<h1 class="jt-single-product__title"><?php the_title(); ?></h1>

							<?php
							$attributes = $product ? $product->get_attributes() : [];
							$categories = wc_get_product_category_list($product->get_id(), ', ');
							?>

							<div class="jt-single-product__attributes-block">
								<div class="jt-single-product__attributes-list">

									<?php if (!empty($categories)) : ?>
										<div class="jt-single-product__attribute-row jt-single-product__attribute-row--categories">
											<div class="jt-single-product__attribute-label">Kategórie</div>
											<div class="jt-single-product__attribute-value">
												<?php echo wp_kses_post($categories); ?>
											</div>
										</div>
									<?php endif; ?>

									<?php if (!empty($attributes)) : ?>
										<?php foreach ($attributes as $attribute_key => $attribute) : ?>
											<?php
											if (!$attribute) {
												continue;
											}

											$label = wc_attribute_label($attribute->get_name());

											if ($attribute->is_taxonomy()) {
												$terms  = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'all']);
												$values = [];

												if (!empty($terms) && !is_wp_error($terms)) {
													// základná cieľová URL = shop
													$base_url = get_permalink(wc_get_page_id('shop'));

													// ak má produkt kategóriu, preferujeme odkaz na prvú kategóriu
													$product_categories = wc_get_product_terms($product->get_id(), 'product_cat', ['fields' => 'all']);

													if (!empty($product_categories) && !is_wp_error($product_categories)) {
														$primary_category = $product_categories[0];
														$category_link    = get_term_link($primary_category);

														if (!is_wp_error($category_link)) {
															$base_url = $category_link;
														}
													}

													foreach ($terms as $term) {
														$taxonomy_name = $attribute->get_name(); // napr. pa_tim
														$filter_name   = str_replace('pa_', 'filter_', $taxonomy_name); // pa_tim -> filter_tim

														$link = add_query_arg(
															[
																$filter_name => $term->slug,
															],
															$base_url
														);

														$values[] = '<a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a>';
													}
												}

												$value_output = implode(', ', $values);
											} else {
												$options      = $attribute->get_options();
												$values       = array_map('esc_html', $options);
												$value_output = implode(', ', $values);
											}

											if (empty($value_output)) {
												continue;
											}
											?>
											<div class="jt-single-product__attribute-row">
												<div class="jt-single-product__attribute-label"><?php echo esc_html($label); ?></div>
												<div class="jt-single-product__attribute-value"><?php echo wp_kses_post($value_output); ?></div>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>

								</div>
							</div>

							<div class="jt-single-product__price">
								<?php echo $product->get_price_html(); ?>
							</div>

							<div class="jt-single-product__stock">
								<?php
								if ($product->managing_stock()) {
									$qty = $product->get_stock_quantity();

									if ($qty !== null && $qty > 0) {
										echo '<span class="jt-stock-inline">Skladom ' . esc_html($qty) . ' ks</span>';
									}
								} elseif ($product->is_in_stock()) {
									echo '<span class="jt-stock-inline">Skladom</span>';
								}
								?>
							</div>

							<div class="jt-single-product__cart">
								<?php woocommerce_template_single_add_to_cart(); ?>
							</div>

							<?php
							$content = trim(get_the_content());
							if (!empty($content)) :
							?>
								<div class="jt-single-product__description">
									<h2 class="jt-single-product__description-title">Popis</h2>
									<div class="jt-single-product__description-content">
										<?php the_content(); ?>
									</div>
								</div>
							<?php endif; ?>

						</div>
					</div>

					<div class="jt-single-product__related">
						<div class="jt-single-product__related-header">
							<h2 class="jt-single-product__related-title">Súvisiace produkty</h2>
						</div>

						<?php
						woocommerce_related_products([
							'posts_per_page' => 6,
							'columns'        => 6,
						]);
						?>
					</div>
				</div>

			</div>
		<?php endwhile; ?>

	</div>
</main>

<?php get_footer(); ?>