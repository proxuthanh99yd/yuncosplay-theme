class SectionBanner {
  constructor() {
    this.searchWrapperEl = document.getElementById("banner-search-container");
    this.searchInputEl = document.getElementById("banner-search-input");
    this.searchClearBtnEl = document.getElementById("banner-search-clear");
    this.searchResultEl = document.getElementById("banner-search-result");
    this.searchResultTemplate = document.getElementById(
      "banner-search-result-item"
    );

    this.init();
    this.events();
  }
  init() {
    this.searchDebounceTimer = null;
    this.isSearching = false;
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
    };
    this.searchInputEl.addEventListener("focus", activate);
    this.searchInputEl.addEventListener("click", activate);
  }
  async searchAPI(query) {
    // Demo API call - thay thế bằng API thật sau
    try {
      this.isSearching = true;

      // Simulate API delay
      await new Promise((resolve) => setTimeout(resolve, 500));

      // Nếu query rỗng hoặc quá ngắn, trả về empty
      if (!query || query.trim().length < 2) {
        this.isSearching = false;
        return [];
      }

      // Demo data với các destinations, hotels, experiences phổ biến
      const allDemoData = [
        // Destinations
        { id: 1, text: "Paris, France", type: "destination" },
        { id: 2, text: "Tokyo, Japan", type: "destination" },
        { id: 3, text: "New York, USA", type: "destination" },
        { id: 4, text: "Bali, Indonesia", type: "destination" },
        { id: 5, text: "London, UK", type: "destination" },
        { id: 6, text: "Dubai, UAE", type: "destination" },
        { id: 7, text: "Sydney, Australia", type: "destination" },
        { id: 8, text: "Rome, Italy", type: "destination" },
        // Hotels
        { id: 9, text: "Grand Hotel Paris", type: "hotel" },
        { id: 10, text: "Tokyo Bay Hotel", type: "hotel" },
        { id: 11, text: "Manhattan Luxury Hotel", type: "hotel" },
        { id: 12, text: "Bali Beach Resort", type: "hotel" },
        { id: 13, text: "London City Hotel", type: "hotel" },
        // Experiences
        { id: 14, text: "Eiffel Tower Tour", type: "experience" },
        { id: 15, text: "Sushi Making Class", type: "experience" },
        { id: 16, text: "Broadway Show Experience", type: "experience" },
        { id: 17, text: "Sunset Yoga in Bali", type: "experience" },
        { id: 18, text: "Thames River Cruise", type: "experience" },
      ];

      // Filter results based on query (case-insensitive)
      const queryLower = query.toLowerCase();
      const filteredResults = allDemoData.filter((item) =>
        item.text.toLowerCase().includes(queryLower)
      );

      // Limit to 6 results for demo
      const demoResults = filteredResults.slice(0, 6);

      this.isSearching = false;
      return demoResults;

      // Uncomment và thay thế bằng API thật khi có:
      // const response = await fetch(
      //   `/wp-json/wp/v2/search?search=${encodeURIComponent(query)}`,
      //   {
      //     method: "GET",
      //     headers: {
      //       "Content-Type": "application/json",
      //     },
      //   }
      // );
      // if (!response.ok) throw new Error("Search failed");
      // const data = await response.json();
      // return data;
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

    // Clear existing results
    this.searchResultEl.innerHTML = "";

    if (results.length === 0) {
      // Hiển thị "No results found" khi không có kết quả
      const template = this.searchResultTemplate.content.cloneNode(true);
      const span = template.querySelector(
        ".banner-swiper-slide__content__search-result__item__text"
      );
      if (span) {
        span.textContent = "No results found";
      }
      this.searchResultEl.appendChild(template);
      return;
    }

    // Render results sử dụng template
    results.forEach((result) => {
      const template = this.searchResultTemplate.content.cloneNode(true);
      const span = template.querySelector(
        ".banner-swiper-slide__content__search-result__item__text"
      );
      if (span) {
        span.textContent = result.text;
      }
      const li = template.querySelector(
        ".banner-swiper-slide__content__search-result__item"
      );
      if (li) {
        // Thêm event listener cho click nếu cần
        li.addEventListener("click", () => {
          console.log("Selected:", result.text);
          // Có thể thêm logic xử lý khi click vào result ở đây
          // Ví dụ: navigate đến trang detail, fill input, etc.
        });
      }
      this.searchResultEl.appendChild(template);
    });
  }

  clearSearchResults() {
    if (!this.searchResultEl) return;
    this.searchResultEl.innerHTML = "";
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
      }
    });
  }
  events() {
    this.handleFocusSearchInput();
    this.handleBlurSearchInput();
    this.handleClearSearchInput();
    this.handleChangeSearchInput();
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
    loop: true,
    navigation: {
      nextEl: ".banner-swiper-button-next",
      prevEl: ".banner-swiper-button-prev",
    },
    pagination: {
      el: ".banner-swiper-pagination",
      clickable: true,
      renderBullet: function (index, className) {
        return (
          '<span class="' +
          className +
          "  " +
          "banner-swiper-pagination-bullet" +
          '">' +
          "</span>"
        );
      },
    },
  });

  // Handle content fade/slide per direction (support loop)
  const realSlides = Array.from(
    bannerSwiperEl.querySelectorAll(
      ".banner-swiper-slide:not(.swiper-slide-duplicate)"
    )
  );
  const contents = realSlides
    .map((slide) => slide.querySelector(".banner-swiper-slide__content"))
    .filter(Boolean);

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

    const prevContent =
      contents[prevRealIndex] !== undefined ? contents[prevRealIndex] : null;
    const activeContent =
      contents[currentRealIndex] !== undefined
        ? contents[currentRealIndex]
        : null;
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

      const leaveClass = isNext
        ? "banner-content--leave-to-left"
        : "banner-content--leave-to-right";
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
    activeContent.classList.add(
      isNext
        ? "banner-content--enter-from-right"
        : "banner-content--enter-from-left"
    );

    lastRealIndex = currentRealIndex;
    updatePaginationVisibility();
  });

  const sectionBanner = new SectionBanner();
}
