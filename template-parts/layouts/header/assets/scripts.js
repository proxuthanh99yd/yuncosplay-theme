/**
 * MegaMenu - Desktop mega menu management
 * Handles panel switching, map images, country items, and triangle positioning
 */
class MegaMenu {
    constructor(options = {}) {
        this.megaMenu = options.megaMenu || document.querySelector(".header-mega-menu");
        this.megaTriggers = options.megaTriggers || document.querySelectorAll("[data-mega-menu]");
        this.pageOverlay = options.pageOverlay || document.querySelector(".page-overlay");
        this.mapImages = options.mapImages || document.querySelectorAll(".header-mega-menu__map-img");
        this.countryItems = options.countryItems || document.querySelectorAll(".header-mega-menu__country-item");

        this.destinationMapSvg =
            this.megaMenu?.querySelector('.header-mega-menu__inner[data-panel="destination"] .header-mega-menu__map .destinations-map') || null;
        this.destinationMapCountries = ["vietnam", "cambodia", "laos"];
        this.destinationMapPathsCache = new Map();

        this.activeTrigger = null;
        this.onCloseCallback = options.onClose || null;
        this.isMobileCheck = options.isMobileCheck || (() => window.innerWidth <= 639.98);

        if (this.megaMenu) {
            this.init();
            this.events();
        }
    }

    updateViewAllLink(panelEl, itemEl) {
        if (!panelEl) return;
        const btn = panelEl.querySelector(".header-mega-menu__journeys .compound-avian-button");
        if (!btn) return;

        const href = itemEl?.getAttribute("href");
        if (!href) return;

        btn.setAttribute("href", href);
    }

    switchJourneysGroup(panelEl, keyAttr, keyValue) {
        if (!panelEl || !keyAttr || !keyValue) return;
        const listEl = panelEl.querySelector(".header-mega-menu__journeys-list");
        if (!listEl) return;

        const groups = listEl.querySelectorAll(".header-mega-menu__journeys-group");
        if (!groups.length) return;

        groups.forEach((g) => g.classList.remove("is-active"));
        const target = listEl.querySelector(`.header-mega-menu__journeys-group[${keyAttr}="${keyValue}"]`);
        if (target) {
            target.classList.add("is-active");
        }
    }

    syncDestinationPanelByCountry(country) {
        if (!this.megaMenu || !country) return;

        const panelEl = this.megaMenu.querySelector('.header-mega-menu__inner[data-panel="destination"]');
        if (!panelEl) return;

        const targetItem = panelEl.querySelector(`.header-mega-menu__country-item[data-country="${country}"]`);
        if (!targetItem) return;

        panelEl.querySelectorAll(".header-mega-menu__country-item").forEach((i) => i.classList.remove("active"));
        targetItem.classList.add("active");

        const journeysList = panelEl.querySelector(".header-mega-menu__journeys-list");
        if (!journeysList) return;

        const keyAttr = journeysList.getAttribute("data-key-attr");
        if (!keyAttr) return;

        const termSlug = targetItem.getAttribute(keyAttr);
        if (!termSlug) return;

        this.switchJourneysGroup(panelEl, keyAttr, termSlug);
    }

    initDestinationSvgMap() {
        if (!this.destinationMapSvg) return;
        this.destinationMapCountries.forEach((country) => {
            this.destinationMapPathsCache.set(country, this.destinationMapSvg.querySelectorAll(`path[data-country="${country}"]`));
        });
    }

    activateDestinationSvgCountry(country) {
        if (!this.destinationMapSvg || !country) return;

        const activeFill = "#E3B92D";
        const inactiveFill = "white";
        const inactiveOpacity = "0.48";

        this.destinationMapCountries.forEach((countryKey) => {
            const paths = this.destinationMapPathsCache.get(countryKey);
            if (!paths) return;

            const isActive = countryKey === country;
            paths.forEach((path) => {
                const isVietnamIsland = path.getAttribute("data-island") === "true" && countryKey === "vietnam";

                if (isActive) {
                    if (isVietnamIsland) {
                        path.style.fill = "rgb(227, 185, 45)";
                        path.style.fillOpacity = "1";
                        path.style.stroke = "rgb(227, 185, 45)";
                    } else {
                        path.style.fill = activeFill;
                        path.style.fillOpacity = "1";
                        path.style.stroke = "white";
                    }
                    path.setAttribute("data-active", "true");
                } else {
                    if (isVietnamIsland) {
                        path.style.fill = "rgba(255, 255, 255, 0.48)";
                        path.style.fillOpacity = "1";
                        path.style.stroke = "rgba(255, 255, 255, 0.48)";
                    } else {
                        path.style.fill = inactiveFill;
                        path.style.fillOpacity = inactiveOpacity;
                        path.style.stroke = "#E1BE47";
                    }
                    path.setAttribute("data-active", "false");
                }
            });
        });
    }

    init() {
        // Ẩn tất cả các panel trừ panel đầu tiên (destination)
        const allPanels = this.megaMenu.querySelectorAll("[data-panel]");
        allPanels.forEach((panel, index) => {
            if (index > 0) {
                panel.classList.add("header-mega-menu__panel--hidden");
            }
        });

        this.initDestinationSvgMap();

        // Preload tất cả map images và set trạng thái ban đầu
        this.initMapImages();

        this.initJourneys();
    }

    initMapImages() {
        if (this.destinationMapSvg) {
            const firstCountryItem = this.megaMenu?.querySelector(
                '.header-mega-menu__inner[data-panel="destination"] .header-mega-menu__country-item[data-country]',
            );
            const firstCountry = firstCountryItem?.getAttribute("data-country");
            if (firstCountry) {
                this.activateDestinationSvgCountry(firstCountry);
            }
            return;
        }

        // Ẩn tất cả map images trừ map đầu tiên
        this.mapImages.forEach((img, index) => {
            if (index === 0) {
                img.classList.remove("header-mega-menu__map-img--hidden");
            } else {
                img.classList.add("header-mega-menu__map-img--hidden");
            }
        });
    }

