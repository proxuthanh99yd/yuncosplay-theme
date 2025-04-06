function sectionExpertsLocaux() {
    var swipernExpertsLocaux = new Swiper(".expertsLocauxSwiper", {
        // Optional parameters
        slidesPerView: 5,
        spaceBetween: 24,
        navigation: {
          nextEl: ".expertsLocauxSwiper-next",
          prevEl: ".expertsLocauxSwiper-prev",
        },
        pagination: {
          el: ".expertsLocauxSwiper-pagination",
          clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1.4,
                spaceBetween: 16,
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 24,
            },

        },
      });
}
export default sectionExpertsLocaux;