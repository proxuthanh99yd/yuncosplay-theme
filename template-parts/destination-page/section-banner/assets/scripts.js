export function sectionBannerScripts() {
    const scrolldownBtn = document.querySelector(".destination-banner_scrolldown");
    const sectionAbout = document.querySelector("#about");
    const headerTop = document.querySelector(".header-top");
    
    let isMobile = window.matchMedia("(max-width: 639px)").matches;

    window.addEventListener("resize", () => {
      isMobile = window.matchMedia("(max-width: 639px)").matches;
    });
    
    scrolldownBtn.addEventListener("click", () => {
        const headerTopHeight = headerTop?.clientHeight || 0;
        
        const EXTRA_OFFSET = isMobile ? 0 : headerTopHeight;
        
        
        const targetY = sectionAbout.getBoundingClientRect().top + window.scrollY - EXTRA_OFFSET;
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