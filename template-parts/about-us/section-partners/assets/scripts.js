const partnerSectionScripts = () => {
  const swiperEl = document.querySelector(".partner-section__slider");
  if (!swiperEl) return;

  const swiper = new Swiper(swiperEl, {
    slidesPerView: 1,
    loop: true,
    speed: 900,
    grabCursor: true,
    autoHeight: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
      pauseOnMouseEnter: false,
    },
    pagination: {
      el: ".partner-section__pagination",
      clickable: true,
      bulletClass: "partner-section__pagination-bullet",
      bulletActiveClass: "is-active",
      renderBullet: function renderBullet(index, className) {
        return (
          '<span class="' +
          className +
          '" aria-label="Go to partner slide ' +
          (index + 1) +
          '"></span>'
        );
      },
    },
  });
};

document.addEventListener("DOMContentLoaded", () => {
  partnerSectionScripts();
});
