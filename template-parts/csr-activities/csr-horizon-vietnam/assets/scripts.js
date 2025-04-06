function CSRHorizonVietnam() {
  // CSR Horizon Vietnam specific JavaScript code goes here
    const swiperHorizonVnCSR = document.querySelector('.mySwiperCsr-activities_horizon-vietnam');
  if (swiperHorizonVnCSR) {

        var swiperHorizonVnCSRC = new Swiper(".mySwiperCsr-activities_horizon-vietnam", {
            navigation: {
              nextEl: ".swiperHorizonVnCSR-next",
            },
            slidesPerView: 1.1,
            spaceBetween: 16,
          });

    }
}
export default CSRHorizonVietnam;