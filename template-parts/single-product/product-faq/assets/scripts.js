function faqScripts() {
  const container = document.querySelector(".product-faq");
  if (!container) return;

  const items = container.querySelectorAll(".product-faq__item");
  if (!items.length) return;

  function expandItem(item) {
    const answer = item.querySelector(".product-faq__item-answer");
    const trigger = item.querySelector(".product-faq__item-trigger");
    if (!answer) return;

    item.classList.add("is-active");
    if (trigger) trigger.setAttribute("aria-expanded", "true");

    answer.style.height = "auto";
    const targetHeight = answer.scrollHeight;
    answer.style.height = "0";

    // Force reflow để transition hoạt động
    answer.offsetHeight;

    answer.style.height = `${targetHeight}px`;
  }

  function collapseItem(item) {
    const answer = item.querySelector(".product-faq__item-answer");
    const trigger = item.querySelector(".product-faq__item-trigger");
    if (!answer) return;

    // Set chiều cao hiện tại trước khi animate về 0
    answer.style.height = `${answer.scrollHeight}px`;

    // Force reflow
    answer.offsetHeight;

    answer.style.height = "0";
    item.classList.remove("is-active");
    if (trigger) trigger.setAttribute("aria-expanded", "false");
  }

  function handleItemClick(item) {
    const isActive = item.classList.contains("is-active");

    // Nếu đang mở → đóng lại
    if (isActive) {
      collapseItem(item);
      return;
    }

    // Đóng tất cả item khác
    items.forEach((otherItem) => {
      if (otherItem !== item) {
        collapseItem(otherItem);
      }
    });

    // Mở item hiện tại
    expandItem(item);
  }

  // Khởi tạo: mở item đầu tiên, đóng các item còn lại
  items.forEach((item, index) => {
    const answer = item.querySelector(".product-faq__item-answer");
    const trigger = item.querySelector(".product-faq__item-trigger");

    if (!answer || !trigger) return;

    if (index === 0) {
      answer.style.height = `${answer.scrollHeight}px`;
      item.classList.add("is-active");
      trigger.setAttribute("aria-expanded", "true");
    } else {
      answer.style.height = "0";
      item.classList.remove("is-active");
      trigger.setAttribute("aria-expanded", "false");
    }

    trigger.addEventListener("click", () => handleItemClick(item));
  });

  const loadMoreBtn = container.querySelector(".product-faq__button-link");
  const initialLimit =
    parseInt(container.dataset.initialLimit, 10) || 5;
  const loadMoreStep = parseInt(container.dataset.loadMore, 10) || 5;
  let isListExpanded = false;

  function setLoadMoreButtonState(expanded) {
    if (!loadMoreBtn) return;

    const text = expanded ? "Rút gọn" : "Xem thêm";
    const iconSrc = expanded
      ? loadMoreBtn.dataset.iconMinus
      : loadMoreBtn.dataset.iconPlus;

    loadMoreBtn.setAttribute(
      "aria-label",
      expanded ? "Rút gọn danh sách câu hỏi" : "Xem thêm câu hỏi"
    );
    loadMoreBtn.setAttribute("aria-expanded", expanded ? "true" : "false");
    loadMoreBtn.classList.toggle("is-expanded", expanded);

    loadMoreBtn
      .querySelectorAll(
        ".animated-btn__content-hidden-text, .animated-btn__content-visible-text"
      )
      .forEach((el) => {
        el.textContent = text;
      });

    if (!iconSrc) return;

    loadMoreBtn.querySelectorAll(".animated-btn__icon").forEach((img) => {
      img.src = iconSrc;
      img.removeAttribute("srcset");
      img.removeAttribute("sizes");
    });
  }

  function getVisibleLimit() {
    if (!isListExpanded) return initialLimit;
    return Math.min(items.length, initialLimit + loadMoreStep);
  }

  function updateFaqListVisibility() {
    const visibleLimit = getVisibleLimit();

    items.forEach((item, index) => {
      const shouldHide = index >= visibleLimit;

      if (shouldHide) {
        if (item.classList.contains("is-active")) {
          collapseItem(item);
        }
        item.classList.add("is-hidden");
        return;
      }

      item.classList.remove("is-hidden");
    });
  }

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener("click", () => {
      isListExpanded = !isListExpanded;
      setLoadMoreButtonState(isListExpanded);
      updateFaqListVisibility();
    });
  }

  // Cập nhật height khi resize (nội dung có thể thay đổi chiều cao)
  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      items.forEach((item) => {
        if (!item.classList.contains("is-active")) return;
        const answer = item.querySelector(".product-faq__item-answer");
        if (!answer) return;
        // Tắt transition tạm thời khi recalculate
        answer.style.transition = "none";
        answer.style.height = "auto";
        const h = answer.scrollHeight;
        answer.style.height = `${h}px`;
        // Khôi phục transition
        answer.offsetHeight;
        answer.style.transition = "";
      });
    }, 150);
  });
}

faqScripts();
