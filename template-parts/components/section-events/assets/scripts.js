// remToPixels: dùng bản global của assets/js/utils.js (core load trước page).
// Bản copy local ở đây đã bỏ — nó khai báo `const rootFontSize` trùng với
// home-page/section-products, gây SyntaxError khi 2 file cùng nằm trên trang chủ.

function sectionEventsScripts() {
  const SELECTORS = {
    SWIPER: ".home_events-swiper",
    SLIDES: ".home_events-swiper .swiper-slide",
    BUTTON_PREV: ".home_events-swiper-button--prev",
    BUTTON_NEXT: ".home_events-swiper-button--next",
    EVENTS_SECTION: ".home__events",
    EVENTS_ITEMS: ".home_events-swiper-item",
    EVENTS_TITLE: ".home__events-title",
    EVENTS_DESCRIPTION: ".home__events-description",
    // PAGINATION: ".home_events-swiper-pagination",
  };

  // GSAP Animation for staggered slide-up
  const initGSAPAnimation = () => {
    // Check if GSAP is available
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
      console.warn("GSAP or ScrollTrigger not available");
      return;
    }

    const section = document.querySelector(SELECTORS.EVENTS_SECTION);
    const items = document.querySelectorAll(SELECTORS.EVENTS_ITEMS);
    const title = document.querySelector(SELECTORS.EVENTS_TITLE);
    const description = document.querySelector(SELECTORS.EVENTS_DESCRIPTION);

    if (!section || !items.length) return;

    // Check mobile - only apply animation on desktop
    const isMobile = window.innerWidth < 640;
    if (isMobile) {
      // Mobile: reset to normal state
      gsap.set(items, { autoAlpha: 1, y: 0 });
      gsap.set([title, description], { autoAlpha: 1, y: 0 });
      return;
    }

    // Set initial state
    gsap.set(items, { autoAlpha: 0, y: remToPixels(21.87) });
    gsap.set([title, description], { autoAlpha: 0 });

    // Create timeline for coordinated animations
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: "top 40%",
        once: true,
      },
    });

    // Animate header elements together (opacity only)
    tl.fromTo(
      [title, description],
      { autoAlpha: 0 },
      {
        autoAlpha: 1,
        duration: 1,
        ease: "power2.out",
      }
    );

    // Animate items independently with stagger
    tl.fromTo(
      items,
      { autoAlpha: 0, y: remToPixels(21.87) },
      {
        autoAlpha: 1,
        y: 0,
        duration: 1.2,
        stagger: 0.35, // Each item is 0.35s apart
        ease: "power2.out",
      },
      0 // Start at the same time as header animation
    );
  };

  let swiper = null;
  let lastMode = null;

  function initSwiper() {
    if (typeof Swiper === "undefined") return;

    const swiperEl = document.querySelector(SELECTORS.SWIPER);
    if (!swiperEl) return;

    const slidesCount = document.querySelectorAll(SELECTORS.SLIDES).length;
    if (!slidesCount) return;

    const isDesktop = window.innerWidth >= 1024;
    const mode = isDesktop ? "desktop" : "mobile";
    if (mode === lastMode && swiper) {
      swiper.update();
      return;
    }
    lastMode = mode;

    if (swiper) {
      swiper.destroy(true, true);
      swiper = null;
    }

    swiper = new Swiper(swiperEl, {
      watchOverflow: true,
      grabCursor: true,
      speed: 600,
      loop: slidesCount > 1,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: SELECTORS.BUTTON_NEXT,
        prevEl: SELECTORS.BUTTON_PREV,
        disabledClass: "is-disabled",
      },
      // pagination: {
      // 	el: SELECTORS.PAGINATION,
      // 	clickable: true,
      // },
      breakpoints: {
        0: {
          slidesPerView: 1,
          spaceBetween: remToPixels(0),
        },
        640: {
          slidesPerView: "auto",
          spaceBetween: remToPixels(1.25),
        },
      },
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      initSwiper();
      initGSAPAnimation();
    });
  } else {
    initSwiper();
    initGSAPAnimation();
  }

  let resizeTimeout;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      initSwiper();
      initGSAPAnimation();
    }, 200);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  sectionEventsScripts();
});