    initJourneys() {
        const panels = this.megaMenu?.querySelectorAll("[data-panel]");
        if (!panels?.length) return;

        panels.forEach((panelEl) => {
            const journeysList = panelEl.querySelector(".header-mega-menu__journeys-list");
            if (!journeysList) return;

            const taxonomy = journeysList.getAttribute("data-taxonomy");
            const postType = journeysList.getAttribute("data-post-type") || "any";
            const postsPerPage = parseInt(journeysList.getAttribute("data-posts-per-page") || "2", 10);
            const keyAttr = journeysList.getAttribute("data-key-attr");

            if (!taxonomy || !keyAttr) return;

            const firstCountryItem = panelEl.querySelector(`.header-mega-menu__country-item[${keyAttr}]`);
            const firstSlug = firstCountryItem?.getAttribute(keyAttr);
            if (!firstSlug) return;
            this.switchJourneysGroup(panelEl, keyAttr, firstSlug);
        });
    }

    open(trigger) {
        // Không mở mega menu desktop nếu đang ở mobile
        if (this.isMobileCheck()) {
            return;
        }

        if (!this.megaMenu || !trigger) return;

        // Lấy panel name từ data attribute
        const panelName = trigger.getAttribute("data-mega-menu");
        if (!panelName) return;

        // Đóng mega menu hiện tại nếu có
        if (this.activeTrigger) {
            this.activeTrigger.classList.remove("active");
        }

        // Ẩn tất cả các panel
        const allPanels = this.megaMenu.querySelectorAll("[data-panel]");
        allPanels.forEach((panel) => {
            panel.classList.add("header-mega-menu__panel--hidden");
        });

        // Hiển thị panel tương ứng
        const targetPanel = this.megaMenu.querySelector(`[data-panel="${panelName}"]`);
        if (targetPanel) {
            targetPanel.classList.remove("header-mega-menu__panel--hidden");
        }

        // Mở mega menu mới
        this.activeTrigger = trigger;
        this.megaMenu.classList.remove("header-mega-menu--hidden");
        trigger.classList.add("active");

        // Hiển thị page overlay
        if (this.pageOverlay) {
            this.pageOverlay.classList.remove("page-overlay--hidden");
        }

        // Reset map images về trạng thái ban đầu khi mở mega menu
        this.initMapImages();

        // Reset tours về trạng thái ban đầu khi mở mega menu
        this.initJourneys();

        this.setFirstCountryItemActive();

        const firstActiveItem =
            targetPanel?.querySelector(".header-mega-menu__country-item.active") || targetPanel?.querySelector(".header-mega-menu__country-item");
        if (firstActiveItem) {
            this.updateViewAllLink(targetPanel, firstActiveItem);
        }

        // Ngăn cuộn trang khi mở megamenu
        this.lockScroll();
    }

    close() {
        if (!this.megaMenu) return;

        this.megaMenu.classList.add("header-mega-menu--hidden");

        // Remove active class from all triggers
        if (this.megaTriggers?.length) {
            this.megaTriggers.forEach((t) => t.classList.remove("active"));
        }

        // Reset panels: hide all except first panel (destination)
        const allPanels = this.megaMenu.querySelectorAll("[data-panel]");
        if (allPanels.length) {
            allPanels.forEach((panel, index) => {
                panel.classList.toggle("header-mega-menu__panel--hidden", index !== 0);
            });
        }

        // Reset country items active state
        this.removeAllCountryItemsActive();

        // Ẩn page overlay
        if (this.pageOverlay) {
            this.pageOverlay.classList.add("page-overlay--hidden");
        }

        // Khôi phục cuộn trang khi đóng megamenu
        this.unlockScroll();

        this.activeTrigger = null;

        // Callback khi đóng
        if (this.onCloseCallback) {
            this.onCloseCallback();
        }
    }

    isOpen() {
        return this.megaMenu && !this.megaMenu.classList.contains("header-mega-menu--hidden");
    }

    lockScroll() {
        // Stop Lenis smooth scroll nếu có
        const lenisInstance = window.app?.lenis;
        if (lenisInstance && typeof lenisInstance.stop === "function") {
            lenisInstance.stop();
        }

        // Chặn scroll body
        document.body.style.overflow = "hidden";
        document.documentElement.style.overflow = "hidden";
    }

    unlockScroll() {
        // Start lại Lenis smooth scroll nếu có
        const lenisInstance = window.app?.lenis;
        if (lenisInstance && typeof lenisInstance.start === "function") {
            lenisInstance.start();
        }

        // Khôi phục scroll body
        document.body.style.overflow = "";
        document.documentElement.style.overflow = "";
    }

    setFirstCountryItemActive() {
        if (!this.megaMenu) return;

        const panelName = this.activeTrigger?.getAttribute("data-mega-menu") || "destination";
        const panelEl = this.megaMenu.querySelector(`[data-panel="${panelName}"]`);
        if (!panelEl) return;

        const items = panelEl.querySelectorAll(".header-mega-menu__country-item");
        if (!items.length) return;

        items.forEach((i) => i.classList.remove("active"));
        items[0].classList.add("active");
    }

    removeAllCountryItemsActive() {
        if (this.countryItems?.length) {
            this.countryItems.forEach((i) => i.classList.remove("active"));
        }
    }

    handleTriggers() {
        if (!this.megaTriggers.length || !this.megaMenu) return;

        this.megaTriggers.forEach((trigger) => {
            trigger.addEventListener("click", (e) => {
                if (this.isMobileCheck()) return;

                e.preventDefault();
                e.stopPropagation();

                const isHidden = this.megaMenu.classList.contains("header-mega-menu--hidden");

                if (isHidden) {
                    this.open(trigger);
                    this.setFirstCountryItemActive();
                } else {
                    if (this.activeTrigger === trigger) {
                        this.close();
                        this.removeAllCountryItemsActive();
                    } else {
                        this.open(trigger);
                        this.setFirstCountryItemActive();
                    }
                }
            });
        });
    }

