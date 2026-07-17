// remToPixels: dùng bản global của assets/js/utils.js (core load trước page).
// Bản copy local ở đây đã bỏ — xem ghi chú ở components/section-events.

function sectionProductsScripts() {
  const SELECTORS = {
    PRODUCTS_SECTION: "#products",
    PRODUCT_ITEMS: ".product",
    FOOTER_LINK: ".h-products__footer-link", // thêm selector
  };

  let ctx; // dùng để cleanup GSAP context

  const initProductsAnimation = () => {
    // cleanup trước khi init lại (quan trọng)
    if (ctx) ctx.revert();

    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
      console.warn("GSAP or ScrollTrigger not available");
      return;
    }

    const section = document.querySelector(SELECTORS.PRODUCTS_SECTION);
    if (!section) return;

    const items = gsap.utils.toArray(
      section.querySelectorAll(SELECTORS.PRODUCT_ITEMS),
    );

    const footerLink = section.querySelector(SELECTORS.FOOTER_LINK);

    const isMobile = window.innerWidth < 640;

    ctx = gsap.context(() => {
      // ===== MOBILE =====
      if (isMobile) {
        gsap.set([items, footerLink], {
          clearProps: "all",
        });
        return;
      }

      // ===== INITIAL STATE =====
      gsap.set(items, {
        autoAlpha: 0,
        y: remToPixels(21.87),
      });

      if (footerLink) {
        gsap.set(footerLink, {
          autoAlpha: 0,
          y: remToPixels(6),
        });
      }

      // ===== GROUP ROW =====
      const rows = {};

      items.forEach((item) => {
        const top = Math.round(item.offsetTop);
        if (!rows[top]) rows[top] = [];
        rows[top].push(item);
      });

      const rowsArray = Object.values(rows);

      // ===== ANIMATE ROWS =====
      rowsArray.forEach((rowItems) => {
        gsap.to(rowItems, {
          autoAlpha: 1,
          y: 0,
          duration: 1.2,
          stagger: {
            each: 0.2,
          },
          ease: "power3.out",
          scrollTrigger: {
            trigger: rowItems[0],
            start: "top 95%",
            once: true,
          },
        });
      });

      // ===== FOOTER BUTTON (COI NHƯ 1 ROW RIÊNG) =====
      if (footerLink) {
        gsap.to(footerLink, {
          autoAlpha: 1,
          y: 0,
          duration: 0.8,
          ease: "power2.out",
          scrollTrigger: {
            trigger: footerLink,
            start: "top 85%",
            once: true,
          },
        });
      }
    }, section);
  };

  // ===== INIT =====
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initProductsAnimation);
  } else {
    initProductsAnimation();
  }

  // ===== RESIZE (debounce + refresh đúng cách) =====
  let resizeTimeout;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      initProductsAnimation();
      ScrollTrigger.refresh(); // cực kỳ quan trọng
    }, 250);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  sectionProductsScripts();
});
