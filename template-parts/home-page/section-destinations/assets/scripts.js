export function sectionDestinationsScripts() {
  const destinationsSection = document.querySelector(".destinations");
  const svgDestinationEl = destinationsSection?.querySelector(".destinations-map-wrapper .destinations-map") || null;
  if (!svgDestinationEl) return;

  const pathDestinationEls = svgDestinationEl.querySelectorAll("path[data-country]");
  const countries = ["vietnam", "cambodia", "laos"];
  const activeFill = "#E3B92D";
  const inactiveFill = "white";
  const inactiveOpacity = "0.48";

  // Cache các paths theo country để tránh query lại nhiều lần
  const countryPathsCache = new Map();
  countries.forEach((country) => {
    countryPathsCache.set(country, svgDestinationEl.querySelectorAll(`path[data-country="${country}"]`));
  });

  // Lấy tất cả các destination items
  const destinationItems = destinationsSection.querySelectorAll(".destination-content__destinations-item[data-country]");

  // Lấy tất cả capital elements
  const capitalElements = destinationsSection.querySelectorAll(".destination-map__capital[data-country]");
  const capitalCache = new Map();
  capitalElements.forEach((capital) => {
    const country = capital.getAttribute("data-country");
    if (country) {
      capitalCache.set(country, capital);
    }
  });

  // Cache popups (nếu có) - để tương thích với code cũ
  const capitalPopupCache = new Map();
  capitalElements.forEach((capital) => {
    const country = capital.getAttribute("data-country");
    const popup = capital.querySelector(".destinations-popup");
    if (country && popup) {
      capitalPopupCache.set(country, { capital, popup });
    }
  });

  // Theo dõi country đang được active
  let activeCountry = null;

  // Theo dõi popup đang mở
  let openPopup = null;

  // Theo dõi chuột có đang ở trong popup không
  let isMouseInPopup = false;

  // Hàm đóng tất cả popups
  function closeAllPopups() {
    const newPopup = document.querySelector(".destinations-popup");
    if (newPopup) {
      newPopup.classList.remove("destinations-popup--active");
    }
    openPopup = null;
    isMouseInPopup = false;
  }

  // Hàm kích hoạt một country (highlight paths và capital)
  function activateCountry(country, showPopup = false) {
    activeCountry = country;

    // Đóng popup nếu hover sang country khác (trừ khi đang mở popup)
    if (!showPopup && openPopup && !isMouseInPopup) {
      const popupCapital = openPopup.closest(".destination-map__capital[data-country]");
      const popupCountry = popupCapital?.getAttribute("data-country");
      if (popupCountry && popupCountry !== country) {
        closeAllPopups();
      }
    }

    // Cập nhật styling cho các paths sử dụng cache
    countries.forEach((countryKey) => {
      const countryPaths = countryPathsCache.get(countryKey);
      if (!countryPaths) return;

      const isActive = countryKey === country;
      countryPaths.forEach((path) => {
        const isVietnamIsland = path.getAttribute("data-island") === "true" && countryKey === "vietnam";

        if (isActive) {
          // Country đang active: highlight
          if (isVietnamIsland) {
            // Styling đặc biệt cho các đảo của Vietnam khi active
            path.style.fill = "rgb(227, 185, 45)";
            path.style.fillOpacity = "1";
            path.style.stroke = "rgb(227, 185, 45)";
          } else {
            // Styling thông thường khi active
            path.style.fill = activeFill;
            path.style.fillOpacity = "1";
            path.style.stroke = "white";
          }
          path.setAttribute("data-active", "true");
        } else {
          // Các country không active: làm mờ
          if (isVietnamIsland) {
            // Styling đặc biệt cho các đảo của Vietnam khi không active
            path.style.fill = "rgba(255, 255, 255, 0.48)";
            path.style.fillOpacity = "1";
            path.style.stroke = "rgba(255, 255, 255, 0.48)";
          } else {
            // Styling thông thường khi không active
            path.style.fill = inactiveFill;
            path.style.fillOpacity = inactiveOpacity;
            path.style.stroke = "#E1BE47";
          }
          path.setAttribute("data-active", "false");
        }
      });
    });

    // Kích hoạt capital element tương ứng sử dụng cache
    capitalCache.forEach((capital, capitalCountry) => {
      if (capitalCountry === country) {
        capital.classList.add("destination-map__capital--active");
      } else {
        capital.classList.remove("destination-map__capital--active");
      }
    });
  }

  // Sử dụng event delegation cho các paths
  svgDestinationEl.addEventListener("click", (e) => {
    const path = e.target.closest("path[data-country]");
    if (!path) return;

    e.stopPropagation();
    const country = path.getAttribute("data-country");
    if (country) {
      activateCountry(country, true);

      // Đóng tất cả popups cũ trước
      closeAllPopups();

      // Mở popup với dữ liệu destination từ window.destinationData
      if (typeof window.openDestinationPopup === "function") {
        // Kiểm tra xem có data cho country này không
        const hasDestinationData = window.destinationData && window.destinationData[country];

        if (hasDestinationData) {
          // Mở popup với country name
          window.openDestinationPopup(country);
          openPopup = document.querySelector(".destinations-popup");
        } else {
          console.warn(`No destination data found for country: ${country}`);
        }
      }
    }
  });

  svgDestinationEl.addEventListener(
    "mouseenter",
    (e) => {
      const path = e.target.closest("path[data-country]");
      if (!path) return;

      // Nếu popup đang mở và chuột đang ở trong popup thì không trigger hover
      if (openPopup && isMouseInPopup) return;

      const country = path.getAttribute("data-country");
      if (country) {
        activateCountry(country, false);
      }
    },
    true
  );

  // Thêm cursor pointer cho tất cả paths
  pathDestinationEls.forEach((path) => {
    path.style.cursor = "pointer";
  });

  // Thêm event listeners cho destination items
  destinationItems.forEach((item) => {
    const country = item.getAttribute("data-country");
    if (!country) return;

    item.addEventListener("mouseenter", () => {
      activateCountry(country);
    });
  });

  // Thêm event listeners cho capital elements
  capitalCache.forEach((capital, country) => {
    capital.addEventListener("mouseenter", () => {
      activateCountry(country, false);
    });

    // Thêm click event để mở popup khi click vào capital
    capital.addEventListener("click", (e) => {
      e.stopPropagation();
      
      // Activate country
      activateCountry(country, true);

      // Đóng tất cả popups cũ trước
      closeAllPopups();

      // Mở popup với dữ liệu destination từ window.destinationData
      if (typeof window.openDestinationPopup === "function") {
        // Kiểm tra xem có data cho country này không
        const hasDestinationData = window.destinationData && window.destinationData[country];

        if (hasDestinationData) {
          // Mở popup với country name
          window.openDestinationPopup(country);
          openPopup = document.querySelector(".destinations-popup");
        } else {
          console.warn(`No destination data found for country: ${country}`);
        }
      }
    });
  });

  // Theo dõi vị trí chuột trong popup
  const newPopup = document.querySelector(".destinations-popup");
  if (newPopup) {
    newPopup.addEventListener("mouseenter", () => {
      isMouseInPopup = true;
    });

    newPopup.addEventListener("mouseleave", () => {
      isMouseInPopup = false;
    });
  }

  // Đóng popup khi click bên ngoài
  document.addEventListener("click", (e) => {
    if (!openPopup) return;

    const clickedPath = e.target.closest("path[data-country]");
    const clickedCapital = e.target.closest(".destination-map__capital[data-country]");
    const clickedPopup = e.target.closest(".destinations-popup");

    // Nếu click không phải vào path, capital hoặc popup thì đóng popup
    if (!openPopup.contains(e.target) && !clickedPath && !clickedCapital && !clickedPopup) {
      closeAllPopups();
    }
  });
}
