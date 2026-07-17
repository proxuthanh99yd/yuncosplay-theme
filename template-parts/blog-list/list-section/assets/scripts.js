window.initBlogAjax = function () {
  const container = document.getElementById("ajax-content-replace");
  if (!container) return;

  const skeletonCount = () =>
    window.matchMedia("(max-width: 639.98px)").matches ? 9 : 16;

  const ajaxUrl = "/wp-admin/admin-ajax.php";
  const mobileLabel = document.querySelector("#openCategoryDrawer span");
  const btnPrev = document.querySelector(".js-filter-prev");
  const btnNext = document.querySelector(".js-filter-next");

  const updateNavButtonsState = () => {
    const items = Array.from(
      document.querySelectorAll(".category-filter__list .js-category-filter"),
    );
    const currentIndex = items.findIndex((item) =>
      item.classList.contains("category-filter__item--active"),
    );

    console.log(currentIndex);

    if (btnPrev) {
      btnPrev.classList.toggle("isActive", currentIndex === 0);
    }

    if (btnNext) {
      btnNext.classList.toggle(
        "isActive",
        currentIndex === items.length - 1 && currentIndex !== -1,
      );
    }
  };

  const skeletonHtml = () => `
            <div class="blog-list-skeleton" aria-hidden="true">
                  ${Array(skeletonCount())
                    .fill(0)
                    .map(
                      () => `
                  <div class="blog-list-skeleton__card">
                        <div class="blog-list-skeleton__media"></div>
                        <div class="blog-list-skeleton__meta"></div>
                        <div class="blog-list-skeleton__title"></div>
                        <div class="blog-list-skeleton__title blog-list-skeleton__title--short"></div>
                  </div>`,
                    )
                    .join("")}
            </div>`;

  const showListSkeleton = () => {
    container.classList.add("ajax-content-replace--loading");
    if (!container.querySelector(".blog-list-skeleton")) {
      container.insertAdjacentHTML("afterbegin", skeletonHtml());
    }
  };

  const fetchPosts = (slug, catName, page) => {
    showListSkeleton();

    const formData = new FormData();
    formData.append("action", "filter_posts");
    formData.append("category", slug);
    formData.append("paged", page);

    fetch(ajaxUrl, { method: "POST", body: formData })
      .then((res) => res.text())
      .then((data) => {
        container.classList.remove("ajax-content-replace--loading");
        container.innerHTML = data;

        // if (mobileLabel && catName) mobileLabel.innerText = catName;

        const closeBtn = document.getElementById("closeDrawer");
        if (closeBtn) closeBtn.click();

        window.scrollTo({
          top: document.getElementById("blog-list").offsetTop - 100,
          behavior: "smooth",
        });

        updateURL(slug, page);
        updateNavButtonsState();
      })
      .catch((error) => {
        console.error("Error:", error);
        container.classList.remove("ajax-content-replace--loading");
        const sk = container.querySelector(".blog-list-skeleton");
        if (sk) sk.remove();
      });
  };

  const updateURL = (slug, page) => {
    let newUrl = window.location.pathname;
    let params = new URLSearchParams();
    if (slug) params.set("categories", slug);
    if (page > 1) params.set("paged", page);
    const qs = params.toString();
    window.history.pushState({}, "", newUrl + (qs ? "?" + qs : ""));
  };

  const navigateCategory = (direction) => {
    const items = Array.from(
      document.querySelectorAll(".category-filter__list .js-category-filter"),
    );
    const currentIndex = items.findIndex((item) =>
      item.classList.contains("category-filter__item--active"),
    );

    let targetIndex =
      direction === "next" ? currentIndex + 1 : currentIndex - 1;

    if (targetIndex >= 0 && targetIndex < items.length) {
      items[targetIndex].click();

      items[targetIndex].scrollIntoView({
        behavior: "smooth",
        block: "nearest",
        inline: "center",
      });
    }
  };

  if (btnPrev)
    btnPrev.addEventListener("click", (e) => {
      e.preventDefault();
      if (!btnPrev.classList.contains("isActive")) navigateCategory("prev");
    });

  if (btnNext)
    btnNext.addEventListener("click", (e) => {
      e.preventDefault();
      if (!btnNext.classList.contains("isActive")) navigateCategory("next");
    });

  document.addEventListener("click", (e) => {
    const filterBtn = e.target.closest(".js-category-filter");
    if (filterBtn) {
      e.preventDefault();
      const slug = filterBtn.dataset.slug || "";
      const catName = filterBtn.innerText.trim();

      document.querySelectorAll(".js-category-filter").forEach((btn) => {
        const isMatch = (btn.dataset.slug || "") === slug;
        btn.classList.toggle("category-filter__item--active", isMatch);

        const radio = btn.querySelector(".category-drawer__custom-radio");
        const label = btn.querySelector(".category-drawer__label");
        const icon = btn.querySelector(".check-icon");
        if (radio) radio.classList.toggle("is-active", isMatch);
        if (label) label.classList.toggle("is-active", isMatch);
        if (icon) icon.style.display = isMatch ? "block" : "none";
      });

      updateNavButtonsState();

      fetchPosts(slug, catName, 1);
    }

    // Phân trang
    const paginationLink = e.target.closest(".pagination__link");
    if (
      paginationLink &&
      !paginationLink.classList.contains("pagination__link--active")
    ) {
      e.preventDefault();
      const url = new URL(paginationLink.href);
      const activeBtn = document.querySelector(
        ".js-category-filter.category-filter__item--active",
      );
      fetchPosts(
        activeBtn ? activeBtn.dataset.slug : "",
        mobileLabel ? mobileLabel.innerText : "",
        url.searchParams.get("paged") || 1,
      );
    }
  });

  // Chạy lần đầu khi load trang để thiết lập trạng thái nút
  updateNavButtonsState();
};
window.toggleModalMenu = function () {
  const drawer = document.getElementById("categoryDrawer");
  const openBtn = document.getElementById("openCategoryDrawer");
  const closeBtn = document.getElementById("closeDrawer");
  const overlay = document.getElementById("drawerOverlay");
  const body = document.body;

  if (!drawer || !openBtn) return;

  function toggleDrawer(isOpen) {
    if (isOpen) {
      drawer.classList.add("category-drawer--active");
      body.style.overflow = "hidden";
    } else {
      drawer.classList.remove("category-drawer--active");
      body.style.overflow = "";
    }
  }

  openBtn.addEventListener("click", (e) => {
    e.preventDefault();
    toggleDrawer(true);
  });

  if (closeBtn) closeBtn.addEventListener("click", () => toggleDrawer(false));
  if (overlay) overlay.addEventListener("click", () => toggleDrawer(false));

  window.addEventListener("resize", function () {
    if (window.innerWidth >= 1024) {
      toggleDrawer(false);
    }
  });
};
