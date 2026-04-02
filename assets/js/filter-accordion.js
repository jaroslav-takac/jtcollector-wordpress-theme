document.addEventListener('DOMContentLoaded', function () {
  const filterBox = document.querySelector('.shop-filter-box');

  if (!filterBox) {
    return;
  }

  const storageKey = 'jt-filter-accordion-state:' + window.location.pathname;
  const accordionState = new Map();
  let reinitTimer = null;

  function getFilterKey(title) {
    return title.textContent.trim();
  }

  function loadAccordionState() {
    try {
      const saved = sessionStorage.getItem(storageKey);

      if (!saved) {
        return;
      }

      const parsed = JSON.parse(saved);

      Object.entries(parsed).forEach(function ([key, value]) {
        accordionState.set(key, Boolean(value));
      });
    } catch (error) {
      console.warn('Accordion state could not be loaded.', error);
    }
  }

  function saveAccordionState() {
    try {
      const data = Object.fromEntries(accordionState);
      sessionStorage.setItem(storageKey, JSON.stringify(data));
    } catch (error) {
      console.warn('Accordion state could not be saved.', error);
    }
  }

  function hasActiveSelection(filter) {
    return !!filter.querySelector(
      'input[type="checkbox"]:checked,' +
      'input[type="radio"]:checked,' +
      'option:checked,' +
      'li.chosen,' +
      '.chosen,' +
      '.active,' +
      '.selected,' +
      '[aria-current="true"]'
    );
  }

  function prepareAccordions() {
    const filters = filterBox.querySelectorAll('.yith-wcan-filter');

    filters.forEach(function (filter, index) {
      const title = filter.querySelector('h4.filter-title, .filter-title, .yith-wcan-filter-title');

      if (!title) {
        return;
      }

      const content =
        filter.querySelector('.filter-content') ||
        title.nextElementSibling;

      if (!content) {
        return;
      }

      const key = getFilterKey(title);
      const isActiveFilter = hasActiveSelection(filter);

      filter.classList.add('jt-filter-accordion');
      title.classList.add('jt-filter-accordion__trigger');
      content.classList.add('jt-filter-accordion__content');

      title.setAttribute('role', 'button');
      title.setAttribute('tabindex', '0');

      if (!content.id) {
        content.id = 'jt-filter-content-' + index;
      }

      title.setAttribute('aria-controls', content.id);

      /**
       * Priorita:
       * 1. aktívny filter = vždy otvorený
       * 2. uložený stav
       * 3. defaultne iba prvý filter
       */
      if (isActiveFilter) {
        accordionState.set(key, true);
      } else if (!accordionState.has(key)) {
        accordionState.set(key, index === 0);
      }

      const isOpen = accordionState.get(key);

      filter.classList.toggle('is-open', isOpen);
      title.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    saveAccordionState();
  }

  function schedulePrepare(delay = 0) {
    clearTimeout(reinitTimer);
    reinitTimer = setTimeout(function () {
      prepareAccordions();
    }, delay);
  }

  function toggleAccordion(title) {
    const filter = title.closest('.yith-wcan-filter');

    if (!filter) {
      return;
    }

    const key = getFilterKey(title);
    const isOpen = !filter.classList.contains('is-open');

    filter.classList.toggle('is-open', isOpen);
    title.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    accordionState.set(key, isOpen);
    saveAccordionState();
  }

  loadAccordionState();
  prepareAccordions();

  filterBox.addEventListener('click', function (event) {
    const title = event.target.closest('.jt-filter-accordion__trigger');

    if (!title) {
      return;
    }

    if (event.target.closest('input, label, a')) {
      return;
    }

    toggleAccordion(title);
  });

  filterBox.addEventListener('keydown', function (event) {
    const title = event.target.closest('.jt-filter-accordion__trigger');

    if (!title) {
      return;
    }

    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      toggleAccordion(title);
    }
  });

  const observer = new MutationObserver(function () {
    schedulePrepare(30);
    schedulePrepare(120);
  });

  observer.observe(filterBox, {
    childList: true,
    subtree: true
  });

  if (window.jQuery) {
    jQuery(document).ajaxComplete(function () {
      schedulePrepare(30);
      schedulePrepare(120);
      schedulePrepare(250);
    });
  }
});