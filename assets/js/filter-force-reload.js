document.addEventListener('DOMContentLoaded', function () {
  const filterAreas = document.querySelectorAll('.shop-filter-box, .shop-quick-links');
  const target = document.querySelector('.shop-archive-layout');

  let redirectTimer = null;
  const initialUrl = window.location.href;

  function goToCurrentUrl() {
    const nextUrl = window.location.href;

    sessionStorage.setItem('jt-scroll-after-filter', '1');

    if (nextUrl !== initialUrl) {
      window.location.assign(nextUrl);
      return;
    }

    setTimeout(function () {
      const delayedUrl = window.location.href;
      window.location.assign(delayedUrl);
    }, 500);
  }

  function isArchiveLikePage() {
    const body = document.body;

    return (
      body.classList.contains('post-type-archive-product') ||
      body.classList.contains('tax-product_cat') ||
      body.classList.contains('tax-product_tag') ||
      body.classList.contains('tax-pa_tim') ||
      body.classList.contains('tax-pa_sezona') ||
      body.classList.contains('tax-pa_kolekcia') ||
      body.classList.contains('tax-pa_subset') ||
      body.classList.contains('tax-pa_vyrobca') ||
      body.classList.contains('tax-pa_gradovana') ||
      window.location.pathname.includes('/product-category/') ||
      window.location.pathname.includes('/product-tag/') ||
      window.location.pathname.includes('/attribute/') ||
      window.location.search.includes('filter_') ||
      target !== null
    );
  }

  /**
   * Kliky vo filtroch / quick links
   */
  filterAreas.forEach(function (area) {
    area.addEventListener('change', function (event) {
      const input = event.target.closest('input[type="checkbox"], input[type="radio"], select');

      if (!input) return;

      clearTimeout(redirectTimer);
      redirectTimer = setTimeout(goToCurrentUrl, 700);
    });

    area.addEventListener('click', function (event) {
      const link = event.target.closest('a');

      if (!link) return;

      clearTimeout(redirectTimer);
      redirectTimer = setTimeout(goToCurrentUrl, 700);
    });
  });

  /**
   * Kliky zo single produktu na kategórie / atribúty
   */
  document.addEventListener('click', function (event) {
    const taxonomyLink = event.target.closest(
      '.jt-single-product__attribute-value a, .jt-single-product__attribute-row--categories a'
    );

    if (!taxonomyLink) return;

    sessionStorage.setItem('jt-scroll-after-filter', '1');
    sessionStorage.setItem('jt-force-archive-reload', '1');
    sessionStorage.removeItem('jt-force-archive-reload-done');
  });

  /**
   * Po príchode na archive/tax stránku spraviť ešte jeden refresh
   */
  const shouldForceReload = sessionStorage.getItem('jt-force-archive-reload') === '1';
  const reloadDone = sessionStorage.getItem('jt-force-archive-reload-done') === '1';

  if (shouldForceReload && isArchiveLikePage() && !reloadDone) {
    sessionStorage.setItem('jt-force-archive-reload-done', '1');

    setTimeout(function () {
      window.location.reload();
    }, 60);

    return;
  }

  if (reloadDone && isArchiveLikePage()) {
    sessionStorage.removeItem('jt-force-archive-reload');
    sessionStorage.removeItem('jt-force-archive-reload-done');
  }

  /**
   * Scroll po refreshe / filtrovaní
   */
  const shouldScroll = sessionStorage.getItem('jt-scroll-after-filter') === '1';

  if (shouldScroll && target) {
    sessionStorage.removeItem('jt-scroll-after-filter');

    const shopNav = document.querySelector('.site-header__shop-nav-wrap');
    const adminBar = document.getElementById('wpadminbar');

    const offset =
      (shopNav ? shopNav.offsetHeight : 50) +
      (adminBar ? adminBar.offsetHeight : 0) + 24;

    const y = target.getBoundingClientRect().top + window.pageYOffset - offset;

    window.scrollTo({
      top: y,
      behavior: 'instant'
    });
  }
});