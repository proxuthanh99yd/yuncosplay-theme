export function outstandingFiguresScripts() {
  const section = document.querySelector(".outstanding__figures");
  if (!section || section.dataset.countInit === "true") return;
  section.dataset.countInit = "true";

  const valueElements = Array.from(
    section.querySelectorAll(".outstanding__figures__content__item__value")
  );
  if (valueElements.length === 0) return;

  const items = valueElements.map((el) => {
    const raw = (el.textContent || "").trim();
    const suffixMatch = raw.match(/[^0-9.,]+$/);
    const suffix = suffixMatch ? suffixMatch[0] : "";
    const numericPart = raw.replace(/[^0-9.,]/g, "");
    const hasDot = numericPart.includes(".");
    const hasComma = numericPart.includes(",");
    const digitsOnly = numericPart.replace(/[.,]/g, "");
    const target = parseInt(digitsOnly, 10) || 0;

    const formatNumber = (value) => {
      const str = Math.floor(value).toString();
      if (hasDot && !hasComma) {
        return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }
      if (hasComma && !hasDot) {
        return str.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }
      return str;
    };

    return { el, target, suffix, formatNumber };
  });

  const runAnimation = () => {
    items.forEach((item) => {
      if (item.el.dataset.countAnimated === "true") return;
      item.el.dataset.countAnimated = "true";
      animateCount(item);
    });
  };

  if (!("IntersectionObserver" in window)) {
    runAnimation();
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        runAnimation();
        observer.disconnect();
      });
    },
    { threshold: 0.4 }
  );

  observer.observe(section);
}

function animateCount(item) {
  const duration = 1500;
  const start = 0;
  const startTime = performance.now();
  const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

  const tick = (now) => {
    const progress = Math.min((now - startTime) / duration, 1);
    const value = start + (item.target - start) * easeOutCubic(progress);
    item.el.textContent = `${item.formatNumber(value)}${item.suffix}`;
    if (progress < 1) {
      requestAnimationFrame(tick);
    }
  };

  requestAnimationFrame(tick);
}
