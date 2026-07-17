function sectionSidebarScripts() {
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

    // Position triangle for server-rendered active card
    const activeCard = sidebar.querySelector(".pl-sidebar__category-card--active");
    if (activeCard) {
        const row = activeCard.closest(".pl-sidebar__category-row");
        if (row) {
            const triangle = row.querySelector(".pl-sidebar__triangle");
            if (triangle) {
                positionTriangle(triangle, activeCard, row);
            }
        }
    }
}
/**
 * Position the triangle indicator under the active card's center
 */
function positionTriangle(triangle, card) {
    const triangleRow = triangle.parentElement;
    if (!triangleRow) return;

    const parentRect = triangleRow.getBoundingClientRect();
    const cardRect = card.getBoundingClientRect();
    const triangleHalf = 14; // 0.875rem
    const cardCenter = cardRect.left + cardRect.width / 2 - parentRect.left;
    triangle.style.left = (cardCenter - triangleHalf) + "px";
}

/*
 * Measure the natural height of an element by cloning it off-screen.
 * This avoids touching the original element's transition/styles.
 */
function measureHeight(el) {
    const clone = el.cloneNode(true);
    clone.style.cssText =
        "height:auto!important;max-height:none!important;overflow:hidden!important;" +
        "position:absolute!important;visibility:hidden!important;pointer-events:none!important;" +
        "left:-9999px!important;top:-9999px!important;" +
        "width:" + el.offsetWidth + "px!important;transition:none!important;";
    el.parentNode.insertBefore(clone, el);
    const height = clone.scrollHeight;
    clone.remove();
    return height;
}

/*
 * Expand an element's height from 0 to its natural height.
 */
function expandHeight(el) {
    const targetHeight = measureHeight(el);
    el.style.height = targetHeight + "px";
}

/**
 * Collapse an element's height to 0
 * Uses current computed height as start value so the transition works.
 */
function collapseHeight(el) {
    el.style.height = el.scrollHeight + "px";
    // Force reflow so the browser registers the explicit height
    el.offsetHeight; // eslint-disable-line no-unused-expressions
    el.style.height = "0";
}

// SVG templates for chip checkbox states
const CHECKBOX_CHECKED_SVG = `<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 1.5C3.17893 1.5 1.5 3.17893 1.5 5.25V12.75C1.5 14.8211 3.17893 16.5 5.25 16.5H12.75C14.8211 16.5 16.5 14.8211 16.5 12.75V5.25C16.5 3.17893 14.8211 1.5 12.75 1.5H5.25ZM5.625 2.625C3.96815 2.625 2.625 3.96815 2.625 5.625V12.375C2.625 14.0319 3.96815 15.375 5.625 15.375H12.375C14.0319 15.375 15.375 14.0319 15.375 12.375V5.625C15.375 3.96815 14.0319 2.625 12.375 2.625H5.625Z" fill="#CB5140" />
                                                            <path d="M13.5 5.625L7.3125 11.8125L4.5 9" stroke="#CB5140" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>`;