    handleClickOutside() {
        document.addEventListener("click", (e) => {
            if (!this.megaMenu) return;

            // Bỏ qua nếu mega menu đang ẩn
            if (this.megaMenu.classList.contains("header-mega-menu--hidden")) {
                return;
            }

            // Kiểm tra xem popup có đang mở không
            const popup = document.querySelector(".destinations-popup");
            const isPopupOpen = popup && popup.classList.contains("destinations-popup--active");

            // Nếu popup đang mở, không đóng megamenu
            if (isPopupOpen) {
                return;
            }

            // Kiểm tra xem click có nằm trong mega menu hoặc các trigger không
            const isClickInsideMegaMenu = this.megaMenu.contains(e.target);
            const isClickOnTrigger = Array.from(this.megaTriggers).some((trigger) => trigger.contains(e.target));
            const isClickOnOverlay = this.pageOverlay && this.pageOverlay.contains(e.target);

            // Kiểm tra xem click có nằm trong popup không (để tránh đóng megamenu khi click vào popup)
            const isClickInsidePopup = popup && popup.contains(e.target);

            // Kiểm tra xem click có nằm trong language dropdown không (để đóng megamenu khi click vào language selector)
            const languageDropdown = document.querySelector(".header-info__item-language-dropdown");
            const isClickInsideLanguageDropdown = languageDropdown && languageDropdown.contains(e.target);

            if (isClickOnOverlay || (!isClickInsideMegaMenu && !isClickOnTrigger && !isClickInsidePopup && !isClickInsideLanguageDropdown)) {
                this.close();
                this.removeAllCountryItemsActive();
            } else if (isClickInsideLanguageDropdown) {
                // Đóng mega-menu khi click vào language dropdown
                this.close();
                this.removeAllCountryItemsActive();
            }
        });
    }

    handleCountryHover() {
        if (!this.countryItems.length) return;

        const activateItem = (item) => {
            const panelEl = item.closest("[data-panel]");
            if (!panelEl) return;

            panelEl.querySelectorAll(".header-mega-menu__country-item").forEach((i) => i.classList.remove("active"));
            item.classList.add("active");

            this.updateViewAllLink(panelEl, item);

            if (panelEl.getAttribute("data-panel") === "destination" && this.destinationMapSvg) {
                const country = item.getAttribute("data-country");
                if (country) {
                    this.activateDestinationSvgCountry(country);
                }
            } else {
                const mapSrc = item.getAttribute("data-map");
                if (mapSrc) {
                    this.mapImages.forEach((img) => img.classList.add("header-mega-menu__map-img--hidden"));
                    const targetMap = Array.from(this.mapImages).find((img) => img.getAttribute("data-map") === mapSrc);
                    if (targetMap) {
                        targetMap.classList.remove("header-mega-menu__map-img--hidden");
                    }
                }
            }

            const journeysList = panelEl.querySelector(".header-mega-menu__journeys-list");
            if (!journeysList) return;

            const taxonomy = journeysList.getAttribute("data-taxonomy");
            const postType = journeysList.getAttribute("data-post-type") || "any";
            const postsPerPage = parseInt(journeysList.getAttribute("data-posts-per-page") || "2", 10);
            const keyAttr = journeysList.getAttribute("data-key-attr");

            if (!taxonomy || !keyAttr) return;

            const termSlug = item.getAttribute(keyAttr);
            if (!termSlug) return;

            this.switchJourneysGroup(panelEl, keyAttr, termSlug);
        };

        this.countryItems.forEach((item) => {
            item.addEventListener("mouseenter", () => activateItem(item));
            item.addEventListener("focus", () => activateItem(item));
        });
    }

    handleMapClick() {
        if (this.destinationMapSvg) {
            this.destinationMapSvg.addEventListener(
                "mouseenter",
                (e) => {
                    const path = e.target.closest("path[data-country]");
                    if (!path) return;
                    const country = path.getAttribute("data-country");
                    if (!country) return;
                    this.activateDestinationSvgCountry(country);
                    this.syncDestinationPanelByCountry(country);
                },
                true,
            );

            this.destinationMapSvg.addEventListener("click", (e) => {
                const path = e.target.closest("path[data-country]");
                if (!path) return;
                e.stopPropagation();
                const country = path.getAttribute("data-country");
                if (!country) return;
                this.activateDestinationSvgCountry(country);
                this.syncDestinationPanelByCountry(country);
                if (typeof window.openDestinationPopup === "function") {
                    window.openDestinationPopup(country, "header");
                }
            });

            const svgPaths = this.destinationMapSvg.querySelectorAll("path[data-country]");
            svgPaths.forEach((p) => (p.style.cursor = "pointer"));
            return;
        }

        if (!this.mapImages.length) return;

        // Thêm click event cho tất cả map images
        this.mapImages.forEach((mapImg) => {
            mapImg.addEventListener("click", () => {
                // Tìm map image đang hiển thị (không có class --hidden)
                const visibleMap = Array.from(this.mapImages).find((img) => !img.classList.contains("header-mega-menu__map-img--hidden"));

                if (visibleMap) {
                    const country = visibleMap.getAttribute("data-country");
                    if (country && typeof window.openDestinationPopup === "function") {
                        // Truyền "header" làm source để popup tính toán vị trí đúng
                        window.openDestinationPopup(country, "header");
                    }
                } else {
                    // Fallback: lấy map đầu tiên nếu không tìm thấy map đang hiển thị
                    const firstMap = this.mapImages[0];
                    if (firstMap) {
                        const country = firstMap.getAttribute("data-country");
                        if (country && typeof window.openDestinationPopup === "function") {
                            window.openDestinationPopup(country, "header");
                        }
                    }
                }
            });

            // Thêm cursor pointer cho map images
            mapImg.style.cursor = "pointer";
        });
    }

    events() {
        this.handleTriggers();
        this.handleClickOutside();
        this.handleCountryHover();
        this.handleMapClick();
    }
}

/**
 * MobileMenu - Mobile menu management
 * Handles submenu accordion, language drawer, and open/close
 */
