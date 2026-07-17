/**
 * Search page — infinite scroll (product + blog, cùng API REST như PHP)
 */

const SP_INFINITE_OBSERVER = { threshold: 0, rootMargin: "200px" };

function scrollSearchToTopAfterTabChange() {
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      scrollSearchToTop();
    });
  });
}

function scrollSearchToTop() {
  const lenis = window.app?.lenis;
  const prefersReducedMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const scrollOpts = {
    immediate: prefersReducedMotion,
    duration: prefersReducedMotion ? 0 : 0.8,
    easing: (t) => 1 - Math.pow(1 - t, 3),
  };

  if (lenis && typeof lenis.scrollTo === "function") {
    try {
      if (typeof lenis.stop === "function") lenis.stop();
      if (typeof lenis.start === "function") lenis.start();
      lenis.scrollTo(0, scrollOpts);
    } catch (e) {
      scrollSearchToTopNative();
    }
    return;
  }

  scrollSearchToTopNative();
}

function scrollSearchToTopNative() {
  const prefersReducedMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const behavior = prefersReducedMotion ? "auto" : "smooth";
  window.scrollTo({ top: 0, left: 0, behavior });
  if (prefersReducedMotion) {
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const tabList = document.querySelector(".sp-tabs");
  const tabItems = document.querySelectorAll(".sp-tab");
  const searchWrapper = document.querySelector(".sp-results-wrapper");

  tabItems.forEach((tab) => {
    tab.addEventListener("click", () => {
      const dataValue = tab.dataset.value;

      tabItems.forEach((t) => t.classList.remove("active"));
      tab.classList.add("active");

      searchWrapper.setAttribute("data-type", dataValue);

      const url = new URL(window.location.href);
      url.searchParams.set("post_type", dataValue);
      window.history.pushState({}, "", url);

      scrollSearchToTopAfterTabChange();
    });
  });

  const offset =
    3.75 * parseFloat(getComputedStyle(document.documentElement).fontSize);

  window.addEventListener("scroll", () => {
    if (!tabList) return;
    const rect = tabList.getBoundingClientRect();
    tabList.classList.toggle("in-viewport", rect.top <= offset);
  });

  const productGrid = document.getElementById("sp-product-grid");
  initSearchInfiniteScroll({
    grid: productGrid,
    sentinel: document.getElementById("sp-product-sentinel"),
    loader: document.getElementById("sp-product-loader"),
    apiPath: "/wp-json/api/v1/products",
    defaultLimit: 15,
    renderItems: (items) => {
      const bgUrl = productGrid?.dataset.bgUrl || "";
      const fragment = document.createDocumentFragment();
      items.forEach((item) => {
        fragment.appendChild(createProductCard(item, bgUrl));
      });
      return fragment;
    },
  });

  const blogGrid = document.getElementById("sp-blog-grid");
  initSearchInfiniteScroll({
    grid: blogGrid,
    sentinel: document.getElementById("sp-blog-sentinel"),
    loader: document.getElementById("sp-blog-loader"),
    apiPath: "/wp-json/api/v1/blogs",
    defaultLimit: 12,
    renderItems: (items) => {
      const overlayUrl = blogGrid?.dataset.overlayUrl || "";
      const fragment = document.createDocumentFragment();
      items.forEach((item) => {
        fragment.appendChild(createBlogCard(item, overlayUrl));
      });
      return fragment;
    },
  });
});

/**
 * Infinite scroll — dùng chung cho product và blog (IntersectionObserver + REST)
 */
function initSearchInfiniteScroll({
  grid,
  sentinel,
  loader,
  apiPath,
  defaultLimit,
  renderItems,
}) {
  if (!grid || !sentinel || !loader) return;

  let currentPage = parseInt(grid.dataset.currentPage, 10) || 1;
  let totalPages = parseInt(grid.dataset.totalPages, 10) || 1;
  const search = grid.dataset.search || "";
  const limit = parseInt(grid.dataset.limit, 10) || defaultLimit;

  let isLoading = false;

  if (currentPage >= totalPages) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && !isLoading && currentPage < totalPages) {
        loadMore();
      }
    });
  }, SP_INFINITE_OBSERVER);

  observer.observe(sentinel);

  async function loadMore() {
    isLoading = true;
    currentPage++;

    loader.classList.add("is-loading");
    loader.style.height = "auto";

    try {
      const params = new URLSearchParams({
        search,
        page: String(currentPage),
        limit: String(limit),
      });

      const response = await fetch(`${apiPath}?${params}`);
      const data = await response.json();

      if (data.success && data.data && data.data.length > 0) {
        const emptyEl = grid.querySelector(".sp-results__empty");
        if (emptyEl) emptyEl.remove();

        grid.appendChild(renderItems(data.data));

        if (data.pagination) {
          totalPages = data.pagination.total_pages;
        }
      }

      if (currentPage >= totalPages) {
        observer.disconnect();
      }
    } catch (e) {
      console.error("Search infinite scroll:", apiPath, e);
      currentPage--;
    } finally {
      isLoading = false;
      loader.classList.remove("is-loading");
    }
  }
}

