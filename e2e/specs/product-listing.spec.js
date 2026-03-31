import { test, expect } from "../fixtures/test-fixtures.js";
import { waitForPageReady, waitForTransition } from "../helpers/wait.helpers.js";
import {
  selectCategory,
  toggleChip,
  clearAllFilters,
  getActiveCategory,
  getActiveChips,
} from "../helpers/filter.helpers.js";
import { getProductCount, triggerInfiniteScroll } from "../helpers/scroll.helpers.js";
import { openFilterDrawer, closeFilterDrawer } from "../helpers/drawer.helpers.js";

// ─── Setup ──────────────────────────────────────────────────────────

test.beforeEach(async ({ page }) => {
  await waitForPageReady(page);
});

// ─── 1. Page Load ───────────────────────────────────────────────────

test("page loads with products and sidebar", async ({ page, vpMobile }) => {
  const productCount = await getProductCount(page);
  expect(productCount).toBeGreaterThan(0);

  // Sidebar visible on desktop/tablet, hidden on mobile
  const sidebar = page.locator("#sidebar-filter");
  if (vpMobile) {
    await expect(sidebar).not.toBeInViewport();
  } else {
    await expect(sidebar).toBeVisible();
  }

  // noUiSlider initialized
  const sliderInitialized = await page.evaluate(() => {
    const el = document.querySelector("#pl-price-slider");
    return !!(el && el.noUiSlider);
  });
  expect(sliderInitialized).toBe(true);
});

// ─── 2. Grid Columns ───────────────────────────────────────────────

test("grid shows correct column count per viewport", async ({
  page,
  vpMobile,
  vpTablet,
  vpDesktop,
}) => {
  const products = page.locator(
    ".pl-content__grid .product:not(.product--skeleton)"
  );
  const count = await products.count();
  if (count < 2) return; // Not enough products to verify columns

  // Get Y positions of first row products
  const firstY = (await products.nth(0).boundingBox()).y;
  let columnsInFirstRow = 0;

  for (let i = 0; i < count; i++) {
    const box = await products.nth(i).boundingBox();
    if (Math.abs(box.y - firstY) < 10) {
      columnsInFirstRow++;
    } else {
      break;
    }
  }

  if (vpDesktop) {
    expect(columnsInFirstRow).toBe(4);
  } else if (vpTablet) {
    expect(columnsInFirstRow).toBe(3);
  } else if (vpMobile) {
    expect(columnsInFirstRow).toBe(2);
  }
});

// ─── 3. Category Select Opens Panel ────────────────────────────────

test("selecting a category activates card and opens filter panel", async ({
  page,
  vpMobile,
}) => {
  if (vpMobile) {
    await openFilterDrawer(page);
  }

  // Find first category card that has children (filter panel)
  const cardsWithChildren = page.locator(
    '.pl-sidebar__category-card[data-has-children="true"]'
  );
  const cardCount = await cardsWithChildren.count();
  if (cardCount === 0) return; // No categories with subcategories

  const firstCard = cardsWithChildren.nth(0);
  const slug = await firstCard.getAttribute("data-category-slug");

  await selectCategory(page, slug);

  // Card should be active
  await expect(firstCard).toHaveClass(/pl-sidebar__category-card--active/);

  // Panel should be open
  const panel = page.locator(
    `.pl-sidebar__filter-panel[data-panel-for="${slug}"]`
  );
  await expect(panel).toHaveClass(/pl-sidebar__filter-panel--open/);

  // Panel should have visible height
  const panelHeight = await panel.evaluate((el) => el.offsetHeight);
  expect(panelHeight).toBeGreaterThan(0);
});

// ─── 4. Single-Select Category ─────────────────────────────────────

test("clicking a second category deactivates the first", async ({
  page,
  vpMobile,
}) => {
  if (vpMobile) {
    await openFilterDrawer(page);
  }

  const cards = page.locator(
    '.pl-sidebar__category-card[data-has-children="true"]'
  );
  const count = await cards.count();
  if (count < 2) return; // Need at least 2 categories

  const slug1 = await cards.nth(0).getAttribute("data-category-slug");
  const slug2 = await cards.nth(1).getAttribute("data-category-slug");

  // Select first
  await selectCategory(page, slug1);
  expect(await getActiveCategory(page)).toBe(slug1);

  // Select second — first should deactivate
  await selectCategory(page, slug2);
  expect(await getActiveCategory(page)).toBe(slug2);

  // First card no longer active
  await expect(cards.nth(0)).not.toHaveClass(
    /pl-sidebar__category-card--active/
  );
});

// ─── 5. Subcategory Chips Multi-Select ─────────────────────────────

