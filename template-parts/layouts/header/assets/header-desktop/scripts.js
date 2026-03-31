class Header {
    static SCROLL_THRESHOLD_PX = 200;

    constructor() {
        this.headerEl = document.querySelector('.header');
        if (!this.headerEl) return;

        // Chỉ front-page mới có header transparent (scroll-based toggle)
        // Các page khác luôn dùng header--white
        this.isFrontPage = document.body.classList.contains('home');

        if (!this.isFrontPage) {
            this.headerEl.classList.add('header--white');
            return;
        }

        this.rafScheduled = false;
        this.handleScroll = this.handleScroll.bind(this);
        this.scheduleScrollCheck = this.scheduleScrollCheck.bind(this);
        window.addEventListener('scroll', this.scheduleScrollCheck, { passive: true });
        window.addEventListener('resize', this.scheduleScrollCheck, { passive: true });
        if (typeof window.lenis !== 'undefined') {
            window.lenis.on('scroll', this.scheduleScrollCheck);
        }
        this.handleScroll();
    }

    /**
     * Throttle bằng requestAnimationFrame: chỉ gọi handleScroll tối đa 1 lần mỗi frame khi scroll.
     */
    scheduleScrollCheck() {
        if (this.rafScheduled) return;
        this.rafScheduled = true;
        requestAnimationFrame(() => {
            this.handleScroll();
            this.rafScheduled = false;
        });
    }

    /**
     * Kiểm tra vị trí scroll: > 200px thì thêm header--white, <= 200px thì xoá header--white.
     * Chỉ cập nhật DOM khi trạng thái thực sự thay đổi.
     */
    handleScroll() {
        const scrollY = window.scrollY ?? window.pageYOffset ?? document.documentElement.scrollTop ?? 0;
        const shouldBeWhite = scrollY > Header.SCROLL_THRESHOLD_PX;
        const hasWhite = this.headerEl.classList.contains('header--white');
        if (shouldBeWhite === hasWhite) return;
        if (shouldBeWhite) {
            this.headerEl.classList.add('header--white');
        } else {
            this.headerEl.classList.remove('header--white');
        }
    }
}

class HeaderMegaMenuService {
    constructor() {
        this.megaMenuServiceEl = document.querySelector(".header__mega-menu-service");
        if (!this.megaMenuServiceEl) return;
        this.serviceItemEls = this.megaMenuServiceEl.querySelectorAll(".header__mega-menu-service__service-item");
        this.servicePanelEls = this.megaMenuServiceEl.querySelectorAll(".header__mega-menu-service-item");
        this.events();
    }
    setActiveByIndex(index) {
        const idx = Number(index);
        if (!Number.isFinite(idx)) return;

        const activeServiceItem = this.megaMenuServiceEl.querySelector(".header__mega-menu-service__service-item--active");
        if (activeServiceItem) {
            activeServiceItem.classList.remove("header__mega-menu-service__service-item--active");
        }
        const nextItem = this.megaMenuServiceEl.querySelector(`[data-service-trigger-index="${idx}"]`);
        if (nextItem) {
            nextItem.classList.add("header__mega-menu-service__service-item--active");
        }

        const activePanel = this.megaMenuServiceEl.querySelector(".header__mega-menu-service-item--active");
        if (activePanel) {
            activePanel.classList.remove("header__mega-menu-service-item--active");
        }
        const nextPanel = this.megaMenuServiceEl.querySelector(`[data-service-target-index="${idx}"]`);
        if (nextPanel) {
            nextPanel.classList.add("header__mega-menu-service-item--active");
        }
    }
    handleHoverServiceItem() {
        if (!this.serviceItemEls.length) return;
        this.serviceItemEls.forEach((serviceItem) => {
            serviceItem.addEventListener("mouseenter", () => {
                const idx = serviceItem.getAttribute("data-service-trigger-index");
                this.setActiveByIndex(idx);
            });
        });
    }
    handleFocusServiceItem() {
        if (!this.serviceItemEls.length) return;
        this.serviceItemEls.forEach((serviceItem) => {
            const link = serviceItem.querySelector("a");
            if (!link) return;
            link.addEventListener("focus", () => {
                const idx = serviceItem.getAttribute("data-service-trigger-index");
                this.setActiveByIndex(idx);
            });
        });
    }
    events() {
        this.handleHoverServiceItem();
        this.handleFocusServiceItem();
    }
}

