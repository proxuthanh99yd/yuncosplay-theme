function animateCounter(counterEl) {
  if (!counterEl || counterEl.dataset.counterFinished === "true") return;

  const target = Number.parseInt(counterEl.dataset.counterTarget || "0", 10);
  const suffix = counterEl.dataset.counterSuffix || "";
  if (!Number.isFinite(target) || target < 0) return;

  const duration = Number.parseInt(counterEl.dataset.counterDuration || "1600", 10);
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const finish = () => {
    counterEl.textContent = `${target}${suffix}`;
    counterEl.dataset.counterFinished = "true";
  };
  const stableWidthCh = String(target).length + String(suffix).length;
  counterEl.style.minWidth = `${stableWidthCh}ch`;

  if (reducedMotion) {
    finish();
    return;
  }

  let startTime = null;

  const step = (timestamp) => {
    if (startTime === null) {
      startTime = timestamp;
    }

    const elapsed = timestamp - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const easedProgress = 1 - Math.pow(1 - progress, 3);
    const currentValue = Math.round(target * easedProgress);

    counterEl.textContent = `${currentValue}${suffix}`;

    if (progress < 1) {
      window.requestAnimationFrame(step);
      return;
    }

    finish();
  };

  window.requestAnimationFrame(step);
}

function sectionAboutScripts() {
  const counters = document.querySelectorAll(".section-about__stat-number");
  if (!counters.length) return;

  if (!("IntersectionObserver" in window)) {
    counters.forEach((counterEl) => animateCounter(counterEl));
    return;
  }

  const observer = new IntersectionObserver(
    (entries, currentObserver) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        animateCounter(entry.target);
        currentObserver.unobserve(entry.target);
      });
    },
    { threshold: 0.35 }
  );

  counters.forEach((counterEl) => observer.observe(counterEl));
}

document.addEventListener("DOMContentLoaded", () => {
  sectionAboutScripts();
});
