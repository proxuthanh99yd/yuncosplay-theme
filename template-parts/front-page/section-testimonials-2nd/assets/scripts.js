function sectionTestimonials2nd() {
    let extraConfig = {};
    if (window.innerWidth < 640) {
        extraConfig = {
            allowTouchMove: true,
        };
    }
    const testimonials2ndSwiper = new Swiper(".testimonials-2nd__swiper", {
        slidesPerView: 1,
        loop: true,
        allowTouchMove: false,
        effect: "fade",
        navigation: {
            nextEl: ".testimonials-2nd__swiper-button-next",
            prevEl: ".testimonials-2nd__swiper-button-prev",
        },
        pagination: {
            el: ".testimonials-2nd__swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 1,
            },
        },
        ...extraConfig,
    });

    const playersMapping = [];
    const players = Array.from(
        document.getElementsByClassName("testimonials-2nd__player")
    );
    playersMapping.push(new Plyr(players[0]));

    const testimonialTabs = Array.from(
        document.getElementsByClassName("testimonials-2nd__nav-item")
    );
    testimonials2ndSwiper.on("slideChange", function () {
        testimonialTabs.forEach((el) => el.classList.remove("active"));
        testimonialTabs[this.realIndex].classList.add("active");

        playersMapping.forEach((player) => player.pause());

        if (!playersMapping[this.realIndex]) {
            playersMapping.push(new Plyr(players[this.realIndex]));
        }
    });
    testimonialTabs.forEach((tab, index) => {
        tab.addEventListener("click", () => {
            testimonialTabs.forEach((el) => el.classList.remove("active"));
            tab.classList.add("active");
            testimonials2ndSwiper.slideToLoop(index);
        });
    });
}
export default sectionTestimonials2nd;
