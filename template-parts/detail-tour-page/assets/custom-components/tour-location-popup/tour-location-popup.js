export function initTourLocationPopup({
    popupId = "tourLocationPopup",
    triggerSelector = ".js-tour-location-trigger",
    getIdFromTrigger = (trigger) => trigger.dataset.locationId,
    loadDataById,
    ANIM_MS = 320,
} = {}) {
    const popup = document.getElementById(popupId);
    if (!popup) return;

    const heroImg = popup.querySelector(".tour-location-popup__hero-img");
    const titleEl = popup.querySelector(".tour-location-popup__title");
    const descEl = popup.querySelector(".tour-location-popup__desc");
    const thumbsTrack = popup.querySelector(
        ".tour-location-popup__thumbs-track"
    );

    const enableDragScroll = (el) => {
        if (!el) return;

        let isDown = false;
        let startX = 0;
        let scrollLeft = 0;
        let moved = false;

        const getX = (e) => (e.touches ? e.touches[0].pageX : e.pageX);

        const onDown = (e) => {
            isDown = true;
            moved = false;
            el.classList.add("is-dragging");
            startX = getX(e);
            scrollLeft = el.scrollLeft;
        };

        const onMove = (e) => {
            if (!isDown) return;

            const x = getX(e);
            const walk = (x - startX) * 1.15;
            if (Math.abs(walk) > 3) moved = true;

            if (e.cancelable) e.preventDefault();

            el.scrollLeft = scrollLeft - walk;
        };

        const onUp = () => {
            isDown = false;
            el.classList.remove("is-dragging");
        };

        el.addEventListener("mousedown", onDown);
        window.addEventListener("mousemove", onMove);
        window.addEventListener("mouseup", onUp);

        el.addEventListener("touchstart", onDown, { passive: true });
        el.addEventListener("touchmove", onMove, { passive: false });
        el.addEventListener("touchend", onUp);

        el.addEventListener(
            "click",
            (e) => {
                if (moved) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                moved = false;
            },
            true
        );
    };

    enableDragScroll(thumbsTrack);

    const renderThumbs = (thumbs, activeSrc) => {
        if (!thumbsTrack) return;

        const finalThumbs =
            Array.isArray(thumbs) && thumbs.length ? thumbs : [activeSrc];

        thumbsTrack.innerHTML = "";

        finalThumbs.forEach((src) => {
            const el = document.createElement("div");
            el.className =
                "tour-location-popup__thumb" +
                (src === activeSrc ? " is-active" : "");

            el.innerHTML = `<img draggable="false" src="${src}" alt="">`;

            el.addEventListener("click", () => {
                thumbsTrack
                    .querySelectorAll(".tour-location-popup__thumb")
                    .forEach((x) => x.classList.remove("is-active"));

                el.classList.add("is-active");
                if (heroImg) heroImg.src = src;
            });

            thumbsTrack.appendChild(el);
        });
    };

    const lockBodyScroll = () => {
        const scrollbarWidth =
            window.innerWidth - document.documentElement.clientWidth;

        document.body.style.overflow = "hidden";
        if (scrollbarWidth > 0) {
            document.body.style.paddingRight = `${scrollbarWidth}px`;
        }
    };

    const unlockBodyScroll = () => {
        document.body.style.overflow = "";
        document.body.style.paddingRight = "";
    };

    const openPopup = ({ title = "", desc = "", image = "", thumbs = [] }) => {
        if (heroImg) {
            heroImg.src = image || "";
            heroImg.alt = title || "";
        }
        if (titleEl) titleEl.textContent = title || "";
        if (descEl) descEl.textContent = desc || "";

        renderThumbs(thumbs, image);

        popup.classList.remove("is-closing");
        popup.classList.add("is-open");
        popup.setAttribute("aria-hidden", "false");

        lockBodyScroll();
    };

    const closePopup = () => {
        if (!popup.classList.contains("is-open")) return;

        popup.classList.remove("is-open");
        popup.classList.add("is-closing");
        popup.setAttribute("aria-hidden", "true");

        unlockBodyScroll();

        window.setTimeout(() => {
            popup.classList.remove("is-closing");
        }, ANIM_MS);
    };

    document.addEventListener("click", async (e) => {
        const trigger = e.target.closest(triggerSelector);
        if (!trigger) return;

        const id = getIdFromTrigger(trigger);
        if (!id) return;

        if (typeof loadDataById !== "function") return;

        const data = await loadDataById(id);
        if (!data) return;

        openPopup(data);
    });

    popup.addEventListener("click", (e) => {
        const closeEl = e.target.closest("[data-popup-close]");
        if (!closeEl) return;
        closePopup();
    });

    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closePopup();
    });

    document.addEventListener("keydown", (e) => {
        const el = document.activeElement;
        if (!el || !el.matches?.(triggerSelector)) return;

        if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            el.click();
        }
    });
}
