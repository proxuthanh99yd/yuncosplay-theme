class CtaNav {
    constructor({ ctaNavItems, activeCtaNavEl }) {
        this.ctaNavItems = ctaNavItems;
        this.activeCtaNavEl = activeCtaNavEl;
        this.init();
        this.events();
    }
    init() {
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                this.handleInitActiveByScrollPosition();
            });
        });
    }
    handleClickCtaNavItem() {
        this.ctaNavItems?.forEach((ctaNavItem) => {
            ctaNavItem.addEventListener("click", () => {
                this.lockObserver = true;

                this.handleActiveCtaNavItem(ctaNavItem);
                this.handleScrollToTarget(ctaNavItem);

                setTimeout(() => {
                    this.lockObserver = false;
                }, 600);
            });
        });
    }
    handleActiveCtaNavItem(ctaNavItem) {
        if (ctaNavItem.classList.contains("active")) return;

        if (this.activeCtaNavEl && this.activeCtaNavEl.classList.contains("active")) {
            this.activeCtaNavEl.classList.remove("active");
        }
        ctaNavItem.classList.add("active");
        this.activeCtaNavEl = ctaNavItem;
    }
    handleScrollToTarget(ctaNavItem) {
        const navTargetId = ctaNavItem.getAttribute("data-nav-trigger");
        const targetEl = document.querySelector(`[data-nav-target="${navTargetId}"]`);
        if (!targetEl) return;

        // Convert 4.375rem to pixels
        const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
        const offsetRem = 8.5;
        const offsetPx = offsetRem * rootFontSize;
        // Sử dụng Lenis để scroll mượt nếu có
        const lenisInstance = window.app?.lenis;
        if (lenisInstance && typeof lenisInstance.scrollTo === "function") {
            lenisInstance.scrollTo(targetEl, {
                offset: -offsetPx, // Âm để scroll cách top một khoảng
                duration: 1.5,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            });
        } else {
            // Fallback: tính toán vị trí scroll với offset
            const elementTop = targetEl.getBoundingClientRect().top;
            const currentScrollY = window.scrollY || window.pageYOffset;
            const targetScrollY = currentScrollY + elementTop - offsetPx;

            window.scrollTo({
                top: targetScrollY,
                behavior: "smooth",
            });
        }
    }
    handleObserveActiveCtaNavItem() {
        if (!this.ctaNavItems || !this.ctaNavItems.length) return;

        const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
        const offsetPx = 8.5 * rootFontSize;

        const observer = new IntersectionObserver(
            (entries) => {
                if (this.lockObserver) return;

                // chỉ lấy entries đang intersect
                const visibleEntries = entries.filter((e) => e.isIntersecting);
                if (!visibleEntries.length) return;

                // chọn section có top gần offset nhất
                let bestEntry = null;
                let bestDistance = Infinity;

                visibleEntries.forEach((entry) => {
                    const top = entry.boundingClientRect.top;
                    const distance = Math.abs(top - offsetPx);

                    if (distance < bestDistance) {
                        bestDistance = distance;
                        bestEntry = entry;
                    }
                });

                if (!bestEntry) return;

                const targetId = bestEntry.target.getAttribute("data-nav-target");
                if (!targetId) return;

                const matchedNavItem = Array.from(this.ctaNavItems).find((item) => item.getAttribute("data-nav-trigger") === targetId);

                if (matchedNavItem) {
                    this.handleActiveCtaNavItem(matchedNavItem);
                }
            },
            {
                root: null,
                rootMargin: `-${offsetPx}px 0px -50% 0px`,
                threshold: [0, 0.25, 0.5],
            },
        );

        // observe target
        this.ctaNavItems.forEach((ctaNavItem) => {
            const id = ctaNavItem.getAttribute("data-nav-trigger");
            const targetEl = id ? document.querySelector(`[data-nav-target="${id}"]`) : null;
            if (targetEl) observer.observe(targetEl);
        });

        this.ctaNavObserver = observer;
    }
    handleInitActiveByScrollPosition() {
        if (!this.ctaNavItems || !this.ctaNavItems.length) return;

        const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
        const offsetPx = 8.5 * rootFontSize;

        const targets = Array.from(this.ctaNavItems)
            .map((item) => {
                const id = item.getAttribute("data-nav-trigger");
                const el = id ? document.querySelector(`[data-nav-target="${id}"]`) : null;
                return { item, el };
            })
            .filter((x) => x.el);

        if (!targets.length) return;

        // "active" = section có top <= offset và gần nhất so với offset
        let best = null;
        let bestDist = Infinity;

        targets.forEach(({ item, el }) => {
            const top = el.getBoundingClientRect().top;
            const dist = Math.abs(top - offsetPx);

            if (top <= offsetPx && dist < bestDist) {
                best = item;
                bestDist = dist;
            }
        });

        // Nếu đang ở trên cùng trang (chưa có cái nào top <= offset) thì chọn section đầu
        if (!best) best = targets[0].item;

        this.handleActiveCtaNavItem(best);
    }
    events() {
        this.handleClickCtaNavItem();
        this.handleObserveActiveCtaNavItem();
    }
}

export const initCtaNav = () => {
    const ctaNavItemEls = document.querySelectorAll(".cta-nav-item");
    const activeCtaNavEl = document.querySelector(".cta-nav-item.active");
    const ctaNav = new CtaNav({
        ctaNavItems: ctaNavItemEls,
        activeCtaNavEl: activeCtaNavEl,
    });
};
