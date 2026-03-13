<?php
if (! function_exists('get_term_link')) {
  return;
}

$cat_hokej = get_term_by('slug', 'hokej', 'product_cat');
$cat_futbal = get_term_by('slug', 'futbal', 'product_cat');
$cat_mma = get_term_by('slug', 'mma', 'product_cat');
$cat_samolepky = get_term_by('slug', 'samolepky', 'product_cat');
$cat_bazar = get_term_by('slug', 'bazar', 'product_cat');

$cat_slovaci_cesi = get_term_by('slug', 'sk-cz', 'product_cat');
$cat_nhl = get_term_by('slug', 'nhl', 'product_cat');
$cat_tipsport = get_term_by('slug', 'tipsport-liga', 'product_cat');
$cat_hokej_slovensko = get_term_by('slug', 'hokejove-slovensko', 'product_cat');

function jtcollector_term_link($term) {
  return ($term && ! is_wp_error($term)) ? get_term_link($term) : '#';
}
?>

<section class="shop-home-entry">
  <div class="shop-home-entry__inner">

    <div class="shop-home-entry__top">
    <nav class="shop-home-entry__nav shop-home-entry__nav--minimal" aria-label="Shop kategórie">
        <a class="shop-home-entry__nav-link shop-home-entry__nav-link--minimal is-active" href="<?php echo esc_url(jtcollector_term_link($cat_hokej)); ?>">Hokej</a>
        <a class="shop-home-entry__nav-link shop-home-entry__nav-link--minimal" href="<?php echo esc_url(jtcollector_term_link($cat_futbal)); ?>">Futbal</a>
        <a class="shop-home-entry__nav-link shop-home-entry__nav-link--minimal" href="<?php echo esc_url(jtcollector_term_link($cat_mma)); ?>">MMA</a>
        <a class="shop-home-entry__nav-link shop-home-entry__nav-link--minimal" href="<?php echo esc_url(jtcollector_term_link($cat_samolepky)); ?>">Samolepky</a>
        <a class="shop-home-entry__nav-link shop-home-entry__nav-link--minimal" href="<?php echo esc_url(jtcollector_term_link($cat_bazar)); ?>">Bazár</a>
    </nav>
    </div>

    <div class="shop-home-entry__content">
      <aside class="shop-home-entry__sidebar" aria-label="Rýchla navigácia shopu">

        <div class="shop-home-entry__card">
          <h2 class="shop-home-entry__card-title">Hokej – rýchly výber</h2>
          <ul class="shop-home-entry__list">
            <li><a href="<?php echo esc_url(jtcollector_term_link($cat_slovaci_cesi)); ?>">Slováci a Česi v NHL</a></li>
            <li><a href="<?php echo esc_url(jtcollector_term_link($cat_nhl)); ?>">NHL</a></li>
            <li><a href="<?php echo esc_url(jtcollector_term_link($cat_tipsport)); ?>">Tipsport Liga</a></li>
            <li><a href="<?php echo esc_url(jtcollector_term_link($cat_hokej_slovensko)); ?>">Hokejové Slovensko</a></li>
          </ul>
        </div>

        <div class="shop-home-entry__card">
          <h2 class="shop-home-entry__card-title">Čoskoro pribudne</h2>
          <p class="shop-home-entry__text">
            Filtrovanie podľa tímu, sezóny, setu, výrobcu a ceny.
          </p>
        </div>
      </aside>

      <div class="shop-home-entry__main">
        <header class="shop-home-entry__header">
          <p class="shop-home-entry__subtitle">Z našej ponuky</p>
          <h2 class="shop-home-entry__title">Novinky, akcie a zaujímavé karty</h2>
          <p class="shop-home-entry__intro">
            Vyber si šport a objav nové prírastky, akciové kúsky alebo špeciálne karty pripravené pre zberateľov.
          </p>
        </header>

        <div class="shop-home-entry__tabs">
          <button class="shop-home-entry__tab is-active" type="button">Novinky</button>
          <button class="shop-home-entry__tab" type="button">Vybrali sme pre vás</button>
          <button class="shop-home-entry__tab" type="button">Akcia</button>
        </div>

        <div class="shop-home-entry__placeholder-grid">
          <article class="shop-home-entry__product-placeholder">
            <div class="shop-home-entry__product-badge">Novinka</div>
            <div class="shop-home-entry__product-image"></div>
            <h3>Priestor pre produkt</h3>
            <p>Sem neskôr napojíme WooCommerce produkty.</p>
          </article>

          <article class="shop-home-entry__product-placeholder">
            <div class="shop-home-entry__product-badge">Top</div>
            <div class="shop-home-entry__product-image"></div>
            <h3>Priestor pre produkt</h3>
            <p>Sem neskôr napojíme WooCommerce produkty.</p>
          </article>

          <article class="shop-home-entry__product-placeholder">
            <div class="shop-home-entry__product-badge">Akcia</div>
            <div class="shop-home-entry__product-image"></div>
            <h3>Priestor pre produkt</h3>
            <p>Sem neskôr napojíme WooCommerce produkty.</p>
          </article>
        </div>
      </div>
    </div>
  </div>
</section>