class MobileMenu {
    constructor(options = {}) {
        this.megaMenuMobile = options.megaMenuMobile || document.querySelector(".header-mega-menu-mobile");
        this.mobileMenuToggle = options.mobileMenuToggle || document.getElementById("header-mobile-menu-toggle");
        this.mobileMenuClose = options.mobileMenuClose || this.megaMenuMobile?.querySelector(".header-top__right-mb");
        this.pageOverlay = options.pageOverlay || document.querySelector(".page-overlay");
        this.onCloseCallback = options.onClose || null;

        this.headerEl = options.headerEl || document.querySelector(".header");

        this.mobileSearchInputEl = this.megaMenuMobile?.querySelector("#header-mega-menu-mobile-search-input") || null;
        this.mobileSearchResultWrapperEl = this.megaMenuMobile?.querySelector(".header-mega-menu-mobile__search-results") || null;
        this.mobileSearchResultEl = this.megaMenuMobile?.querySelector("#header-mega-menu-mobile-search-result") || null;
        this.mobileSearchResultTemplate = this.megaMenuMobile?.querySelector("#header-mega-menu-mobile-search-result-item") || null;
        this.mobileSearchDebounceTimer = null;
        this.mobileSearchAbortController = null;

        if (this.megaMenuMobile) {
            this.init();
            this.events();
        }
    }

    abortMobileSearch() {
        if (this.mobileSearchAbortController) {
            this.mobileSearchAbortController.abort();
            this.mobileSearchAbortController = null;
        }
    }

    clearMobileSearchResults() {
        if (this.mobileSearchResultEl) {
            this.mobileSearchResultEl.innerHTML = "";
            this.mobileSearchResultEl.removeAttribute("data-empty");
        }
        if (this.mobileSearchResultWrapperEl) {
            this.mobileSearchResultWrapperEl.classList.add("header-mega-menu-mobile__search-results--hidden");
        }
    }

    async searchMobileAPI(query) {
        const base = window.wpApiSettings?.root;
        const nonce = window.wpApiSettings?.nonce;
        if (!base) return [];

        this.abortMobileSearch();
        const controller = new AbortController();
        this.mobileSearchAbortController = controller;

        const url = new URL("api/v1/search", base);
        url.searchParams.set("keyword", query);

        try {
            const res = await fetch(url.toString(), {
                method: "GET",
                headers: nonce ? { "X-WP-Nonce": nonce } : undefined,
                signal: controller.signal,
            });

            if (!res.ok) return [];
            const json = await res.json();
            if (json?.success && Array.isArray(json?.data)) return json.data;
            return [];
        } catch (err) {
            if (err && err.name === "AbortError") return [];
            return [];
        }
    }

    renderMobileSearchResults(results) {
        if (!this.mobileSearchResultEl || !this.mobileSearchResultTemplate || !this.mobileSearchResultWrapperEl) return;

        this.mobileSearchResultEl.innerHTML = "";
        this.mobileSearchResultEl.removeAttribute("data-empty");

        const items = Array.isArray(results) ? results : [];

        if (!items.length) {
            const template = this.mobileSearchResultTemplate.content.cloneNode(true);
            const textEl = template.querySelector(".header-mega-menu-mobile__search-results-text");
            const linkEl = template.querySelector(".header-mega-menu-mobile__search-results-link");
            if (textEl) textEl.textContent = "No results found";
            if (linkEl) {
                linkEl.setAttribute("href", "#");
                linkEl.addEventListener("click", (e) => e.preventDefault());
            }
            this.mobileSearchResultEl.appendChild(template);
            this.mobileSearchResultEl.setAttribute("data-empty", "true");
            this.mobileSearchResultWrapperEl.classList.remove("header-mega-menu-mobile__search-results--hidden");
            return;
        }

        items.forEach((item) => {
            const template = this.mobileSearchResultTemplate.content.cloneNode(true);
            const textEl = template.querySelector(".header-mega-menu-mobile__search-results-text");
            const linkEl = template.querySelector(".header-mega-menu-mobile__search-results-link");
            if (textEl) textEl.textContent = item?.title || "";
            if (linkEl) linkEl.setAttribute("href", item?.url || "#");
            this.mobileSearchResultEl.appendChild(template);
        });

        this.mobileSearchResultWrapperEl.classList.remove("header-mega-menu-mobile__search-results--hidden");
    }

    init() {
        // Set initial max-height to 0 for all submenus
        this.setSubmenuHeights();
    }

    setSubmenuHeights() {
        if (!this.megaMenuMobile) return;

        const mobileMenuItems = this.megaMenuMobile.querySelectorAll(".header-mega-menu-mobile__item");
        mobileMenuItems.forEach((item) => {
            const submenu = item.querySelector(".header-mega-menu-mobile__submenu");
            if (submenu) {
                submenu.style.maxHeight = "0px";
            }
        });
    }

    initSubmenuToggle() {
        if (!this.megaMenuMobile) return;

        const mobileMenuItems = this.megaMenuMobile.querySelectorAll(".header-mega-menu-mobile__item");

        mobileMenuItems.forEach((item) => {
            const hasSubMenuAttr = item.getAttribute("data-has-sub-menu");
            const hasSubMenu = hasSubMenuAttr && hasSubMenuAttr !== "0" && hasSubMenuAttr !== "false";
            const link = item.querySelector(".header-mega-menu-mobile__item-link");
            const submenu = item.querySelector(".header-mega-menu-mobile__submenu");

            if (hasSubMenu && link && submenu) {
                link.addEventListener("click", (e) => {
                    e.preventDefault();
                    this.toggleSubmenu(item);
                });
            }
        });
    }

