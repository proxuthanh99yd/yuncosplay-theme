export function sectionFreedomScripts() {
  const freedomSection = document.querySelector(".section__freedom");

  const contentFirst = document.querySelector(".section__freedom__container");

  const titleValues = document.querySelector(".section__freedom__values__title");

  const listItems = document.querySelectorAll(".section__freedom__values__item");

  const isMobile = window.innerWidth < 640;


  if (!freedomSection || !contentFirst || !titleValues || !listItems) return;

  const scrollDelay = isMobile ? 0 : 500;
  const getTotalEndDistance = () => {
    const animationDistance = isMobile
      ? Math.max(1, freedomSection.offsetHeight)
      : 4500;
    return animationDistance + scrollDelay;
  };

  const freedomTl = gsap.timeline({
    paused: true,
    defaults: { ease: "expo.out" },
  });

  if (contentFirst) {
    // Trạng thái ban đầu theo CSS để không lệch layout.
    freedomTl.set(contentFirst, {
      opacity: 1,
    }, 0);
    // Fade + dịch nhẹ text đầu tiên.
    freedomTl.to(contentFirst, {
      opacity: 0,
      pointerEvents: "none",
      duration: isMobile ? 0.02 : 0.4,
    }, isMobile ? 0.01 : 0);
  }
  if (titleValues) {
    // Trạng thái ban đầu theo CSS để không lệch layout.
    freedomTl.set(titleValues, {
      opacity: 0,
      transform: "translateY(8rem)",
      pointerEvents: "none",
    }, 0);
    // Fade + dịch nhẹ text đầu tiên.
    freedomTl.to(titleValues, {
      opacity: 1,
      transform: "translateY(0)",
      pointerEvents: "auto",
      duration: 0.1,
    }, isMobile ? 0.01 : 0.1);
  }

  if (listItems.length > 0) {
    Array.from(listItems).forEach((item, index) => {
      if (item) {
        // Đặt trạng thái ban đầu cho từng item
        freedomTl.set(item, {
          opacity: 0,
          transform: "translateY(8rem)",
          pointerEvents: "none",
        }, 0);

        // Áp dụng animation cho từng item
        freedomTl.to(item, {
          opacity: 1,
          transform: "translateY(0)",
          pointerEvents: "auto",
          duration: 0.05, // Thời gian animation cho mỗi item
        }, isMobile ? 0.02 + index / 100 : 0.3 + index * 0.2); // Thêm độ trễ giữa các item
      }
    });
  }


  const setTimelineProgress = gsap.quickTo(freedomTl, "progress", {
    duration: isMobile ? 0.2 : 0.7,
    ease: "expo.out",
    overwrite: true,
  });

  ScrollTrigger.create({
    id: "freedomPin",
    trigger: freedomSection,
    start: "top top",
    end: () => `+=${getTotalEndDistance()}`,
    pin: !isMobile,
    anticipatePin: 1,
    fastScrollEnd: true,
    invalidateOnRefresh: true,
    onUpdate: (self) => {
      const pinDistance = Math.max(1, self.end - self.start);
      const animationDistance = Math.max(1, pinDistance - scrollDelay);
      const rawScroll = self.progress * pinDistance;
      const adjustedProgress =
        rawScroll <= scrollDelay
          ? 0
          : (rawScroll - scrollDelay) / animationDistance;
      const safeProgress = Math.min(1, Math.max(0, adjustedProgress));

      setTimelineProgress(safeProgress);
    },
  });

  // sectionFreedomScriptsSwiper();
}
// let ROOT_FONT_SIZE = parseFloat(
//   getComputedStyle(document.documentElement).fontSize
// );

// function remToPixels(rem) {
//   return rem * ROOT_FONT_SIZE;
// }

// export const sectionFreedomScriptsSwiper = () => {
//   const CONFIG = {
//     BREAKPOINT_MOBILE: 639.98,
//     SLIDES_PER_VIEW: 3,
//   };

//   const SELECTORS = {
//     SWIPER: "#freedom-values-swiper",
//     SECTION: "#freedom",
//     SLIDES: ".freedom-freedom__values__swiper .swiper-slide",
//     BUTTON_PREV: ".freedom__values__button--prev",
//     BUTTON_NEXT: ".freedom__values__button--next",
//   };

//   let swiper = null;
//   let isDesktop = null;
//   let hasInitialized = false;

//   function initSwiper() {
//     if (typeof Swiper === "undefined") return;

//     const swiperEl = document.querySelector(SELECTORS.SWIPER);
//     if (!swiperEl) return;

//     const currentIsDesktop = window.innerWidth > CONFIG.BREAKPOINT_MOBILE;
//     isDesktop = currentIsDesktop;

//     // Mobile → destroy
//     if (!isDesktop) {
//       if (swiper) {
//         swiper.destroy(true, true);
//         swiper = null;
//       }
//       return;
//     }

//     if (swiper) {
//       swiper.destroy(true, true);
//       swiper = null;
//     }

//     // Desktop → init
//     requestAnimationFrame(() => {
//       swiper = new Swiper(SELECTORS.SWIPER, {
//         slidesPerView: CONFIG.SLIDES_PER_VIEW,
//         spaceBetween: remToPixels(1.5),
//         grabCursor: true,
//         watchOverflow: true,
//         navigation: {
//           nextEl: SELECTORS.BUTTON_NEXT,
//           prevEl: SELECTORS.BUTTON_PREV,
//           disabledClass: "is-disabled",
//         },
//         preventInteractionOnTransition: true,
//       });
//     });
//   }

//   function init() {
//     const sectionEl = document.querySelector(SELECTORS.SECTION);
//     if (!sectionEl) return;

//     const observer = new IntersectionObserver(
//       ([entry]) => {
//         if (entry.isIntersecting && !hasInitialized) {
//           hasInitialized = true;
//           requestAnimationFrame(initSwiper);
//           observer.disconnect();
//         }
//       },
//       { rootMargin: "100px" }
//     );

//     observer.observe(sectionEl);

//     let resizeTimeout;

//     window.addEventListener(
//       "resize",
//       () => {
//         ROOT_FONT_SIZE = parseFloat(
//           getComputedStyle(document.documentElement).fontSize
//         );
//         clearTimeout(resizeTimeout);
//         resizeTimeout = setTimeout(() => {
//           if (hasInitialized) initSwiper();
//         }, 200);
//       },
//       { passive: true }
//     );
//   }

//   document.readyState === "loading"
//     ? document.addEventListener("DOMContentLoaded", init)
//     : init();
// };
