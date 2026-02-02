export function initDetailedItinerary() {
    const btnExpandAll = document.querySelector(".itinerary-actions [data-action='expand-all']");
    const btnCloseAll = document.querySelector(".itinerary-actions [data-action='close-all']");
    const itineraryItemEls = document.querySelectorAll(".itinerary-item");
    const isMobile = window.innerWidth < 640;

    btnExpandAll?.addEventListener("click", () => {
        itineraryItemEls.forEach((itemEl) => {
            toggleAccordionItem({
                itemEl,
                isOpen: true,
                openClass: "itinerary-item--open",
                contentSelector: ".itinerary-content",
            });
        });
    });

    btnCloseAll?.addEventListener("click", () => {
        itineraryItemEls.forEach((itemEl) => {
            toggleAccordionItem({
                itemEl,
                isOpen: false,
                openClass: "itinerary-item--open",
                contentSelector: ".itinerary-content",
            });
        });
    });

    if (itineraryItemEls.length) {
        itineraryItemEls.forEach((itemEl) => {
            const toggleItineraryBtn = itemEl.querySelector(".itinerary-toggle");
            if (!toggleItineraryBtn) return;
            toggleItineraryBtn.addEventListener("click", () => {
                const isOpen = itemEl.classList.contains("itinerary-item--open");
                toggleAccordionItem({
                    itemEl,
                    isOpen: !isOpen,
                    openClass: "itinerary-item--open",
                    contentSelector: ".itinerary-content",
                });
            });
        });
    }

    const hotelGalleryEls = document.querySelectorAll("[data-itinerary-gallery]");
    if (hotelGalleryEls.length) {
        hotelGalleryEls.forEach((galleryEl) => {
            const hotelItineraryGallerySwiper = galleryEl.querySelector(".hotel-gallery-swiper");
            if (!hotelItineraryGallerySwiper) return;
            const btnPrev = galleryEl.querySelector(".itinerary-nav-btn--prev");
            const btnNext = galleryEl.querySelector(".itinerary-nav-btn--next");
            const itineraryGallerySwiper = new Swiper(hotelItineraryGallerySwiper, {
                slidesPerView: isMobile ? 1 : 2,
                spaceBetween: remToPixels(1.25) || 20,
                loop: false,
                grabCursor: true,
                navigation: {
                    nextEl: btnNext,
                    prevEl: btnPrev,
                },
            });
        });
    }
}

export function initAccommodationOption() {
    const btnExpandAll = document.querySelector(".acc-actions [data-action='expand-all']");
    const btnCloseAll = document.querySelector(".acc-actions [data-action='close-all']");
    const accommodationItemEls = document.querySelectorAll(".acc-item");

    btnExpandAll?.addEventListener("click", () => {
        accommodationItemEls.forEach((itemEl) => {
            toggleAccordionItem({
                itemEl,
                isOpen: true,
                openClass: "acc-item--open",
                contentSelector: ".acc-panel",
            });
        });
    });

    btnCloseAll?.addEventListener("click", () => {
        accommodationItemEls.forEach((itemEl) => {
            toggleAccordionItem({
                itemEl,
                isOpen: false,
                openClass: "acc-item--open",
                contentSelector: ".acc-panel",
            });
        });
    });

    if (accommodationItemEls.length) {
        accommodationItemEls.forEach((itemEl) => {
            const toggleAccommodationBtn = itemEl.querySelector(".acc-toggle");
            if (!toggleAccommodationBtn) return;
            toggleAccommodationBtn.addEventListener("click", () => {
                const isOpen = itemEl.classList.contains("acc-item--open");
                toggleAccordionItem({
                    itemEl,
                    isOpen: !isOpen,
                    openClass: "acc-item--open",
                    contentSelector: ".acc-panel",
                });
            });
        });
    }
}

function toggleAccordionItem({ itemEl, isOpen, openClass, contentSelector }) {
    if (!itemEl) return;

    const contentEl = itemEl.querySelector(contentSelector);
    if (!contentEl) return;

    if (isOpen) {
        itemEl.classList.add(openClass);

        contentEl.style.height = contentEl.scrollHeight + "px";
        contentEl.style.opacity = "1";
    } else {
        itemEl.classList.remove(openClass);

        contentEl.style.height = contentEl.scrollHeight + "px";
        contentEl.offsetHeight; // force reflow
        contentEl.style.height = "0px";
        contentEl.style.opacity = "0";
    }
}
