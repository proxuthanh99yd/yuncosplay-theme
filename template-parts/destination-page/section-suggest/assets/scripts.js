const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
};

export function sectionSuggestScripts() {

  new Swiper(".destination-suggest_cards.swiper", {
    slidesPerView: 2,
    spaceBetween: convertRemToPx(1.5),
    grabCursor: true,
    navigation: {
      nextEl: ".destination-suggest .swiper-button-next",
      prevEl: ".destination-suggest .swiper-button-prev",
    },
  });
  
   
}