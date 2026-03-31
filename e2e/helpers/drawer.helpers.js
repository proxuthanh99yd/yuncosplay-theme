import { waitForTransition } from "./wait.helpers.js";

/**
 * Open the mobile filter drawer.
 * Clicks the filter button (inline or sticky) and waits for transform transition.
 *
 * @param {import('@playwright/test').Page} page
 */
export async function openFilterDrawer(page) {
  // Try inline button first, fallback to sticky bar button
  const inlineBtn = page.locator(".pl-filter-btn-wrap .pl-filter-btn");
  const stickyBtn = page.locator(".pl-sticky-bar .pl-filter-btn");

  if (await inlineBtn.isVisible()) {
    await inlineBtn.click();
  } else {
    await stickyBtn.click();
  }

  // Wait for drawer to fully open (transform transition)
  await waitForTransition(page, "#pl-filter-drawer", "transform", 500);

  // Verify drawer is open
  await page.waitForSelector("#pl-filter-drawer.pl-filter-drawer--open", {
    timeout: 2_000,
  });
}

/**
 * Close the mobile filter drawer via the close button.
 *
 * @param {import('@playwright/test').Page} page
 */
export async function closeFilterDrawer(page) {
  const closeBtn = page.locator('[data-action="close-drawer"]');
  await closeBtn.click();

  // Wait for drawer to fully close
  await waitForTransition(page, "#pl-filter-drawer", "transform", 500);
}
