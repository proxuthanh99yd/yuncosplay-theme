function sectionHorizonVietnam() {
    let extraConfig = {};
    if (window.innerWidth < 640) {
        extraConfig = {
            slidesOffsetBefore: remToPixels(1),
            slidesOffsetAfter: remToPixels(1),
        };
    }
    new Swiper(".horizon-vietnam__swiper", {
        slidesPerView: 1.9,
        spaceBetween: remToPixels(0.92),
        navigation: {
            nextEl: ".horizon-vietnam__swiper-next",
            prevEl: ".horizon-vietnam__swiper-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 4,
            },
        },
        ...extraConfig,
    });
}
export default sectionHorizonVietnam;
