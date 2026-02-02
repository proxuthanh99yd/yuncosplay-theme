import { initCtaNav } from "./cta-nav-scripts.js";

export function sectionTourOverviewScripts() {
    initCtaNav();
    initTourOverviewMobileTabs();
}

export function initTourOverviewMobileTabs() {
    const mq = window.matchMedia("(max-width: 639.98px)");
    if (!mq.matches) return;

    const content = document.querySelector(".tour-overview__content");
    const left = document.querySelector(".tour-overview__left");
    const mid = document.querySelector(".tour-overview__mid");
    console.log({});
    if (!content || !left || !mid) return;

    const btnLeft = document.querySelector(".tour-overview__mobile-tab--left");
    const btnMid = document.querySelector(".tour-overview__mobile-tab--right");

    console.log({ btnLeft, btnMid });

    const setActive = (type) => {
        const isLeft = type === "left";

        btnLeft.classList.toggle("is-active", isLeft);
        btnMid.classList.toggle("is-active", !isLeft);

        left.classList.toggle("is-hidden", !isLeft);
        mid.classList.toggle("is-hidden", isLeft);
    };

    setActive("left");

    if (btnLeft && btnMid) {
        btnLeft?.addEventListener("click", () => setActive("left"));
        btnMid?.addEventListener("click", () => setActive("mid"));
    }
}
