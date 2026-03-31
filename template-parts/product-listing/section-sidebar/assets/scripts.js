export function sectionSidebarScripts() {
    const sidebar = document.getElementById("sidebar-filter");
    if (!sidebar) return;

    initOpenPanels(sidebar);
    initPriceSlider(sidebar);
    initCategoryCards(sidebar);
    initFilterChips(sidebar);
    initClearButton(sidebar);
    initDrawer();
}

/**
 * Set height for panels/triangles already open from PHP (server-rendered --open/--visible).
 * Runs without transition so there's no flash.
 */
function initOpenPanels(sidebar) {
    const openPanels = sidebar.querySelectorAll(
        ".pl-sidebar__filter-panel--open"
    );
    const visibleTriangles = sidebar.querySelectorAll(
        ".pl-sidebar__triangle-row--visible"
    );

    visibleTriangles.forEach((el) => {
        el.style.transition = "none";
        el.style.height = el.scrollHeight + "px";
        el.offsetHeight;
        el.style.transition = "";
    });

    openPanels.forEach((el) => {
        el.style.transition = "none";
        el.style.height = el.scrollHeight + "px";
        el.offsetHeight;
        el.style.transition = "";
    });
}
/*
 * Expand an element's height from 0 to its scrollHeight
 */
export function expandHeight(el) {
    el.style.height = el.scrollHeight + "px";
}

/**
 * Collapse an element's height to 0
 * Uses current computed height as start value so the transition works.
 */
export function collapseHeight(el) {
    el.style.height = el.scrollHeight + "px";
    // Force reflow so the browser registers the explicit height
    el.offsetHeight; // eslint-disable-line no-unused-expressions
    el.style.height = "0";
}

// SVG templates for chip checkbox states
const CHECKBOX_CHECKED_SVG = `<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect width="18" height="18" rx="4" fill="#CB5140"/>
  <path d="M4 9L7.5 12.5L14 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`;

const CHECKBOX_UNCHECKED_SVG = `<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect x="0.5" y="0.5" width="17" height="17" rx="3.5" stroke="#1C1C1C"/>
</svg>`;

/**
 * Price Range Slider (noUiSlider)
 */
function initPriceSlider(sidebar) {
    const sliderEl = sidebar.querySelector("#pl-price-slider");
    if (!sliderEl || typeof noUiSlider === "undefined") return;

    const minLabel = sidebar.querySelector('[data-role="min-label"]');
    const maxLabel = sidebar.querySelector('[data-role="max-label"]');

    noUiSlider.create(sliderEl, {
        start: [100000, 10000000],
        connect: true,
        range: {
            min: 100000,
            max: 10000000,
        },
        step: 10000,
        format: {
            to: (value) => Math.round(value),
            from: (value) => Number(value),
        },
    });

    sliderEl.noUiSlider.on("update", (values) => {
        const min = values[0];
        const max = values[1];
        if (minLabel)
            minLabel.textContent = Number(min).toLocaleString("vi-VN") + "đ";
        if (maxLabel)
            maxLabel.textContent = Number(max).toLocaleString("vi-VN") + "đ";
    });

    sliderEl.noUiSlider.on("change", () => {
        dispatchFilterChange(sidebar);
    });
}

/**
 * Category Cards — click to select, show triangle + filter panel within same row
 */
function initCategoryCards(sidebar) {
    const cards = sidebar.querySelectorAll(".pl-sidebar__category-card");
    const rows = sidebar.querySelectorAll(".pl-sidebar__category-row");

    cards.forEach((card) => {
        card.addEventListener("click", () => {
            const slug = card.dataset.categorySlug;
            const isActive = card.classList.contains(
                "pl-sidebar__category-card--active"
            );

            // Deactivate all cards
            cards.forEach((c) =>
                c.classList.remove("pl-sidebar__category-card--active")
            );

            // Collapse all triangle rows and filter panels across all rows
            rows.forEach((row) => {
                const triangleRow = row.querySelector(
                    ".pl-sidebar__triangle-row"
                );
                if (
                    triangleRow &&
                    triangleRow.classList.contains(
                        "pl-sidebar__triangle-row--visible"
                    )
                ) {
                    triangleRow.classList.remove(
                        "pl-sidebar__triangle-row--visible"
                    );
                    collapseHeight(triangleRow);
                }
                row.querySelectorAll(".pl-sidebar__filter-panel").forEach(
                    (p) => {
                        if (
                            p.classList.contains(
                                "pl-sidebar__filter-panel--open"
                            )
                        ) {
                            p.classList.remove(
                                "pl-sidebar__filter-panel--open"
                            );
                            collapseHeight(p);
                        }
                    }
                );
            });

            if (!isActive) {
                card.classList.add("pl-sidebar__category-card--active");

                // Find the row this card belongs to
                const row = card.closest(".pl-sidebar__category-row");
                if (!row) return;

                // Show matching filter panel within this row
                const panel = row.querySelector(
                    `.pl-sidebar__filter-panel[data-panel-for="${slug}"]`
                );
                if (panel) {
                    // Expand triangle
                    const triangleRow = row.querySelector(
                        ".pl-sidebar__triangle-row"
                    );
                    if (triangleRow) {
                        triangleRow.classList.add(
                            "pl-sidebar__triangle-row--visible"
                        );
                        expandHeight(triangleRow);
                    }
                    // Expand panel
                    panel.classList.add("pl-sidebar__filter-panel--open");
                    expandHeight(panel);
                }
            }

            dispatchFilterChange(sidebar);
        });
    });
}

