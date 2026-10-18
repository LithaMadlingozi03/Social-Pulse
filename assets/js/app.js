document.addEventListener('change', (event) => {
  const input = event.target;
  if (input instanceof HTMLInputElement && input.type === 'file' && input.files?.[0]) {
    const file = input.files[0];
    if (file.size > 8 * 1024 * 1024) {
      alert('Please choose an image smaller than 8 MB.');
      input.value = '';
    }
  }
});

document.querySelectorAll('textarea[maxlength]').forEach((textarea) => {
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto';
    textarea.style.height = `${textarea.scrollHeight}px`;
  });
});

// Light/dark mode: remember the visitor's choice on this browser.
(() => {
  const root = document.documentElement;
  const toggle = document.querySelector('[data-theme-toggle]');
  const themeColor = document.querySelector('#theme-color-meta');
  if (!toggle) return;

  const updateToggle = (theme) => {
    const dark = theme === 'dark';
    const nextTheme = dark ? 'light' : 'dark';
    const icon = toggle.querySelector('.theme-toggle-icon');
    const label = toggle.querySelector('.theme-toggle-label');
    if (icon) icon.textContent = dark ? '☀' : '☾';
    if (label) label.textContent = dark ? 'Light mode' : 'Dark mode';
    toggle.setAttribute('aria-label', `Switch to ${nextTheme} mode`);
    toggle.setAttribute('aria-pressed', String(dark));
    if (themeColor) themeColor.setAttribute('content', dark ? '#12111b' : '#f7f6fc');
  };

  const savedTheme = root.dataset.theme === 'dark' ? 'dark' : 'light';
  root.dataset.theme = savedTheme;
  updateToggle(savedTheme);

  toggle.addEventListener('click', () => {
    const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
    root.dataset.theme = nextTheme;
    try {
      localStorage.setItem('socialpulse-theme', nextTheme);
    } catch (error) {
      // Theme switching still works for this page if browser storage is disabled.
    }
    updateToggle(nextTheme);
  });
})();

