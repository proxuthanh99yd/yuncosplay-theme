function slugify(str) {
  return str
    .toString()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase()
    .trim()
    .replace(/đ/g, "d")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

export function whenToGoScripts() {
  const tabList = document.querySelector(".ht-when_tabs");
  const btnDrawerSpan = document.querySelector(".ht-when_btn-drawer span");
  const drawerItems = document.querySelectorAll(".ht-when_drawer-item");
  const itemsMb = document.querySelector(".ht-when_items-mb");
  const drawerSearch = document.querySelector(".ht-when_drawer-search-input");

  const breakpoint = window.matchMedia("(max-width: 639px)");

  let swiperInstance = null;
  let tabIndex = 0;
  let currentDestination = null;

  /* ------------------ RENDER ------------------ */
  const initDefaultDestination = () => {
    if (currentDestination) return;

    currentDestination = whenToGoDatas[0];

    renderTabs(currentDestination.best_time_to_visit);
  };

  const clearContent = () => {
    tabList.innerHTML = "";
    itemsMb.innerHTML = "";

    if (swiperInstance) {
      swiperInstance.slides.forEach((slide) => {
        const itemsEl = slide.querySelector(".ht-when_items");
        if (itemsEl) itemsEl.innerHTML = "";
      });
    }
  };

  const renderTabs = (bestTime) => {
    if (!bestTime || !bestTime.length) {
      tabList.innerHTML = "";
      return;
    }

    tabList.innerHTML = bestTime
      .map(
        (data, i) =>
          `<button type="button" class="ht-when_tab ${i === tabIndex ? "active" : ""}" data-month="${data.month}">
            ${data.month}
          </button>`,
      )
      .join("");

    tabList.querySelectorAll(".ht-when_tab").forEach((tab, i) => {
      tab.addEventListener("click", () => {
        tabIndex = i;
        renderTabs(bestTime);
        renderContent();
      });
    });
  };

  const renderDesktopItems = () => {
    const slide = swiperInstance.slides[swiperInstance.activeIndex];
    const itemsEl = slide.querySelector(".ht-when_items");
    const items = currentDestination.best_time_to_visit[tabIndex].items;

    itemsEl.innerHTML = items
      .map((item) => {
        const rainfallPercent = (item.monthly_rainfall / 1000) * 100;
        return `
        <div class="ht-when_item-row">
          <div class="ht-when_item-wrapper">
            <span class="ht-when_item-area">${item.beach_island_area}</span>
          </div>
          <div class="ht-when_ranges">
            <div class="ht-when_range range-color">
              <div class="ht-when_range-thumb" style="width:${item.daily_max_temperature}%"></div>
              <div class="ht-when_range-value" style="left:${item.daily_max_temperature}%">
                ${item.daily_max_temperature}
              </div>
            </div>
            <div class="ht-when_range">
              <div class="ht-when_range-thumb" style="width:${rainfallPercent}%"></div>
              <div class="ht-when_range-value" style="left:${rainfallPercent}%">
                ${item.monthly_rainfall}
              </div>
            </div>
          </div>
          <div class="ht-when_circle lg ${slugify(item.do_or_dont)}"></div>
        </div>`;
      })
      .join("");
  };

  const renderMobileItems = () => {
    const items = currentDestination.best_time_to_visit[tabIndex].items;

    itemsMb.innerHTML = items
      .map(
        (item) => `
      <div class="ht-when_item-mb">
        <div class="ht-when_item-area">
          <div class="ht-when_circle ${slugify(item.do_or_dont)}"></div>
          <span>${item.beach_island_area}</span>
        </div>
        <div class="ht-when_item-specifications">
          <div class="ht-when_range-circle lg color">
            ${item.daily_max_temperature}
          </div>
          <div class="ht-when_range-circle lg">
            ${item.monthly_rainfall}
          </div>
        </div>
      </div>`,
      )
      .join("");
  };

  const renderContent = () => {
    if (
      !currentDestination ||
      !currentDestination.best_time_to_visit ||
      !currentDestination.best_time_to_visit.length
    ) {
      clearContent();
      return;
    }

    if (breakpoint.matches) {
      renderMobileItems();
    } else {
      renderDesktopItems();
    }
  };

  /* ------------------ INIT ------------------ */

  const initDesktop = () => {
    if (swiperInstance) return;

    swiperInstance = new Swiper(".ht-when_item-body .swiper", {
      slidesPerView: 1,
      navigation: {
        nextEl: ".ht-when .swiper-button-next",
        prevEl: ".ht-when .swiper-button-prev",
      },
      grabCursor: "true",
      on: {
        slideChange(swiper) {
          const id = swiper.slides[swiper.activeIndex].dataset.id;
          currentDestination = whenToGoDatas.find(
            (d) => d.id.toString() === id,
          );
          tabIndex = 0;
          renderTabs(currentDestination.best_time_to_visit);
          renderContent();
        },
      },
    });
  };

  const destroyDesktop = () => {
    swiperInstance?.destroy(true, true);
    swiperInstance = null;
  };

  /* ------------------ MOBILE DRAWER ------------------ */
  drawerSearch.addEventListener("input", (e) => {
    const keyword = slugify(e.target.value);
    drawerItems.forEach((item) => {
      const name = slugify(item.textContent);
      const isMatch = name.includes(keyword);

      item.style.display = isMatch ? "" : "none";
    });
  });

  drawerItems.forEach((item) => {
    item.addEventListener("click", () => {
      drawerItems.forEach((i) => i.classList.remove("active"));
      item.classList.add("active");

      const id = item.dataset.id;
      currentDestination = whenToGoDatas.find((d) => d.id.toString() === id);

      if (!currentDestination) return;

      btnDrawerSpan.textContent = currentDestination.name;
      tabIndex = 0;

      if (drawerSearch) drawerSearch.value = "";

      renderTabs(currentDestination.best_time_to_visit);
      renderContent();
    });
  });

  /* ------------------ BREAKPOINT ------------------ */

  const handleBreakpoint = () => {
    initDefaultDestination();
    if (breakpoint.matches) {
      destroyDesktop();
    } else {
      initDesktop();
    }
    renderContent();
  };

  breakpoint.addEventListener("change", handleBreakpoint);
  handleBreakpoint();
}
