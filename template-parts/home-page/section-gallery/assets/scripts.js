let marqueeResizeTimer = null;
let lastWindowWidth = window.innerWidth;

function waitForMarqueeAssets(root) {
  const images = Array.from(root.querySelectorAll("img"));

  const imagePromises = images.map((img) => {
    if (img.complete) {
      return img.decode ? img.decode().catch(() => {}) : Promise.resolve();
    }

    return new Promise((resolve) => {
      img.addEventListener("load", resolve, { once: true });
      img.addEventListener("error", resolve, { once: true });
    });
  });

  const fontPromise = document.fonts?.ready || Promise.resolve();

  return Promise.all([fontPromise, ...imagePromises]);
}

async function initGsapMarquee() {
  if (!window.gsap) return;

  const marquees = document.querySelectorAll(".js-marquee");

  for (const marquee of marquees) {
    const track = marquee.querySelector(".marquee__track");
    if (!track) continue;

    await waitForMarqueeAssets(marquee);

    // Kill tween cũ nếu init lại
    if (track._marqueeTween) {
      track._marqueeTween.kill();
      track._marqueeTween = null;
    }

    gsap.set(track, { x: 0 });

    // Xóa clone cũ
    track.querySelectorAll("[data-clone='true']").forEach((el) => el.remove());

    const originalItems = Array.from(track.children);
    if (!originalItems.length) continue;

    // Luôn cần clone ít nhất 1 bộ để loop liền mạch
    originalItems.forEach((item) => {
      const clone = item.cloneNode(true);
      clone.dataset.clone = "true";
      clone.setAttribute("aria-hidden", "true");
      track.appendChild(clone);
    });

    const firstOriginal = originalItems[0];
    const firstClone = track.querySelector("[data-clone='true']");

    if (!firstOriginal || !firstClone) continue;

    // Đo đúng cả item khác size + gap
    const loopWidth =
      firstClone.getBoundingClientRect().left -
      firstOriginal.getBoundingClientRect().left;

    if (loopWidth <= 0) continue;

    // Nếu track vẫn chưa đủ dài thì clone thêm
    while (track.scrollWidth < marquee.offsetWidth + loopWidth * 2) {
      originalItems.forEach((item) => {
        const clone = item.cloneNode(true);
        clone.dataset.clone = "true";
        clone.setAttribute("aria-hidden", "true");
        track.appendChild(clone);
      });
    }

    const speed = Number(marquee.dataset.speed) || 60; // px/s
    const duration = loopWidth / speed;
    const direction = marquee.dataset.direction || "left";

    if (direction === "right") {
      gsap.set(track, { x: -loopWidth });

      track._marqueeTween = gsap.to(track, {
        x: 0,
        duration,
        ease: "none",
        repeat: -1,
      });
    } else {
      gsap.set(track, { x: 0 });

      track._marqueeTween = gsap.to(track, {
        x: -loopWidth,
        duration,
        ease: "none",
        repeat: -1,
      });
    }

    // Pause khi hover
    marquee.addEventListener("mouseenter", () => {
      track._marqueeTween?.pause();
    });

    marquee.addEventListener("mouseleave", () => {
      track._marqueeTween?.play();
    });
  }
}

window.addEventListener("load", initGsapMarquee);

// Fix iOS: chỉ init lại khi width đổi, không init khi address bar thay đổi height
