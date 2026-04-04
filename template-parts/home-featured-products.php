<?php
/**
 * Template part for displaying featured products on the homepage.
 *
 * @package JTCollector
 */

$featured_args = array(
  'post_type'           => 'product',
  'post_status'         => 'publish',
  'posts_per_page'      => 24,
  'orderby'             => 'rand',
  'ignore_sticky_posts' => 1,
  'tax_query'           => array(
    array(
      'taxonomy' => 'product_visibility',
      'field'    => 'name',
      'terms'    => array('featured'),
      'operator' => 'IN',
    ),
  ),
);

$featured_query = new WP_Query($featured_args);
?>

<section class="shop-home-entry">
  <div class="shop-home-entry__inner">
    <div class="shop-home-entry__main">

      <header class="shop-home-entry__header">
        <div class="shop-home-entry__heading">
          <p class="shop-home-entry__subtitle"></p>
          <h2 class="shop-home-entry__title">TOP karty z ponuky</h2>
        </div>

        <a class="shop-home-entry__button" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
          Pozrieť ďalšie TOP karty
        </a>
      </header>

      <?php if ($featured_query->have_posts()) : ?>
        <div class="shop-home-entry__products">
          <ul class="products columns-6">
            <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
              <?php
              global $product;

              if (!$product || !is_a($product, 'WC_Product')) {
                continue;
              }

              $stock_quantity = $product->get_stock_quantity();
              $show_stock = $product->managing_stock() && $stock_quantity !== null && $stock_quantity > 0;
              ?>
              <li <?php wc_product_class('', $product); ?>>

                <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                  <div class="jt-home-product__image-wrap">
                    <div class="jt-home-product__wishlist" aria-label="Pridať do obľúbených">
                      <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                    </div>

                    <?php
                    if (has_post_thumbnail()) {
                      echo woocommerce_get_product_thumbnail();
                    }
                    ?>
                  </div>

                  <h3 class="woocommerce-loop-product__title"><?php the_title(); ?></h3>
                </a>

                <div class="jt-home-product__price-row">
                  <?php if ($show_stock) : ?>
                    <span class="jt-stock-inline">Skladom <?php echo esc_html($stock_quantity); ?> ks</span>
                  <?php endif; ?>

                  <?php if ($price_html = $product->get_price_html()) : ?>
                    <span class="price"><?php echo wp_kses_post($price_html); ?></span>
                  <?php endif; ?>
                </div>

                <?php woocommerce_template_loop_add_to_cart(); ?>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>
      <?php else : ?>
        <div class="shop-home-entry__empty">
          <p>Zatiaľ nemáš označené žiadne HITY z ponuky.</p>
        </div>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>

    </div>
  </div>
</section>