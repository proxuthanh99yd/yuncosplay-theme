export function inspirationsScripts() {
  const convertRemToPx = (rem) => {
    const rootFontSize = parseFloat(
      getComputedStyle(document.documentElement).fontSize,
    );
    return rem * rootFontSize;
  };
  const insiderSwiper = new Swiper(".destination-insider_cards.swiper", {
    slidesPerView: 4,
    grabCursor: true,
    spaceBetween: convertRemToPx(1.5),
    navigation: {
      nextEl: ".destination-insider .swiper-button-next",
      prevEl: ".destination-insider .swiper-button-prev",
    },
  });
}
