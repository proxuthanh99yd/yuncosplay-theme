export function sectionStaysScripts() {
  const convertRemToPx = (rem) => {
    const rootFontSize = parseFloat(
      getComputedStyle(document.documentElement).fontSize,
    );
    return rem * rootFontSize;
  };

  let mainSwiper = null;
  let thumbsSwiper = null;
  let lastActiveElement = null;

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

  const btnPopups = document.querySelectorAll(".destination-stays_card");
  const popup = document.querySelector(".stays-popup__popup");
  const popupOverlay = document.querySelector(".stays-popup__overlay");
  const popupClose = document.querySelector(".stays-popup__close");
  const popupContent = document.querySelector(".stays-popup__content");
  const popupTitle = document.querySelector(".stays-popup__title");
  const popupDesc = document.querySelector(".stays-popup__desc");
  const popupPriceFrom = document.querySelector(".stays-popup__price-from");
  const popupPriceTo = document.querySelector(".stays-popup__price-to");
  const popupRating = document.querySelector(".stays-popup__stars");
  const popupAddressText = document.querySelector(".stays-popup__address-text");
  const popupAddressLink = document.querySelector(".stays-popup__address-link");
  const popupServices = document.querySelector(".stays-popup__services");
  const mainWrapper = document.querySelector(
    ".stays-popup__gallery-swiper .swiper-wrapper",
  );
  const thumbsWrapper = document.querySelector(
    ".stays-popup__gallery-thumbs-swiper .swiper-wrapper",
  );
  const popupLink = document.querySelector(".stays-popup__link");

  if (!popup || !popupOverlay || !popupClose || !popupContent) return;

  const starTemplateHTML = popupRating?.querySelector(".stays-popup__stars-icon")
    ?.outerHTML;

  const renderRatingStars = (ratingValue) => {
    if (!popupRating) return;
    if (!starTemplateHTML) {
      popupRating.innerHTML = "";
      return;
    }

    const raw = parseFloat(ratingValue);
    const normalized = Number.isFinite(raw) ? raw : 0;
    const filled = Math.max(0, Math.min(5, Math.floor(normalized)));

    popupRating.innerHTML = starTemplateHTML.repeat(filled);
  };

  function openPopupFromCard(cardEl) {
    if (!cardEl) return;

    const stayId = cardEl.getAttribute("data-stay-id") || "";
    const currentStayId = popup.getAttribute("data-current-stay-id") || "";
    const isDifferentStay = stayId && stayId !== currentStayId;

    const name = cardEl.getAttribute("data-name") || "";
    const desc = cardEl.getAttribute("data-desc") || "";
    const link = cardEl.getAttribute("data-link") || "#";
    const priceFrom = cardEl.getAttribute("data-price-from") || "";
    const priceTo = cardEl.getAttribute("data-price-to") || "";
    const rating = cardEl.getAttribute("data-rating") || "";
    const googleMapLink = cardEl.getAttribute("data-google-map-link") || "";
    const googleMapTarget = cardEl.getAttribute("data-google-map-target") || "";
    const googleMapTitle = cardEl.getAttribute("data-google-map-title") || "";
    const galleryData = cardEl.getAttribute("data-gallery");
    const servicesData = cardEl.getAttribute("data-services");

    if (popupTitle) popupTitle.textContent = name;
    if (popupDesc) popupDesc.textContent = desc;
    if (popupLink) popupLink.setAttribute("href", link);
    if (popupPriceFrom) popupPriceFrom.textContent = priceFrom;
    if (popupPriceTo) popupPriceTo.textContent = priceTo;
    renderRatingStars(rating);

    if (popupAddressText) popupAddressText.textContent = googleMapTitle;

    if (popupAddressLink) {
      popupAddressLink.setAttribute("href", googleMapLink || "#");
      if (googleMapTarget) {
        popupAddressLink.setAttribute("target", googleMapTarget);
        if (googleMapTarget === "_blank") {
          popupAddressLink.setAttribute("rel", "noopener noreferrer");
        }
      }
    }

    if (popupServices) {
      popupServices.innerHTML = "";
      if (servicesData) {
        try {
          const services = JSON.parse(servicesData);
          if (Array.isArray(services) && services.length) {
            services.forEach((svc) => {
              const item = document.createElement("div");
              item.className = "stays-popup__service";

              const nameEl = document.createElement("span");
              nameEl.textContent = svc?.name || "";
              item.appendChild(nameEl);

              if (svc?.icon_url) {
                const img = document.createElement("img");
                img.src = svc.icon_url;
                img.alt = "";
                img.loading = "lazy";
                img.className = "stays-popup__service-icon";
                item.appendChild(img);
              }

              popupServices.appendChild(item);
            });
          }
        } catch (e) {
          // ignore invalid JSON
        }
      }
    }

    if (isDifferentStay) {
      if (mainWrapper) mainWrapper.innerHTML = "";
      if (thumbsWrapper) thumbsWrapper.innerHTML = "";
    }

    if (galleryData && isDifferentStay) {
      try {
        const galleryImages = JSON.parse(galleryData);
        if (Array.isArray(galleryImages) && galleryImages.length > 0) {
          galleryImages.forEach((imageUrl) => {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";
            const img = document.createElement("img");
            img.src = imageUrl;
            img.alt = "";
            img.loading = "lazy";
            img.className = "stays-popup__img";
            slide.appendChild(img);
            mainWrapper?.appendChild(slide);
          });

          galleryImages.forEach((imageUrl) => {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";
            const img = document.createElement("img");
            img.src = imageUrl;
            img.alt = "";
            img.loading = "lazy";
            img.className = "stays-popup__thumb-img";
            slide.appendChild(img);
            thumbsWrapper?.appendChild(slide);
          });
        }
      } catch (e) {
        // ignore invalid JSON
      }
    }

    if (typeof Swiper === "undefined") return;

    if (isDifferentStay) {
      destroyPopupSwipers();
    }

    if (!thumbsSwiper || isDifferentStay) {
      thumbsSwiper = new Swiper(".stays-popup__gallery-thumbs-swiper", {
        spaceBetween: convertRemToPx(0.25),
        slidesPerView: 7,
        freeMode: false,
        watchSlidesProgress: true,
        direction: "horizontal",
        speed: 500,
        loop: true,
        slideToClickedSlide: true,
      });

      mainSwiper = new Swiper(".stays-popup__gallery-swiper", {
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

    popup.setAttribute("data-current-stay-id", stayId);
  }

  const closePopup = () => {
    popup.classList.remove("active");
    popup.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";

    if (lastActiveElement && typeof lastActiveElement.focus === "function") {
      lastActiveElement.focus();
    }
    lastActiveElement = null;
  };

  const openPopup = () => {
    popup.classList.add("active");
    popup.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    lastActiveElement = document.activeElement;
    requestAnimationFrame(() => {
      popupContent.focus();
    });
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

// export function sectionStaysScripts() {
//   const convertRemToPx = (rem) => {
//     const rootFontSize = parseFloat(
//       getComputedStyle(document.documentElement).fontSize,
//     );
//     return rem * rootFontSize;
//   };

//   let popupSwiper = null;

//   const initPopupSwiper = () => {
//     if (popupSwiper && typeof popupSwiper.destroy === "function") {
//       popupSwiper.destroy(true, true);
//       popupSwiper = null;
//     }

//     popupSwiper = new Swiper(".stays-popup-gallery.swiper", {
//       slidesPerView: 7,
//       spaceBetween: convertRemToPx(0.25),
//       observer: true,
//       observeParents: true,
//     });
//   };

//   const btnPopups = document.querySelectorAll(".destination-stays_card");
//   const popup = document.querySelector(".stays-popup");
//   const popupOverlay = document.querySelector(".stays-popup-overlay");
//   const popupClose = document.querySelector(".stays-popup-close");
//   const popupTitle = document.querySelector(".stays-popup-title");
//   const popupDesc = document.querySelector(".stays-popup-desc");
//   const popUpWrapper = document.querySelector(
//     ".stays-popup-gallery .swiper-wrapper",
//   );
//   const popupThumnail = document.querySelector(".stays-popup-thumbnail");
//   const popupLink = document.querySelector(".stays-popup-link");

//   const renderDataPopup = (id) => {
//     const item = stays.find((item) => item.id.toString() === id.toString());
//     if (!item) return;

//     const gallery = item.gallery;

//     const galleryHTML = gallery
//       .map((img) => {
//         return `<div class="swiper-slide">
//           <img src="${img}" alt="" class="stays-popup-img" />
//         </div>`;
//       })
//       .join("");

//     popupThumnail.setAttribute("src", item.thumbnail);
//     popupLink.setAttribute("href", item.link);
//     popUpWrapper.innerHTML = galleryHTML;
//     popupTitle.textContent = item.name;
//     popupDesc.textContent = item.desc;
//     initPopupSwiper();
//   };

//   const closePopup = () => {
//     popup.classList.remove("active");
//     document.body.style.overflow = "";
//   };

//   const openPopup = () => {
//     popup.classList.add("active");
//     document.body.style.overflow = "hidden";
//   };

//   btnPopups.forEach((btn) => {
//     btn.addEventListener("click", () => {
//       const dataId = btn.getAttribute("data-id");
//       renderDataPopup(dataId);
//       openPopup();
//     });
//   });

//   popupOverlay.addEventListener("click", () => {
//     closePopup();
//   });

//   popupClose.addEventListener("click", () => {
//     closePopup();
//   });

//   popUpWrapper.addEventListener("click", (e) => {
//     const slide = e.target.closest(".swiper-slide");
//     if (!slide) return;

//     const img = slide.querySelector("img");
//     if (!img) return;

//     popupThumnail.src = img.src;
//   });
// }
