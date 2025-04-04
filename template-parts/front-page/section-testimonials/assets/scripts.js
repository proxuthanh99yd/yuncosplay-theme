function sectionTestimonials() {
    let extraConfig = {};
    if (window.innerWidth < 640) {
        extraConfig = {
            slidesOffsetBefore: remToPixels(1),
            slidesOffsetAfter: remToPixels(1),
        };
    }
    new Swiper(".testimonials__swiper", {
        slidesPerView: 1.8,
        spaceBetween: remToPixels(0.75),
        navigation: {
            nextEl: ".testimonials__swiper-next",
            prevEl: ".testimonials__swiper-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 4,
                spaceBetween: remToPixels(1),
            },
        },
        ...extraConfig,
    });
    const testimonialItemSwiper = Array.from(
        document.getElementsByClassName("testimonials__item-swiper")
    );
    testimonialItemSwiper.forEach((item, index) => {
        new Swiper(item, {
            effect: "fade",
            slidesPerView: 1,
            allowTouchMove: false,
            // loop: true,
            navigation: {
                nextEl: `.testimonials__item-swiper-next-${index}`,
                prevEl: `.testimonials__item-swiper-prev-${index}`,
            },
            pagination: {
                el: `.testimonials__item-swiper-pagination-${index}`,
                clickable: true,
            },
        });
    });
}
export default sectionTestimonials;
