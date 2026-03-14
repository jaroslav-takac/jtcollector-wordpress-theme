<?php
/**
 * The header for our theme
 *
 * @package JTCollector
 */

$shop_cat_hokej      = get_term_by('slug', 'hokej', 'product_cat');
$shop_cat_futbal     = get_term_by('slug', 'futbal', 'product_cat');
$shop_cat_mma        = get_term_by('slug', 'mma', 'product_cat');
$shop_cat_samolepky  = get_term_by('slug', 'samolepky', 'product_cat');
$shop_cat_bazar      = get_term_by('slug', 'bazar', 'product_cat');

function jtcollector_header_term_link($term) {
	return ($term && ! is_wp_error($term)) ? get_term_link($term) : '#';
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="site-header__top">
    <div class="site-header__inner">

      <div class="site-header__brand">
        <?php if ( has_custom_logo() ) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          <a class="site-header__logo-text" href="<?php echo esc_url( home_url('/') ); ?>">
            JTCollector
          </a>
        <?php endif; ?>
      </div>

      <div class="site-header__center">
        <nav class="site-header__tabs" aria-label="<?php esc_attr_e('Header tabs', 'jtcollector'); ?>">
          <button class="site-header__tab is-active" type="button" data-panel="about">O mne</button>
          <button class="site-header__tab" type="button" data-panel="info">Info</button>
          <button class="site-header__tab" type="button" data-panel="blog">Blog</button>
          <button class="site-header__tab" type="button" data-panel="search">Vyhľadávanie</button>
        </nav>

        <div class="site-header__panel">

          <div class="site-header__panel-content is-active" data-panel-content="about">
            <?php
            wp_nav_menu([
              'theme_location' => 'header_about_menu',
              'container'      => false,
              'menu_class'     => 'header-panel-menu',
              'fallback_cb'    => false,
            ]);
            ?>
          </div>

          <div class="site-header__panel-content" data-panel-content="info">
            <?php
            wp_nav_menu([
              'theme_location' => 'header_info_menu',
              'container'      => false,
              'menu_class'     => 'header-panel-menu',
              'fallback_cb'    => false,
            ]);
            ?>
          </div>

          <div class="site-header__panel-content" data-panel-content="blog">
            <?php
            wp_nav_menu([
              'theme_location' => 'header_blog_menu',
              'container'      => false,
              'menu_class'     => 'header-panel-menu',
              'fallback_cb'    => false,
            ]);
            ?>
          </div>

          <div class="site-header__panel-content" data-panel-content="search">
            <form role="search" method="get" class="header-search-form" action="<?php echo esc_url(home_url('/')); ?>">
              <label class="screen-reader-text" for="header-search-field">
                <?php esc_html_e('Vyhľadávanie', 'jtcollector'); ?>
              </label>
              <input
                id="header-search-field"
                class="header-search-form__input"
                type="search"
                name="s"
                value="<?php echo get_search_query(); ?>"
                placeholder="Vyhľadaj meno hráča, klubu, kolekcie alebo setu..."
              >
              <button class="header-search-form__button" type="submit">Hľadať</button>
            </form>
          </div>

        </div>
      </div>

      <div class="site-header__shop">
        <div class="site-header__shop-top">
          <button class="site-header__utility" type="button">EUR</button>
          <button class="site-header__utility" type="button">SK</button>
        </div>

        <div class="site-header__shop-bottom">
          <a href="#" class="site-header__account">Prihlásenie</a>
          <a href="#" class="site-header__cart">
            <span class="site-header__cart-icon">🛒</span>
            <span class="site-header__cart-text">0 ks / 0 €</span>
          </a>
        </div>
      </div>

    </div>
  </div>

  <div class="site-header__shop-nav-wrap">
    <div class="site-header__shop-nav-inner">
      <nav class="site-header__shop-nav" aria-label="<?php esc_attr_e('Shop menu', 'jtcollector'); ?>">
        <a class="site-header__shop-link is-active" href="<?php echo esc_url(jtcollector_header_term_link($shop_cat_hokej)); ?>">Hokej</a>
        <a class="site-header__shop-link" href="<?php echo esc_url(jtcollector_header_term_link($shop_cat_futbal)); ?>">Futbal</a>
        <a class="site-header__shop-link" href="<?php echo esc_url(jtcollector_header_term_link($shop_cat_mma)); ?>">MMA</a>
        <a class="site-header__shop-link" href="<?php echo esc_url(jtcollector_header_term_link($shop_cat_samolepky)); ?>">Samolepky</a>
        <a class="site-header__shop-link" href="<?php echo esc_url(jtcollector_header_term_link($shop_cat_bazar)); ?>">Bazár</a>
      </nav>
    </div>
  </div>
</header>

<main class="site-main">