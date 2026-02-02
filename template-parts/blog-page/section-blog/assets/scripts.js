export function sectionBlogScripts() {
    class BlogSection {
        constructor() {
            this.root = document.querySelector("[data-blog-section]");
            if (!this.root) return;

            this.gridEl = this.root.querySelector("[data-blog-grid]");
            this.paginationEl = this.root.querySelector("[data-blog-pagination]");
            this.progressInnerEl = this.root.querySelector(".pagination__progress-inner");
            this.prevLink = this.root.querySelector(".pagination__prev[data-blog-page-link]");
            this.nextLink = this.root.querySelector(".pagination__next[data-blog-page-link]");
            this.paginationTextEl = this.root.querySelector(".pagination__text");

            this.skeletonTemplate = this.root.querySelector("[data-blog-skeleton-template]");
            this.cardTemplate = this.root.querySelector("[data-blog-card-template]");

            this.noResultButton = this.root.querySelector(".no-result__button");

            const ssrPage = Number(this.root.dataset.page || 1);
            const ssrLimit = Number(this.root.dataset.limit || 11);
            const ssrTotal = Number(this.root.dataset.total || 0);
            const ssrTotalPages = Number(this.root.dataset.totalPages || 1);

            this.endpoint = this.root.dataset.endpoint || "/wp-json/api/v1/get-all/post";
            this.limit = ssrLimit || 11;

            const url = new URL(window.location.href);
            this.page = this._parsePageFromUrl(url) || ssrPage || 1;
            this.total = ssrTotal || 0;
            this.totalPages = ssrTotalPages || 1;
            this.categorySlug = (this.root.dataset.category || "").trim();

            this.isLoading = false;

            this._bindEvents();

            const hasPaged = this.page > 1;
            if (hasPaged) this.request({ push: false, scroll: false });
            else this._syncNavState();
        }

        _parsePageFromUrl(urlObj) {
            const fromParam = Number(urlObj.searchParams.get("paged") || 0);
            if (fromParam > 0) return fromParam;

            const m = String(urlObj.pathname || "").match(/\/page\/(\d+)\/?$/);
            if (m && m[1]) return Number(m[1]);
            return 0;
        }

        _setLoading(loading) {
            this.root.classList.toggle("is-loading", loading);
            this.root.setAttribute("aria-busy", loading ? "true" : "false");
        }

        _renderSkeleton() {
            if (!this.gridEl || !this.skeletonTemplate || !("content" in this.skeletonTemplate)) return;
            const fragment = this.skeletonTemplate.content.cloneNode(true);
            this.gridEl.replaceChildren(fragment);
        }

        _scrollToSection() {
            if ("scrollRestoration" in history) history.scrollRestoration = "manual";

            const rect = this.root.getBoundingClientRect();
            const top = rect.top + window.scrollY;
            const targetTop = Math.max(0, top);

            const app = window.app;
            const lenis = app && app.lenis;
            const prefersReducedMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

            if (lenis && typeof lenis.scrollTo === "function") {
                try {
                    if (typeof lenis.stop === "function") lenis.stop();
                    if (typeof lenis.start === "function") lenis.start();
                    lenis.scrollTo(targetTop, {
                        immediate: prefersReducedMotion,
                        duration: prefersReducedMotion ? 0 : 0.8,
                        easing: (t) => 1 - Math.pow(1 - t, 3),
                    });
                } finally {
                }
                return;
            }

            window.scrollTo({
                top: targetTop,
                behavior: prefersReducedMotion ? "auto" : "smooth",
            });
        }

        _scrollToCategory() {
            const app = window.app;
            const lenis = app && app.lenis;
            const prefersReducedMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

            if (lenis && typeof lenis.scrollTo === "function") {
                try {
                    if (typeof lenis.stop === "function") lenis.stop();
                    if (typeof lenis.start === "function") lenis.start();
                    lenis.scrollTo("#section-category", {
                        immediate: prefersReducedMotion,
                        duration: prefersReducedMotion ? 0 : 0.8,
                        easing: (t) => 1 - Math.pow(1 - t, 3),
                    });
                } finally {
                }
                return;
            }

            const el = document.getElementById("section-category");
            if (el) {
                el.scrollIntoView({
                    behavior: prefersReducedMotion ? "auto" : "smooth",
                });
            }
        }

        _getLayoutClass(index, count) {
            if (count === 1 && index === 0) return "blog-card--xl";
            if (index === 0) return "blog-card--lg";
            if (count >= 4 && index === 3) return "blog-card--lg";
            if (count > 4 && index === count - 1) return "blog-card--xl";
            return "";
        }

        _computeReadingTimeMinutes(text) {
            const content = String(text || "").trim();
            if (!content) return "";
            const words = content.split(/\s+/).filter(Boolean).length;
            const minutes = Math.max(1, Math.ceil(words / 200));
            return `${minutes} min read`;
        }

        _normalizeThumbnailUrl(src) {
            const placeholder = "/wp-content/uploads/2025/10/placeholder.webp";
            const fallback = "/wp-content/uploads/Thunbnail.webp";

            const raw = String(src || "").trim();
            if (!raw) return fallback;

            if (raw === placeholder) return fallback;

            if (/^https?:\/\//i.test(raw)) return raw;

            if (raw.startsWith("/")) return `${window.location.origin}${raw}`;

            return raw;
        }

        _extractCategoryName(item) {
            const fromTaxonomies = item && item.taxonomies && item.taxonomies.category && item.taxonomies.category[0];
            if (fromTaxonomies && fromTaxonomies.name) return String(fromTaxonomies.name);

            if (item && item.category) return String(item.category);
            if (item && item.categories && item.categories[0] && item.categories[0].name) return String(item.categories[0].name);
            return "";
        }

        _renderList(items) {
            if (!this.gridEl) return;
            this.gridEl.innerHTML = "";

            if (!Array.isArray(items) || items.length === 0) return;
            if (!this.cardTemplate || !("content" in this.cardTemplate)) return;

            items.forEach((it, index) => {
                const count = items.length;
                const layoutClass = this._getLayoutClass(index, count);

                const node = this.cardTemplate.content.cloneNode(true);
                const article = node.querySelector("article");
                const a = node.querySelector("a.blog-card");
                const img = node.querySelector(".blog-card__image img");
                const titleEl = node.querySelector(".blog-card__title");
                const categoryEl = node.querySelector("[data-blog-card-category]");
                const readingEl = node.querySelector("[data-blog-card-reading-time]");

                const title = it && it.title ? String(it.title) : "";
                const href = it && (it.url || it.link) ? String(it.url || it.link) : "#";
                const thumb = this._normalizeThumbnailUrl(it && it.thumbnail ? String(it.thumbnail) : "");

                if (article && layoutClass) article.classList.add(layoutClass);
                if (a) {
                    if (layoutClass) a.classList.add(layoutClass);
                    a.href = href;
                }
                if (img) {
                    if (thumb) img.src = thumb;
                    img.alt = title;
                }
                if (titleEl) titleEl.textContent = title;
                if (categoryEl) categoryEl.textContent = this._extractCategoryName(it);

                if (readingEl) {
                    if (it && it.reading_time) readingEl.textContent = String(it.reading_time);
                    else readingEl.textContent = this._computeReadingTimeMinutes(it && (it.excerpt || it.title || ""));
                }

                this.gridEl.appendChild(node);
            });
        }

        _setPagedInUrl(inputUrl, page) {
            const u = new URL(inputUrl, window.location.origin);

            const cleanPath = (pathname) => pathname.replace(/\/page\/(\d+)\/?$/, "/");
            u.pathname = cleanPath(u.pathname);

            if (page && page > 1) {
                u.searchParams.set("paged", String(page));
            } else {
                u.searchParams.delete("paged");
            }

            return u.toString();
        }

        _buildApiUrl() {
            const url = new URL(this.endpoint, window.location.origin);
            url.searchParams.set("paged", String(this.page));
            url.searchParams.set("limit", String(this.limit));
            url.searchParams.set("orderby", "modified");
            url.searchParams.set("order", "DESC");

            if (this.categorySlug) {
                url.searchParams.set("tax", "category");
                url.searchParams.set("category", this.categorySlug);
            }

            return url.toString();
        }

        _renderPagination(total, totalPages) {
            const tp = Number(totalPages || 1);
            const viewed = Math.min(this.page * this.limit, total || this.page * this.limit);

            if (this.paginationTextEl) {
                this.paginationTextEl.textContent = total ? `You've viewed ${viewed} of ${total} articles` : `Page ${this.page} of ${tp}`;
            }

            if (this.progressInnerEl) {
                const progress = tp > 0 ? Math.round((this.page / tp) * 100) : 100;
                this.progressInnerEl.style.width = `${progress}%`;
            }
        }

        _syncNavState() {
            const max = Number(this.totalPages || 1);

            const prevDisabled = this.page <= 1;
            const nextDisabled = this.page >= max;

            if (this.prevLink) {
                this.prevLink.classList.toggle("is-disabled", prevDisabled);
                this.prevLink.setAttribute("aria-disabled", prevDisabled ? "true" : "false");
                this.prevLink.href = this._setPagedInUrl(window.location.href, Math.max(1, this.page - 1));
            }

            if (this.nextLink) {
                this.nextLink.classList.toggle("is-disabled", nextDisabled);
                this.nextLink.setAttribute("aria-disabled", nextDisabled ? "true" : "false");
                this.nextLink.href = this._setPagedInUrl(window.location.href, Math.min(max, this.page + 1));
            }
        }

        async request({ push = true, scroll = true } = {}) {
            if (!this.gridEl || this.isLoading) return;
            this.isLoading = true;

            try {
                this._setLoading(true);
                this._renderSkeleton();
                if (scroll) this._scrollToSection();

                const apiUrl = this._buildApiUrl();
                const res = await fetch(apiUrl, { headers: { Accept: "application/json" } });
                if (!res.ok) throw new Error();

                const json = await res.json();
                const data = json && (json.data || json.items) ? json.data || json.items : [];
                const page = Number(json && (json.paged || json.page)) || this.page;
                const totalPages = Number(json && (json.totalPages || json.total_pages)) || 1;
                const total = Number(json && (json.total || json.found_posts)) || 0;

                this.page = page;
                this.totalPages = totalPages;
                this.total = total;

                this._renderList(data);
                this._renderPagination(total, totalPages);
                this._syncNavState();

                const nextUrl = this._setPagedInUrl(window.location.href, this.page);
                if (push) window.history.pushState({ blogApi: true, url: nextUrl }, "", nextUrl);
            } catch (e) {
                const fallbackUrl = this._setPagedInUrl(window.location.href, this.page);
                window.location.href = fallbackUrl;
            } finally {
                this._setLoading(false);
                this.isLoading = false;
            }
        }

        _bindEvents() {
            this.root.addEventListener("click", (e) => {
                const link = e.target.closest("[data-blog-page-link]");
                if (!link || this.isLoading) return;
                if (link.classList.contains("is-disabled")) return;

                const href = link.getAttribute("href");
                if (!href) return;

                const nextUrl = new URL(href, window.location.origin);
                const nextPage = this._parsePageFromUrl(nextUrl) || 1;

                e.preventDefault();
                this.page = nextPage;
                this.request({ push: true, scroll: true });
            });

            if (this.noResultButton) {
                this.noResultButton.addEventListener("click", () => {
                    this._scrollToCategory();
                });
            }

            window.addEventListener("popstate", () => {
                const u = new URL(window.location.href);
                const p = this._parsePageFromUrl(u) || 1;
                this.page = p;
                this.request({ push: false, scroll: false });
            });
        }
    }

    new BlogSection();
}
