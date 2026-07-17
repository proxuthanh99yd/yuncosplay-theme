// [selector, y] — di chuyển theo trục dọc
const VERTICAL_MOVE = [
  [".section-about__zoro", "11.875rem"],
  [".section-about__building", "11.25rem"],
  [".section-about__birds", "16.875rem"],
  [".section-about__tree", "12.5rem"],
  [".section-about__mountain-left", "3.7501rem"],
  [".section-about__mountain-right", "3.75rem"],
  [".section-about__mermaid", "14.375rem"],
  [".section-about__bg-bottom", "1.25rem"],
  [".section-about__sun", "11.875rem"],
];

// [selector, x, y] — di chuyển chéo
const MOVE_XY = [
  [".section-about__astronaut", "7.5rem", "6.25rem"],
  [".section-about__wave", "5.625rem", "5.625rem"],
  [".section-about__tower", "3.125rem", "8.75rem"],
  [".section-about__photo", "-5.625rem", "7.5rem"],
  [".section-about__nezuko", "7.5rem", "6.875rem"],
  [".section-about__cosplay", "-5.625rem", "5.625rem"],
];

// [selector, y] — fade in + di chuyển dọc
const FADE_VERTICAL = [
  [".section-about__title", "9.375rem"],
  [".section-about__description", "11.875rem"],
];

// [selector, x] — di chuyển ngang
const HORIZONTAL_MOVE = [
  [".section-about__spiderman", "8.125rem"],
  [".section-about__balloon", "-5rem"],
];

// Ảnh decor là ACF field không bắt buộc — bỏ qua phần tử không tồn tại
// thay vì huỷ toàn bộ animation.
function resolveTargets(container, entries) {
  const resolved = [];

  for (const [selector, ...values] of entries) {
    const el = container.querySelector(selector);
    if (el) resolved.push([el, ...values]);
  }

  return resolved;
}

function registerExplorersEase() {
  if (!window.CustomEase) return "power2.inOut";

  gsap.registerPlugin(CustomEase);

  if (typeof CustomEase.get !== "function" || !CustomEase.get("explorersEase")) {
    CustomEase.create("explorersEase", "0.41, 0.02, 0.1, 0.85");
  }

  return "explorersEase";
}

// Mặt trời quay vô hạn bằng CSS — chỉ cho chạy khi section trong viewport
// để không đốt CPU suốt vòng đời trang.
function initSunRotation(container) {
  if (!("IntersectionObserver" in window)) return null;

  container.classList.add("section-about--paused");

  const observer = new IntersectionObserver(
    ([entry]) => {
      container.classList.toggle("section-about--paused", !entry.isIntersecting);
    },
    // Bỏ pause sớm để layer kịp raster trước khi section lộ ra
    { rootMargin: "200px 0px" }
  );

  observer.observe(container);

  return () => observer.disconnect();
}

function initEntranceAnimation(container) {
  const vertical = resolveTargets(container, VERTICAL_MOVE);
  const moveXY = resolveTargets(container, MOVE_XY);
  const fade = resolveTargets(container, FADE_VERTICAL);
  const horizontal = resolveTargets(container, HORIZONTAL_MOVE);

  const allTargets = [...vertical, ...moveXY, ...fade, ...horizontal].map(([el]) => el);
  if (!allTargets.length) return null;

  const onLenisScroll = () => ScrollTrigger.update();
  if (typeof window.app?.lenis?.on === "function") {
    window.app.lenis.on("scroll", onLenisScroll);
  }

  // Promote layer từ lúc section chạm đáy viewport, animation chạy ở "top 50%"
  // → GPU có nửa màn hình scroll để raster, không giật frame đầu.
  const preload = ScrollTrigger.create({
    trigger: container,
    start: "top bottom",
    once: true,
    onEnter: () => gsap.set(allTargets, { willChange: "transform" }),
  });

  const tl = gsap.timeline({
    defaults: {
      duration: 2,
      ease: registerExplorersEase(),
      force3D: true,
    },
    scrollTrigger: {
      trigger: container,
      start: "top 50%",
      once: true,
      invalidateOnRefresh: true,
    },
    // Trả layer về cho browser sau khi animation chạy xong (chỉ chạy 1 lần)
    onComplete: () => gsap.set(allTargets, { clearProps: "transform,willChange,opacity" }),
  });

  // Tất cả tween cùng bắt đầu ở vị trí 0 — không dùng "<" vì nếu một nhóm
  // bị bỏ qua (thiếu ảnh decor) thì các nhóm sau sẽ lệch thời điểm.
  if (vertical.length) {
    tl.fromTo(
      vertical.map(([el]) => el),
      { y: (i) => vertical[i][1] },
      { y: "0rem" },
      0
    );
  }

  if (moveXY.length) {
    tl.fromTo(
      moveXY.map(([el]) => el),
      { x: (i) => moveXY[i][1], y: (i) => moveXY[i][2] },
      { x: "0rem", y: "0rem" },
      0
    );
  }

  if (fade.length) {
    tl.fromTo(
      fade.map(([el]) => el),
      { y: (i) => fade[i][1], opacity: 0 },
      { y: "0rem", opacity: 1 },
      0
    );
  }

  if (horizontal.length) {
    tl.fromTo(
      horizontal.map(([el]) => el),
      { x: (i) => horizontal[i][1] },
      { x: "0rem" },
      0
    );
  }

  return () => {
    if (typeof window.app?.lenis?.off === "function") {
      window.app.lenis.off("scroll", onLenisScroll);
    }
    preload.kill();
    tl.scrollTrigger?.kill();
    tl.kill();
    gsap.set(allTargets, { clearProps: "transform,willChange,opacity" });
  };
}

function sectionAboutScripts() {
  const container = document.querySelector(".section-about");
  if (!container) return;

  const stopSunRotation = initSunRotation(container);
  window.addEventListener("pagehide", () => stopSunRotation?.(), { once: true });

  if (!window.gsap || !window.ScrollTrigger) return;
  gsap.registerPlugin(ScrollTrigger);

  // Chỉ chạy từ 640px trở lên, và tôn trọng prefers-reduced-motion —
  // khi tắt, mọi phần tử giữ nguyên vị trí cuối trong CSS.
  gsap
    .matchMedia()
    .add("(min-width: 640px) and (prefers-reduced-motion: no-preference)", () =>
      initEntranceAnimation(container)
    );
}

document.addEventListener("DOMContentLoaded", () => {
  sectionAboutScripts();
});
