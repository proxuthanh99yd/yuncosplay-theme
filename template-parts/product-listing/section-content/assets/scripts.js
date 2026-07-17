
function sectionContentScripts() {
  initExpandCollapse();
  initInfiniteScroll();
  initFilterListener();
}

/**
 * Convert rem to pixels
 */
function remToPx(rem) {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
}

/**
 * Description Expand/Collapse
 * Uses JS-measured scrollHeight instead of CSS max-height.
 */
function initExpandCollapse() {
  const btn = document.getElementById("pl-expand-btn");
  const desc = document.getElementById("pl-desc");
  if (!btn || !desc) return;

  // Determine collapsed height based on breakpoint
  const isMobile = window.matchMedia("(max-width: 639.98px)").matches;
  const collapsedRem = isMobile ? 5.0625 : 7.19;
  const collapsedPx = remToPx(collapsedRem);

  // Replace CSS max-height with precise JS height (no transition on init)
  desc.style.transition = "none";
  desc.style.maxHeight = "none";
  desc.style.height = collapsedPx + "px";
  desc.offsetHeight; // force reflow
  desc.style.transition = "";

  btn.addEventListener("click", () => {
    const isExpanded = desc.classList.contains("is-expanded");

    if (isExpanded) {
      // Collapse: set explicit scrollHeight first, then animate to collapsed
      desc.style.height = desc.scrollHeight + "px";
      desc.offsetHeight; // force reflow
      desc.style.height = collapsedPx + "px";
      desc.classList.remove("is-expanded");
      btn.setAttribute("aria-expanded", "false");
    } else {
      // Expand: animate to scrollHeight
      desc.style.height = desc.scrollHeight + "px";
      desc.classList.add("is-expanded");
      btn.setAttribute("aria-expanded", "true");
    }
  });
}

/**
 * Infinite Scroll with IntersectionObserver
 */
let currentPage = 1;
let totalPages = 1;
let isLoading = false;
let currentFilters = {};
let infiniteScrollObserver = null;
let filterAbortController = null;
let loadMoreAbortController = null;
let latestFilterRequestId = 0;

function initInfiniteScroll() {
  const grid = document.getElementById("pl-grid");
  const sentinel = document.getElementById("pl-sentinel");
  const loader = document.getElementById("pl-loader");

  if (!grid || !sentinel) return;

  currentPage = parseInt(grid.dataset.currentPage, 10) || 1;
  totalPages = parseInt(grid.dataset.totalPages, 10) || 1;

  // Store initial filter state from URL/data attributes
  currentFilters = {
    category: grid.dataset.category || "",
    minPrice: "",
    maxPrice: "",
    subcategories: [],
  };

  // Create and store observer
  infiniteScrollObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !isLoading && currentPage < totalPages) {
          loadMoreProducts(grid, loader);
        }
      });
    },
    {
      rootMargin: "200px",
    },
  );

  infiniteScrollObserver.observe(sentinel);
}

/**
 * Reconnect infinite scroll observer after filter changes
 */
function reconnectInfiniteScrollObserver() {
  const sentinel = document.getElementById("pl-sentinel");

  if (!infiniteScrollObserver || !sentinel) return;

  infiniteScrollObserver.disconnect();

  // Force reflow to ensure DOM is stable
  requestAnimationFrame(() => {
    infiniteScrollObserver.observe(sentinel);
  });
}

/**
 * Listen for filter changes from sidebar
 */
