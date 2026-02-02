let ROOT_FONT_SIZE = parseFloat(
  getComputedStyle(document.documentElement).fontSize
);

function remToPixels(rem) {
  return rem * ROOT_FONT_SIZE;
}

export const sectionInspiringScripts = () => {
  const CONFIG = {
    BREAKPOINT_MOBILE: 639.98,
    SLIDES_PER_VIEW: 3,
  };

  const SELECTORS = {
    SWIPER: "#inspiring-swiper",
    SECTION: "#inspiring",
    SLIDES: ".inspiring__swiper .swiper-slide",
    BUTTON_PREV: ".inspiring__button--prev",
    BUTTON_NEXT: ".inspiring__button--next",
  };

  let swiper = null;
  let isDesktop = null;
  let hasInitialized = false;

  function initSwiper() {
    if (typeof Swiper === "undefined") return;

    const swiperEl = document.querySelector(SELECTORS.SWIPER);
    if (!swiperEl) return;

    const currentIsDesktop = window.innerWidth > CONFIG.BREAKPOINT_MOBILE;
    isDesktop = currentIsDesktop;

    // Mobile → destroy
    if (!isDesktop) {
      if (swiper) {
        swiper.destroy(true, true);
        swiper = null;
      }
      return;
    }

    if (swiper) {
      swiper.destroy(true, true);
      swiper = null;
    }

    // Desktop → init
    requestAnimationFrame(() => {
      swiper = new Swiper(SELECTORS.SWIPER, {
        slidesPerView: CONFIG.SLIDES_PER_VIEW,
        spaceBetween: remToPixels(1.5),
        grabCursor: true,
        watchOverflow: true,
        navigation: {
          nextEl: SELECTORS.BUTTON_NEXT,
          prevEl: SELECTORS.BUTTON_PREV,
          disabledClass: "is-disabled",
        },
        preventInteractionOnTransition: true,
      });
    });
  }

  function init() {
    const sectionEl = document.querySelector(SELECTORS.SECTION);
    if (!sectionEl) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting && !hasInitialized) {
          hasInitialized = true;
          requestAnimationFrame(initSwiper);
          observer.disconnect();
        }
      },
      { rootMargin: "100px" }
    );

    observer.observe(sectionEl);

    let resizeTimeout;

    window.addEventListener(
      "resize",
      () => {
        ROOT_FONT_SIZE = parseFloat(
          getComputedStyle(document.documentElement).fontSize
        );
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
          if (hasInitialized) initSwiper();
        }, 200);
      },
      { passive: true }
    );
  }

  document.readyState === "loading"
    ? document.addEventListener("DOMContentLoaded", init)
    : init();
};
