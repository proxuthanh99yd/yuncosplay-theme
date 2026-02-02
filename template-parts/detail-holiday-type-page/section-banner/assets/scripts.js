const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
};

export function bannerScripts() {
    const scrolldownBtn = document.querySelector(".ht-banner_scrolldown");
    const sectionIntroduction = document.querySelector("#introduction");
    const headerTop = document.querySelector(".header-top");
    
    
    const headerTopHeight = headerTop?.clientHeight || 0;
    
    
    scrolldownBtn.addEventListener("click", () => {
        const targetY = sectionIntroduction.getBoundingClientRect().top + window.scrollY - headerTopHeight;
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
    })
}