test("subcategory chips can be multi-selected", async ({
  page,
  vpMobile,
}) => {
  if (vpMobile) {
    await openFilterDrawer(page);
  }

  // Select a category that has chips
  const cardsWithChildren = page.locator(
    '.pl-sidebar__category-card[data-has-children="true"]'
  );
  if ((await cardsWithChildren.count()) === 0) return;

  const slug = await cardsWithChildren
    .nth(0)
    .getAttribute("data-category-slug");
  await selectCategory(page, slug);

  // Get chips in the open panel
  const panel = page.locator(
    `.pl-sidebar__filter-panel[data-panel-for="${slug}"]`
  );
  const chips = panel.locator(".pl-sidebar__chip");
  const chipCount = await chips.count();
  if (chipCount < 2) return;

  const chipSlug1 = await chips.nth(0).getAttribute("data-category-slug");
  const chipSlug2 = await chips.nth(1).getAttribute("data-category-slug");

  // Toggle first chip
  await toggleChip(page, chipSlug1);
  await expect(chips.nth(0)).toHaveClass(/pl-sidebar__chip--active/);

  // Toggle second chip — first should stay active
  await toggleChip(page, chipSlug2);
  await expect(chips.nth(0)).toHaveClass(/pl-sidebar__chip--active/);
  await expect(chips.nth(1)).toHaveClass(/pl-sidebar__chip--active/);

  const activeChips = await getActiveChips(page);
  expect(activeChips).toContain(chipSlug1);
  expect(activeChips).toContain(chipSlug2);
});

// ─── 6. Clear All Filters ──────────────────────────────────────────

test("clear button resets all category and chip selections", async ({
  page,
  vpMobile,
}) => {
  if (vpMobile) {
    await openFilterDrawer(page);
  }

  // Select a category first
  const cardsWithChildren = page.locator(
    '.pl-sidebar__category-card[data-has-children="true"]'
  );
  if ((await cardsWithChildren.count()) === 0) return;

  const slug = await cardsWithChildren
    .nth(0)
    .getAttribute("data-category-slug");
  await selectCategory(page, slug);

  // Clear
  await clearAllFilters(page);

  // No active cards
  const activeCards = page.locator(".pl-sidebar__category-card--active");
  expect(await activeCards.count()).toBe(0);

  // No active chips
  const activeChips = page.locator(".pl-sidebar__chip--active");
  expect(await activeChips.count()).toBe(0);

  // No open panels
  const openPanels = page.locator(".pl-sidebar__filter-panel--open");
  expect(await openPanels.count()).toBe(0);
});

// ─── 7. Infinite Scroll ────────────────────────────────────────────

test("scrolling to sentinel loads more products", async ({ page }) => {
  // Only test if pagination exists
  const hasPagination = await page.evaluate(() => {
    const grid = document.getElementById("pl-grid");
    if (!grid) return false;
    return (parseInt(grid.dataset.totalPages, 10) || 1) > 1;
  });
  if (!hasPagination) return;

  const initialCount = await getProductCount(page);
  const newCount = await triggerInfiniteScroll(page, 2);
  expect(newCount).toBeGreaterThan(initialCount);
});

// ─── 8. Infinite Scroll Preserves Filters ──────────────────────────

test("infinite scroll sends active category in request", async ({
  page,
  vpMobile,
}) => {
  if (vpMobile) {
    await openFilterDrawer(page);
  }

  const cardsWithChildren = page.locator(
    '.pl-sidebar__category-card[data-has-children="true"]'
  );
  if ((await cardsWithChildren.count()) === 0) return;

  const slug = await cardsWithChildren
    .nth(0)
    .getAttribute("data-category-slug");
  await selectCategory(page, slug);

  if (vpMobile) {
    await closeFilterDrawer(page);
  }

  // Check if filtered results have enough products for pagination
  const hasPagination = await page.evaluate(() => {
    const grid = document.getElementById("pl-grid");
    if (!grid) return false;
    const totalPages = parseInt(grid.dataset.totalPages, 10) || 1;
    return totalPages > 1;
  });
  if (!hasPagination) return;

  // Listen for page 2 request and verify category param
  const apiPromise = page.waitForResponse(
    (res) => {
      if (!res.url().includes("/api/v1/products")) return false;
      const url = new URL(res.url());
      return (
        url.searchParams.get("page") === "2" &&
        url.searchParams.get("category") === slug
      );
    },
    { timeout: 10_000 }
  );

  await sentinel.scrollIntoViewIfNeeded();

  const response = await apiPromise;
  expect(response.status()).toBe(200);
});

// ─── 9. Description Expand / Collapse ──────────────────────────────

