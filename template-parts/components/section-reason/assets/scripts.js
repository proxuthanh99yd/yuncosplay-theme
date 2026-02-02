const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
};

export function sectionReasonScripts() {
    new Swiper(".destination-reason_cards-mb.swiper", {
    slidesPerView: 1,
    grabCursor: true,
    spaceBetween: convertRemToPx(1.25),
    pagination: {
      el: ".destination-reason_pagination.swiper-pagination",
      clickable: true,
    },
  });
}