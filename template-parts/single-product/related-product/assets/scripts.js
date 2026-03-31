function remToPixel(rem) {
    const rootFontSize = parseFloat(
        getComputedStyle(document.documentElement).fontSize
    );
    return rem * rootFontSize;
}
const IS_MOBILE = (() => window.innerWidth <= 640)();
if (!IS_MOBILE) {
    const relatedSwiper = new Swiper(".related-products__swiper", {
        slidesPerView: "auto",
        spaceBetween: remToPixel(2.5),
        navigation: {
            prevEl: ".related-products__nav--prev",
            nextEl: ".related-products__nav--next",
        },
    });
}
