function remToPx(rem) {
  const baseFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * baseFontSize;
}

function initToggleToCMobile() {
  const mobileToc = document.getElementById("mobileToc");
  const toggleBtn = document.getElementById("toggleToc");
  const openBtn = document.getElementById("openMobileToc");
  const overlay = document.getElementById("mobileTocOverlay");
  if (!mobileToc) return;

  const tocWrap = mobileToc.querySelector("#ez-toc-container");
  if (tocWrap) {
    const tocList = tocWrap.querySelector("ul.ez-toc-list");
    const tocItems = tocList
      ? Array.from(tocList.querySelectorAll(":scope > li"))
      : [];

    if (tocItems.length > 4) {
      tocWrap.classList.add("toc-mobile-collapsible", "toc-mobile-collapsed");

      if (!tocWrap.querySelector(".toc-mobile-toggle-btn")) {
        const mobileToggleBtn = document.createElement("button");
        mobileToggleBtn.type = "button";
        mobileToggleBtn.className = "toc-mobile-toggle-btn";
        mobileToggleBtn.setAttribute("aria-expanded", "false");
        mobileToggleBtn.textContent = "Xem thêm";

        mobileToggleBtn.addEventListener("click", () => {
          const isCollapsed = tocWrap.classList.toggle("toc-mobile-collapsed");
          mobileToggleBtn.setAttribute(
            "aria-expanded",
            isCollapsed ? "false" : "true",
          );
          mobileToggleBtn.textContent = isCollapsed ? "Xem thêm" : "Thu gọn";
        });

        tocWrap.appendChild(mobileToggleBtn);
      }
    }
  }

  const openDrawer = () => {
    mobileToc.classList.add("is-open");
    if (overlay) overlay.classList.add("is-open");
    if (openBtn) openBtn.setAttribute("aria-expanded", "true");
  };

  const closeDrawer = () => {
    mobileToc.classList.remove("is-open");
    if (overlay) overlay.classList.remove("is-open");
    if (openBtn) openBtn.setAttribute("aria-expanded", "false");
  };

  if (openBtn) {
    openBtn.addEventListener("click", function () {
      openDrawer();
    });
  }

  if (toggleBtn) {
    toggleBtn.addEventListener("click", function () {
      closeDrawer();
    });
  }

  if (overlay) {
    overlay.addEventListener("click", closeDrawer);
  }

  const tocLinks = mobileToc.querySelectorAll("a");
  tocLinks.forEach((link) => {
    link.addEventListener("click", () => {
      closeDrawer();
    });
  });
}

function initRelatedPostsSlider() {
  const sliderEl = document.querySelector(".related-news-slider");
  if (!sliderEl) return;
  if (sliderEl.classList.contains("swiper-initialized")) return;
  if (typeof window.Swiper === "undefined") return;

  const desktopMedia = window.matchMedia("(min-width: 1024px)");

  const relatedSlider = new Swiper(".related-news-slider", {
    slidesPerView: "auto",
    spaceBetween: remToPx(0.75),
    loop: true,
    // autoplay: {
    //       delay: 5000,
    //       disableOnInteraction: false,
    // },
    navigation: {
      nextEl: ".related-next",
      prevEl: ".related-prev",
    },
    breakpoints: {
      1024: { spaceBetween: remToPx(1.5) },
    },
  });

  const syncAutoplayByViewport = () => {
    if (!relatedSlider.autoplay) return;
    if (desktopMedia.matches) relatedSlider.autoplay.stop();
    else relatedSlider.autoplay.start();
  };

  syncAutoplayByViewport();
  if (desktopMedia.addEventListener) {
    desktopMedia.addEventListener("change", syncAutoplayByViewport);
  } else if (desktopMedia.addListener) {
    desktopMedia.addListener(syncAutoplayByViewport);
  }
}

function initDesktopTocToggle() {
  const tocWrap = document.querySelector(
    ".toc-custom-container #ez-toc-container",
  );
  if (!tocWrap) return;

  const tocList = tocWrap.querySelector("ul.ez-toc-list");
  if (!tocList) return;

  const items = Array.from(tocList.querySelectorAll(":scope > li"));
  if (items.length <= 4) return;

  tocWrap.classList.add("toc-is-collapsible", "toc-is-collapsed");

  // Tránh chèn trùng nếu shortcode re-render (hiếm)
  if (tocWrap.querySelector(".toc-toggle-btn")) return;

  const btn = document.createElement("button");
  btn.type = "button";
  btn.className = "toc-toggle-btn";
  btn.setAttribute("aria-expanded", "false");
  btn.textContent = "Xem thêm";

  btn.addEventListener("click", () => {
    const isCollapsed = tocWrap.classList.toggle("toc-is-collapsed");
    btn.setAttribute("aria-expanded", isCollapsed ? "false" : "true");
    btn.textContent = isCollapsed ? "Xem thêm" : "Thu gọn";
  });

  tocWrap.appendChild(btn);
}

document.addEventListener("DOMContentLoaded", function () {
  const copyBtn = document.getElementById("copy_link");
  if (copyBtn) {
    copyBtn.addEventListener("click", function (e) {
      e.preventDefault();
      const url = this.getAttribute("data-url") || window.location.href;

      function fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;

        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
          var successful = document.execCommand("copy");
          if (successful) alert("Đã sao chép liên kết!");
        } catch (err) {
          console.error("Không thể copy", err);
        }

        document.body.removeChild(textArea);
      }

      if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(url);
      } else {
        navigator.clipboard.writeText(url).then(
          function () {
            alert("Đã sao chép liên kết!");
          },
          function (err) {
            fallbackCopyTextToClipboard(url);
          },
        );
      }
    });
  }

  const shareTriggers = document.querySelectorAll(".share-trigger");

  shareTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function (e) {
      e.preventDefault();
      const shareUrl = this.getAttribute("data-share-url");

      if (shareUrl) {
        const width = 600;
        const height = 450;
        const left = window.innerWidth / 2 - width / 2;
        const top = window.innerHeight / 2 - height / 2;

        window.open(
          shareUrl,
          "shareWindow",
          `width=${width},height=${height},left=${left},top=${top},toolbar=0,status=0,menubar=0`,
        );
      }
    });
  });

  initRelatedPostsSlider();
  initDesktopTocToggle();
  initToggleToCMobile();
});
