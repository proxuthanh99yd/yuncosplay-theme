import { sectionSidebarScripts, getActiveFilters, expandHeight, collapseHeight } from "../section-sidebar/assets/scripts.js";
import { sectionContentScripts } from "../section-content/assets/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
  sectionSidebarScripts();
  sectionContentScripts();
  initStickyBar();
  initSelectedFilters();
});

/**
 * Sticky Bar — show when the inline filter button scrolls out of view (mobile)
 */
function initStickyBar() {
  const filterBtnWrap = document.getElementById("pl-filter-btn-wrap");
  const stickyBar = document.getElementById("pl-sticky-bar");
  if (!filterBtnWrap || !stickyBar) return;

  // Only run on mobile
  const mql = window.matchMedia("(max-width: 639.98px)");
  if (!mql.matches) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          stickyBar.classList.remove("pl-sticky-bar--visible");
        } else {
          stickyBar.classList.add("pl-sticky-bar--visible");
        }
      });
    },
    {
      threshold: 0,
    }
  );

  observer.observe(filterBtnWrap);
}

/**
 * Selected Filters — render active filter chips on mobile
 */
function initSelectedFilters() {
  const container = document.getElementById("pl-selected-filters");
  const sidebar = document.getElementById("sidebar-filter");
  if (!container || !sidebar) return;

  // Only run on mobile
  const mql = window.matchMedia("(max-width: 639.98px)");
  if (!mql.matches) return;

  // Close circle SVG for chip remove button
  const CLOSE_CIRCLE_SVG = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="10" cy="10" r="9" stroke="rgba(28,28,28,0.4)" stroke-width="1"/>
    <path d="M7 7l6 6M13 7l-6 6" stroke="rgba(28,28,28,0.4)" stroke-width="1.2" stroke-linecap="round"/>
  </svg>`;

  // Listen for filter changes
  document.addEventListener("filterchange", (e) => {
    const filters = e.detail;
    renderSelectedChips(container, filters, CLOSE_CIRCLE_SVG, sidebar);
  });
}

/**
 * Render selected filter chips into the container
 */
function renderSelectedChips(container, filters, closeSvg, sidebar) {
  container.innerHTML = "";

  const chips = [];

  // Category chip
  if (filters.category) {
    const categoryCard = sidebar.querySelector(
      `.pl-sidebar__category-card[data-category-slug="${filters.category}"]`
    );
    const categoryName = categoryCard
      ? categoryCard.querySelector(".pl-sidebar__category-name")?.textContent.trim()
      : filters.category;

    chips.push({
      type: "category",
      slug: filters.category,
      label: categoryName,
    });
  }

  // Subcategory chips
  if (filters.subcategories && filters.subcategories.length > 0) {
    filters.subcategories.forEach((slug) => {
      const chipEl = sidebar.querySelector(
        `.pl-sidebar__chip[data-filter-value="${slug}"]`
      );
      const label = chipEl
        ? chipEl.querySelector(".pl-sidebar__chip-label")?.textContent.trim()
        : slug;

      chips.push({
        type: "subcategory",
        slug: slug,
        label: label,
      });
    });
  }

  // Price range chip (only if not at defaults)
  if (filters.minPrice > 100000 || filters.maxPrice < 10000000) {
    const minFormatted = Number(filters.minPrice).toLocaleString("vi-VN");
    const maxFormatted = Number(filters.maxPrice).toLocaleString("vi-VN");
    chips.push({
      type: "price",
      slug: "price",
      label: `${minFormatted}đ — ${maxFormatted}đ`,
    });
  }

  if (chips.length === 0) {
    container.classList.remove("pl-selected-filters--visible");
    collapseHeight(container);
    return;
  }

  container.classList.add("pl-selected-filters--visible");

  // Render chips
  chips.forEach((chipData) => {
    const btn = document.createElement("button");
    btn.className = "pl-selected-chip";
    btn.type = "button";
    btn.innerHTML = `<span>${escapeHtml(chipData.label)}</span><span class="pl-selected-chip__close">${closeSvg}</span>`;
    btn.addEventListener("click", () => {
      removeFilter(chipData, sidebar);
    });
    container.appendChild(btn);
  });

  // Clear all chip
  const clearBtn = document.createElement("button");
  clearBtn.className = "pl-selected-chip pl-selected-chip--clear";
  clearBtn.type = "button";
  clearBtn.textContent = "Xoá tất cả";
  clearBtn.addEventListener("click", () => {
    const clearAllBtn = sidebar.querySelector(".pl-sidebar__clear-btn");
    if (clearAllBtn) clearAllBtn.click();
  });
  container.appendChild(clearBtn);

  // Measure and animate to content height
  expandHeight(container);
}

/**
 * Remove a single filter
 */
function removeFilter(chipData, sidebar) {
  if (chipData.type === "category") {
    // Deactivate category card
    const card = sidebar.querySelector(
      `.pl-sidebar__category-card[data-category-slug="${chipData.slug}"]`
    );
    if (card) card.click();
  } else if (chipData.type === "subcategory") {
    // Deactivate subcategory chip
    const chip = sidebar.querySelector(
      `.pl-sidebar__chip[data-filter-value="${chipData.slug}"]`
    );
    if (chip) chip.click();
  } else if (chipData.type === "price") {
    // Reset price slider
    const sliderEl = sidebar.querySelector("#pl-price-slider");
    if (sliderEl && sliderEl.noUiSlider) {
      sliderEl.noUiSlider.set([100000, 10000000]);
    }
    // Dispatch filter change
    sidebar.dispatchEvent(
      new CustomEvent("filterchange", {
        detail: getActiveFilters(sidebar),
        bubbles: true,
      })
    );
  }
}

/**
 * Simple HTML escape
 */
function escapeHtml(str) {
  const div = document.createElement("div");
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
}
