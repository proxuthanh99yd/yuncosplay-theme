function sectionBannerScripts() {
  const banner = document.querySelector(".section-banner");
  if (!banner) return;

  const swiperEl = banner.querySelector(".swiper-banner");
  const paginationEl = banner.querySelector(".feature__item--pagination");
  if (!swiperEl) return;

  const mobileQuery = window.matchMedia("(max-width: 639.98px)");
  let swiper;

  const initSwiper = () => {
    if (swiper) swiper.destroy(true, true);

    swiper = new Swiper(swiperEl, {
      speed: 800,
      loop: mobileQuery.matches,
      autoplay: mobileQuery.matches
        ? {
            delay: 3000,
            disableOnInteraction: false,
          }
        : false,
      pagination: {
        el: paginationEl,
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
  };

  initSwiper();
  mobileQuery.addEventListener("change", initSwiper);
}

document.addEventListener("DOMContentLoaded", () => {
  sectionBannerScripts();
});
