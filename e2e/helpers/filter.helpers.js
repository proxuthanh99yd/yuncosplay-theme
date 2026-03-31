import { waitForTransition, waitForProductsResponse } from "./wait.helpers.js";

/**
 * Click a category card and wait for the filter panel to expand.
 *
 * @param {import('@playwright/test').Page} page
 * @param {string} slug  Category slug (data-category-slug attribute)
 */
export async function selectCategory(page, slug) {
  const card = page.locator(
    `.pl-sidebar__category-card[data-category-slug="${slug}"]`
  );

  // Register API wait BEFORE clicking
  const apiPromise = waitForProductsResponse(page);

  await card.click();

  // Wait for panel expand transition (0.4s height)
  const panel = page.locator(
    `.pl-sidebar__filter-panel[data-panel-for="${slug}"]`
  );

  if (await panel.count()) {
    await waitForTransition(
      page,
      `.pl-sidebar__filter-panel[data-panel-for="${slug}"]`,
      "height",
      600
    );
  }

  await apiPromise;
}

/**
 * Click a subcategory chip to toggle it.
 *
 * @param {import('@playwright/test').Page} page
 * @param {string} slug  Subcategory slug (data-category-slug on chip)
 */
export async function toggleChip(page, slug) {
  const chip = page.locator(
    `.pl-sidebar__chip[data-category-slug="${slug}"]`
  );

  const apiPromise = waitForProductsResponse(page);
  await chip.click();
  await apiPromise;
}

/**
 * Click the "Xoa lua chon" clear button and wait for panels to collapse.
 *
 * @param {import('@playwright/test').Page} page
 */
export async function clearAllFilters(page) {
  const clearBtn = page.locator('[data-action="clear-categories"]');
  const apiPromise = waitForProductsResponse(page);
  await clearBtn.click();
  await apiPromise;
}

/**
 * Get the currently active category slug (or null if none).
 *
 * @param {import('@playwright/test').Page} page
 * @returns {Promise<string|null>}
 */
export async function getActiveCategory(page) {
  const active = page.locator(".pl-sidebar__category-card--active");
  if ((await active.count()) === 0) return null;
  return active.getAttribute("data-category-slug");
}

/**
 * Get all active chip slugs.
 *
 * @param {import('@playwright/test').Page} page
 * @returns {Promise<string[]>}
 */
export async function getActiveChips(page) {
  const chips = page.locator(".pl-sidebar__chip--active");
  const count = await chips.count();
  const slugs = [];
  for (let i = 0; i < count; i++) {
    slugs.push(await chips.nth(i).getAttribute("data-category-slug"));
  }
  return slugs;
}