function createProductCard(item, bgUrl) {
  const article = document.createElement("article");
  article.className = "product";

  const rentFormatted = item.rent_price?.formatted || "0";
  const saleFormatted = item.price?.formatted || "0";
  const videoUrl = item.video || "";
  const thumbUrl = item.thumbnail || "";
  const title = item.title || "";
  const url = item.url || "#";

  article.innerHTML = `
    <a href="${escapeHtml(url)}" class="product__link">
      <div class="product__img-wrapper">
        <video src="${escapeHtml(videoUrl)}" playsinline muted loop preload="none" class="product__video"></video>
        ${thumbUrl ? `<img src="${escapeHtml(thumbUrl)}" alt="${escapeHtml(title)}" class="product__img" loading="lazy" decoding="async" />` : ""}
        <div class="product__title-wrapper">
          <h3 class="product__title">${escapeHtml(title)}</h3>
        </div>
      </div>
      <div class="product__content">
        <div class="product__content-background">
          ${bgUrl ? `<img src="${escapeHtml(bgUrl)}" class="product__content-background-img" alt="" loading="lazy" />` : ""}
        </div>
        <div class="product__rent">
          <span class="product__rent-label">Giá thuê</span>
          <p class="product__rent-price">
            <span class="product__rent-price-value">${escapeHtml(rentFormatted)}đ</span>
            <span class="product__rent-price-time">/Ngày</span>
          </p>
        </div>
        <div class="product__price">
          <span class="product__price-label">Giá bán:</span>
          <p class="product__price-value">${escapeHtml(saleFormatted)}đ</p>
        </div>
        <div class="product__price-mb">( Giá bán: ${escapeHtml(saleFormatted)}đ )</div>
      </div>
    </a>
  `;

  return article;
}

function formatBlogDate(iso) {
  if (!iso) return "";
  const d = new Date(iso);
  if (Number.isNaN(d.getTime())) return "";
  const day = String(d.getDate()).padStart(2, "0");
  const month = String(d.getMonth() + 1).padStart(2, "0");
  const year = d.getFullYear();
  return `${day}/${month}/${year}`;
}

function createBlogCard(item, overlayUrl) {
  const article = document.createElement("article");
  article.className = "blog-item-v2";

  const title = item.title || "";
  const url = item.url || "#";
  const thumb = item.thumbnail || "";
  const category = item.category || "";
  const dateStr = formatBlogDate(item.date);

  article.innerHTML = `
    <a href="${escapeHtml(url)}" class="blog-item-v2__link">
      <div class="blog-item-v2__thumbnail">
        ${overlayUrl ? `<img src="${escapeHtml(overlayUrl)}" alt="" class="blog-item-v2__thumbnail-overlay" loading="lazy" decoding="async" />` : ""}
        ${thumb ? `<img src="${escapeHtml(thumb)}" alt="${escapeHtml(title)}" class="blog-item-v2__thumbnail-image" loading="lazy" decoding="async" />` : ""}
      </div>
      <div class="blog-item-v2__content">
        <div class="blog-item-v2__meta">
          <p class="blog-item-v2__category">
            <span class="blog-item-v2__category-text">${escapeHtml(category)}</span>
          </p>
          <p class="blog-item-v2__date">${escapeHtml(dateStr)}</p>
        </div>
        <h3 class="blog-item-v2__title">${escapeHtml(title)}</h3>
      </div>
    </a>
  `;

  return article;
}

function escapeHtml(str) {
  if (!str) return "";
  const div = document.createElement("div");
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
}
