const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
};

export function beachGetawayScripts() {
  new Swiper(".ht-beach-getaway_cards.swiper", {
    slidesPerView: 2,
    spaceBetween: convertRemToPx(1.5),
    grabCursor: true,
    navigation: {
      nextEl: ".ht-beach-getaway .swiper-button-next",
      prevEl: ".ht-beach-getaway .swiper-button-prev",
    },
  });
}
