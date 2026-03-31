/**
 * Get the number of real (non-skeleton) products in the grid.
 *
 * @param {import('@playwright/test').Page} page
 * @returns {Promise<number>}
 */
export async function getProductCount(page) {
  return page.locator(
    ".pl-content__grid .product:not(.product--skeleton)"
  ).count();
}

/**
 * Trigger infinite scroll by scrolling the sentinel into view,
 * then wait for the next page API response and new products to appear.
 *
 * @param {import('@playwright/test').Page} page
 * @param {number} expectedPage  The page number expected in the API request (e.g. 2)
 * @returns {Promise<number>}  New total product count after scroll
 */
export async function triggerInfiniteScroll(page, expectedPage = 2) {
  const countBefore = await getProductCount(page);

  // Register API listener BEFORE scrolling
  const apiPromise = page.waitForResponse(
    (res) => {
      if (!res.url().includes("/api/v1/products")) return false;
      const url = new URL(res.url());
      return url.searchParams.get("page") === String(expectedPage);
    },
    { timeout: 10_000 }
  );

  // Scroll sentinel into view
  const sentinel = page.locator(".pl-content__sentinel");
  await sentinel.scrollIntoViewIfNeeded();

  // Wait for API response
  await apiPromise;

  // Wait for new products to render
  await page.waitForFunction(
    (prevCount) =>
      document.querySelectorAll(
        ".pl-content__grid .product:not(.product--skeleton)"
      ).length > prevCount,
    countBefore,
    { timeout: 10_000 }
  );

  return getProductCount(page);
}
