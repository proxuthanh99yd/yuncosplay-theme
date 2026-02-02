export function sectionBannerScripts() {
  const discoverBtn = document.querySelector('.banner__discover');
  if (!discoverBtn) return;

  discoverBtn.addEventListener('click', () => {
    const overviewSection = document.getElementById('overview');
    if (!overviewSection) return;

    const header = document.querySelector('header');
    const headerHeight = header?.offsetHeight || 0;

    const EXTRA_OFFSET_REM = 2; 

    // rem → px theo root font-size hiện tại
    const rootFontSize = parseFloat(
      getComputedStyle(document.documentElement).fontSize
    );
    const extraOffsetPx = EXTRA_OFFSET_REM * rootFontSize;

    const targetY =
      overviewSection.getBoundingClientRect().top +
      window.scrollY -
      headerHeight +
      extraOffsetPx;

    const lenisInstance = window.app?.lenis;

    if (lenisInstance?.scrollTo) {
      lenisInstance.scrollTo(targetY, {
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      });
    } else {
      window.scrollTo({
        top: targetY,
        behavior: 'smooth',
      });
    }
  });
}
