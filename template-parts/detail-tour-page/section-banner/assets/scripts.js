export function sectionBannerScripts() {
    const discoverBtnEl = document.querySelector(".banner-discover-btn");

    function handleClickDiscoverBtn() {
        // Tìm section banner
        const bannerSection = document.getElementById("banner");
        if (!bannerSection) {
            console.warn("Banner section not found");
            return;
        }

        // Tìm section tiếp theo sau banner
        let nextSection = bannerSection.nextElementSibling;

        // Nếu không có next sibling, tìm section đầu tiên sau banner trong DOM
        if (!nextSection || nextSection.tagName !== "SECTION") {
            const allSections = document.querySelectorAll("section");
            const bannerIndex = Array.from(allSections).indexOf(bannerSection);
            if (bannerIndex !== -1 && bannerIndex < allSections.length - 1) {
                nextSection = allSections[bannerIndex + 1];
            }
        }

        if (!nextSection) {
            console.warn("Next section not found");
            return;
        }

        // Convert 4.375rem to pixels
        const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
        const offsetRem = 4.375;
        const offsetPx = offsetRem * rootFontSize;

        // Sử dụng Lenis để scroll mượt nếu có
        const lenisInstance = window.app?.lenis;
        if (lenisInstance && typeof lenisInstance.scrollTo === "function") {
            lenisInstance.scrollTo(nextSection, {
                offset: -offsetPx, // Âm để scroll cách top một khoảng
                duration: 1.5,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            });
        } else {
            // Fallback: tính toán vị trí scroll với offset
            const elementTop = nextSection.getBoundingClientRect().top;
            const currentScrollY = window.scrollY || window.pageYOffset;
            const targetScrollY = currentScrollY + elementTop - offsetPx;

            window.scrollTo({
                top: targetScrollY,
                behavior: "smooth",
            });
        }
    }

    if (discoverBtnEl) {
        discoverBtnEl.addEventListener("click", handleClickDiscoverBtn)
    }
}