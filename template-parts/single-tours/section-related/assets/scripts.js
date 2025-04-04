function sectionRelated() {
    let extraConfig = {};
    if (window.innerWidth < 640) {
        extraConfig = {
            slidesOffsetBefore: remToPixels(1),
            slidesOffsetAfter: remToPixels(1),
        };
    }
    const customizedSwiper = new Swiper(".customized-trip__swiper", {
        slidesPerView: 1.5,
        spaceBetween: remToPixels(1),
        breakpoints: {
            640: {
                slidesPerView: 4,
                spaceBetween: remToPixels(1.11),
            },
        },
        ...extraConfig,
        navigation: {
            nextEl: ".customized-trip__swiper-button-next",
            prevEl: ".customized-trip__swiper-button-prev",
        },
        pagination: {
            el: ".customized-trip__swiper-pagination",
            clickable: true,
        },
    });
}

export default sectionRelated;
