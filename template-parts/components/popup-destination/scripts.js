export function popupDestinationScripts() {
  const popup = document.querySelector(".destinations-popup");
  if (!popup) return;

  // Ensure popup is a direct child of body to avoid stacking-context issues
  // (e.g. parents with transform) that can cause it to render under the header/mega-menu.
  if (popup.parentElement !== document.body) {
    document.body.appendChild(popup);
  }

  const popupOverlay = popup.querySelector(".destination-popup-overlay");
  const popupCloseBtn = popup.querySelector(".destinations-popup-close");
  const popupTitle = popup.querySelector(".destination-capital__popup__title");
  const popupThumbnail = popup.querySelector(".destination-popup-thumbnail-img");
  const popupTourQuantity = popup.querySelector(".destination-capital__popup__tour-quantity__value");
  const popupDescription = popup.querySelector(".destination-capital__popup-content__description");
  const popupLink = popup.querySelector(".destinations-popup__link");
  const popupContent = popup.querySelector(".destinations-popup-content");
  const collapseBtn = popup.querySelector(".destination-capital__popup__collapse-btn");
  const zoomInIcon = popup.querySelector(".destination-capital__popup__collapse-btn__icon--zoom-in");
  const zoomOutIcon = popup.querySelector(".destination-capital__popup__collapse-btn__icon--zoom-out");

  /**
   * Tính toán vị trí popup để đặt ở góc trái trên cùng (top: 0, left: 0) của map-wrapper
   *
   * @param {HTMLElement} mapWrapper - Element .destinations-map-wrapper
   * @param {HTMLElement} popupContent - Element .destinations-popup-content
   * @param {Object} options - Tùy chọn: { offsetX: number, offsetY: number }
   * @returns {Object} - { top: number, left: number }
   *
   * Giải thích từng bước:
   * 1. Lấy getBoundingClientRect() của map-wrapper để có vị trí viewport
   * 2. Lấy scroll position (scrollX, scrollY) để tính vị trí absolute
   * 3. Tính toán vị trí góc trái trên: mapLeft + scrollX + offsetX, mapTop + scrollY + offsetY
   * 4. Kiểm tra viewport để đảm bảo không bị tràn
   * 5. Nếu tràn sang phải: đặt ở mép phải viewport - offset
   * 6. Nếu tràn xuống dưới: đặt ở mép dưới viewport - offset
   */
  function calculatePopupPosition(mapWrapper, popupContent, options = {}) {
    const {
      offsetX = 0, // Offset mặc định 0px từ mép trái map (top: 0, left: 0)
      offsetY = 0, // Offset mặc định 0px từ mép trên map
    } = options;

    // Bước 1: Lấy vị trí và kích thước của map-wrapper trong viewport
    const mapRect = mapWrapper.getBoundingClientRect();

    // Bước 2: Lấy scroll position để tính toán vị trí absolute
    const scrollX = window.scrollX || window.pageXOffset || 0;
    const scrollY = window.scrollY || window.pageYOffset || 0;

    // Bước 3: Lấy kích thước popup (bao gồm cả khi chưa render)
    const popupRect = popupContent.getBoundingClientRect();
    const popupWidth = popupRect.width || popupContent.offsetWidth || 356; // 22.25rem = 356px
    const popupHeight = popupRect.height || popupContent.offsetHeight || 233; // 14.5625rem = 233px

    // Bước 4: Tính toán vị trí góc trái trên cùng của map-wrapper (absolute position)
    // mapRect.top và mapRect.left là vị trí trong viewport, cộng với scroll để có absolute position
    const mapTopAbsolute = mapRect.top + scrollY;
    const mapLeftAbsolute = mapRect.left + scrollX;

    // Vị trí mong muốn: góc trái trên cùng của map-wrapper (top: 0, left: 0 của map)
    let topPosition = mapTopAbsolute + offsetY;
    let leftPosition = mapLeftAbsolute + offsetX;

    // Bước 5: Kiểm tra và xử lý tràn khỏi viewport
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    const viewportRight = scrollX + viewportWidth;
    const viewportBottom = scrollY + viewportHeight;

    // Kiểm tra tràn sang phải
    if (leftPosition + popupWidth > viewportRight) {
      // Đặt popup ở mép phải viewport với offset
      leftPosition = viewportRight - popupWidth - offsetX;

      // Nếu vẫn tràn, đặt ở mép trái viewport
      if (leftPosition < scrollX) {
        leftPosition = scrollX + offsetX;
      }
    }

    // Kiểm tra tràn xuống dưới
    if (topPosition + popupHeight > viewportBottom) {
      // Đặt popup ở mép dưới viewport với offset
      topPosition = viewportBottom - popupHeight - offsetX;

      // Nếu vẫn tràn, đặt ở mép trên viewport
      if (topPosition < scrollY) {
        topPosition = scrollY + offsetX;
      }
    }

    // Kiểm tra tràn sang trái (ít xảy ra nhưng cần xử lý)
    if (leftPosition < scrollX) {
      leftPosition = scrollX + offsetX;
    }

    // Kiểm tra tràn lên trên (ít xảy ra nhưng cần xử lý)
    if (topPosition < scrollY) {
      topPosition = scrollY + offsetX;
    }

    return {
      top: topPosition,
      left: leftPosition,
    };
  }

  // Hàm mở popup và điền dữ liệu từ window.destinationData
  function openPopupWithData(country, source = "destinations") {
    if (!country) return;

    // Lấy data từ window.destinationData
    const destinationData = window.destinationData?.[country];
    if (!destinationData) {
      console.warn(`No destination data found for country: ${country}`);
      return;
    }

    const name = destinationData.name || "";
    const thumbnail = destinationData.thumbnail || "";
    const description = destinationData.description || "";
    const link = destinationData.link || "#";
    const tourCount = destinationData.tour_count || 0;

    // Điền dữ liệu vào popup
    if (popupTitle) {
      popupTitle.textContent = name;
    }

    if (popupThumbnail) {
      if (thumbnail) {
        popupThumbnail.src = thumbnail;
        popupThumbnail.alt = name;
        popupThumbnail.style.display = "block";
        popupThumbnail.onerror = () => {
          popupThumbnail.style.display = "none";
        };
      } else {
        popupThumbnail.src = "";
        popupThumbnail.style.display = "none";
      }
    }

    if (popupTourQuantity) {
      const tourText = tourCount && tourCount !== 0 ? `${tourCount} tours` : "0 tours";
      popupTourQuantity.textContent = tourText;
    }

    if (popupDescription) {
      popupDescription.textContent = description;
    }

    if (popupLink) {
      popupLink.href = link;
    }

    // Stop Lenis smooth scroll khi mở popup
    const lenisInstance = window.app?.lenis;
    if (lenisInstance && typeof lenisInstance.stop === "function") {
      lenisInstance.stop();
    }

    // Chặn scroll body
    document.body.style.overflow = "hidden";
    document.documentElement.style.overflow = "hidden";

    // Reset collapse state về mặc định trước khi hiển thị
    resetCollapseState();

    // Hiển thị popup trước để có kích thước chính xác
    popup.classList.add("destinations-popup--active");

    // Tính toán và set vị trí popup
    // Chỉ thực hiện trên PC (width >= 640px)
    const popupContent = popup.querySelector(".destinations-popup-content");
    const isDesktop = window.innerWidth >= 640;

    if (popupContent && isDesktop) {
      // Sử dụng double requestAnimationFrame để đảm bảo DOM đã render xong và có kích thước chính xác
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          let mapElement = null;
          
          // Xác định map element dựa trên source
          if (source === "header") {
            // Nếu mở từ header megamenu, sử dụng map trong header
            mapElement = document.querySelector(".header-mega-menu__map");
          } else {
            // Mặc định sử dụng map trong destinations section
            mapElement = document.querySelector(".destinations-map-wrapper");
          }

          if (mapElement) {
            const mapRect = mapElement.getBoundingClientRect();
            const distanceFromTop = mapRect.top;
            const distanceFromLeft = mapRect.left;

            // Set vị trí cho popup content
            popupContent.style.top = `${distanceFromTop}px`;
            popupContent.style.left = `${distanceFromLeft}px`;
          } else {
            // Fallback: reset về giá trị mặc định nếu không tìm thấy map
            popupContent.style.top = "";
            popupContent.style.left = "";
          }
        });
      });
    } else if (popupContent && !isDesktop) {
      // Trên mobile, reset về giá trị mặc định hoặc không set position
      popupContent.style.top = "";
      popupContent.style.left = "";
    }
  }

  // Export hàm để có thể dùng từ script khác
  window.openDestinationPopup = function (country, source = "destinations") {
    if (typeof country === "string") {
      openPopupWithData(country, source);
    } else if (country && country.nodeType === Node.ELEMENT_NODE) {
      // Fallback: nếu truyền element, lấy country từ data-country attribute
      const countryFromElement = country.getAttribute("data-country");
      if (countryFromElement) {
        openPopupWithData(countryFromElement, source);
      }
    }
  };

  /**
   * Đo chiều cao thực tế của popup content
   *
   * Logic:
   * 1. Tạm thời set max-height = none để element có thể expand tự nhiên
   * 2. Đo scrollHeight (chiều cao thực tế bao gồm overflow)
   * 3. Set lại max-height về giá trị cũ để không ảnh hưởng UI
   * 4. Trả về chiều cao thực tế
   *
   * @param {HTMLElement} element - Element cần đo chiều cao
   * @returns {number} - Chiều cao thực tế tính bằng px
   */
  function measureRealHeight(element) {
    if (!element) return 0;

    // Lưu giá trị max-height hiện tại
    const currentMaxHeight = element.style.maxHeight || getComputedStyle(element).maxHeight;

    // Tạm thời set max-height = none để đo chiều cao thực tế
    element.style.maxHeight = "none";

    // Đo chiều cao thực tế (scrollHeight bao gồm cả phần overflow)
    const realHeight = element.scrollHeight;

    // Khôi phục max-height về giá trị cũ
    element.style.maxHeight = currentMaxHeight;

    return realHeight;
  }

  /**
   * Toggle collapse/expand cho popup content
   *
   * Logic:
   * 1. Kiểm tra trạng thái hiện tại (collapsed hay expanded)
   * 2. Nếu collapsed: đo chiều cao thực tế và expand
   * 3. Nếu expanded: collapse về 14.5625rem
   * 4. Toggle class và icon tương ứng
   */
  /**
   * Convert rem sang px dựa trên root font size
   * @param {string} remValue - Giá trị rem (ví dụ: "3.0625rem")
   * @returns {number} - Giá trị px
   */
  function remToPx(remValue) {
    const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize) || 16;
    const rem = parseFloat(remValue);
    return rem * rootFontSize;
  }

  function toggleCollapse() {
    if (!popupContent) return;

    // Chỉ thực hiện collapse logic trên PC (width >= 640px)
    const isDesktop = window.innerWidth >= 640;
    if (!isDesktop) return;

    const isCollapsed = popup.classList.contains("destinations-popup--collapsed");
    const COLLAPSED_HEIGHT = "14.5625rem"; // Chiều cao khi collapsed
    const EXPANDED_PADDING_BOTTOM = "3.0625rem"; // Padding-bottom khi expanded
    const EXPANDED_HEIGHT_OFFSET_REM = "3.0625rem"; // Offset thêm vào max-height khi expanded

    if (isCollapsed) {
      // Mở rộng (expand): đo chiều cao thực tế và set max-height
      // Đảm bảo element đã có nội dung và render xong
      requestAnimationFrame(() => {
        // Đo chiều cao thực tế
        const realHeight = measureRealHeight(popupContent);

        // Set về collapsed trước để có điểm bắt đầu cho transition
        popupContent.style.maxHeight = COLLAPSED_HEIGHT;
        popupContent.style.paddingBottom = "1rem"; // Reset padding-bottom về mặc định

        // Force reflow để browser nhận biết thay đổi
        void popupContent.offsetHeight;

        // Tính toán offset trong px
        const heightOffsetPx = remToPx(EXPANDED_HEIGHT_OFFSET_REM);

        // Expand đến chiều cao thực tế + 3.0625rem
        const expandedHeight = realHeight + heightOffsetPx;
        popupContent.style.maxHeight = `${expandedHeight}px`;
        popupContent.style.paddingBottom = EXPANDED_PADDING_BOTTOM;

        // Remove collapsed class và toggle icon
        popup.classList.remove("destinations-popup--collapsed");

        if (zoomInIcon) zoomInIcon.style.display = "none";
        if (zoomOutIcon) zoomOutIcon.style.display = "block";
      });
    } else {
      // Thu gọn (collapse): set max-height về 14.5625rem và reset padding-bottom
      requestAnimationFrame(() => {
        popupContent.style.maxHeight = COLLAPSED_HEIGHT;
        popupContent.style.paddingBottom = "1rem"; // Reset về padding mặc định

        // Add collapsed class và toggle icon
        popup.classList.add("destinations-popup--collapsed");

        if (zoomInIcon) zoomInIcon.style.display = "block";
        if (zoomOutIcon) zoomOutIcon.style.display = "none";
      });
    }
  }

  /**
   * Reset collapse state về mặc định (collapsed) khi mở popup mới
   * Chỉ thực hiện trên PC (width >= 640px)
   */
  function resetCollapseState() {
    if (!popupContent) return;

    // Chỉ thực hiện collapse logic trên PC (width >= 640px)
    const isDesktop = window.innerWidth >= 640;
    if (!isDesktop) return;

    const COLLAPSED_HEIGHT = "14.5625rem";

    // Set về collapsed state
    popupContent.style.maxHeight = COLLAPSED_HEIGHT;
    popupContent.style.paddingBottom = "1rem"; // Reset padding-bottom về mặc định
    popup.classList.add("destinations-popup--collapsed");

    // Reset icon
    if (zoomInIcon) zoomInIcon.style.display = "block";
    if (zoomOutIcon) zoomOutIcon.style.display = "none";
  }

  // Hàm đóng popup
  function closePopup() {
    popup.classList.remove("destinations-popup--active");

    // Reset collapse state khi đóng popup
    resetCollapseState();

    // Kiểm tra xem mega menu có đang mở không
    const megaMenu = document.querySelector(".header-mega-menu");
    const isMegaMenuOpen = megaMenu && !megaMenu.classList.contains("header-mega-menu--hidden");

    // Chỉ khôi phục scroll nếu mega menu không đang mở
    // Nếu mega menu đang mở, giữ nguyên scroll bị chặn
    if (!isMegaMenuOpen) {
      // Start lại Lenis smooth scroll khi đóng popup
      const lenisInstance = window.app?.lenis;
      if (lenisInstance && typeof lenisInstance.start === "function") {
        lenisInstance.start();
      }

      // Khôi phục scroll body
      document.body.style.overflow = "";
      document.documentElement.style.overflow = "";
    }
  }

  // Đóng popup khi click vào overlay
  if (popupOverlay) {
    popupOverlay.addEventListener("click", closePopup);
  }

  // Đóng popup khi click vào nút close
  if (popupCloseBtn) {
    popupCloseBtn.addEventListener("click", closePopup);
  }

  // Đóng popup khi click bên ngoài popup content
  popup.addEventListener("click", (e) => {
    if (e.target === popup || e.target === popupOverlay) {
      closePopup();
    }
  });

  // Ngăn đóng popup khi click vào popup content
  if (popupContent) {
    popupContent.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  // Thêm event listener cho collapse button
  if (collapseBtn) {
    collapseBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleCollapse();
    });
  }
}