    toggleSubmenu(item) {
        const isActive = item.classList.contains("header-mega-menu-mobile__item--active");
        const submenu = item.querySelector(".header-mega-menu-mobile__submenu");

        // Đóng tất cả submenu khác
        this.closeAllSubmenus();

        // Toggle submenu hiện tại
        if (!isActive) {
            item.classList.add("header-mega-menu-mobile__item--active");
            // Tính toán và set max-height
            if (submenu) {
                const scrollHeight = submenu.scrollHeight;
                submenu.style.maxHeight = `${scrollHeight}px`;
            }
        } else {
            item.classList.remove("header-mega-menu-mobile__item--active");
            // Set max-height về 0
            if (submenu) {
                submenu.style.maxHeight = "0px";
            }
        }
    }

    closeAllSubmenus() {
        if (!this.megaMenuMobile) return;

        const mobileMenuItems = this.megaMenuMobile.querySelectorAll(".header-mega-menu-mobile__item");
        mobileMenuItems.forEach((item) => {
            item.classList.remove("header-mega-menu-mobile__item--active");
            const submenu = item.querySelector(".header-mega-menu-mobile__submenu");
            if (submenu) {
                submenu.style.maxHeight = "0px";
            }
        });
    }

    handleLanguageDrawer() {
        const LANGUAGE_CODE = {
            English: "EN",
            Japanese: "JP",
        };

        const languageDrawer = document.querySelector(".header-mega-menu-mobile__footer-content-language-drawer");
        if (!languageDrawer) return;
        const languageDrawerTrigger = document.querySelector(".header-mega-menu-mobile__footer-content-language-drawer-trigger");
        const languageDrawerContent = document.querySelector(".header-mega-menu-mobile__footer-content-language-drawer-content");
        if (!languageDrawerTrigger || !languageDrawerContent) return;

        const languageDrawerTriggerFlag = languageDrawerTrigger.querySelector("img");
        const languageDrawerTriggerText = languageDrawerTrigger.querySelector("span");

        const currentLanguageLink = languageDrawerContent.querySelector("a.glink.gt-current-lang");
        if (currentLanguageLink && languageDrawerTriggerFlag && languageDrawerTriggerText) {
            const languageFlag = currentLanguageLink.querySelector("img")?.src || "";
            const languageText = currentLanguageLink.querySelector("span")?.textContent || "";

            languageDrawerTriggerFlag.src = languageFlag;
            languageDrawerTriggerText.textContent = LANGUAGE_CODE[languageText] || languageText;

			console.log({languageFlag, languageText})
        }

        const languageLinkItems = languageDrawerContent.querySelectorAll("a.glink");
        if (languageLinkItems.length && languageDrawerTriggerFlag && languageDrawerTriggerText) {
            languageLinkItems.forEach((linkItem) => {
                linkItem.addEventListener("click", () => {
                    const languageFlag = linkItem.querySelector("img")?.src || "";
                    const languageText = linkItem.querySelector("span")?.textContent || "";
					
					console.log({languageFlag, languageText})

                    languageDrawerTriggerFlag.src = languageFlag;
                    languageDrawerTriggerText.textContent = LANGUAGE_CODE[languageText] || languageText;
                });
            });
        }
    }

    open(megaMenuInstance = null) {
        if (!this.megaMenuMobile) return;

        // Đóng mega menu desktop nếu đang mở
        if (megaMenuInstance && megaMenuInstance.isOpen()) {
            megaMenuInstance.close();
        }

        // Đóng tất cả submenu khi mở menu mobile
        this.closeAllSubmenus();

        if (this.mobileSearchInputEl) {
            this.mobileSearchInputEl.value = "";
        }
        this.clearMobileSearchResults();

        this.megaMenuMobile.classList.remove("header-mega-menu-mobile--hidden");

        if (this.headerEl) {
            this.headerEl.classList.add("header--mobile-menu-open");
        }

        if (this.mobileMenuToggle) {
            this.mobileMenuToggle.classList.add("is-active");
        }

        // Hiển thị page overlay
        if (this.pageOverlay) {
            this.pageOverlay.classList.remove("page-overlay--hidden");
        }

        // Ngăn cuộn trang khi mở menu mobile
        this.lockScroll();
    }

    close() {
        if (!this.megaMenuMobile) return;

        this.megaMenuMobile.classList.add("header-mega-menu-mobile--hidden");

        if (this.headerEl) {
            this.headerEl.classList.remove("header--mobile-menu-open");
        }

        if (this.mobileMenuToggle) {
            this.mobileMenuToggle.classList.remove("is-active");
        }

        // Ẩn page overlay
        if (this.pageOverlay) {
            this.pageOverlay.classList.add("page-overlay--hidden");
        }

        // Khôi phục cuộn trang khi đóng menu mobile
        this.unlockScroll();

        this.abortMobileSearch();
        this.clearMobileSearchResults();

        // Đóng tất cả submenu
        this.closeAllSubmenus();

        // Callback khi đóng
        if (this.onCloseCallback) {
            this.onCloseCallback();
        }
    }

    isOpen() {
        return this.megaMenuMobile && !this.megaMenuMobile.classList.contains("header-mega-menu-mobile--hidden");
    }

    lockScroll() {
        const lenisInstance = window.app?.lenis;
        if (lenisInstance && typeof lenisInstance.stop === "function") {
            lenisInstance.stop();
        }

        document.body.style.overflow = "hidden";
        document.documentElement.style.overflow = "hidden";
    }

    unlockScroll() {
        const lenisInstance = window.app?.lenis;
        if (lenisInstance && typeof lenisInstance.start === "function") {
            lenisInstance.start();
        }

        document.body.style.overflow = "";
        document.documentElement.style.overflow = "";
    }

    events() {
        if (!this.megaMenuMobile || !this.mobileMenuToggle) return;

        this.mobileMenuToggle.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (this.isOpen()) {
                this.close();
                return;
            }
            this.open();
        });

        // Đóng menu mobile khi click vào overlay
        if (this.pageOverlay) {
            this.pageOverlay.addEventListener("click", () => {
                if (this.megaMenuMobile && !this.megaMenuMobile.classList.contains("header-mega-menu-mobile--hidden")) {
                    this.close();
                }
            });
        }

        // Xử lý submenu accordion
        this.initSubmenuToggle();

        // Xử lý language drawer
        this.handleLanguageDrawer();

        if (this.mobileSearchInputEl) {
            this.mobileSearchInputEl.addEventListener("input", (e) => {
                const query = (e.target?.value || "").trim();

                if (this.mobileSearchDebounceTimer) {
                    clearTimeout(this.mobileSearchDebounceTimer);
                }

                if (!query || query.length < 2) {
                    this.abortMobileSearch();
                    this.clearMobileSearchResults();
                    return;
                }

                this.mobileSearchDebounceTimer = setTimeout(async () => {
                    const results = await this.searchMobileAPI(query);
                    this.renderMobileSearchResults(results);
                }, 250);
            });
        }
    }
}

