export function sectionCategoryScripts() {
  const itemsEl = document.querySelector(".h-category__items .swiper-slide");

  let itemsSwiper;

  function convertRemToPx(rem) {
    const rootFontSize = parseFloat(
      getComputedStyle(document.documentElement).fontSize,
    );
    return rem * rootFontSize;
  }

  const tabSwiper = new Swiper(".h-category__tabs.swiper", {
    slidesPerView: 2,
    watchSlidesProgress: true,
    grabCursor: true,
    navigation: {
      nextEl: ".h-category__tab-next",
      prevEl: ".h-category__tab-prev",
    },
    breakpoints: {
      0: {
        slidesPerView: 2,
        spaceBetween: convertRemToPx(0.5),
        slideToClickedSlide: true, // click tab sẽ chuyển slide
      },
      639: {
        slidesPerView: "auto",
      },
    },
  });

  if (window.innerWidth < 639.98) {
    tabSwiper.on("click", function () {
      const index = tabSwiper.clickedIndex;

      const parentId = tabSwiper.slides[index].getAttribute("data-parent-id");

      tabSwiper.slides.forEach((slide) =>
        slide.classList.remove("swiper-slide-active"),
      );

      tabSwiper.slides[index].classList.add("swiper-slide-active");

      const subCategories = categories.filter(
        (c) => c.parent.toString() === parentId,
      );

      const itemsHTML = subCategories.map(
        (c, i) => `
       <a href="#" class="h-category__item">
          <img src="${c.img_url}" alt="" class="h-category__item-img" />
          <div class="h-category__item-text">
            <span>${c.name}</span>
          </div>
        </a>
       ${(i + 1) % 2 === 0 ? '<div class="h-category__line"></div>' : ""}
    `,
      );

      itemsEl.innerHTML = itemsHTML.join("");
    });

    tabSwiper.on("slideChange", function () {
      const index = tabSwiper.activeIndex;
      const parentId = tabSwiper.slides[index].getAttribute("data-parent-id");

      const subCategories = categories.filter(
        (c) => c.parent.toString() === parentId,
      );

      const itemsHTML = subCategories.map(
        (c, i) => `
       <a href="${c.link}" class="h-category__item">
          <img src="${c.img_url}" alt="" class="h-category__item-img" />
          <div class="h-category__item-text">
            <span>${c.name}</span>
          </div>
        </a>
        ${(i + 1) % 2 === 0 ? '<div class="h-category__line"></div>' : ""}
    `,
      );

      itemsEl.innerHTML = itemsHTML.join("");
    });
  }

  function initItemsSwiper() {
    if (window.innerWidth >= 639.98) {
      itemsSwiper = new Swiper(".h-category__items.swiper", {
        slidesPerView: "auto",
        grabCursor: true,
      });
      tabSwiper.controller.control = itemsSwiper;
      itemsSwiper.controller.control = tabSwiper;
    }
  }

  function handleResize() {
    if (window.innerWidth < 639.98 && itemsSwiper) {
      itemsSwiper.destroy(true, true);
      itemsSwiper = undefined;
    } else if (window.innerWidth >= 639.98 && !itemsSwiper) {
      initItemsSwiper();
    }
  }

  initItemsSwiper();
  window.addEventListener("resize", handleResize);
}
