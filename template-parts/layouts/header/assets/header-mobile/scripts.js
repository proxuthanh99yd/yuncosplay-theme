
class HeaderMobile {
  constructor() {
    // Header Menu Elements
    this.headerMenuBtnEl = document.querySelector(".header-main__menu-btn");
    this.headerMenuContentEl = document.querySelector(".header-menu");
    this.headerMenuCloseBtnEl = document.querySelector(".header-menu__header-close-btn",);

    // Header Service Elements
    this.headerServiceContentEl = document.querySelector(".header-service");
    this.headerServiceBtnOpenEl = document.querySelector(".header-menu__nav-item__link[data-trigger='header-service']",);
    this.headerServiceBtnCloseEls = document.querySelectorAll("[data-close='header-service']",);
    this.headerServiceBtnTriggerToggleEls = document.querySelectorAll(".header-service__service-item__header",);

    // Header Product Elements
    this.headerProductContentEl = document.querySelector(".header-product");
    this.headerProductBtnOpenEl = document.querySelector(".header-menu__nav-item__link[data-trigger='header-product']",);
    this.headerProductBtnCloseEls = document.querySelectorAll("[data-close='header-product']",);

    // Header main search (→ trang search với ?s=&post_type=)
    this.headerMainSearchWrapperEl = document.querySelector(".header-main__search-input-wrapper",);
    this.headerMainSearchInputEl = document.querySelector(".header-main__search-input",);
    this.headerMainSearchBtnEl = document.querySelector(".header-main__search-button",);

    // Init & Events
    this.init();
    this.events();
  }
  
  navigateHeaderMainSearch() {
    const wrap = this.headerMainSearchWrapperEl;
    const input = this.headerMainSearchInputEl;
    if (!wrap || !input) return;
    const base = wrap.dataset.searchBase || `${window.location.origin}/`;
    const postType = wrap.dataset.searchPostType || "product";
    const q = input.value.trim();
    const url = new URL(base);
    url.searchParams.set("s", q);
    url.searchParams.set("post_type", postType);
    window.location.assign(url.toString());
  }
  
  handleHeaderMainSearch() {
    const btn = this.headerMainSearchBtnEl;
    const input = this.headerMainSearchInputEl;
    if (btn) {
      btn.addEventListener("click", () => this.navigateHeaderMainSearch());
    }
    if (input) {
      input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
          e.preventDefault();
          this.navigateHeaderMainSearch();
        }
      });
    }
  }
  
  initOutstandingProductSwiper() {
    new Swiper(".header-menu__outstanding-product-swiper", {
      slidesPerView: 2,
      spaceBetween: remToPixels(0.375),
      pagination: {
        el: ".header-menu__outstanding-product-swiper-pagination",
        clickable: true,
      },
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      loop: true,
      speed: 500,
    });
  }
  
  initCategoryProductSwiper() {
    const parentSwiper = new Swiper(
      ".header-product__category-mul-level__parent-swiper",
      {
        slidesPerView: 2,
        spaceBetween: remToPixels(0.5),
        freeMode: true,
        navigation: {
          nextEl:
            ".header-product__category-mul-level__parent-swiper-button-nav--next",
          prevEl:
            ".header-product__category-mul-level__parent-swiper-button-nav--prev",
        },
        watchSlidesProgress: true,
      },
    );
    const childSwiper = new Swiper(
      ".header-product__category-mul-level__child-swiper",
      {
        autoHeight: true,
        slidesPerView: 1,
        spaceBetween: remToPixels(0.75),
        thumbs: {
          swiper: parentSwiper,
        },
      },
    );
  }
  
  handleClickMenuOpenBtn() {
    if (!this.headerMenuBtnEl || !this.headerMenuContentEl) return;
    this.headerMenuBtnEl.addEventListener("click", () => {
      this.headerMenuContentEl.classList.add("header-menu--active");
      window.app.disableScroll();
    });
  }
  
  handleClickMenuCloseBtn() {
    if (!this.headerMenuCloseBtnEl || !this.headerMenuContentEl) return;
    this.headerMenuCloseBtnEl.addEventListener("click", () => {
      this.headerMenuContentEl.classList.remove("header-menu--active");
      window.app.enableScroll();
    });
  }
  
  handleClickServiceBtnOpen() {
    if (!this.headerServiceBtnOpenEl) return;
    this.headerServiceBtnOpenEl.addEventListener("click", () => {
      this.headerServiceContentEl.classList.add("header-service--active");
      window.app.disableScroll();
    });
  }
  
  handleClickServiceBtnClose() {
    this.headerServiceBtnCloseEls.forEach((btn) => {
      btn.addEventListener("click", () => {
        this.headerServiceContentEl.classList.remove("header-service--active");
        window.app.enableScroll();
      });
    });
  }
  
  updateServiceItemHeights() {
    const items = document.querySelectorAll(".header-service__service-item");
    if (!items.length) return;
    items.forEach((item) => {
      const content = item.querySelector(
        ".header-service__service-item__content",
      );
      if (!content) return;
      if (item.classList.contains("header-service__service-item--active")) {
        // Set to auto height transition: first set to scrollHeight for smooth animation
        const fullHeight = content.scrollHeight;
        content.style.height = fullHeight + "px";
      } else {
        content.style.height = "0px";
      }
    });
  }
  
  initServiceAccordion() {
    this.updateServiceItemHeights();
  }
  
  handleClickServiceBtnTriggerToggle() {
    if (!this.headerServiceBtnTriggerToggleEls?.length) return;
    const container = document.querySelector(".header-service__service-list");
    if (!container) return;

    this.headerServiceBtnTriggerToggleEls.forEach((btn) => {
      btn.addEventListener("click", () => {
        const itemEl = btn.closest(".header-service__service-item");
        if (!itemEl) return;
        const isOpening = !itemEl.classList.contains(
          "header-service__service-item--active",
        );
        // Accordion: đóng tất cả item khác
        container
          .querySelectorAll(".header-service__service-item")
          .forEach((el) => {
            el.classList.remove("header-service__service-item--active");
          });
        if (isOpening) {
          itemEl.classList.add("header-service__service-item--active");
        }
        this.updateServiceItemHeights();
      });
    });
  }
  
  handleClickProductBtnOpen() {
    if (!this.headerProductBtnOpenEl) return;
    this.headerProductBtnOpenEl.addEventListener("click", () => {
      this.headerProductContentEl.classList.add("header-product--active");
      window.app.disableScroll();
    });
  }
  
  handleClickProductBtnClose() {
    this.headerProductBtnCloseEls.forEach((btn) => {
      btn.addEventListener("click", () => {
        this.headerProductContentEl.classList.remove("header-product--active");
        window.app.enableScroll();
      });
    });
  }
  
  init() {
    this.initOutstandingProductSwiper();
    this.initServiceAccordion();
    this.initCategoryProductSwiper();
  }
  
  events() {
    this.handleClickMenuOpenBtn();
    this.handleClickMenuCloseBtn();
    this.handleClickServiceBtnOpen();
    this.handleClickServiceBtnClose();
    this.handleClickServiceBtnTriggerToggle();
    this.handleClickProductBtnOpen();
    this.handleClickProductBtnClose();
    this.handleHeaderMainSearch();
  }
}

const headerMobileInit = () => {
  console.log("Call headerMobileInit");
   createAutoHideOnScroll({
    selector: ".header-main",
    hiddenClassName: "header-main--auto-hidden",
    hideOffsetPx: 80,
    directionThresholdPx: 8,
  });
  new HeaderMobile();
};
