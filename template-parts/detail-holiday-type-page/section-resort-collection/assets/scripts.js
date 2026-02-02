function formatNumber(n) {
  return n.toString().padStart(2, "0");
}

export function resortScripts() {
  const currentSlide = document.querySelector(".ht-resort_count .current");
  const totalSlide = document.querySelector(".ht-resort_count .total");

  // Thumbnails
  const thumbnailList = new Swiper(".ht-resort_thumbnails.swiper", {
    slidesPerView: 1,
    grabCursor: true,
  });

  // Content list
  const contentList = new Swiper(".ht-resort_list.swiper", {
    slidesPerView: 1,
    grabCursor: true,
    pagination: {
      el: ".ht-resort_list .swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".ht-resort .swiper-button-next",
      prevEl: ".ht-resort .swiper-button-prev",
    },
    on: {
      init(swiper) {
        // Set tổng slide
        if (totalSlide) {
          totalSlide.textContent = formatNumber(swiper.slides.length);
        }

        // Set slide hiện tại (bắt đầu từ 1)
        if (currentSlide) {
          currentSlide.textContent = formatNumber(swiper.realIndex + 1);
        }
      },
      slideChange(swiper) {
        // Update số slide hiện tại khi đổi slide
        if (currentSlide) {
          currentSlide.textContent = formatNumber(swiper.realIndex + 1);
        }
      },
    },
  });

  contentList.controller.control = thumbnailList;
  thumbnailList.controller.control = contentList;
}
