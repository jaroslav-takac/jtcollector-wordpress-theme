<?php
/**
 * Template part for displaying featured products on the homepage.
 * (pre zobrazenie vybraných produktov na homepage)
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
          <p class="shop-home-entry__subtitle">  </p>  <!-- Prázdný element pre TITULOK na ďalšie použitie -->
          <h2 class="shop-home-entry__title">HITY z našej ponuky</h2>
        </div>

        <a class="shop-home-entry__button" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
          Pozrieť ďalšie HITY
        </a>
      </header>

      <?php if ($featured_query->have_posts()) : ?>
        <div class="shop-home-entry__products">
          <ul class="products columns-6">
            <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
              <?php wc_get_template_part('content', 'product'); ?>
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