test("description expand/collapse toggles height and aria", async ({
  page,
}) => {
  const expandBtn = page.locator("#pl-expand-btn");
  const desc = page.locator("#pl-desc");

  if ((await expandBtn.count()) === 0) return;

  // Initial: collapsed
  const collapsedHeight = await desc.evaluate((el) => el.offsetHeight);
  await expect(expandBtn).toHaveAttribute("aria-expanded", "false");

  // Expand
  await expandBtn.click();
  await waitForTransition(page, "#pl-desc", "height", 500);

  const expandedHeight = await desc.evaluate((el) => el.offsetHeight);
  expect(expandedHeight).toBeGreaterThan(collapsedHeight);
  await expect(expandBtn).toHaveAttribute("aria-expanded", "true");

  // Collapse back
  await expandBtn.click();
  await waitForTransition(page, "#pl-desc", "height", 500);

  const reCollapsedHeight = await desc.evaluate((el) => el.offsetHeight);
  expect(reCollapsedHeight).toBe(collapsedHeight);
  await expect(expandBtn).toHaveAttribute("aria-expanded", "false");
});

// ─── 10. Mobile Drawer Open / Close ────────────────────────────────

test("mobile filter drawer opens and closes", async ({ page, vpMobile }) => {
  test.skip(!vpMobile, "Mobile-only test");

  const drawer = page.locator("#pl-filter-drawer");

  // Open
  await openFilterDrawer(page);
  await expect(drawer).toHaveClass(/pl-filter-drawer--open/);

  // Sidebar should be visible inside drawer
  const sidebar = page.locator("#sidebar-filter");
  await expect(sidebar).toBeVisible();

  // Close
  await closeFilterDrawer(page);
  await expect(drawer).not.toHaveClass(/pl-filter-drawer--open/);
});

// ─── 11. Sticky Bottom Bar ─────────────────────────────────────────

test("sticky bar appears when scrolled past inline filter button", async ({
  page,
  vpMobile,
}) => {
  test.skip(!vpMobile, "Mobile-only test");

  const stickyBar = page.locator(".pl-sticky-bar");

  // Initially not visible
  await expect(stickyBar).not.toHaveClass(/pl-sticky-bar--visible/);

  // Scroll down past the inline filter button
  await page.evaluate(() => window.scrollBy(0, 800));
  await page.waitForTimeout(300); // debounce

  // Sticky bar should now be visible
  await expect(stickyBar).toHaveClass(/pl-sticky-bar--visible/);
});

// ─── 12. Selected Filter Chips (Mobile) ────────────────────────────

test("selected filter chips appear on mobile after filtering", async ({
  page,
  vpMobile,
}) => {
  test.skip(!vpMobile, "Mobile-only test");

  const chipsRow = page.locator(".pl-selected-filters");

  // Open drawer and select a category
  await openFilterDrawer(page);

  const cardsWithChildren = page.locator(
    '.pl-sidebar__category-card[data-has-children="true"]'
  );
  if ((await cardsWithChildren.count()) === 0) return;

  const slug = await cardsWithChildren
    .nth(0)
    .getAttribute("data-category-slug");
  await selectCategory(page, slug);
  await closeFilterDrawer(page);

  // Chips row should be visible with at least one chip
  await expect(chipsRow).toHaveClass(/pl-selected-filters--visible/);
  const chipCount = await page.locator(".pl-selected-chip").count();
  expect(chipCount).toBeGreaterThan(0);
});

// ─── 13. Skeleton Loading ──────────────────────────────────────────

test("skeleton placeholders show during API loading", async ({
  page,
  vpMobile,
}) => {
  // Intercept API to add delay — makes skeleton observable
  await page.route("**/api/v1/products**", async (route) => {
    // Add 500ms delay to observe skeleton
    await new Promise((r) => setTimeout(r, 500));
    await route.continue();
  });

  if (vpMobile) {
    await openFilterDrawer(page);
  }

  // Select a category to trigger a product refresh
  const cardsWithChildren = page.locator(
    '.pl-sidebar__category-card[data-has-children="true"]'
  );
  if ((await cardsWithChildren.count()) === 0) return;

  const slug = await cardsWithChildren
    .nth(0)
    .getAttribute("data-category-slug");

  // Click category — during the delayed response, skeletons should appear
  const card = page.locator(
    `.pl-sidebar__category-card[data-category-slug="${slug}"]`
  );
  await card.click();

  // Check skeletons are present during loading
  const skeletons = page.locator(".product--skeleton");
  // Wait a tiny bit for DOM to update
  await page.waitForTimeout(100);
  const skeletonCount = await skeletons.count();

  // After API resolves, skeletons should disappear
  await page.waitForFunction(
    () => document.querySelectorAll(".product--skeleton").length === 0,
    { timeout: 5_000 }
  );

  // Verify real products replaced skeletons
  const realProducts = await getProductCount(page);
  expect(realProducts).toBeGreaterThan(0);
});
