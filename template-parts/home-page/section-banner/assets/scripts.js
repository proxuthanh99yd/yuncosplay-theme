class SectionBanner {
  constructor() {
    this.endpointApiSearch = "/wp-json/api/v1/search";
    this.searchWrapperEl = document.getElementById("banner-search-container");
    this.searchInputEl = document.getElementById("banner-search-input");
    this.searchClearBtnEl = document.getElementById("banner-search-clear");
    this.searchResultEl = document.getElementById("banner-search-result");
    this.searchResultTemplate = document.getElementById("banner-search-result-item");
    this.discoverBtnEl = document.querySelector(".banner-discover-btn");

    this.init();
    this.events();
  }
  init() {
    this.searchDebounceTimer = null;
    this.isSearching = false;
    
    this.swiperWrapperEl = document.querySelector(".banner-swiper .swiper-wrapper");
    this.swiperWrapperOriginalZIndex = this.swiperWrapperEl ? this.swiperWrapperEl.style.zIndex : "";
    this.swiperWrapperResetTimer = null;
  }
  
  isMobile() {
    return window.innerWidth <= 639.98;
  }
  
  setSwiperWrapperZIndexActive() {
    if (!this.swiperWrapperEl || !this.isMobile()) return;
    if (this.swiperWrapperResetTimer) {
      clearTimeout(this.swiperWrapperResetTimer);
      this.swiperWrapperResetTimer = null;
    }
    this.swiperWrapperEl.style.zIndex = "6";
  }
  
  scheduleResetSwiperWrapperZIndex(delayMs = 200) {
    if (!this.swiperWrapperEl || !this.isMobile()) return;
    if (this.swiperWrapperResetTimer) {
      clearTimeout(this.swiperWrapperResetTimer);
    }
    this.swiperWrapperResetTimer = setTimeout(() => {
      this.swiperWrapperEl.style.zIndex = this.swiperWrapperOriginalZIndex;
      this.swiperWrapperResetTimer = null;
    }, delayMs);
  }
  
  handleClearSearchInput() {
    if (!this.searchInputEl || !this.searchClearBtnEl) return;
    this.searchClearBtnEl.addEventListener("click", () => {
      this.searchInputEl.value = "";
      this.clearSearchResults();
    });
  }
  handleFocusSearchInput() {
    if (!this.searchInputEl || !this.searchResultEl) return;
    const activate = () => {
      if (this.searchWrapperEl.classList.contains("active")) return;
      this.searchWrapperEl.classList.add("active");
      this.setSwiperWrapperZIndexActive();
    };
    this.searchInputEl.addEventListener("focus", activate);
    this.searchInputEl.addEventListener("click", activate);
  }
  async searchAPI(query) {
    try {
      this.isSearching = true;

      // Nếu query rỗng hoặc quá ngắn, trả về empty
      if (!query || query.trim().length < 2) {
        this.isSearching = false;
        return [];
      }

      // Gọi API search
      const response = await fetch(
        `${this.endpointApiSearch}?keyword=${encodeURIComponent(query.trim())}`,
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      if (!response.ok) {
        throw new Error(`Search failed: ${response.status}`);
      }

      const data = await response.json();

      // Kiểm tra response có success và data không
      if (data.success && Array.isArray(data.data)) {
        this.isSearching = false;
        return data.data;
      }

      this.isSearching = false;
      return [];
    } catch (error) {
      console.error("Search API error:", error);
      this.isSearching = false;
      return [];
    }
  }

  renderSearchResults(results) {
    if (!this.searchResultEl || !this.searchResultTemplate) {
      console.warn("Search result elements not found");
      return;
    }
    
    this.setSwiperWrapperZIndexActive();

    // Clear existing results
    this.searchResultEl.innerHTML = "";
    this.searchResultEl.removeAttribute("data-empty");

    if (results.length === 0) {
      // Hiển thị "No results found" khi không có kết quả
      const template = this.searchResultTemplate.content.cloneNode(true);
      const span = template.querySelector(".banner-swiper-slide__content__search-result__item__text");
      if (span) {
        span.textContent = "No results found";
      }
      this.searchResultEl.appendChild(template);
      this.searchResultEl.setAttribute("data-empty", "true");
      return;
    }

    // Render results sử dụng template
    results.forEach((result) => {
      const template = this.searchResultTemplate.content.cloneNode(true);
      const span = template.querySelector(".banner-swiper-slide__content__search-result__item__text");
      if (span) {
        span.textContent = result.title || "";
      }
      const li = template.querySelector(".banner-swiper-slide__content__search-result__item");
      if (li) {
        // Thêm event listener để navigate đến URL khi click
        li.addEventListener("click", () => {
          if (result.url) {
            window.location.href = result.url;
          }
        });
      }
      this.searchResultEl.appendChild(template);
    });
  }

  clearSearchResults() {
    if (!this.searchResultEl) return;
    this.searchResultEl.innerHTML = "";
    this.scheduleResetSwiperWrapperZIndex();
  }

  handleChangeSearchInput() {
    if (!this.searchInputEl) return;

    this.searchInputEl.addEventListener("input", (e) => {
      const query = e.target.value.trim();

      // Clear previous debounce timer
      if (this.searchDebounceTimer) {
        clearTimeout(this.searchDebounceTimer);
      }

      // Nếu query rỗng, clear results ngay
      if (!query || query.length < 2) {
        this.clearSearchResults();
        return;
      }

      // Debounce 300ms trước khi call API
      this.searchDebounceTimer = setTimeout(async () => {
        const results = await this.searchAPI(query);
        this.renderSearchResults(results);
      }, 300);
    });
  }
  handleBlurSearchInput() {
    if (!this.searchWrapperEl || !this.searchResultEl) return;

    document.addEventListener("click", (e) => {
      // click ra ngoài searchWrapper
      if (!this.searchWrapperEl.contains(e.target)) {
        this.searchWrapperEl.classList.remove("active");
        this.scheduleResetSwiperWrapperZIndex();
      }
    });
  }
  handleDiscoverButtonClick() {
    if (!this.discoverBtnEl) return;

    this.discoverBtnEl.addEventListener("click", () => {
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
    });
  }
  events() {
    this.handleFocusSearchInput();
    this.handleBlurSearchInput();
    this.handleClearSearchInput();
    this.handleChangeSearchInput();
    this.handleDiscoverButtonClick();
  }
}

export function sectionBannerScripts() {
  const bannerSwiperEl = document.querySelector(".banner-swiper");
  if (!bannerSwiperEl || typeof Swiper === "undefined") return;

  const bannerSwiper = new Swiper(bannerSwiperEl, {
    speed: 750,
    spaceBetween: 0,
    slidesPerView: 1,
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
    autoplay: {
      delay: 7000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    loop: true,
    navigation: {
      nextEl: ".banner-swiper-button-next",
      prevEl: ".banner-swiper-button-prev",
    },
    pagination: {
      el: ".banner-swiper-pagination",
      clickable: true,
      renderBullet: function (index, className) {
        if (index === 0) {
          return `
            <span class="${className} banner-swiper-pagination-bullet banner-swiper-pagination-bullet--search">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M9.58268 17.5001C13.9549 17.5001 17.4993 13.9557 17.4993 9.58341C17.4993 5.21116 13.9549 1.66675 9.58268 1.66675C5.21043 1.66675 1.66602 5.21116 1.66602 9.58341C1.66602 13.9557 5.21043 17.5001 9.58268 17.5001Z" stroke="currentColor" stroke-opacity="0.6" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18.3327 18.3334L16.666 16.6667" stroke="currentColor" stroke-opacity="0.6" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          `;
        }
        return `<span class="${className} banner-swiper-pagination-bullet"></span>`;
      },
    },
  });

  // Pause autoplay while user is focusing any input inside banner (avoid flicker when tabbing)
  const bannerFocusableSelector = 'input, textarea, select';

  const stopAutoplayIfAvailable = () => {
    if (bannerSwiper?.autoplay?.running) {
      bannerSwiper.autoplay.stop();
    }
  };

  const startAutoplayIfAvailable = () => {
    if (bannerSwiper?.params?.autoplay && bannerSwiper?.autoplay && !bannerSwiper.autoplay.running) {
      bannerSwiper.autoplay.start();
    }
  };

  bannerSwiperEl.addEventListener('focusin', (e) => {
    const target = e.target;
    if (target && target.matches && target.matches(bannerFocusableSelector)) {
      stopAutoplayIfAvailable();
    }
  });

  bannerSwiperEl.addEventListener('focusout', () => {
    // Wait one tick so document.activeElement is updated.
    setTimeout(() => {
      const active = document.activeElement;
      if (active && bannerSwiperEl.contains(active) && active.matches && active.matches(bannerFocusableSelector)) {
        return;
      }
      startAutoplayIfAvailable();
    }, 0);
  });   

  // Handle content fade/slide per direction (support loop)
  const realSlides = Array.from(bannerSwiperEl.querySelectorAll(".banner-swiper-slide:not(.swiper-slide-duplicate)"));
  const contents = realSlides.map((slide) => slide.querySelector(".banner-swiper-slide__content")).filter(Boolean);

  const totalSlides = realSlides.length;
  let lastRealIndex = bannerSwiper.realIndex || 0;

  const updatePaginationVisibility = () => {
    const isFirst = bannerSwiper.realIndex === 0;
    if (isFirst) {
      bannerSwiperEl.classList.add("banner-swiper--hide-pagination");
    } else {
      bannerSwiperEl.classList.remove("banner-swiper--hide-pagination");
    }
  };

  const resetContentState = () => {
    contents.forEach((content) => {
      content.classList.remove(
        "banner-content--active",
        "banner-content--enter-from-right",
        "banner-content--enter-from-left",
        "banner-content--leave-to-left",
        "banner-content--leave-to-right"
      );
      content.classList.add("banner-content--hidden");
    });
  };

  const setInitialActiveContent = () => {
    resetContentState();

    const activeContent = contents[bannerSwiper.realIndex];
    if (!activeContent) return;

    activeContent.classList.remove("banner-content--hidden");
    activeContent.classList.add("banner-content--active");

    updatePaginationVisibility();
  };

  setInitialActiveContent();

  bannerSwiper.on("slideChangeTransitionStart", (swiper) => {
    const currentRealIndex = swiper.realIndex;
    const prevRealIndex = lastRealIndex;

    // Tính hướng di chuyển dựa trên realIndex để không bị sai khi loop
    if (currentRealIndex === prevRealIndex) {
      return;
    }

    const diff = currentRealIndex - prevRealIndex;
    let isNext;

    if (diff === 1 || diff === -(totalSlides - 1)) {
      // Đi tới slide tiếp theo (kể cả case từ cuối về đầu)
      isNext = true;
    } else if (diff === -1 || diff === totalSlides - 1) {
      // Đi lùi (kể cả case từ đầu về cuối)
      isNext = false;
    } else {
      isNext = diff > 0;
    }

    const prevContent = contents[prevRealIndex] !== undefined ? contents[prevRealIndex] : null;
    const activeContent = contents[currentRealIndex] !== undefined ? contents[currentRealIndex] : null;
    if (!activeContent) return;

    // Ẩn các content khác, trừ current (prev) và next (active)
    contents.forEach((content) => {
      if (content === prevContent || content === activeContent) return;
      content.classList.remove(
        "banner-content--active",
        "banner-content--enter-from-right",
        "banner-content--enter-from-left",
        "banner-content--leave-to-left",
        "banner-content--leave-to-right"
      );
      content.classList.add("banner-content--hidden");
    });

    // Animate out content của slide cũ
    if (prevContent) {
      prevContent.classList.remove(
        "banner-content--hidden",
        "banner-content--enter-from-right",
        "banner-content--enter-from-left",
        "banner-content--leave-to-left",
        "banner-content--leave-to-right"
      );
      prevContent.classList.add("banner-content--active");

      const leaveClass = isNext ? "banner-content--leave-to-left" : "banner-content--leave-to-right";
      prevContent.classList.add(leaveClass);

      const handleAnimationEnd = () => {
        prevContent.classList.remove(
          "banner-content--active",
          "banner-content--leave-to-left",
          "banner-content--leave-to-right"
        );
        prevContent.classList.add("banner-content--hidden");
        prevContent.removeEventListener("animationend", handleAnimationEnd);
      };

      prevContent.addEventListener("animationend", handleAnimationEnd);
    }

    // Animate in content của slide mới
    activeContent.classList.remove(
      "banner-content--hidden",
      "banner-content--enter-from-right",
      "banner-content--enter-from-left",
      "banner-content--leave-to-left",
      "banner-content--leave-to-right"
    );
    activeContent.classList.add("banner-content--active");
    activeContent.classList.add(isNext ? "banner-content--enter-from-right" : "banner-content--enter-from-left");

    lastRealIndex = currentRealIndex;
    updatePaginationVisibility();
  });

  const sectionBanner = new SectionBanner();
}
