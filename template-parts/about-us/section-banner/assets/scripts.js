const aboutUsBannerScripts = () => {
  const container = document.querySelector("#about-us-banner");
  if (!container) return;

  const swiperEl = container.querySelector(".about-us-banner__swiper");
  if (!swiperEl) return;

  const navPrev = container.querySelector(".about-us-banner__nav-prev");
  const navNext = container.querySelector(".about-us-banner__nav-next");

  const contentItems = Array.from(
    container.querySelectorAll(".about-us-banner__content-item")
  );
  const slideCount = swiperEl.querySelectorAll(".swiper-slide").length;
  const enableLoop = slideCount > 1;

  const setActiveContent = (activeIndex) => {
    contentItems.forEach((item) => {
      const indexAttr = item.getAttribute("data-banner-index");
      const index = indexAttr == null ? NaN : Number(indexAttr);
      const isActive = index === activeIndex;

      item.classList.toggle("is-active", isActive);
      item.setAttribute("aria-hidden", isActive ? "false" : "true");
    });
  };

  const paginationEl = container.querySelector(".about-us-banner__pagination");

  const swiperOptions = {
    slidesPerView: 1,
    speed: 1500,
    loop: enableLoop,
    parallax: true,
    watchOverflow: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
      waitForTransition: false,
    },
    on: {
      init(swiper) {
        setActiveContent(swiper.realIndex);
        queueMicrotask(() => {
          if (swiper?.autoplay?.running === false) swiper.autoplay.start();
        });
      },
      slideChange(swiper) {
        setActiveContent(swiper.realIndex);
      },
    },
  };

  if (paginationEl) {
    swiperOptions.pagination = {
      el: paginationEl,
      clickable: true,
    };
  }

  if (navPrev && navNext) {
    swiperOptions.navigation = {
      prevEl: navPrev,
      nextEl: navNext,
    };
  }

  const swiper = new Swiper(swiperEl, swiperOptions);

  document.addEventListener("visibilitychange", () => {
    if (document.visibilityState !== "visible") return;
    if (swiper?.autoplay?.running === false) swiper.autoplay.start();
  });
};

document.addEventListener("DOMContentLoaded", () => {
  aboutUsBannerScripts();
});
