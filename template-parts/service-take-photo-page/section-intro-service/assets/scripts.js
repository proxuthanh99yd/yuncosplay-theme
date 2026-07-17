export function sectionBannerScripts() {
  let swiper = new Swiper(".swiper-banner", {
    pagination: {
      el: ".feature__item--pagination",
      clickable: true,
    },

    breakpoints: {
      0: { spaceBetween: remToPixels(1), slidesPerView: 1 },
      640: {
        slidesPerView: 3,
        spaceBetween: remToPixels(0.875),
      },
    },
  });
}