const CHECKBOX_UNCHECKED_SVG = `<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 1.5C3.17893 1.5 1.5 3.17893 1.5 5.25V12.75C1.5 14.8211 3.17893 16.5 5.25 16.5H12.75C14.8211 16.5 16.5 14.8211 16.5 12.75V5.25C16.5 3.17893 14.8211 1.5 12.75 1.5H5.25ZM5.625 2.625C3.96815 2.625 2.625 3.96815 2.625 5.625V12.375C2.625 14.0319 3.96815 15.375 5.625 15.375H12.375C14.0319 15.375 15.375 14.0319 15.375 12.375V5.625C15.375 3.96815 14.0319 2.625 12.375 2.625H5.625Z" fill="#1D1D1D" />
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

                            // Reset tất cả chips trong panel cũ để tránh accumulate category slugs
                            p.querySelectorAll(".pl-sidebar__chip--active").forEach((chip) => {
                                setChipState(chip, false);
                            });
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
                    // Reset all chips in panel, then activate "Tất cả" chip
                    panel.querySelectorAll(".pl-sidebar__chip").forEach((c) => {
                        c.classList.remove("pl-sidebar__chip--active");
                        const icon = c.querySelector(".pl-sidebar__chip-icon");
                        if (icon) icon.innerHTML = CHECKBOX_UNCHECKED_SVG;
                    });
                    const allChip = panel.querySelector(`.pl-sidebar__chip[data-category-slug="${slug}"]`);
                    if (allChip) {
                        allChip.classList.add("pl-sidebar__chip--active");
                        const icon = allChip.querySelector(".pl-sidebar__chip-icon");
                        if (icon) icon.innerHTML = CHECKBOX_CHECKED_SVG;
                    }

                    // Expand triangle and position it under active card
                    const triangleRow = row.querySelector(
                        ".pl-sidebar__triangle-row"
                    );
                    if (triangleRow) {
                        const triangle = triangleRow.querySelector(".pl-sidebar__triangle");
                        if (triangle) {
                            positionTriangle(triangle, card);
                        }
                        triangleRow.classList.add(
                            "pl-sidebar__triangle-row--visible"
                        );
                        expandHeight(triangleRow);
                    }
                    // Expand panel
                    panel.classList.add("pl-sidebar__filter-panel--open");
                    panel.offsetHeight; // force reflow to pick up new padding
                    expandHeight(panel);
                }
            }

            dispatchFilterChange(sidebar);
        });
    });
}

/**
 * Filter Chips — toggle checkbox
 * - Click chip "Tất cả" → check tất cả con + check "Tất cả"
 * - Click chip con → uncheck "Tất cả", nếu check full con thì tích lại "Tất cả"
 */
function initFilterChips(sidebar) {
    const chips = sidebar.querySelectorAll(".pl-sidebar__chip");

    chips.forEach((chip) => {
        chip.addEventListener("click", () => {
            const panel = chip.closest(".pl-sidebar__filter-panel");
            if (!panel) return;

            const allChip = panel.querySelector('.pl-sidebar__chip[data-chip-role="all"]');
            const childChips = Array.from(
                panel.querySelectorAll('.pl-sidebar__chip:not([data-chip-role="all"])')
            );

            if (chip.dataset.chipRole === "all") {
                // Click "Tất cả" → check tất cả (bao gồm chính nó) + check tất cả con
                setChipState(allChip, true);
                childChips.forEach((c) => setChipState(c, true));
            } else {
                // Click chip con → toggle nó
                chip.classList.toggle("pl-sidebar__chip--active");
                const iconEl = chip.querySelector(".pl-sidebar__chip-icon");
                if (iconEl) {
                    iconEl.innerHTML = chip.classList.contains("pl-sidebar__chip--active")
                        ? CHECKBOX_CHECKED_SVG
                        : CHECKBOX_UNCHECKED_SVG;
                }

                // Kiểm tra tất cả con đã check hết chưa
                const allChildrenChecked = childChips.every((c) =>
                    c.classList.contains("pl-sidebar__chip--active")
                );
                const anyChildChecked = childChips.some((c) =>
                    c.classList.contains("pl-sidebar__chip--active")
                );

                if (allChildrenChecked) {
                    // Full con → tích lại "Tất cả"
                    setChipState(allChip, true);
                } else if (anyChildChecked) {
                    // Có ít nhất 1 con → bỏ "Tất cả"
                    setChipState(allChip, false);
                } else {
                    // Không còn con nào → bỏ "Tất cả"
                    setChipState(allChip, false);
                }
            }

            dispatchFilterChange(sidebar);
        });
    });
}

/**
 * Helper: set chip checked/unchecked state
 */
function setChipState(chip, active) {
    if (!chip) return;
    if (active) {
        chip.classList.add("pl-sidebar__chip--active");
    } else {
        chip.classList.remove("pl-sidebar__chip--active");
    }
    const iconEl = chip.querySelector(".pl-sidebar__chip-icon");
    if (iconEl) {
        iconEl.innerHTML = active ? CHECKBOX_CHECKED_SVG : CHECKBOX_UNCHECKED_SVG;
    }
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

    // Update button state on filter changes
    updateClearButtonState(sidebar);
    sidebar.addEventListener("filterchange", () => {
        updateClearButtonState(sidebar);
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
function getActiveFilters(sidebar) {
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

/**
 * Update clear button state based on active filters
 */
function updateClearButtonState(sidebar) {
    const clearBtn = sidebar.querySelector(".pl-sidebar__clear-btn");
    if (!clearBtn) return;

    const filters = getActiveFilters(sidebar);
    const hasActiveFilters = 
        filters.category !== "" || 
        filters.subcategories.length > 0 || 
        filters.minPrice !== 100000 || 
        filters.maxPrice !== 10000000;

    if (hasActiveFilters) {
        clearBtn.disabled = false;
        clearBtn.removeAttribute("aria-disabled");
    } else {
        clearBtn.disabled = true;
        clearBtn.setAttribute("aria-disabled", "true");
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
