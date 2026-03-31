/**
 * Search Page — Scripts
 * Handles infinite scroll for product search results
 */

document.addEventListener("DOMContentLoaded", () => {
  initProductInfiniteScroll();
});

/**
 * Infinite Scroll for product search results
 * Uses IntersectionObserver to load more products when the sentinel enters the viewport
 */
function initProductInfiniteScroll() {
  const grid     = document.getElementById("sp-product-grid");
  const sentinel = document.getElementById("sp-product-sentinel");
  const loader   = document.getElementById("sp-product-loader");

  if (!grid || !sentinel || !loader) return;

  let currentPage = parseInt(grid.dataset.currentPage, 10) || 1;
  let totalPages  = parseInt(grid.dataset.totalPages, 10) || 1;
  const search    = grid.dataset.search || "";
  const bgUrl     = grid.dataset.bgUrl || "";
  let isLoading   = false;

  if (currentPage >= totalPages) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !isLoading && currentPage < totalPages) {
          loadMore();
        }
      });
    },
    { threshold: 0, rootMargin: "200px" }
  );

  observer.observe(sentinel);

  async function loadMore() {
    isLoading = true;
    currentPage++;

    // Show loader
    loader.classList.add("is-loading");
    loader.style.height = "auto";

    try {
      const params = new URLSearchParams({
        search: search,
        page: currentPage,
        limit: 15,
      });

      const response = await fetch(`/wp-json/api/v1/products?${params}`);
      const data = await response.json();

      if (data.success && data.data && data.data.length > 0) {
        const fragment = document.createDocumentFragment();

        data.data.forEach((item) => {
          const article = createProductCard(item, bgUrl);
          fragment.appendChild(article);
        });

        grid.appendChild(fragment);

        // Update total pages from response
        if (data.pagination) {
          totalPages = data.pagination.total_pages;
        }
      }

      // If we've loaded all pages, disconnect observer
      if (currentPage >= totalPages) {
        observer.disconnect();
      }
    } catch (error) {
      console.error("Search: Failed to load more products:", error);
      currentPage--;
    } finally {
      isLoading = false;
      loader.classList.remove("is-loading");
    }
  }
}

/**
 * Create a product card element from API data
 * Matches the HTML structure in template-parts/components/product/index.php
 */
function createProductCard(item, bgUrl) {
  const article = document.createElement("article");
  article.className = "product";

  const rentFormatted = item.rent_price?.formatted || "0";
  const saleFormatted = item.price?.formatted || "0";
  const videoUrl      = item.video || "";
  const thumbUrl      = item.thumbnail || "";
  const title         = item.title || "";
  const url           = item.url || "#";

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

/**
 * Simple HTML escape utility
 */
function escapeHtml(str) {
  if (!str) return "";
  const div = document.createElement("div");
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
}
