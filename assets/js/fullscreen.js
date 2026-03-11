(() => {
  const STORAGE_KEY = 'hh_fullscreen_enabled_v1';
  const docEl = document.documentElement;

  function canFullscreen() {
    return (
      !!docEl &&
      typeof docEl.requestFullscreen === 'function' &&
      typeof document.fullscreenEnabled === 'boolean' &&
      document.fullscreenEnabled
    );
  }

  async function enterFullscreenOnce() {
    try {
      if (!canFullscreen()) return;
      if (document.fullscreenElement) return;
      if (localStorage.getItem(STORAGE_KEY) === '1') return;

      await docEl.requestFullscreen();
      localStorage.setItem(STORAGE_KEY, '1');
    } catch {
      // Ignore errors
  }}

  function onFirstGesture() {
    enterFullscreenOnce();
    document.removeEventListener('pointerdown', onFirstGesture, true);
    document.removeEventListener('keydown', onFirstGesture, true);
    document.removeEventListener('touchstart', onFirstGesture, true);
  }

  document.addEventListener('pointerdown', onFirstGesture, true);
  document.addEventListener('keydown', onFirstGesture, true);
  document.addEventListener('touchstart', onFirstGesture, true);
})();

