const createAutoHideOnScroll = ({
  selector,
  hiddenClassName,
  hideOffsetPx = 100,
  directionThresholdPx = 8,
}) => {
  const targetEl = document.querySelector(selector);
  if (!targetEl) return null;

  let lastScrollY = Math.max(window.scrollY || 0, 0);
  let rafScheduled = false;

  const handleScroll = () => {
    const currentScrollY = Math.max(window.scrollY || 0, 0);
    const delta = currentScrollY - lastScrollY;
    const isNearTop = currentScrollY <= hideOffsetPx;

    if (isNearTop) {
      targetEl.classList.remove(hiddenClassName);
      lastScrollY = currentScrollY;
      return;
    }

    if (Math.abs(delta) < directionThresholdPx) return;

    if (delta > 0) {
      targetEl.classList.add(hiddenClassName);
    } else {
      targetEl.classList.remove(hiddenClassName);
    }

    lastScrollY = currentScrollY;
  };

  const scheduleUpdate = () => {
    if (rafScheduled) return;
    rafScheduled = true;
    requestAnimationFrame(() => {
      handleScroll();
      rafScheduled = false;
    });
  };

  window.addEventListener("scroll", scheduleUpdate, { passive: true });
  window.addEventListener("resize", scheduleUpdate, { passive: true });

  const lenisInstance = window.app?.lenis || window.lenis;
  if (lenisInstance && typeof lenisInstance.on === "function") {
    lenisInstance.on("scroll", scheduleUpdate);
  }

  handleScroll();
  return { scheduleUpdate };
};
