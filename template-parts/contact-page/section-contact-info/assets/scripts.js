/**
 * Contact Info — Social Media Slider (Swiper)
 * PC: 3 slides, navigation arrows
 * Mobile: freeMode, slidesPerView auto, no arrows
 */

const MOBILE_BREAKPOINT = 639.98;

function initSocialSwiper() {
    const swiperEl = document.querySelector(".contact-info__social-swiper");
    if (!swiperEl) return;

    const swiper = new Swiper(swiperEl, {
        slidesPerView: 3,
        spaceBetween: 12,
        navigation: {
            prevEl: ".contact-info__social-arrow--prev",
            nextEl: ".contact-info__social-arrow--next",
        },
        breakpoints: {
            0: {
                slidesPerView: "auto",
                spaceBetween: 8,
                freeMode: true,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 12,
                freeMode: false,
            },
        },
    });

    return swiper;
}

// Init khi DOM ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSocialSwiper);
} else {
    initSocialSwiper();
}

export { initSocialSwiper };
