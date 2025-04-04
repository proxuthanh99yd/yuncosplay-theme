function travelReviews() {
    const travelReviewSwipers = Array.from(
        document.getElementsByClassName("testimonial__item-swiper")
    );

    travelReviewSwipers.forEach((swiper, index) => {
        new Swiper(swiper, {
            effect: "fade",
            slidesPerView: 1,
            pagination: {
                el: `.testimonial__item-swiper-pagination--${index}`,
                clickable: true,
            },
            navigation: {
                nextEl: `.testimonial__item-swiper-next--${index}`,
                prevEl: `.testimonial__item-swiper-prev--${index}`,
            },
        });
    });

    const testimonialItems = Array.from(
        document.getElementsByClassName("testimonial__item-content")
    );
    const testimonialPopup = document.querySelector(".testimonial-popup");
    const testimonialPopupContent = testimonialPopup.querySelector(
        ".testimonial-popup__content"
    );
    const testimonialPopupTemplate = document.getElementById(
        "testimonial-popup__template"
    );
    const testimonialPopupClose = testimonialPopup.querySelectorAll(
        ".testimonial-popup__close"
    );
    const testimonialPopupOverlay = document.querySelector(
        ".testimonial-popup__overlay"
    );
    const testimonialPopupItemTemplate = document.getElementById(
        "testimonial-popup__item-template"
    );

    testimonialItems.forEach((item, index) => {
        item.addEventListener("click", () => {
            const items = Array.from({ length: 2 })
                .map((_, i) => {
                    return testimonialPopupItemTemplate.innerHTML
                        .replace(
                            /{{label_1}}/g,
                            "De : " + (index + 1) + (i + 1)
                        )
                        .replace(
                            /{{content_1}}/g,
                            "Québec - Canada " + (index + 1) + (i + 1)
                        )
                        .replace(
                            /{{label_2}}/g,
                            "Date de voyage : " + (index + 1) + (i + 1)
                        )
                        .replace(
                            /{{content_2}}/g,
                            "04/10/1997 " + (index + 1) + (i + 1)
                        );
                })
                .join("");

            const popupContent = testimonialPopupTemplate.innerHTML
                .replace(/{{title}}/g, "Testimonial " + (index + 1))
                .replace(/{{content}}/g, "Testimonial content" + (index + 1))
                .replace(/{{subtitle}}/g, "Testimonial subtitle" + (index + 1))
                .replace(/{{destination}}/g, items);

            testimonialPopupContent.innerHTML = popupContent;
            testimonialPopup.classList.add("active");
            document.body.classList.add("no-scroll");
        });
    });
    function closePopup() {
        testimonialPopup.classList.remove("active");
        document.body.classList.remove("no-scroll");
    }
    testimonialPopupClose.forEach((closeButton) => {
        closeButton.addEventListener("click", closePopup);
    });
    testimonialPopupOverlay.addEventListener("click", closePopup);
    window.addEventListener("keyup", (event) => {
        if (event.key === "Escape") {
            closePopup();
        }
    });
}
export default travelReviews;
