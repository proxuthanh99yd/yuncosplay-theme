function remToPixel(rem) {
    const rootFontSize = parseFloat(
        getComputedStyle(document.documentElement).fontSize
    );
    return rem * rootFontSize;
}

const relatedSwiper = new Swiper(".related-products__swiper", {
    slidesPerView: "auto",
    spaceBetween: remToPixel(2.5),
    autoplay: {
        delay: 3000, 
        disableOnInteraction: false,
        pauseOnMouseEnter: true 
    },
    navigation: {
        prevEl: ".related-products__nav--prev",
        nextEl: ".related-products__nav--next",
    },
    pagination: {
        el: ".related-products__pagination",
        clickable: true,
    },
    breakpoints: {
        0: {
            slidesPerView: "auto",
            spaceBetween: remToPixel(0.375),
        },
        640: {
            slidesPerView: "auto",
            spaceBetween: remToPixel(2.5),
        }
    }
});
