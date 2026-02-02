export function finestBeachScripts() {
  const convertRemToPx = (rem) => {
    const rootFontSize = parseFloat(
      getComputedStyle(document.documentElement).fontSize,
    );
    return rem * rootFontSize;
  };
  let mainSwiper = null;
  let thumbsSwiper = null;
  

  const destroyPopupSwipers = () => {
    if (mainSwiper && typeof mainSwiper.destroy === "function") {
      mainSwiper.destroy(true, true);
      mainSwiper = null;
    }
    if (thumbsSwiper && typeof thumbsSwiper.destroy === "function") {
      thumbsSwiper.destroy(true, true);
      thumbsSwiper = null;
    }
  };

  const btnViewMore = document.querySelector(".ht-finest-beach_view-more");
  const progressCurrent = document.querySelector(
    ".ht-finest-beach-progress-current",
  );
  const progressViewed = document.querySelector(
    ".ht-finest-beach_progress-viewed",
  );
  const cards = Array.from(document.querySelectorAll(".ht-finest-beach_card"));
  const btnPopups = document.querySelectorAll(".ht-finest-beach_card");
  const popup = document.querySelector(".attractions-popup__popup");
  const popupOverlay = document.querySelector(".attractions-popup__overlay");
  const popupClose = document.querySelector(".attractions-popup__close");
  const popupContent = document.querySelector(".attractions-popup__content");
  const popupTitle = document.querySelector(".attractions-popup__title");
  const popupDesc = document.querySelector(".attractions-popup__desc");
  const mainWrapper = document.querySelector(
    ".attractions-popup__gallery-swiper .swiper-wrapper",
  );
  const thumbsWrapper = document.querySelector(
    ".attractions-popup__gallery-thumbs-swiper .swiper-wrapper",
  );
  const popupLink = document.querySelector(".attractions-popup__link");

  if (!popup || !popupOverlay || !popupClose || !popupContent) return;

  const total = cards.length;
  const STEP = 3;
  let visibleCount = STEP;

  const updateView = () => {
    cards.forEach((card, index) => {
      card.classList.toggle("hidden-mb", index >= visibleCount);
    });
    const percentViewed = (visibleCount / total) * 100;

    if (progressCurrent) progressCurrent.style.width = `${percentViewed}%`;
    if (progressViewed) progressViewed.textContent = visibleCount;

    // hết card thì ẩn nút
    if (visibleCount >= total) {
      if (btnViewMore) btnViewMore.style.display = "none";
    }
  };

  btnViewMore?.addEventListener("click", () => {
    if (visibleCount >= total) return;
    visibleCount = Math.min(total, visibleCount + STEP);
    updateView();
  });

  function openPopupFromCard(cardEl) {
    if (!cardEl) return;

    const id = cardEl.getAttribute("data-id") || "";
    const currentId = popup.getAttribute("data-current-attraction-id") || "";
    const isDifferent = id && id !== currentId;

    const name = cardEl.getAttribute("data-name") || "";
    const desc = cardEl.getAttribute("data-desc") || "";
    const link = cardEl.getAttribute("data-link") || "#";
    const galleryData = cardEl.getAttribute("data-gallery");
    const thumbnail = cardEl.getAttribute("data-thumbnail");

    if (popupTitle) popupTitle.textContent = name;
    if (popupDesc) popupDesc.textContent = desc;
    if (popupLink) popupLink.setAttribute("href", link);

    if (isDifferent) {
      if (mainWrapper) mainWrapper.innerHTML = "";
      if (thumbsWrapper) thumbsWrapper.innerHTML = "";
    }

    if (galleryData && isDifferent) {
 
        const gallery = JSON.parse(galleryData);
        if (Array.isArray(gallery) && gallery.length) {
          gallery.forEach((imageUrl) => {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";
            const img = document.createElement("img");
            img.src = imageUrl;
            img.alt = "";
            img.loading = "lazy";
            img.className = "attractions-popup__img";
            slide.appendChild(img);
            mainWrapper?.appendChild(slide);
          });

          gallery.forEach((imageUrl) => {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";
            const img = document.createElement("img");
            img.src = imageUrl;
            img.alt = "";
            img.loading = "lazy";
            img.className = "attractions-popup__thumb-img";
            slide.appendChild(img);
            thumbsWrapper?.appendChild(slide);
          });
        } else {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";
            const img = document.createElement("img");
            img.src = thumbnail;
            img.alt = "";
            img.loading = "lazy";
            img.className = "attractions-popup__img";
            slide.appendChild(img);
            mainWrapper?.appendChild(slide);
        }
    }

    if (typeof Swiper === "undefined") return;

    if (isDifferent) {
      destroyPopupSwipers();
    }

    if (!thumbsSwiper || isDifferent) {
      thumbsSwiper = new Swiper(".attractions-popup__gallery-thumbs-swiper", {
        spaceBetween: convertRemToPx(0.25),
        slidesPerView: 7,
        freeMode: false,
        watchSlidesProgress: true,
        direction: "horizontal",
        speed: 500,
        loop: true,
        slideToClickedSlide: true,
      });

      mainSwiper = new Swiper(".attractions-popup__gallery-swiper", {
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 500,
        loop: true,
        loopedSlides: thumbsSwiper.slides.length,
        thumbs: {
          swiper: thumbsSwiper,
        },
      });
    }

    popup.setAttribute("data-current-attraction-id", id);
  }

  const closePopup = () => {
    popup.classList.remove("active");
    popup.setAttribute("aria-hidden", "true");
    window.app.enableScroll();
  };

  const openPopup = () => {
    popup.classList.add("active");
    popup.setAttribute("aria-hidden", "false");
    window.app.disableScroll();
  };

  btnPopups.forEach((btn) => {
    btn.addEventListener("click", () => {
      openPopupFromCard(btn);
      openPopup();
    });
  });

  popupOverlay.addEventListener("click", () => {
    closePopup();
  });

  popupClose.addEventListener("click", () => {
    closePopup();
  });

  document.addEventListener("keydown", (e) => {
    if (!popup.classList.contains("active")) return;
    if (e.key === "Escape") {
      e.preventDefault();
      closePopup();
    }
  });
}
