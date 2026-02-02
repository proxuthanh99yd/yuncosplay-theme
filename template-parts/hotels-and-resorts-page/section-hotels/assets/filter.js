export function hotelFilter() {
  class Hotels {
    constructor() {
      this.root = document.querySelector("[data-hotels]");
      if (!this.root) return;

      // Elements
      this.listEl = this.root.querySelector("[data-hotels-list]");
      this.paginationEl = this.root.querySelector("[data-hotels-pagination]");
      this.paginationTextEl = this.root.querySelector("[data-pagination-text]");
      this.progressEl = this.root.querySelector("[data-pagination-progress]");
      this.prevBtn = this.root.querySelector("[data-page-prev]");
      this.nextBtn = this.root.querySelector("[data-page-next]");
      this.contentEl = this.root.querySelector("[data-hotels-content]");
      this.countryBox = this.root.querySelector("[data-filter-country]");
      this.citiesBox = this.root.querySelector("[data-filter-cities]");
      this.ratingBox = this.root.querySelector("[data-filter-rating]");

      this.citySearchInput = this.root.querySelector("[data-city-search]");
      this.citiesEmptyMessage = this.root.querySelector("#citiesEmptyMessage");

      this.template = document.getElementById("template-hotel-card")?.content || null;

      // SSR data fallback
      const ssrPage = Number(this.root.dataset.page || 1);
      const ssrLimit = Number(this.root.dataset.limit || 1);
      const ssrTotal = Number(this.root.dataset.total || 0);
      const ssrTotalPages = Number(this.root.dataset.totalPages || 1);

      this.limit = ssrLimit || 1;
      this.endpoint = this.root.dataset.endpoint || "/wp-json/api/v1/get-all/hotel";

      // URL state
      const url = new URL(window.location.href);
      const p = url.searchParams;

      // ✅ dùng paged (đúng API)
      this.page = Number(p.get("paged") || ssrPage || 1);
      this.totalPages = ssrTotalPages || 1;
      this.total = ssrTotal || 0;

      this.selectedCountries = this._splitParam(p.get("indochina"));
      this.selectedCities = this._splitParam(p.get("cities"));
      this.selectedRatings = this._splitParam(p.get("rating"));

      this._initDropdowns();
      this._syncUIFromState();
      this._bindEvents();

      // Nếu có filter/paged > 1 thì fetch lại để đúng data
      const hasFilterInUrl =
        this.selectedCountries.length ||
        this.selectedCities.length ||
        this.selectedRatings.length ||
        (p.get("paged") && Number(p.get("paged")) > 1);

      if (hasFilterInUrl) this.request();
      else {
        // sync nút theo SSR (khỏi trắng)
        this._syncPaginationButtons(this.totalPages);
      }
    }

    _splitParam(val) {
      if (!val) return [];
      return String(val)
        .split(",")
        .map((s) => s.trim())
        .filter(Boolean);
    }

    _getCheckedValues(container, inputName) {
      if (!container) return [];
      return Array.from(container.querySelectorAll(`input[name="${inputName}"]:checked`)).map(
        (i) => i.value
      );
    }

    _getCheckedCountryParentIds() {
      if (!this.countryBox) return [];
      return Array.from(this.countryBox.querySelectorAll('input[name="indochina"]:checked'))
        .map((i) => i.getAttribute("data-parent-term-id"))
        .filter(Boolean);
    }

    _initDropdowns() {
      const dropdowns = this.root.querySelectorAll(".hotels__list-filter-item--dropdown");
      if (!dropdowns.length) return;

      dropdowns.forEach((dd) => {
        const header = dd.querySelector(".hotels__list-filter-item-header");
        if (!header) return;

        header.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          dropdowns.forEach((other) => {
            if (other !== dd) other.classList.remove("is-open");
          });
          dd.classList.toggle("is-open");
        });
      });

      document.addEventListener("click", () => {
        dropdowns.forEach((dd) => dd.classList.remove("is-open"));
      });
    }

    _syncUIFromState() {
      if (this.countryBox) {
        const inputs = this.countryBox.querySelectorAll('input[name="indochina"]');
        inputs.forEach((i) => (i.checked = this.selectedCountries.includes(i.value)));
      }

      if (this.ratingBox) {
        const inputs = this.ratingBox.querySelectorAll('input[name="rating"]');
        inputs.forEach((i) => (i.checked = this.selectedRatings.includes(i.value)));
      }

      if (this.citiesBox) {
        const inputs = this.citiesBox.querySelectorAll('input[name="cities"]');
        inputs.forEach((i) => (i.checked = this.selectedCities.includes(i.value)));
      }

      this._updateCitiesVisibility();
      this._applyCitySearchFilter();
      this._syncPaginationButtons(this.totalPages);
    }

    _updateCitiesVisibility() {
      if (!this.citiesBox) return;

      const parentIds = this._getCheckedCountryParentIds();
      const cityLabels = this.citiesBox.querySelectorAll(
        ".hotels__list-filter-dropdown-item[data-parent-id]"
      );

      if (!parentIds.length) {
        cityLabels.forEach((lb) => {
          lb.style.display = "none";
          const inp = lb.querySelector('input[name="cities"]');
          if (inp) inp.checked = false;
        });
        if (this.citiesEmptyMessage) this.citiesEmptyMessage.style.display = "block";
        this.selectedCities = [];
        return;
      }

      if (this.citiesEmptyMessage) this.citiesEmptyMessage.style.display = "none";

      cityLabels.forEach((lb) => {
        const pid = lb.getAttribute("data-parent-id");
        const isMatch = parentIds.includes(pid);
        lb.style.display = isMatch ? "" : "none";

        if (!isMatch) {
          const inp = lb.querySelector('input[name="cities"]');
          if (inp) inp.checked = false;
        }
      });

      this.selectedCities = this._getCheckedValues(this.citiesBox, "cities");
    }

    _applyCitySearchFilter() {
      if (!this.citiesBox) return;

      const q = (this.citySearchInput?.value || "").trim().toLowerCase();
      const cityLabels = this.citiesBox.querySelectorAll(
        ".hotels__list-filter-dropdown-item[data-parent-id]"
      );

      cityLabels.forEach((lb) => {
        if (lb.style.display === "none") return;
        const text = (lb.textContent || "").trim().toLowerCase();
        lb.style.display = text.includes(q) ? "" : "none";
      });
    }

    _syncPaginationButtons(totalPages = null) {
      const max = Number(totalPages || this.totalPages || 1);

      if (this.prevBtn) {
        const disabled = this.page <= 1;
        this.prevBtn.classList.toggle("is-disabled", disabled);
        this.prevBtn.disabled = disabled;
      }

      if (this.nextBtn) {
        const disabled = this.page >= max;
        this.nextBtn.classList.toggle("is-disabled", disabled);
        this.nextBtn.disabled = disabled;
      }
    }

    _bindEvents() {
      if (this.countryBox) {
        this.countryBox.addEventListener("change", () => {
          this.page = 1;
          this.selectedCountries = this._getCheckedValues(this.countryBox, "indochina");
          this._updateCitiesVisibility();
          this._applyCitySearchFilter();
          this._updateURL();
          this.request();
        });
      }

      if (this.citiesBox) {
        this.citiesBox.addEventListener("change", () => {
          this.page = 1;
          this.selectedCities = this._getCheckedValues(this.citiesBox, "cities");
          this._updateURL();
          this.request();
        });
      }

      if (this.ratingBox) {
        this.ratingBox.addEventListener("change", () => {
          this.page = 1;
          this.selectedRatings = this._getCheckedValues(this.ratingBox, "rating");
          this._updateURL();
          this.request();
        });
      }

      if (this.citySearchInput) {
        this.citySearchInput.addEventListener("input", () => this._applyCitySearchFilter());
      }

      if (this.prevBtn) {
        this.prevBtn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          if (this.prevBtn.disabled) return;

          this.page = Math.max(1, this.page - 1);
          this._updateURL();
          this.request();
        });
      }

      if (this.nextBtn) {
        this.nextBtn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          if (this.nextBtn.disabled) return;

          const max = this.totalPages || 1;
          if (this.page >= max) return;

          this.page += 1;
          this._updateURL();
          this.request();
        });
      }
    }

    _updateURL() {
      const u = new URL(window.location.href);
      const baseUrl = u.origin + u.pathname;
      const params = new URLSearchParams();

      // Build params thủ công để tránh encode dấu phẩy thành %2C
      if (this.selectedCountries.length) {
        params.set("indochina", this.selectedCountries.join(","));
      }

      if (this.selectedCities.length) {
        params.set("cities", this.selectedCities.join(","));
      }

      if (this.selectedRatings.length) {
        params.set("rating", this.selectedRatings.join(","));
      }

      if (this.page > 1) {
        params.set("paged", String(this.page));
      }

      // Build URL với dấu phẩy không bị encode
      const queryString = params.toString().replace(/%2C/g, ",");
      const finalUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;

      window.history.replaceState({}, "", finalUrl);
    }

    _buildApiUrl() {
      const destinationSlugs = Array.from(
        new Set([...(this.selectedCountries || []), ...(this.selectedCities || [])])
      );

      const url = new URL(this.endpoint, window.location.origin);

      // ✅ API của bạn dùng paged
      url.searchParams.set("paged", String(this.page));
      url.searchParams.set("limit", String(this.limit));

      url.searchParams.set("tax", "destination,rating-of-the-property");

      if (destinationSlugs.length) url.searchParams.set("destination", destinationSlugs.join(","));
      if (this.selectedRatings.length) {
        url.searchParams.set("rating-of-the-property", this.selectedRatings.join(","));
      }

      url.searchParams.set("orderby", "modified");
      url.searchParams.set("order", "DESC");

      return url.toString();
    }

    async request() {
      if (!this.listEl) return;

      try {
        this.listEl.classList.add("loading");
        this._renderSkeleton(this.limit);

        const apiUrl = this._buildApiUrl();
        const res = await fetch(apiUrl, { headers: { Accept: "application/json" } });

        if (!res.ok) throw new Error("Hotel API endpoint không đúng hoặc bị chặn.");

        this._handleResponse(await res.json());
      } catch (err) {
        console.error(err);
        this._renderNoResults("Không tải được dữ liệu. Vui lòng thử lại.");
      } finally {
        this.listEl.classList.remove("loading");
      }
    }

    _handleResponse(json) {
      const data = json?.data || json?.items || [];
      const page = Number(json?.paged || json?.page || this.page || 1); // nhận cả 2
      const totalPages = Number(json?.totalPages || json?.total_pages || 1);
      const total = Number(json?.total || json?.found_posts || 0);

      this.page = page;
      this.totalPages = totalPages;
      this.total = total;

      this._renderList(data);
      this._renderPagination(total, totalPages);
      this._syncPaginationButtons(totalPages);

      // scroll nhẹ lên section
      this.root.scrollIntoView({ behavior: "smooth", block: "start" });
    }

    _renderNoResults(message = "No suitable hotels available") {
      if (!this.contentEl) return;
    
      if (this.listEl) this.listEl.innerHTML = "";
      this.contentEl.querySelector(".no-results-found")?.remove();
    
      const no = document.createElement("div");
      no.className = "no-results-found";
    
      const img = document.createElement("img");
      img.src = "/wp-content/uploads/Frame-2147263890.svg";
      img.className = "no-results-found__img";
    
      const title = document.createElement("span");
      title.className = "no-results-found__title";
      title.textContent = message;
    
      no.append(img, title);
    
      // ✅ chèn ngay trước pagination
      const anchor = this.paginationEl || this.contentEl.lastElementChild;
      if (anchor) this.contentEl.insertBefore(no, anchor);
      else this.contentEl.appendChild(no);
    }
    
    _renderList(items) {
      if (!this.listEl) return;
      this.listEl.innerHTML = "";

      if (!Array.isArray(items) || items.length === 0) {
        this._renderNoResults("No suitable hotels available");
        return;
      }

      // ✅ Xóa message "no results" cũ khi có items
      if (this.contentEl) {
        this.contentEl.querySelector(".no-results-found")?.remove();
      }

      if (!this.template) return;

      items.forEach((it) => {
        const link = it.link || it.permalink || "#";
        const title = it.title || it.name || "";
        const imageUrl = it?.thumbnail || it?.thumbnail?.url || it?.thumb || "";
        const imageAlt = it?.image?.alt || title;

        const ratingText = it?.taxonomies?.["rating-of-the-property"]?.[0]?.name || "";
        const locationText = it?.taxonomies?.["destination"]?.[1]?.name || it?.taxonomies?.["destination"]?.[0]?.name || "";

        const node = this.template.cloneNode(true);

        const card = node.querySelector(".hotel-card");
        const img = node.querySelector(".hotel-card__image");
        const titleEl = node.querySelector(".hotel-card__title");

        const ratingWrap = node.querySelector(".hotel-card__info-item--rating");
        const ratingVal = ratingWrap?.querySelector(".hotel-card__info-item-value");

        const locWrap = node.querySelector(".hotel-card__info-item--location");
        const locVal = locWrap?.querySelector(".hotel-card__info-item-value");

        if (card) card.href = link;
        if (img && imageUrl) img.src = imageUrl;
        if (img) img.alt = imageAlt;

        if (titleEl) titleEl.textContent = title;

        if (ratingWrap && ratingVal) {
          ratingWrap.style.display = ratingText ? "" : "none";
          if (ratingText) ratingVal.textContent = ratingText;
          const ratingIcon = ratingWrap.querySelector("[data-rating-icon]");
          if (ratingIcon && this.root.dataset.ratingIconUrl) {
            ratingIcon.src = this.root.dataset.ratingIconUrl;
          }
        }

        if (locWrap && locVal) {
          locWrap.style.display = locationText ? "" : "none";
          if (locationText) locVal.textContent = locationText;
        }

        this.listEl.appendChild(node);
      });
    }

    _renderSkeleton(count = this.limit) {
      const tpl = document.getElementById("template-hotel-skeleton")?.content;
      if (!tpl || !this.listEl) return;

      this.listEl.innerHTML = "";
      for (let i = 0; i < count; i++) {
        this.listEl.appendChild(tpl.cloneNode(true));
      }
    }

    _renderPagination(total, totalPages) {
      if (!this.paginationEl) return;

      const tp = Number(totalPages || 1);
      const viewed = Math.min(this.page * this.limit, total || this.page * this.limit);

      if (this.paginationTextEl) {
        this.paginationTextEl.textContent = total
          ? `You've viewed ${viewed} of ${total} articles`
          : `Page ${this.page} of ${tp}`;
      }

      if (this.progressEl) {
        const progress = tp > 0 ? Math.round((this.page / tp) * 100) : 100;
        this.progressEl.style.width = `${progress}%`;
      }
    }
  }

  // init khi DOM sẵn sàng (tránh load trước HTML)
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => new Hotels());
  } else {
    new Hotels();
  }
}
