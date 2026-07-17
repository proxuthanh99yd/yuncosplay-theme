document.addEventListener("DOMContentLoaded", function () {
  const nextEl = document.querySelector(".related-next");
  const prevEl = document.querySelector(".related-prev");
  const navigation = nextEl && prevEl ? { nextEl, prevEl } : undefined;

  const relatedSlider = new Swiper(".related-news-slider", {
    slidesPerView: "auto", // Mặc định hiện 4 bài trên PC
    spaceBetween: 24, // Khoảng cách giữa các bài (giống gap cũ)
    loop: true, // Lặp lại slide
    // autoplay: {
    //     delay: 5000,
    //     disableOnInteraction: false,
    // },
    ...(navigation ? { navigation } : {}),
    // Tối ưu cho Mobile & Tablet
    breakpoints: {
      320: {
        slidesPerView: "auto",
        spaceBetween: 16,
      },
      768: {
        slidesPerView: "auto",
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: "auto",
        spaceBetween: 24,
      },
    },
  });
});
