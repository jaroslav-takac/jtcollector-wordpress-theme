(function ($) {
  'use strict';

  let autoCloseTimer = null;
  let canClosePopup = false;
  let pendingData = null;
  let justOpenedAt = 0;
  let popupOpenedViaAjax = false;

  function getPopupElements() {
    const popup = document.querySelector('.jt-atc-popup');

    if (!popup) {
      return null;
    }

    return {
      popup,
      dialog: popup.querySelector('.jt-atc-popup__dialog'),
      overlay: popup.querySelector('.jt-atc-popup__overlay'),
      image: popup.querySelector('.jt-atc-popup__image'),
      productName: popup.querySelector('.jt-atc-popup__product-name'),
      productPrice: popup.querySelector('.jt-atc-popup__product-price'),
      title: popup.querySelector('#jt-atc-popup-title'),
      description: popup.querySelector('#jt-atc-popup-text'),
      cartButton: popup.querySelector('.jt-atc-popup__button--primary'),
      closeButtons: popup.querySelectorAll('[data-jt-popup-close]')
    };
  }

  function clearAutoClose() {
    if (autoCloseTimer) {
      window.clearTimeout(autoCloseTimer);
      autoCloseTimer = null;
    }
  }

  function normalizeText(value) {
    return value ? value.replace(/\s+/g, ' ').trim() : '';
  }

  function getBestImageFromElement($context) {
    if (!$context || !$context.length) {
      return '';
    }

    const $img = $context.find('img').first();

    if (!$img.length) {
      return '';
    }

    return (
      $img.attr('data-large_image') ||
      $img.attr('data-src') ||
      $img.attr('data-lazy-src') ||
      $img.attr('src') ||
      ''
    );
  }

  function savePendingToSession(data) {
    if (!data) {
      return;
    }

    try {
      sessionStorage.setItem('jt_atc_popup_pending', JSON.stringify(data));
    } catch (error) {
      // ignore
    }
  }

  function readPendingFromSession() {
    try {
      const storedData = sessionStorage.getItem('jt_atc_popup_pending');

      if (!storedData) {
        return null;
      }

      sessionStorage.removeItem('jt_atc_popup_pending');
      return JSON.parse(storedData);
    } catch (error) {
      sessionStorage.removeItem('jt_atc_popup_pending');
      return null;
    }
  }

  function shouldSkipPopupForButton(button) {
    const $button = $(button);
    return $button.closest('.jt-empty-cart-featured').length > 0;
  }

  function openPopup(data) {
    const els = getPopupElements();

    if (!els) {
      return;
    }

    clearAutoClose();
    canClosePopup = false;
    justOpenedAt = Date.now();

    const image = data.image || (window.jtAddToCartPopup && window.jtAddToCartPopup.defaultImage) || '';
    const name = data.name || 'Produkt bol pridaný do košíka';
    const price = data.price || '';
    const description = data.description || 'Produkt bol úspešne pridaný do košíka.';

    if (image) {
      els.image.src = image;
      els.image.alt = name;
    } else {
      els.image.alt = '';
    }

    els.productName.textContent = name;
    els.productPrice.textContent = price;
    els.productPrice.style.display = price ? '' : 'none';

    els.title.textContent =
      (window.jtAddToCartPopup && window.jtAddToCartPopup.successText) ||
      'Produkt bol pridaný do košíka';

    els.description.textContent = description;

    if (els.cartButton && window.jtAddToCartPopup && window.jtAddToCartPopup.cartUrl) {
      els.cartButton.setAttribute('href', window.jtAddToCartPopup.cartUrl);
    }

    els.popup.hidden = false;

    window.requestAnimationFrame(function () {
      els.popup.classList.add('is-visible');
      document.body.classList.add('jt-atc-popup-open');
    });

    window.setTimeout(function () {
      canClosePopup = true;
    }, 220);

    if (
      window.jtAddToCartPopup &&
      Number(window.jtAddToCartPopup.autoCloseDelay) > 0
    ) {
      autoCloseTimer = window.setTimeout(closePopup, Number(window.jtAddToCartPopup.autoCloseDelay));
    }

    pendingData = null;
  }

  function closePopup(force) {
    const els = getPopupElements();

    if (!els) {
      return;
    }

    if (!force && (!canClosePopup || Date.now() - justOpenedAt < 180)) {
      return;
    }

    clearAutoClose();

    els.popup.classList.remove('is-visible');
    document.body.classList.remove('jt-atc-popup-open');

    window.setTimeout(function () {
      if (!els.popup.classList.contains('is-visible')) {
        els.popup.hidden = true;
      }
    }, 240);
  }

  function getArchiveOrFeaturedData(button) {
    const $button = $(button);
    const $product = $button.closest('.product, li.product');
    const $fallbackCard = $button.closest('[class*="product"], [class*="shop-home"]');
    const $scope = $product.length ? $product : $fallbackCard;

    const $title = $scope.find('.woocommerce-loop-product__title').first();
    const $price = $scope.find('.price').first();

    return {
      name: normalizeText($title.text()),
      price: normalizeText($price.text()),
      image: getBestImageFromElement($scope),
      description: 'Produkt bol úspešne pridaný do košíka.'
    };
  }

    function getSingleProductData(button) {
    const $button = $(button);

    const $productRoot = $('.single-product .product').first().length
        ? $('.single-product .product').first()
        : $('main .product').first();

    const $title = $productRoot.find('.product_title').first().length
        ? $productRoot.find('.product_title').first()
        : $('h1.product_title, .product h1, .jt-single-product h1').first();

    const $price = $productRoot.find('.summary .price').first().length
        ? $productRoot.find('.summary .price').first()
        : $('.summary .price, .jt-single-product__summary .price, .jt-single-product .price').first();

    const $galleryImg = $('.woocommerce-product-gallery__image img').first().length
        ? $('.woocommerce-product-gallery__image img').first()
        : $('.jt-single-product__gallery-frame img, .jt-single-product img.wp-post-image, .product img.wp-post-image').first();

    let image = '';
    if ($galleryImg.length) {
        image =
        $galleryImg.attr('data-large_image') ||
        $galleryImg.attr('data-src') ||
        $galleryImg.attr('data-lazy-src') ||
        $galleryImg.attr('src') ||
        '';
    }

    const name = normalizeText($title.text());
    const price = normalizeText($price.text());

    return {
        name: name || 'Produkt bol pridaný do košíka',
        price: price || '',
        image: image,
        description: 'Produkt bol úspešne pridaný do košíka.'
    };
  }

  function getFallbackNoticeData() {
    const $notice = $('.woocommerce-message, .wc-block-components-notice-banner, .woocommerce-notices-wrapper .message, .woocommerce-notices-wrapper .woocommerce-message').first();

    if (!$notice.length) {
      return null;
    }

    const noticeText = normalizeText($notice.text());

    if (
      noticeText.indexOf('bol pridaný do košíka') === -1 &&
      noticeText.indexOf('has been added to your cart') === -1
    ) {
      return null;
    }

    let productName = '';
    const $noticeLink = $notice.find('a').not('.button, .restore-item').last();

    if ($noticeLink.length) {
      productName = normalizeText($noticeLink.text());
    }

    return {
      name: productName || 'Produkt bol pridaný do košíka',
      price: '',
      image: '',
      description: 'Produkt bol úspešne pridaný do košíka.'
    };
  }

  function bindPopupClose() {
    const els = getPopupElements();

    if (!els) {
      return;
    }

    els.closeButtons.forEach(function (button) {
      button.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        closePopup(true);
      });
    });

    if (els.overlay) {
      els.overlay.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        closePopup();
      });
    }

    if (els.dialog) {
      els.dialog.addEventListener('click', function (event) {
        event.stopPropagation();
      });

      els.dialog.addEventListener('mousedown', function (event) {
        event.stopPropagation();
      });
    }

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closePopup(true);
      }
    });
  }

  function bindAddToCartEvents() {
    $(document).on('click', '.add_to_cart_button, .ajax_add_to_cart, .single_add_to_cart_button', function () {
      const button = this;
      const $button = $(button);
      const isSingle =
        $button.hasClass('single_add_to_cart_button') ||
        $('body').hasClass('single-product');

      if (shouldSkipPopupForButton(button)) {
        pendingData = null;

        try {
          sessionStorage.removeItem('jt_atc_popup_pending');
        } catch (error) {
          // ignore
        }

        return;
      }

      pendingData = isSingle
        ? getSingleProductData(button)
        : getArchiveOrFeaturedData(button);

      popupOpenedViaAjax = false;
      savePendingToSession(pendingData);
    });

    $(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {
      if ($button && $button.length && shouldSkipPopupForButton($button.get(0))) {
        try {
          sessionStorage.removeItem('jt_atc_popup_pending');
        } catch (error) {
          // ignore
        }
        return;
      }

      popupOpenedViaAjax = true;

      if (pendingData) {
        openPopup(pendingData);
      } else {
        openPopup({
          name: 'Produkt bol pridaný do košíka',
          price: '',
          image: '',
          description: 'Produkt bol úspešne pridaný do košíka.'
        });
      }

      try {
        sessionStorage.removeItem('jt_atc_popup_pending');
      } catch (error) {
        // ignore
      }
    });

    $(document).on('submit', 'form.cart', function () {
      const data = getSingleProductData($(this).find('.single_add_to_cart_button').get(0) || this);
      pendingData = data;
      savePendingToSession(data);
    });

    const storedData = readPendingFromSession();

    if (storedData && !($('.woocommerce-cart .jt-empty-cart-featured').length > 0)) {
      window.setTimeout(function () {
        openPopup(storedData);
      }, 260);
    } else {
      const fallbackData = getFallbackNoticeData();

      if (fallbackData && !popupOpenedViaAjax) {
        window.setTimeout(function () {
          openPopup(fallbackData);
        }, 260);
      }
    }
  }

  $(function () {
    if (!$('.jt-atc-popup').length) {
      return;
    }

    bindPopupClose();
    bindAddToCartEvents();
  });
})(jQuery);