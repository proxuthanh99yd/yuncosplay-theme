export function sectionCulturesScripts() {
  let cultureSwiper;
  function initCultureSwiper() {
      const isDesktop = window.innerWidth >= 639;
    
      if (cultureSwiper) {
        cultureSwiper.destroy(true, true);
      }
    
      cultureSwiper = new Swiper(".destination-cultures_slides", {
        slidesPerView: 1,
        grabCursor: true,
        speed: 500,
        effect: isDesktop ? "fade" : "slide",
        fadeEffect: isDesktop ? { crossFade: true } : undefined,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        navigation: {
          nextEl: "destination-cultures .swiper-button-next",
          prevEl: "destination-cultures .swiper-button-prev",
        }
      });
  }
  
  initCultureSwiper();
  window.addEventListener("resize", initCultureSwiper);
}