function initFilterListener() {
  document.addEventListener("filterchange", (e) => {
    const filters = e.detail;
    currentFilters = filters;
    currentPage = 1;
    totalPages = 1;
    
    // Reset loading state to prevent blocking
    isLoading = false;

    const grid = document.getElementById("pl-grid");
    if (!grid) return;

    // Clear grid and show skeleton loading
    grid.innerHTML = "";
    grid.setAttribute("aria-busy", "true");
    appendSkeletons(grid, 12);

    // Delay scroll to next frame to prevent double trigger
    requestAnimationFrame(() => {
      scrollToGrid(grid);
    });

    const filterRequestId = ++latestFilterRequestId;

    fetchProducts(1, filters, 'filter').then((result) => {
      // Ignore stale filter responses to prevent mixed UI state
      if (filterRequestId !== latestFilterRequestId) return;

      removeSkeletons(grid);
      grid.setAttribute("aria-busy", "false");

      if (result && result.data && result.data.length > 0) {
        grid.innerHTML = "";
        appendProducts(grid, result.data);
        initProduct();
        currentPage = result.pagination.page;
        totalPages = result.pagination.total_pages;

        grid.dataset.currentPage = currentPage;
        grid.dataset.totalPages = totalPages;
        
        // RECONNECT INFINITE SCROLL OBSERVER after filter
        reconnectInfiniteScrollObserver();
      } else {
        showNoResults(grid);
      }
    });
  });
}

/**
 * Advanced scroll to grid with Lenis support and reduced motion detection
 */
function scrollToGrid(grid) {
  if ("scrollRestoration" in history) history.scrollRestoration = "manual";

  const app = window.app;
  const lenis = app && app.lenis;
  const prefersReducedMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (lenis && typeof lenis.scrollTo === "function") {
    try {
      if (typeof lenis.stop === "function") lenis.stop();
      if (typeof lenis.start === "function") lenis.start();
      lenis.scrollTo(0, {
        immediate: prefersReducedMotion,
        duration: prefersReducedMotion ? 0 : 0.8,
        easing: (t) => 1 - Math.pow(1 - t, 3),
      });
    } finally {
    }
    return;
  }

  window.scrollTo({
    top: 0,
    behavior: prefersReducedMotion ? "auto" : "smooth",
  });
}

/**
 * Show no results message using template cloning
 */
function showNoResults(grid) {
  const template = document.getElementById("pl-no-results-template");
  if (!template) return;

  grid.replaceChildren(template.content.cloneNode(true));
}

async function loadMoreProducts(grid, loader) {
  if (isLoading || currentPage >= totalPages) return;

  isLoading = true;
  appendSkeletons(grid, 4);

  const nextPage = currentPage + 1;
  const result = await fetchProducts(nextPage, currentFilters, 'loadMore');

  removeSkeletons(grid);
  isLoading = false;

  if (result && result.data && result.data.length > 0) {
    appendProducts(grid, result.data);
    initProduct();
    currentPage = result.pagination.page;
    totalPages = result.pagination.total_pages;

    grid.dataset.currentPage = currentPage;
    grid.dataset.totalPages = totalPages;
  }
}

/**
 * Fetch products from REST API
 */
async function fetchProducts(page, filters, controllerType = 'filter') {
  // Use appropriate abort controller based on request type
  let abortController;
  
  if (controllerType === 'filter') {
    if (filterAbortController) {
      filterAbortController.abort();
    }
    filterAbortController = new AbortController();
    abortController = filterAbortController;
  } else {
    if (loadMoreAbortController) {
      loadMoreAbortController.abort();
    }
    loadMoreAbortController = new AbortController();
    abortController = loadMoreAbortController;
  }

  const params = new URLSearchParams({
    page: page,
    limit: 12,
  });

  if (filters.category) {
    // If subcategories are selected, use those; otherwise use main category
    if (filters.subcategories && filters.subcategories.length > 0) {
      params.set("category", filters.subcategories.join(","));
    } else {
      params.set("category", filters.category);
    }
  }

  if (filters.minPrice && filters.minPrice > 100000) {
    params.set("price_min", filters.minPrice);
  }
  if (filters.maxPrice && filters.maxPrice < 10000000) {
    params.set("price_max", filters.maxPrice);
  }

  try {
    const apiRoot =
      (window.wpApiSettings && window.wpApiSettings.root) || "/wp-json/";
    const resp = await fetch(`${apiRoot}api/v1/products?${params.toString()}`, {
      signal: abortController.signal,
    });
    const json = await resp.json();

    if (json.success) {
      return json;
    }
    return null;
  } catch (err) {
    if (err.name !== 'AbortError') {
      console.error("Product fetch error:", err);
    }
    return null;
  }
}

