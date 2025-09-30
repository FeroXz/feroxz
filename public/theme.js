(function () {
  const storageKey = 'feroxz-theme';
  const className = 'dark-mode';
  const darkLabel = '☀️ Light Mode';
  const lightLabel = '🌙 Dark Mode';

  const applyTheme = (mode) => {
    const body = document.body;
    if (!body) {
      return;
    }
    if (mode === 'dark') {
      body.classList.add(className);
    } else {
      body.classList.remove(className);
    }
    updateButtons(mode);
  };

  const updateButtons = (mode) => {
    const buttons = document.querySelectorAll('#theme-toggle');
    buttons.forEach((button) => {
      if (!(button instanceof HTMLButtonElement)) {
        return;
      }
      const isDark = mode === 'dark';
      button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
      button.textContent = isDark ? darkLabel : lightLabel;
    });
  };

  const detectInitialMode = () => {
    const stored = localStorage.getItem(storageKey);
    if (stored === 'dark' || stored === 'light') {
      return stored;
    }
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
      ? 'dark'
      : 'light';
  };

  const toggleMode = () => {
    const isDark = document.body.classList.contains(className);
    const next = isDark ? 'light' : 'dark';
    localStorage.setItem(storageKey, next);
    applyTheme(next);
  };

  document.addEventListener('DOMContentLoaded', () => {
    const initialMode = detectInitialMode();
    applyTheme(initialMode);

    const buttons = document.querySelectorAll('#theme-toggle');
    buttons.forEach((button) => {
      button.addEventListener('click', toggleMode);
    });

  });
})();
