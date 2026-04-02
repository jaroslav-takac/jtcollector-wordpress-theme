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