class HeaderMegaMenuProduct {
    static MIN_THUMB_HEIGHT_PX = 40;

    constructor() {
        this.container = document.querySelector('[data-mega-menu-content="mega-menu-product"]');
        if (!this.container) return;

        this.parentSwiperEl = this.container.querySelector('.header__mega-menu-product__parent-categories-swiper');
        this.childSwiperEl = this.container.querySelector('.header__mega-menu-product__child-categories-swiper');
        this.prevBtn = this.container.querySelector('.header__mega-menu-product__parent-categories-swiper-prev');
        this.nextBtn = this.container.querySelector('.header__mega-menu-product__parent-categories-swiper-next');
        this.scrollbarTrack = this.container.querySelector('.header__mega-menu-product__child-categories-swiper-scrollbar');
        this.scrollbarThumb = this.container.querySelector('.header__mega-menu-product__child-categories-swiper-scrollbar-inner');

        if (!this.parentSwiperEl || !this.childSwiperEl || !this.prevBtn || !this.nextBtn) return;

        this.parentSwiper = null;
        this.childSwiper = null;
        this.initSwipers();
        this.bindButtons();
        if (this.scrollbarTrack && this.scrollbarThumb) {
            this.bindCustomScrollbar();
        }
    }

    initSwipers() {
        const speed = 400;
        const commonOptions = {
            slidesPerView: 'auto',
            spaceBetween: 0,
            speed,
            allowTouchMove: true,
            simulateTouch: true,
            grabCursor: true,
            threshold: 5,
            touchAngle: 35,
            touchStartPreventDefault: false,
            touchMoveStopPropagation: false,
        };

        this.parentSwiper = new Swiper(this.parentSwiperEl, commonOptions);
        this.childSwiper = new Swiper(this.childSwiperEl, commonOptions);

        this.parentSwiper.controller.control = this.childSwiper;
        this.childSwiper.controller.control = this.parentSwiper;
    }

    bindButtons() {
        this.prevBtn.addEventListener('click', () => {
            this.parentSwiper?.slidePrev();
        });
        this.nextBtn.addEventListener('click', () => {
            this.parentSwiper?.slideNext();
        });
    }

    /**
     * Custom scrollbar: đồng bộ với nội dung cuộn, kéo thumb để scroll.
     */
    bindCustomScrollbar() {
        const scrollEl = this.childSwiperEl;
        const track = this.scrollbarTrack;
        const thumb = this.scrollbarThumb;

        const updateScrollbar = () => {
            const scrollHeight = scrollEl.scrollHeight;
            const clientHeight = scrollEl.clientHeight;
            const scrollTop = scrollEl.scrollTop;
            const trackHeight = track.offsetHeight;

            if (scrollHeight <= clientHeight) {
                thumb.style.height = trackHeight + 'px';
                thumb.style.top = '0';
                track.classList.add('header__mega-menu-product__child-categories-swiper-scrollbar--no-scroll');
                return;
            }
            track.classList.remove('header__mega-menu-product__child-categories-swiper-scrollbar--no-scroll');

            const scrollableRange = scrollHeight - clientHeight;
            const thumbHeight = Math.max(
                HeaderMegaMenuProduct.MIN_THUMB_HEIGHT_PX,
                (clientHeight / scrollHeight) * trackHeight
            );
            const thumbTravel = trackHeight - thumbHeight;
            const thumbTop = scrollableRange > 0 ? (scrollTop / scrollableRange) * thumbTravel : 0;

            thumb.style.height = thumbHeight + 'px';
            thumb.style.top = thumbTop + 'px';
        };

        scrollEl.addEventListener('scroll', updateScrollbar);
        new ResizeObserver(updateScrollbar).observe(scrollEl);
        updateScrollbar();

        let isDragging = false;
        let startY = 0;
        let startScrollTop = 0;

        const onThumbMouseDown = (e) => {
            isDragging = true;
            startY = e.clientY;
            startScrollTop = scrollEl.scrollTop;
            document.body.style.userSelect = 'none';
            document.body.style.cursor = 'grabbing';
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        };

        const onMouseMove = (e) => {
            if (!isDragging) return;
            const trackHeight = track.offsetHeight;
            const thumbHeight = parseFloat(thumb.style.height) || trackHeight;
            const thumbTravel = trackHeight - thumbHeight;
            const scrollableRange = scrollEl.scrollHeight - scrollEl.clientHeight;
            if (scrollableRange <= 0) return;
            const deltaY = e.clientY - startY;
            const ratio = thumbTravel > 0 ? deltaY / thumbTravel : 0;
            const newScrollTop = startScrollTop + ratio * scrollableRange;
            scrollEl.scrollTop = Math.max(0, Math.min(newScrollTop, scrollableRange));
        };

        const onMouseUp = () => {
            isDragging = false;
            document.body.style.userSelect = '';
            document.body.style.cursor = '';
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        };

        thumb.addEventListener('mousedown', onThumbMouseDown);

        track.addEventListener('mousedown', (e) => {
            if (e.target === thumb) return;
            const trackRect = track.getBoundingClientRect();
            const trackHeight = track.offsetHeight;
            const thumbHeight = parseFloat(thumb.style.height) || HeaderMegaMenuProduct.MIN_THUMB_HEIGHT_PX;
            const thumbTravel = trackHeight - thumbHeight;
            const scrollableRange = scrollEl.scrollHeight - scrollEl.clientHeight;
            if (scrollableRange <= 0) return;
            const clickY = e.clientY - trackRect.top;
            const thumbTop = (scrollEl.scrollTop / scrollableRange) * thumbTravel;
            const clientHeight = scrollEl.clientHeight;
            if (clickY < thumbTop) {
                scrollEl.scrollTop = Math.max(0, scrollEl.scrollTop - clientHeight);
            } else if (clickY > thumbTop + thumbHeight) {
                scrollEl.scrollTop = Math.min(scrollableRange, scrollEl.scrollTop + clientHeight);
            }
        });
    }
}

