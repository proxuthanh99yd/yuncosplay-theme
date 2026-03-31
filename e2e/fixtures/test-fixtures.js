import { test as base } from "@playwright/test";

/**
 * Custom test fixtures — auto-detect viewport type from project config.
 *
 * Uses "vp*" prefix to avoid collision with Playwright built-in `isMobile`.
 */
export const test = base.extend({
  /** true when viewport width < 640 */
  vpMobile: async ({ page }, use) => {
    const vp = page.viewportSize();
    await use(vp ? vp.width < 640 : false);
  },

  /** true when viewport width 640–1023 */
  vpTablet: async ({ page }, use) => {
    const vp = page.viewportSize();
    await use(vp ? vp.width >= 640 && vp.width < 1024 : false);
  },

  /** true when viewport width >= 1024 */
  vpDesktop: async ({ page }, use) => {
    const vp = page.viewportSize();
    await use(vp ? vp.width >= 1024 : false);
  },
});

export { expect } from "@playwright/test";
