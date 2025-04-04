function sectionDiscover() {
    const thumbSlide = new Swiper(".discover__thumb-swiper", {
        loop: true,
        spaceBetween: remToPixels(0.69),
        slidesPerView: 5,
        freeMode: true,
        watchSlidesProgress: true,
    });
    const mainSlide = new Swiper(".discover__swiper", {
        effect: "fade",
        loop: true,
        navigation: {
            nextEl: ".discover__swiper-next",
            prevEl: ".discover__swiper-prev",
        },
        thumbs: {
            swiper: thumbSlide,
        },
    });

    const discoverCategories = document.querySelectorAll(".discover__category");

    discoverCategories.forEach((category) => {
        const contentWrapper = category.querySelector(
            ".discover__category-description:not(.discover__category-description--inner)"
        );
        const content = category.querySelector(
            ".discover__category-description--inner"
        );
        category.addEventListener("mouseover", () => {
            contentWrapper.style.maxHeight = `${content.offsetHeight + 16}px`;
        });
        category.addEventListener("mouseleave", () => {
            contentWrapper.style.maxHeight = null;
        });
    });
}
export default sectionDiscover;
