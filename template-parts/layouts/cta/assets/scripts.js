/** Khớp với @media (max-width: 639.98px) trong cta/assets/styles.css */
const CTA_MOBILE_MEDIA_QUERY = "(max-width: 639.98px)";

function initCtaScrollTop() {
    const scrollTopBtnEl = document.querySelector(".cta-right__btn-scroll-top");
    if (!scrollTopBtnEl) return;

    const progressRectEl = scrollTopBtnEl.querySelector("rect[stroke]");
    if (!progressRectEl) return;

    const rectLength = progressRectEl.getTotalLength();
    progressRectEl.style.strokeDasharray = `${rectLength}`;
    progressRectEl.style.strokeDashoffset = `${rectLength}`;

    const updateScrollProgress = () => {
        const scrollTop = window.scrollY || document.documentElement.scrollTop || 0;
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = scrollHeight > 0 ? Math.min(scrollTop / scrollHeight, 1) : 0;
        const dashOffset = rectLength * (1 - progress);
        progressRectEl.style.strokeDashoffset = `${dashOffset}`;
    };

    let ticking = false;
    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(() => {
            updateScrollProgress();
            ticking = false;
        });
    };

    scrollTopBtnEl.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    });

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);
    updateScrollProgress();
}

function initServiceDrawerMobile() {
    const serviceBtnEl = document.querySelector(".cta-bottom__btn-service");
    const drawerEl = document.querySelector(".service-drawer-mobile");
    if (!serviceBtnEl || !drawerEl) return;

    const overlayEl = drawerEl.querySelector(".service-drawer-mobile__overlay");
    const closeBtnEl = drawerEl.querySelector(".service-drawer-mobile__button-close");
    const mobileMq = window.matchMedia(CTA_MOBILE_MEDIA_QUERY);

    const isMobile = () => mobileMq.matches;

    const openDrawer = () => {
        drawerEl.classList.add("service-drawer-mobile--active");
        document.body.style.overflow = "hidden";
        serviceBtnEl.setAttribute("aria-expanded", "true");
        if (window.app) { window.app.disableScroll(); }
    };

    const closeDrawer = () => {
        drawerEl.classList.remove("service-drawer-mobile--active");
        document.body.style.overflow = "";
        serviceBtnEl.setAttribute("aria-expanded", "false");
        if (window.app) { window.app.enableScroll(); }
    };

    if (!drawerEl.id) {
        drawerEl.id = "service-drawer-mobile";
    }
    serviceBtnEl.setAttribute("aria-expanded", "false");
    serviceBtnEl.setAttribute("aria-controls", drawerEl.id);

    serviceBtnEl.addEventListener("click", (e) => {
        if (!isMobile()) return;
        e.preventDefault();
        openDrawer();
    });

    overlayEl?.addEventListener("click", () => {
        if (drawerEl.classList.contains("service-drawer-mobile--active")) {
            closeDrawer();
        }
    });

    closeBtnEl?.addEventListener("click", () => {
        closeDrawer();
    });

    const onEscape = (e) => {
        if (e.key !== "Escape") return;
        if (!drawerEl.classList.contains("service-drawer-mobile--active")) return;
        closeDrawer();
    };
    document.addEventListener("keydown", onEscape);

    const onMqChange = () => {
        if (!isMobile() && drawerEl.classList.contains("service-drawer-mobile--active")) {
            closeDrawer();
        }
    };
    if (typeof mobileMq.addEventListener === "function") {
        mobileMq.addEventListener("change", onMqChange);
    } else {
        mobileMq.addListener(onMqChange);
    }
}

function initCtaScripts() {
    initCtaScrollTop();
    initServiceDrawerMobile();
}

document.addEventListener("DOMContentLoaded", () => {
    initCtaScripts();
});
