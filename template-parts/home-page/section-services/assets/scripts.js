function sectionServicesScripts() {
  // Tìm section, không có thì bỏ
  const container = document.querySelector(".home-services");
  if (!container) return;

  // Tìm container chính để áp dụng animation
  const servicesContainer = document.querySelector(".home-services__container");
  if (!servicesContainer) return;

  // Thu thập tab (list trái) và panel (media phải)
  const tabs = Array.from(
    container.querySelectorAll(".home-services__list-item"),
  );
  const panels = Array.from(
    container.querySelectorAll(".home-services__media"),
  );
  const accordions = Array.from(
    container.querySelectorAll(".home-services__accordion"),
  );

  // Cache index on tab elements to avoid O(n) lookup
  tabs.forEach((tab, i) => (tab.dataset.index = i));

  const setTabAriaExpanded = (tab, expanded) => {
    if (!tab) return;
    tab.setAttribute("aria-expanded", expanded ? "true" : "false");
  };

  // Kiểm tra breakpoint mobile
  const mql = window.matchMedia("(max-width: 639px)");
  const isMobile = () => mql.matches;

  // Intersection Observer để trigger animation khi vào viewport (chỉ trên desktop)
  const observerOptions = {
    root: null,
    rootMargin: "0px",
    threshold: 0.1
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && !isMobile()) {
        entry.target.classList.add("is-in-viewport");
        // Ngừng observe sau khi animation đã trigger
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Chỉ observe trên desktop
  if (!isMobile()) {
    observer.observe(servicesContainer);
  }

  // Đồng bộ accordion: chỉ mở mục trùng index khi ở mobile
  const syncAccordion = (targetIndex) => {
    const mobile = isMobile();

    accordions.forEach((acc, i) => {
      acc.classList.toggle("active", mobile && i === targetIndex);
    });

    tabs.forEach((tab, i) => {
      setTabAriaExpanded(tab, mobile && i === targetIndex);
    });
  };

  // Khi đổi kích thước: tắt accordion trên desktop, giữ mục hiện tại trên mobile
  const handleResize = () => {
    if (!isMobile()) {
      accordions.forEach((acc) => acc.classList.remove("active"));
      tabs.forEach((tab) => setTabAriaExpanded(tab, false));
    } else {
      syncAccordion(current);
    }
  };

  // Xác định tab/panel active ban đầu
  let current = Math.max(
    0,
    tabs.findIndex((t) => t.classList.contains("active")),
    panels.findIndex((p) => p.classList.contains("active")),
  );

  syncAccordion(current);

  // Track animation hiện tại
  let currentAnimation = null;

  const cancelAnimation = () => {
    if (!currentAnimation) return;

    const { leavingPanel, enteringPanel, onDone } = currentAnimation;

    leavingPanel.classList.remove("is-leaving", "is-up", "is-down");
    enteringPanel.classList.remove("is-up", "is-down");

    // Remove event listeners to prevent leaks
    if (onDone) {
      leavingPanel.removeEventListener("transitionend", onDone);
      leavingPanel.removeEventListener("animationend", onDone);
    }

    currentAnimation = null;
  };

  // Dọn class trạng thái sau khi animate xong
  const cleanup = (leavingPanel, enteringPanel) => {
    leavingPanel.classList.remove("is-leaving", "is-up", "is-down");
    enteringPanel.classList.remove("is-up", "is-down");

    currentAnimation = null;
  };

  // Lắng nghe click trên danh sách dịch vụ
  container.addEventListener("click", (e) => {
    const tab = e.target.closest(".home-services__list-item");
    if (!tab) return;

    cancelAnimation();

    const index = Number(tab.dataset.index);
    if (!Number.isInteger(index)) return;

    // Mobile: cho phép click lại để đóng accordion (đóng tất cả)
    if (index === current) {
      if (isMobile()) {
        const acc = accordions[index];
        const willOpen = acc && !acc.classList.contains("active");
        tabs[current]?.classList.toggle("active", !!willOpen);
        syncAccordion(willOpen ? index : -1);
        if (!willOpen) current = index;
      }
      return;
    }

    // Xác định hướng di chuyển (xuống / lên)
    const direction = index > current ? "is-down" : "is-up";

    const currentPanel = panels[current];
    const nextPanel = panels[index];
    const nextDecor = nextPanel?.querySelector(".home-services__decor-text");
    const nextContent = nextPanel?.querySelector(".home-services__content");

    if (!currentPanel || !nextPanel) return;

    currentAnimation = {
      leavingPanel: currentPanel,
      enteringPanel: nextPanel,
      onDone: null,
    };

    // Bỏ class hướng cũ trước khi set hướng mới
    currentPanel.classList.remove("is-up", "is-down");
    nextPanel.classList.remove("is-up", "is-down", "is-leaving");

    // set direction
    currentPanel.classList.add("is-leaving", direction);
    nextPanel.classList.remove("active");
    nextPanel.classList.add(direction);

    // Force browser to apply directional start state before activating new panel
    if (nextDecor || nextContent) {
      const prevDecorTransition = nextDecor?.style.transition;
      const prevContentTransition = nextContent?.style.transition;

      // Đặt trạng thái xuất phát cho decor theo hướng
      if (nextDecor) {
        const startTransform =
          direction === "is-down"
            ? "rotate(-180deg) translateY(-100%)"
            : "rotate(-180deg) translateY(100%)";

        nextDecor.style.transition = "none";
        nextDecor.style.transform = startTransform;
        nextDecor.style.opacity = "0";
      }

      // Đặt trạng thái xuất phát cho content: dưới + mờ
      if (nextContent) {
        nextContent.style.transition = "none";
        nextContent.style.transform = "translateY(100%)";
        nextContent.style.opacity = "0";
      }

      nextPanel.offsetWidth;

      // Khôi phục transition rồi kích hoạt panel mới
      requestAnimationFrame(() => {
        if (nextDecor) {
          nextDecor.style.transition = prevDecorTransition;
          nextDecor.style.transform = "";
          nextDecor.style.opacity = "";
        }

        if (nextContent) {
          nextContent.style.transition = prevContentTransition;
          nextContent.style.transform = "";
          nextContent.style.opacity = "";
        }

        requestAnimationFrame(() => {
          nextPanel.classList.add("active");
        });
      });
    } else {
      nextPanel.offsetWidth;
      requestAnimationFrame(() => {
        nextPanel.classList.add("active");
      });
    }

    // Cập nhật class active cho tab/panel
    currentPanel.classList.remove("active");
    tab.classList.add("active");
    tabs[current].classList.remove("active");

    syncAccordion(index);

    const onDone = (ev) => {
      if (!currentAnimation) return;
      if (ev.target !== currentPanel) return;

      cleanup(currentPanel, nextPanel);
    };

    currentAnimation.onDone = onDone;

    // Kết thúc animation theo event
    currentPanel.addEventListener("transitionend", onDone, { once: true });
    currentPanel.addEventListener("animationend", onDone, { once: true });

    current = index;
  });

  // Đồng bộ khi đổi kích thước (ẩn accordion ở desktop, mở đúng mục ở mobile)
  let resizeRAF = null;
  window.addEventListener("resize", () => {
    if (resizeRAF) return;
    resizeRAF = requestAnimationFrame(() => {
      handleResize();
      resizeRAF = null;
    });
  });
  handleResize();
}

document.addEventListener("DOMContentLoaded", () => {
  sectionServicesScripts();
});
