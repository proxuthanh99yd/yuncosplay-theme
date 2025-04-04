function sectionFaqs() {
    const thumbSlide = new Swiper(".discover__thumb-swiper", {
        loop: true,
        spaceBetween: remToPixels(0.69),
        slidesPerView: 5,
        freeMode: true,
        watchSlidesProgress: true,
    });
    const mainSlide = new Swiper(".discover__swiper", {
        effect: "fade",
        loop: true,
        navigation: {
            nextEl: ".discover__swiper-next",
            prevEl: ".discover__swiper-prev",
        },
        thumbs: {
            swiper: thumbSlide,
        },
    });
}
export default sectionFaqs;