class HeaderMegaMenuSearch {
    static PRODUCTS_ENDPOINT = "/wp-json/api/v1/products";
    static PRODUCTS_LIMIT = 8;
    static PRODUCTS_PAGE = 1;
    static BLOGS_ENDPOINT = "/wp-json/api/v1/blogs";
    static BLOGS_LIMIT = 10;
    static BLOGS_PAGE = 1;
    static DEBOUNCE_MS = 250;
    static TRANSPARENT_PIXEL_DATA_URL = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";

    constructor() {
        this.containerEl = document.querySelector('[data-mega-menu-content="mega-menu-search"]');
        this.btnSearchEl = document.querySelector(".header__search-input-wrapper")
        this.overlayEl = document.querySelector(".header__mega-menu-overlay")
        if (!this.containerEl) return;
        this.inputEl = this.containerEl.querySelector(".header__mega-menu-navbar__searchbar-input");
        this.resultContainerEl = this.containerEl.querySelector(".header__mega-menu-search__result");
        this.resultListEl = this.containerEl.querySelector(".header__mega-menu-search__result-list");
        this.relatedBlogEl = this.containerEl.querySelector(".header__mega-menu-search__related-blog");
        this.relatedBlogListEl = this.containerEl.querySelector(".header__mega-menu-search__related-blog-list");
        this.noResultEl = this.containerEl.querySelector(".header__mega-menu-search__no-result");
        this.productNoResultEl = this.containerEl.querySelector(".header__mega-menu-search__product-no-result");
        this.relatedBlogNoResultEl = this.containerEl.querySelector(".header__mega-menu-search__related-blog-no-result");
        this.itemTemplateEl = document.getElementById("product-search-result-item");
        this.productPrototypeEl = this.resultListEl?.querySelector(".product")?.cloneNode(true) || null;
        this.blogItemTemplateEl = document.getElementById("blog-search-result-item");
        this.blogPrototypeEl = this.relatedBlogListEl?.querySelector(".blog-item")?.cloneNode(true) || null;
        this.activeRequestController = null;
        this.debounceTimer = null;
        this.events();
    }
    createProductItemNode() {
        if (this.productPrototypeEl) {
            return this.productPrototypeEl.cloneNode(true);
        }
        if (this.itemTemplateEl && ("content" in this.itemTemplateEl)) {
            return this.itemTemplateEl.content.firstElementChild?.cloneNode(true) || null;
        }
        return null;
    }
    createBlogItemNode() {
        if (this.blogPrototypeEl) {
            return this.blogPrototypeEl.cloneNode(true);
        }
        if (this.blogItemTemplateEl && ("content" in this.blogItemTemplateEl)) {
            return this.blogItemTemplateEl.content.firstElementChild?.cloneNode(true) || null;
        }
        return null;
    }
    /**
     * Chỉ hiện no-result toàn màn khi cả product và blog đều không có kết quả.
     * Nếu một trong hai có kết quả: hiện cả hai cột; cột không có dữ liệu hiện
     * .header__mega-menu-search__product-no-result / __related-blog-no-result.
     */
    updateSearchResultsLayout(productCount, blogCount) {
        const hasProducts = productCount > 0;
        const hasBlogs = blogCount > 0;
        const showNoResult = !hasProducts && !hasBlogs;

        if (this.noResultEl) this.noResultEl.style.display = showNoResult ? "" : "none";
        if (this.resultContainerEl) {
            this.resultContainerEl.style.display = showNoResult ? "none" : "";
        }
        if (this.relatedBlogEl) {
            this.relatedBlogEl.style.display = showNoResult ? "none" : "";
        }

        if (this.productNoResultEl) {
            if (showNoResult) {
                this.productNoResultEl.style.display = "none";
            } else {
                this.productNoResultEl.style.display = hasProducts ? "none" : "flex";
            }
        }
        if (this.relatedBlogNoResultEl) {
            if (showNoResult) {
                this.relatedBlogNoResultEl.style.display = "none";
            } else {
                this.relatedBlogNoResultEl.style.display = hasBlogs ? "none" : "flex";
            }
        }
    }
    showSearchLoadingPanels() {
        if (this.noResultEl) this.noResultEl.style.display = "none";
        if (this.resultContainerEl) this.resultContainerEl.style.display = "";
        if (this.relatedBlogEl) this.relatedBlogEl.style.display = "";
        if (this.productNoResultEl) this.productNoResultEl.style.display = "none";
        if (this.relatedBlogNoResultEl) this.relatedBlogNoResultEl.style.display = "none";
    }
    renderSkeletonLoading(itemCount = HeaderMegaMenuSearch.PRODUCTS_LIMIT) {
        if (!this.resultListEl) return;
        this.resultListEl.setAttribute("aria-busy", "true");
        this.showSearchLoadingPanels();

        const skeletonFragment = document.createDocumentFragment();
        const count = Math.max(1, Number(itemCount) || HeaderMegaMenuSearch.PRODUCTS_LIMIT);

        for (let i = 0; i < count; i++) {
            const skeletonItemNode = this.createProductItemNode();
            if (!skeletonItemNode) continue;

            skeletonItemNode.classList.add("product--skeleton");
            skeletonItemNode.removeAttribute("href");

            const skeletonVideoEl = skeletonItemNode.querySelector(".product__video");
            if (skeletonVideoEl) {
                skeletonVideoEl.removeAttribute("src");
            }

            const skeletonImageEl = skeletonItemNode.querySelector(".product__img");
            if (skeletonImageEl) {
                skeletonImageEl.setAttribute("src", HeaderMegaMenuSearch.TRANSPARENT_PIXEL_DATA_URL);
                skeletonImageEl.setAttribute("alt", "");
            }

            const skeletonTitleEl = skeletonItemNode.querySelector(".product__title");
            if (skeletonTitleEl) skeletonTitleEl.textContent = "";

            const skeletonRentPriceEl = skeletonItemNode.querySelector(".product__rent-price");
            if (skeletonRentPriceEl) skeletonRentPriceEl.textContent = "";

            const skeletonSalePriceEl = skeletonItemNode.querySelector(".product__price");
            if (skeletonSalePriceEl) skeletonSalePriceEl.textContent = "";

            skeletonFragment.appendChild(skeletonItemNode);
        }

        this.resultListEl.replaceChildren(skeletonFragment);
    }
    renderBlogSkeletonLoading(itemCount = HeaderMegaMenuSearch.BLOGS_LIMIT) {
        if (!this.relatedBlogListEl) return;
        this.relatedBlogListEl.setAttribute("aria-busy", "true");
        this.showSearchLoadingPanels();

        const skeletonFragment = document.createDocumentFragment();
        const count = Math.max(1, Number(itemCount) || HeaderMegaMenuSearch.BLOGS_LIMIT);

        for (let i = 0; i < count; i++) {
            const skeletonNode = this.createBlogItemNode();
            if (!skeletonNode) continue;

            skeletonNode.classList.add("blog-item--skeleton");
            const linkEl = skeletonNode.querySelector(".blog-item__link");
            if (linkEl) {
                linkEl.removeAttribute("href");
                linkEl.setAttribute("aria-label", "");
            }
            const imgEl = skeletonNode.querySelector(".blog-item__thumbnail img");
            if (imgEl) {
                imgEl.setAttribute("src", HeaderMegaMenuSearch.TRANSPARENT_PIXEL_DATA_URL);
                imgEl.setAttribute("alt", "");
            }
            const categoryEl = skeletonNode.querySelector(".blog-item__category");
            if (categoryEl) categoryEl.textContent = "";
            const titleEl = skeletonNode.querySelector(".blog-item__title");
            if (titleEl) titleEl.textContent = "";

            skeletonFragment.appendChild(skeletonNode);
        }

        this.relatedBlogListEl.replaceChildren(skeletonFragment);
    }
    renderProducts(products) {
        if (!this.resultListEl) return;
        this.resultListEl.setAttribute("aria-busy", "false");
        const productList = Array.isArray(products) ? products : [];

        if (productList.length === 0) {
            this.resultListEl.replaceChildren();
            return;
        }

        const resultFragment = document.createDocumentFragment();
        productList.forEach((productData) => {
            const productUrl = productData?.url || "#";
            const productTitle = productData?.title || "";
            const productThumbnailUrl = productData?.thumbnail || "";
            const productVideoUrl = productData?.video || "";
            const productRentPriceText = productData?.rent_price?.formatted ?? "0";
            const productSalePriceText = productData?.price?.formatted ?? "0";

            const productItemNode = this.createProductItemNode();
            if (!productItemNode) return;

            const productLinkEl = productItemNode;
            productLinkEl.setAttribute("href", productUrl);
            productLinkEl.classList.remove("product--skeleton");

            const productVideoEl = productLinkEl.querySelector(".product__video");
            if (productVideoEl) {
                if (productVideoUrl) {
                    productVideoEl.setAttribute("src", productVideoUrl);
                } else {
                    productVideoEl.removeAttribute("src");
                }
            }

            const productImageEl = productLinkEl.querySelector(".product__img");
            if (productImageEl) {
                if (productThumbnailUrl) {
                    productImageEl.setAttribute("src", productThumbnailUrl);
                    productImageEl.setAttribute("loading", "lazy");
                    productImageEl.setAttribute("decoding", "async");
                } else {
                    productImageEl.setAttribute("src", HeaderMegaMenuSearch.TRANSPARENT_PIXEL_DATA_URL);
                }
                productImageEl.setAttribute("alt", productTitle);
            }

            const productTitleEl = productLinkEl.querySelector(".product__title");
            if (productTitleEl) productTitleEl.innerHTML = productTitle;

            const productRentPriceEl = productLinkEl.querySelector(".product__rent-price");
            if (productRentPriceEl) productRentPriceEl.textContent = `${productRentPriceText}đ`;

            const productSalePriceEl = productLinkEl.querySelector(".product__price");
            if (productSalePriceEl) productSalePriceEl.textContent = `(Giá bán: ${productSalePriceText}đ)`;

            resultFragment.appendChild(productLinkEl);
        });

        this.resultListEl.replaceChildren(resultFragment);
    }
    renderBlogs(blogs) {
        if (!this.relatedBlogListEl) return;
        this.relatedBlogListEl.setAttribute("aria-busy", "false");
        const blogList = Array.isArray(blogs) ? blogs : [];

        if (blogList.length === 0) {
            this.relatedBlogListEl.replaceChildren();
            return;
        }

        const fragment = document.createDocumentFragment();
        blogList.forEach((blogData) => {
            const blogUrl = blogData?.url || "#";
            const blogTitle = blogData?.title || "";
            const blogThumb = blogData?.thumbnail || "";
            const blogCategory = blogData?.category || "";

            const blogNode = this.createBlogItemNode();
            if (!blogNode) return;

            blogNode.classList.remove("blog-item--skeleton");
            const linkEl = blogNode.querySelector(".blog-item__link");
            if (linkEl) {
                linkEl.setAttribute("href", blogUrl);
                linkEl.setAttribute("aria-label", blogTitle);
            }
            const imgEl = blogNode.querySelector(".blog-item__thumbnail img");
            if (imgEl) {
                if (blogThumb) {
                    imgEl.setAttribute("src", blogThumb);
                    imgEl.setAttribute("loading", "lazy");
                    imgEl.setAttribute("decoding", "async");
                } else {
                    imgEl.setAttribute("src", HeaderMegaMenuSearch.TRANSPARENT_PIXEL_DATA_URL);
                }
                imgEl.setAttribute("alt", blogTitle);
            }
            const categoryEl = blogNode.querySelector(".blog-item__category");
            if (categoryEl) categoryEl.textContent = blogCategory;
            const titleEl = blogNode.querySelector(".blog-item__title");
            if (titleEl) titleEl.innerHTML = blogTitle;

            fragment.appendChild(blogNode);
        });

        this.relatedBlogListEl.replaceChildren(fragment);
    }
    async fetchSearchResults(keyword) {
        if (!this.resultListEl) return;
        const searchKeyword = String(keyword ?? "").trim();

        this.renderSkeletonLoading(HeaderMegaMenuSearch.PRODUCTS_LIMIT);
        this.renderBlogSkeletonLoading(HeaderMegaMenuSearch.BLOGS_LIMIT);

        if (this.activeRequestController) {
            this.activeRequestController.abort();
        }
        this.activeRequestController = new AbortController();
        const signal = this.activeRequestController.signal;

        const productsEndpointUrl = new URL(HeaderMegaMenuSearch.PRODUCTS_ENDPOINT, window.location.origin);
        productsEndpointUrl.searchParams.set("search", searchKeyword || "");
        productsEndpointUrl.searchParams.set("limit", String(HeaderMegaMenuSearch.PRODUCTS_LIMIT));
        productsEndpointUrl.searchParams.set("page", String(HeaderMegaMenuSearch.PRODUCTS_PAGE));

        const blogsEndpointUrl = new URL(HeaderMegaMenuSearch.BLOGS_ENDPOINT, window.location.origin);
        blogsEndpointUrl.searchParams.set("search", searchKeyword || "");
        blogsEndpointUrl.searchParams.set("limit", String(HeaderMegaMenuSearch.BLOGS_LIMIT));
        blogsEndpointUrl.searchParams.set("page", String(HeaderMegaMenuSearch.BLOGS_PAGE));

        try {
            const [productsResponse, blogsResponse] = await Promise.all([
                fetch(productsEndpointUrl.toString(), { method: "GET", signal }),
                fetch(blogsEndpointUrl.toString(), { method: "GET", signal }),
            ]);

            let products = [];
            let blogs = [];

            if (productsResponse.ok) {
                const productsJson = await productsResponse.json();
                if (productsJson?.success) {
                    products = productsJson?.data || [];
                }
            }
            if (blogsResponse.ok) {
                const blogsJson = await blogsResponse.json();
                if (blogsJson?.success) {
                    blogs = blogsJson?.data || [];
                }
            }

            this.renderProducts(products);
            this.renderBlogs(blogs);
            this.updateSearchResultsLayout(products.length, blogs.length);
        } catch (error) {
            if (error?.name === "AbortError") return;
            this.resultListEl.setAttribute("aria-busy", "false");
            if (this.relatedBlogListEl) this.relatedBlogListEl.setAttribute("aria-busy", "false");
        }
    }
    handleSearchTyping() {
        if (!this.inputEl) return;
        const onInput = () => {
            const searchKeyword = this.inputEl.value?.trim() || "";
            if (this.debounceTimer) clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.fetchSearchResults(searchKeyword);
            }, HeaderMegaMenuSearch.DEBOUNCE_MS);
        };
        this.inputEl.addEventListener("input", onInput);
        // Initial fetch (when opening menu) to ensure synced with API ordering
        this.fetchSearchResults(this.inputEl.value?.trim() || "");
    }
    handleClickSearchBtn() {
        if (!this.btnSearchEl) return;
        this.btnSearchEl.addEventListener("click", () => {
            this.containerEl.classList.add("header__mega-menu-search--active");
            this.overlayEl.classList.add("header__mega-menu-overlay--active");
        });
    }
    handleClickOverlay() {
        if (!this.overlayEl) return;
        this.overlayEl.addEventListener("click", () => {
            this.containerEl.classList.remove("header__mega-menu-search--active");
            this.overlayEl.classList.remove("header__mega-menu-overlay--active");
        });
    }
    events() {
        this.handleClickSearchBtn()
        this.handleClickOverlay()
        this.handleSearchTyping()
    }
}

export const headerDesktopInit = () => {
    console.log("Header desktop init")
    new Header();
    new HeaderMegaMenuService();
    new HeaderMegaMenuProduct();
    new HeaderMegaMenuSearch();
}