/**
 * Header - Main header orchestrator
 * Manages scroll behavior, header height, search, and coordinates between components
 */
class Header {
    constructor() {
        // Header elements
        this.headerEl = document.querySelector(".header");
        this.headerSearchEl = document.querySelector(".header-search");
        this.headerSearchInputEl = document.getElementById("header-search-input");
        this.headerSearchInputWrapperEl = this.headerSearchEl?.querySelector(".header-search__input-wrapper") || null;
        this.headerSearchCloseBtnEl = this.headerSearchEl?.querySelector(".header-search__button-close") || null;
        this.headerSearchResultEl = document.getElementById("header-search-result");
        this.headerSearchResultTemplate = document.getElementById("header-search-result-item");
        this.headerSearchPanelEl = document.querySelector(".header-search-panel");
        this.headerBottom = document.querySelector(".header-bottom");
        this.languageItem = document.querySelector(".header-info__item--language");
        this.headerTop = document.querySelector(".header-top");

        // Shared elements
        this.pageOverlay = document.querySelector(".page-overlay");

        // State
        this.lastScrollY = 0;
        this.scrollThreshold = 5;
        this.headerTopHeight = 0;

        this.mobileHasFixedOnce = false;

        this.headerSearchDebounceTimer = null;
        this.headerSearchAbortController = null;
        this.headerSearchIsOpen = false;

        this.mobileTopTransparentTimer = null;

        // Language dropdown elements
        this.dropdownLanguage = document.querySelector(".header-info__item-language-dropdown");
        this.dropdownLanguageTrigger = document.querySelector(".header-info__item-language-dropdown-trigger");
        this.dropdownLanguageContent = document.querySelector(".header-info__item-language-dropdown-content");

        // Initialize components
        this.megaMenu = new MegaMenu({
            megaMenu: document.querySelector(".header-mega-menu"),
            megaTriggers: document.querySelectorAll("[data-mega-menu]"),
            pageOverlay: this.pageOverlay,
            mapImages: document.querySelectorAll(".header-mega-menu__map-img"),
            countryItems: document.querySelectorAll(".header-mega-menu__country-item"),
            isMobileCheck: () => this.isMobile(),
        });

        this.mobileMenu = new MobileMenu({
            megaMenuMobile: document.querySelector(".header-mega-menu-mobile"),
            mobileMenuToggle: document.getElementById("header-mobile-menu-toggle"),
            mobileMenuClose: document.querySelector(".header-mega-menu-mobile")?.querySelector(".header-top__right-mb"),
            pageOverlay: this.pageOverlay,
            onClose: () => {
                // Callback khi đóng mobile menu
            },
        });

        this.init();
        this.events();
    }

    init() {
        // Tính toán chiều cao header-top
        if (this.headerTop) {
            this.headerTopHeight = this.headerTop.offsetHeight;
            // Set CSS variable cho header-top-height
            document.documentElement.style.setProperty("--header-top-height", `${this.headerTopHeight}px`);
        }

        // Kiểm tra và set class transparent ban đầu
        this.updateHeaderTransparent();
    }

    isMobile() {
        return window.innerWidth <= 639.98;
    }

