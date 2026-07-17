function remToPx(rem) {
  return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
}

const SELECTORS = {
  SWIPER: ".section-services__slider",
  NAV_NEXT: ".section-services__nav-next",
  NAV_PREV: ".section-services__nav-prev",
  PAGINATION: ".section-services__pagination",
};

function sectionServicesScripts() {
  const swiperEl = document.querySelector(SELECTORS.SWIPER);
  if (!swiperEl) return;

  const swiper = new Swiper(swiperEl, {
    slidesPerView: "auto",
    loop: true,
    speed: 600,
    spaceBetween: remToPx(1.5),
    grabCursor: true,
    autoplay: {
      delay: 3000,
    },
    pagination: {
      el: SELECTORS.PAGINATION,
      clickable: true,
      type: "fraction",
    },
    navigation: {
      nextEl: SELECTORS.NAV_NEXT,
      prevEl: SELECTORS.NAV_PREV,
    },
    breakpoints: {
      0: {
        spaceBetween: remToPx(0.75),
      },
      640: {
        slidesPerView: "auto",
        spaceBetween: remToPx(1.5),
      },
    },
  });
}

document.addEventListener("DOMContentLoaded", () => {
  sectionServicesScripts();
});