/**
 * Filter Chips — toggle checkbox
 */
function initFilterChips(sidebar) {
    const chips = sidebar.querySelectorAll(".pl-sidebar__chip");

    chips.forEach((chip) => {
        chip.addEventListener("click", () => {
            chip.classList.toggle("pl-sidebar__chip--active");

            const iconEl = chip.querySelector(".pl-sidebar__chip-icon");
            if (iconEl) {
                if (chip.classList.contains("pl-sidebar__chip--active")) {
                    iconEl.innerHTML = CHECKBOX_CHECKED_SVG;
                } else {
                    iconEl.innerHTML = CHECKBOX_UNCHECKED_SVG;
                }
            }

            dispatchFilterChange(sidebar);
        });
    });
}

/**
 * Clear Button — reset all filters
 */
function initClearButton(sidebar) {
    const clearBtn = sidebar.querySelector(".pl-sidebar__clear-btn");
    if (!clearBtn) return;

    clearBtn.addEventListener("click", () => {
        // Deactivate all category cards
        sidebar
            .querySelectorAll(".pl-sidebar__category-card--active")
            .forEach((c) =>
                c.classList.remove("pl-sidebar__category-card--active")
            );

        // Collapse all triangle rows and filter panels
        sidebar
            .querySelectorAll(".pl-sidebar__triangle-row--visible")
            .forEach((t) => {
                t.classList.remove("pl-sidebar__triangle-row--visible");
                collapseHeight(t);
            });
        sidebar
            .querySelectorAll(".pl-sidebar__filter-panel--open")
            .forEach((p) => {
                p.classList.remove("pl-sidebar__filter-panel--open");
                collapseHeight(p);
            });

        // Deactivate all chips and reset checkbox SVGs
        sidebar
            .querySelectorAll(".pl-sidebar__chip--active")
            .forEach((chip) => {
                chip.classList.remove("pl-sidebar__chip--active");
                const iconEl = chip.querySelector(".pl-sidebar__chip-icon");
                if (iconEl) {
                    iconEl.innerHTML = CHECKBOX_UNCHECKED_SVG;
                }
            });

        // Reset price slider
        const sliderEl = sidebar.querySelector("#pl-price-slider");
        if (sliderEl && sliderEl.noUiSlider) {
            sliderEl.noUiSlider.set([100000, 10000000]);
        }

        dispatchFilterChange(sidebar);
    });
}

/**
 * Dispatch custom event for filter changes
 */
function dispatchFilterChange(sidebar) {
    const filters = getActiveFilters(sidebar);
    sidebar.dispatchEvent(
        new CustomEvent("filterchange", {
            detail: filters,
            bubbles: true,
        })
    );
}

/**
 * Get all active filter values
 */
export function getActiveFilters(sidebar) {
    if (!sidebar) sidebar = document.getElementById("sidebar-filter");
    if (!sidebar)
        return {
            category: "",
            subcategories: [],
            minPrice: 100000,
            maxPrice: 10000000,
        };

    const filters = {
        category: "",
        subcategories: [],
        minPrice: 100000,
        maxPrice: 10000000,
    };

    // Active category
    const activeCard = sidebar.querySelector(
        ".pl-sidebar__category-card--active"
    );
    if (activeCard) {
        filters.category = activeCard.dataset.categorySlug || "";
    }

    // Active subcategory chips
    const activeChips = sidebar.querySelectorAll(".pl-sidebar__chip--active");
    activeChips.forEach((chip) => {
        const value = chip.dataset.filterValue;
        if (value) filters.subcategories.push(value);
    });

    // Price range
    const sliderEl = sidebar.querySelector("#pl-price-slider");
    if (sliderEl && sliderEl.noUiSlider) {
        const values = sliderEl.noUiSlider.get();
        filters.minPrice = Number(values[0]);
        filters.maxPrice = Number(values[1]);
    }

    return filters;
}

/**
 * Filter Drawer — open/close, scroll lock
 */
function initDrawer() {
    const drawer = document.getElementById("pl-filter-drawer");
    if (!drawer) return;

    // Open buttons (filter button in content + sticky bar)
    document.querySelectorAll('[data-action="open-drawer"]').forEach((btn) => {
        btn.addEventListener("click", () => openDrawer(drawer));
    });

    // Close button inside drawer
    const closeBtn = drawer.querySelector('[data-action="close-drawer"]');
    if (closeBtn) {
        closeBtn.addEventListener("click", () => closeDrawer(drawer));
    }

    // Close on overlay click (only if clicking the drawer wrapper itself, not children)
    drawer.addEventListener("click", (e) => {
        if (e.target === drawer) closeDrawer(drawer);
    });

    // Close on Escape key
    document.addEventListener("keydown", (e) => {
        if (
            e.key === "Escape" &&
            drawer.classList.contains("pl-filter-drawer--open")
        ) {
            closeDrawer(drawer);
        }
    });
}

function openDrawer(drawer) {
    drawer.classList.add("pl-filter-drawer--open");
    document.body.style.overflow = "hidden";

    // Pause Lenis smooth scroll
    if (window.lenis) {
        window.lenis.stop();
    }
}

function closeDrawer(drawer) {
    drawer.classList.remove("pl-filter-drawer--open");
    document.body.style.overflow = "";

    // Resume Lenis smooth scroll
    if (window.lenis) {
        window.lenis.start();
    }
}