/**
 * Append product cards to the grid from API data
 * Optimized with DocumentFragment to reduce reflows
 */
function appendProducts(grid, products) {
  const fragment = document.createDocumentFragment();
  const temp = document.createElement("div");

  products.forEach((product) => {
    temp.innerHTML = createProductCard(product);
    fragment.appendChild(temp.firstElementChild);
  });

  grid.appendChild(fragment);
}

/**
 * Create product card HTML from API data
 * Matches the existing product component markup exactly
 */
function createProductCard(product) {
  const thumbnail = product.thumbnail || "";
  const title = escapeHtml(decodeHtml(product.title || ""));
  const url = product.url || "#";
  const rentPrice = product.rent_price ? product.rent_price.formatted : "0";
  const salePrice = product.price ? product.price.formatted : "0";
  const videoUrl = product.video || "";

  return `
    <article class="product product--small">
      <a href="${escapeHtml(url)}" class="product__link">
        <div class="product__img-wrapper">
          <video src="${escapeHtml(videoUrl)}" playsinline muted loop preload="none" class="product__video"></video>
          ${
            thumbnail
              ? `<img class="product__img" src="${escapeHtml(thumbnail)}" alt="${title}" loading="lazy" decoding="async" />`
              : ""
          }
          <div class="product__title-wrapper">
            <h3 class="product__title">${title}</h3>
          </div>
        </div>
        <div class="product__content">
          <div class="product__rent">
            <span class="product__rent-label">Giá thuê</span>
            <p class="product__rent-price">
              <span class="product__rent-price-value">${escapeHtml(rentPrice)}đ</span>
              <span class="product__rent-price-time">/Ngày</span>
            </p>
          </div>
          <div class="product__price">
            <span class="product__price-label">Giá bán:</span>
            <p class="product__price-value">${escapeHtml(salePrice)}đ</p>
          </div>
          <div class="product__price-mb">( Giá bán: ${escapeHtml(salePrice)}đ )</div>
        </div>
      </a>
    </article>
  `;
}

/**
 * Simple HTML escape
 */
function escapeHtml(str) {
  const div = document.createElement("div");
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
}

/**
 * Decode HTML entities (e.g. &#8211; → –)
 */
function decodeHtml(str) {
  const txt = document.createElement("textarea");
  txt.innerHTML = str;
  return txt.value;
}

/**
 * Create a single skeleton card HTML
 * Same structure as product card but with --skeleton modifier
 */
function createSkeletonCard() {
  return `<article class="product product--skeleton">
      <div class="product__link">
        <div class="product__img-wrapper">
          <div class="product__title-wrapper">
            <h3 class="product__title"></h3>
          </div>
        </div>
        <div class="product__content">
          <div class="product__rent">
            <span class="product__rent-label"></span>
            <p class="product__rent-price">
              <span class="product__rent-price-value"></span>
            </p>
          </div>
          <div class="product__price">
            <span class="product__price-label"></span>
            <p class="product__price-value"></p>
          </div>
          <div class="product__price-mb"></div>
        </div>
      </div>
    </article>`;
}

/**
 * Append skeleton cards to the grid
 */
function appendSkeletons(grid, count) {
  const fragment = document.createDocumentFragment();
  const temp = document.createElement("div");

  for (let i = 0; i < count; i++) {
    temp.innerHTML = createSkeletonCard();
    fragment.appendChild(temp.firstElementChild);
  }

  grid.appendChild(fragment);
}

/**
 * Remove all skeleton cards from the grid
 */
function removeSkeletons(grid) {
  const skeletons = grid.querySelectorAll(".product--skeleton");
  skeletons.forEach((el) => el.remove());
}
