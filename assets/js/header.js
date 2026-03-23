document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.site-header__tab');
  const panels = document.querySelectorAll('.site-header__panel-content');

  if (!tabs.length || !panels.length) {
    return;
  }

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.panel;

      tabs.forEach((item) => item.classList.remove('is-active'));
      panels.forEach((panel) => panel.classList.remove('is-active'));

      tab.classList.add('is-active');

      const activePanel = document.querySelector(
        `.site-header__panel-content[data-panel-content="${target}"]`
      );

      if (activePanel) {
        activePanel.classList.add('is-active');
      }
    });
  });
});