    handleFocusSearchInput() {
        if (!this.headerSearchEl || !this.headerSearchInputEl || !this.headerSearchInputWrapperEl || !this.headerSearchPanelEl) return;

        const openSearch = () => {
            if (this.headerSearchIsOpen) return;

            if (this.megaMenu && this.megaMenu.isOpen()) {
                this.megaMenu.close();
            }
            if (this.mobileMenu && this.mobileMenu.isOpen()) {
                this.mobileMenu.close();
            }

            this.headerSearchIsOpen = true;
            this.headerSearchInputWrapperEl.classList.add("is-active");

            if (this.headerBottom) {
                this.headerBottom.classList.add("is-search-open");
            }

            this.headerSearchPanelEl.classList.add("header-search-panel--hidden");

            if (this.pageOverlay) {
                this.pageOverlay.classList.remove("page-overlay--hidden");
            }

            document.documentElement.style.overflow = "hidden";
        };

        const closeSearch = () => {
            if (!this.headerSearchIsOpen) return;

            this.headerSearchIsOpen = false;
            this.headerSearchInputWrapperEl.classList.remove("is-active");

            if (this.headerBottom) {
                this.headerBottom.classList.remove("is-search-open");
            }

            this.headerSearchPanelEl.classList.add("header-search-panel--hidden");

            this.abortHeaderSearch();
            this.clearHeaderSearchResults();
            this.headerSearchInputEl.value = "";

            if (this.pageOverlay && !(this.megaMenu && this.megaMenu.isOpen()) && !(this.mobileMenu && this.mobileMenu.isOpen())) {
                this.pageOverlay.classList.add("page-overlay--hidden");
            }

            if (!(this.megaMenu && this.megaMenu.isOpen()) && !(this.mobileMenu && this.mobileMenu.isOpen())) {
                document.documentElement.style.overflow = "";
            }
        };

        this._openHeaderSearch = openSearch;
        this._closeHeaderSearch = closeSearch;

        this.headerSearchInputEl.addEventListener("focus", openSearch);
        this.headerSearchInputEl.addEventListener("click", openSearch);

        if (this.headerSearchCloseBtnEl) {
            this.headerSearchCloseBtnEl.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                closeSearch();
            });
        }

        if (this.pageOverlay) {
            this.pageOverlay.addEventListener("click", () => {
                if (this.headerSearchIsOpen) {
                    closeSearch();
                }
            });
        }

        document.addEventListener("keydown", (e) => {
            if (!this.headerSearchIsOpen) return;
            if (e.key === "Escape") {
                closeSearch();
            }
        });

        this.headerSearchInputEl.addEventListener("input", (e) => {
            const query = (e.target?.value || "").trim();

            if (this.headerSearchDebounceTimer) {
                clearTimeout(this.headerSearchDebounceTimer);
            }

            if (!query || query.length < 2) {
                this.abortHeaderSearch();
                this.clearHeaderSearchResults();

                if (this.headerSearchPanelEl) {
                    this.headerSearchPanelEl.classList.add("header-search-panel--hidden");
                }
                return;
            }

            this.headerSearchDebounceTimer = setTimeout(async () => {
                const results = await this.searchHeaderAPI(query);
                this.renderHeaderSearchResults(results);
            }, 250);
        });
    }

    abortHeaderSearch() {
        if (this.headerSearchAbortController) {
            this.headerSearchAbortController.abort();
            this.headerSearchAbortController = null;
        }
    }

    async searchHeaderAPI(query) {
        const base = window.wpApiSettings?.root;
        const nonce = window.wpApiSettings?.nonce;
        if (!base) return [];

        this.abortHeaderSearch();
        const controller = new AbortController();
        this.headerSearchAbortController = controller;

        const url = new URL("api/v1/search", base);
        url.searchParams.set("keyword", query);

        try {
            const res = await fetch(url.toString(), {
                method: "GET",
                headers: nonce ? { "X-WP-Nonce": nonce } : undefined,
                signal: controller.signal,
            });

            if (!res.ok) return [];
            const json = await res.json();
            if (json?.success && Array.isArray(json?.data)) return json.data;
            return [];
        } catch (err) {
            if (err && err.name === "AbortError") return [];
            return [];
        }
    }

    clearHeaderSearchResults() {
        if (!this.headerSearchResultEl) return;
        this.headerSearchResultEl.innerHTML = "";
        this.headerSearchResultEl.removeAttribute("data-empty");
    }

    renderHeaderSearchResults(results) {
        if (!this.headerSearchResultEl || !this.headerSearchResultTemplate) return;

        this.headerSearchResultEl.innerHTML = "";
        this.headerSearchResultEl.removeAttribute("data-empty");

        const items = Array.isArray(results) ? results : [];
        if (!items.length) {
            const template = this.headerSearchResultTemplate.content.cloneNode(true);
            const textEl = template.querySelector(".header-search__results-item-text");
            const linkEl = template.querySelector(".header-search__results-item-link");
            if (textEl) textEl.textContent = "No results found";
            if (linkEl) {
                linkEl.setAttribute("href", "#");
                linkEl.addEventListener("click", (e) => e.preventDefault());
            }
            this.headerSearchResultEl.appendChild(template);
            this.headerSearchResultEl.setAttribute("data-empty", "true");

            if (this.headerSearchIsOpen && this.headerSearchPanelEl) {
                this.headerSearchPanelEl.classList.remove("header-search-panel--hidden");
            }
            return;
        }

        items.forEach((item) => {
            const template = this.headerSearchResultTemplate.content.cloneNode(true);
            const textEl = template.querySelector(".header-search__results-item-text");
            const linkEl = template.querySelector(".header-search__results-item-link");
            if (textEl) textEl.textContent = item?.title || "";
            if (linkEl) {
                linkEl.setAttribute("href", item?.url || "#");
            }
            this.headerSearchResultEl.appendChild(template);
        });

        if (this.headerSearchIsOpen && this.headerSearchPanelEl) {
            this.headerSearchPanelEl.classList.remove("header-search-panel--hidden");
        }
    }

    handleLanguageDropdown() {
        const dropdownLanguage = document.querySelector(".header-info__item-language-dropdown");
        if (!dropdownLanguage) return;

        const dropdownLanguageTrigger = dropdownLanguage.querySelector(".header-info__item-language-dropdown-trigger");
        const dropdownLanguageContent = dropdownLanguage.querySelector(".header-info__item-language-dropdown-content");

        if (!dropdownLanguageTrigger || !dropdownLanguageContent) return;

        const dropdownTriggerFlagImg = dropdownLanguageTrigger.querySelector("img");
        const dropdownTriggerTextSpan = dropdownLanguageTrigger.querySelector(".header-info__item-language-dropdown-trigger-text");

        const currentLanguage = dropdownLanguage.querySelector("a.gt-current-lang");
        if (currentLanguage && dropdownTriggerFlagImg && dropdownTriggerTextSpan) {
            const initLanguageText = currentLanguage.querySelector("span")?.textContent || "";
            const initLanguageFlag = currentLanguage.querySelector("img")?.src || "";
            
            dropdownTriggerFlagImg.src = initLanguageFlag;
            dropdownTriggerTextSpan.textContent = initLanguageText;
        }

    

        dropdownLanguageTrigger.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownLanguageContent.classList.toggle("active");
        });

        document.addEventListener("click", (e) => {
            if (!dropdownLanguage.contains(e.target)) {
                dropdownLanguageContent.classList.remove("active");
            }
        });

        const languageOptions = dropdownLanguageContent.querySelectorAll("a.glink");
        if (!languageOptions.length) return;

        languageOptions.forEach((option) => {
            option.addEventListener("click", (e) => {
                const languageFlag = option?.querySelector("img")?.src || "";
                const languageText = option?.querySelector("span")?.textContent || "";
                console.log({ languageFlag, languageText });
                if (!dropdownTriggerFlagImg || !dropdownTriggerTextSpan) return;
                dropdownTriggerFlagImg.src = languageFlag;
                dropdownTriggerTextSpan.textContent = languageText;
            });
        });
    }

    updateHeaderTransparent() {
        if (!this.headerEl) return;

        const currentScroll = window.scrollY;

        // Mobile: before ever becoming fixed, keep transparent until user scrolls past header height.
        // After becoming fixed once, only switch back to transparent when scrollY = 0.
        if (this.isMobile() && this.headerTopHeight) {
            const shouldBeTransparent = this.mobileHasFixedOnce ? currentScroll <= 0 : currentScroll <= this.headerTopHeight;
            if (shouldBeTransparent) {
                this.headerEl.classList.add("header--default");
            } else {
                this.headerEl.classList.remove("header--default");
            }
            return;
        }

        // Nếu ở đầu trang (scrollY = 0 hoặc rất nhỏ) thì thêm class transparent
        if (currentScroll <= 100) {
            this.headerEl.classList.add("header--default");
        } else {
            this.headerEl.classList.remove("header--default");
        }
    }

    handleScroll() {
        const currentScroll = window.scrollY;

        if (this.mobileTopTransparentTimer && currentScroll > 0) {
            clearTimeout(this.mobileTopTransparentTimer);
            this.mobileTopTransparentTimer = null;
        }

        // Mobile: scroll down -> hide header, scroll up -> show header solid, scroll to top -> transparent
        if (this.isMobile() && this.headerEl) {
            const fixedThreshold = this.headerTopHeight || 0;

            // If mobile menu is open, keep header visible and let menu-open styles win
            if (this.headerEl.classList.contains("header--mobile-menu-open")) {
                this.headerEl.classList.add("header--mobile-solid");
                this.headerEl.classList.remove("header--mobile-hidden");
                this.mobileHasFixedOnce = true;
                this.lastScrollY = currentScroll;
                return;
            }

            const wasPastThreshold = this.mobileHasFixedOnce;
            const isPastThreshold = fixedThreshold ? currentScroll > fixedThreshold : true;

            const delta = currentScroll - this.lastScrollY;
            const absDelta = Math.abs(delta);

            // Only revert to transparent at scrollY = 0
            if (currentScroll <= 0) {
                this.headerEl.classList.remove("header--mobile-hidden");

                if (!this.mobileTopTransparentTimer) {
                    this.mobileTopTransparentTimer = setTimeout(() => {
                        this.mobileHasFixedOnce = false;
                        this.headerEl.classList.remove("header--mobile-solid");
                        this.updateHeaderTransparent();
                        this.mobileTopTransparentTimer = null;
                    }, 300);
                }

                this.lastScrollY = currentScroll;
                return;
            }

            this.updateHeaderTransparent();

            // Before ever reaching header height: keep original state (transparent + visible)
            if (!isPastThreshold && !this.mobileHasFixedOnce) {
                this.headerEl.classList.remove("header--mobile-solid");
                this.headerEl.classList.remove("header--mobile-hidden");
                this.lastScrollY = currentScroll;
                return;
            }

            // After reaching header height at least once: solid
            if (isPastThreshold) {
                this.mobileHasFixedOnce = true;
            }
            if (this.mobileHasFixedOnce) {
                this.headerEl.classList.add("header--mobile-solid");
            }

            // If we just crossed the threshold while scrolling down: hide immediately
            if (!wasPastThreshold && isPastThreshold && delta > 0) {
                this.headerEl.classList.add("header--mobile-hidden");
                this.lastScrollY = currentScroll;
                return;
            }

            // If we've been solid once but are now above threshold (scrolling up), keep visible until top.
            if (!isPastThreshold && this.mobileHasFixedOnce) {
                this.headerEl.classList.remove("header--mobile-hidden");
                this.lastScrollY = currentScroll;
                return;
            }

            // Avoid jitter by only toggling hide/show when the user scrolls a meaningful distance.
            if (absDelta >= this.scrollThreshold) {
                if (delta > 0) {
                    this.headerEl.classList.add("header--mobile-hidden");
                } else {
                    this.headerEl.classList.remove("header--mobile-hidden");
                }
            }

            this.lastScrollY = currentScroll;
            return;
        }

        this.updateHeaderTransparent();

        if (!this.headerBottom) return;

        const diff = Math.abs(currentScroll - this.lastScrollY);

        // Chỉ xử lý nếu scroll đủ nhiều để tránh trigger quá nhiều
        if (diff < this.scrollThreshold) return;

        if (currentScroll > this.lastScrollY && currentScroll > this.headerTopHeight) {
            // Scroll xuống và đã scroll quá chiều cao header-top → ẩn header-bottom và mờ language
            this.headerBottom.classList.add("is-hidden");

            // Mờ language item khi scroll xuống
            if (this.languageItem) {
                this.languageItem.classList.add("header-info__item--hidden");
            }

            // Đóng mega menu nếu đang mở
            if (this.megaMenu && this.megaMenu.isOpen()) {
                this.megaMenu.close();
            }
        } else if (currentScroll < this.lastScrollY) {
            // Scroll lên → hiện lại header-bottom
            this.headerBottom.classList.remove("is-hidden");

            // Chỉ hiện lại language item khi scroll về gần đầu trang (scrollY <= headerTopHeight)
            if (this.languageItem) {
                if (currentScroll <= this.headerTopHeight) {
                    this.languageItem.classList.remove("header-info__item--hidden");
                }
            }
        }

        this.lastScrollY = currentScroll;
    }

    handleResize() {
        window.addEventListener("resize", () => {
            // Cập nhật lại chiều cao header-top khi resize
            if (this.headerTop) {
                this.headerTopHeight = this.headerTop.offsetHeight;
                document.documentElement.style.setProperty("--header-top-height", `${this.headerTopHeight}px`);
            }
        });
    }

    events() {
        this.handleFocusSearchInput();
        this.handleResize();

        // Đảm bảo language dropdown được khởi tạo sau khi DOM ready
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => {
                this.handleLanguageDropdown();
            });
        } else {
            // DOM đã ready, khởi tạo ngay
            this.handleLanguageDropdown();
        }

        // Thêm scroll event listener với requestAnimationFrame để tối ưu performance
        let ticking = false;
        window.addEventListener(
            "scroll",
            () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        this.handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            },
            { passive: true },
        );
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    new Header();
});
