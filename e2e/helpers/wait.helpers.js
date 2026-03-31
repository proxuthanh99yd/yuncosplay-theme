/**
 * Wait for a CSS transition to finish on an element.
 *
 * @param {import('@playwright/test').Page} page
 * @param {string} selector  CSS selector of the transitioning element
 * @param {string} property  CSS property name to wait for (e.g. "height", "transform")
 * @param {number} [timeout=600]  Safety timeout in ms
 */
export async function waitForTransition(page, selector, property, timeout = 600) {
  await page.evaluate(
    ({ sel, prop, ms }) =>
      new Promise((resolve) => {
        const el = document.querySelector(sel);
        if (!el) return resolve();

        const timer = setTimeout(resolve, ms);

        el.addEventListener(
          "transitionend",
          (e) => {
            if (e.propertyName === prop) {
              clearTimeout(timer);
              resolve();
            }
          },
          { once: true }
        );
      }),
    { sel: selector, prop: property, ms: timeout }
  );
}

/**
 * Wait until all skeleton products are replaced by real products.
 *
 * @param {import('@playwright/test').Page} page
 */
export async function waitForSkeletonDone(page) {
  await page.waitForFunction(
    () => document.querySelectorAll(".product--skeleton").length === 0,
    { timeout: 10_000 }
  );
}

/**
 * Navigate to /shop/ and wait until page is interactive:
 * products loaded + noUiSlider initialized.
 *
 * @param {import('@playwright/test').Page} page
 */
export async function waitForPageReady(page) {
  await page.goto("/shop/", { waitUntil: "domcontentloaded" });

  // Wait for at least one real product
  await page.waitForFunction(
    () =>
      document.querySelectorAll(
        ".pl-content__grid .product:not(.product--skeleton)"
      ).length > 0,
    { timeout: 15_000 }
  );

  // Wait for noUiSlider to init
  await page.waitForFunction(
    () => {
      const slider = document.querySelector("#pl-price-slider");
      return slider && slider.noUiSlider;
    },
    { timeout: 10_000 }
  );
}

/**
 * Wait for the products API response.
 *
 * @param {import('@playwright/test').Page} page
 * @returns {Promise<import('@playwright/test').Response>}
 */
export async function waitForProductsResponse(page) {
  return page.waitForResponse(
    (res) =>
      res.url().includes("/api/v1/products") && res.status() === 200,
    { timeout: 10_000 }
  );
}
