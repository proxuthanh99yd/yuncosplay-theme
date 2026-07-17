
const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize
  );
  return rem * rootFontSize;
}

function initSocialSwiper() {
    const swiperEl = document.querySelector(".contact-info__social-swiper");
    if (!swiperEl) return;

    const desktopQuery = window.matchMedia("(min-width: 640px)");
    let swiper = null;

    const createDesktopSwiper = () => {
        if (swiper) return;

        swiper = new Swiper(swiperEl, {
            slidesPerView: 3,
            spaceBetween: convertRemToPx(0.88),
            navigation: {
              prevEl: ".contact-info__social-arrow--prev",
              nextEl: ".contact-info__social-arrow--next",
            },
            on: {
                init(instance) {
                    toggleNav(instance);
                },
            },
        });
    };

    const destroyDesktopSwiper = () => {
        if (!swiper) return;
        swiper.destroy(true, true);
        swiper = null;
    };

    const syncByViewport = () => {
        if (desktopQuery.matches) createDesktopSwiper();
        else destroyDesktopSwiper();
    };
    
    function toggleNav(swiper) {
        const prevEl = document.querySelector(".contact-info__social-arrow--prev");
        const nextEl = document.querySelector(".contact-info__social-arrow--next");
        if (!prevEl || !nextEl) return;
    
        const slidesCount = swiper.slides.length;
    
        const shouldHide = slidesCount <= 3;
    
        if (shouldHide) {
            prevEl.style.display = "none";
            nextEl.style.display = "none";
        } else {
            prevEl.style.display = "";
            nextEl.style.display = "";
        }
    }

    syncByViewport();
    if (desktopQuery.addEventListener) {
        desktopQuery.addEventListener("change", syncByViewport);
    } else if (desktopQuery.addListener) {
        desktopQuery.addListener(syncByViewport);
    }

    return swiper;
}

// Init khi DOM ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSocialSwiper);
} else {
    initSocialSwiper();
}


