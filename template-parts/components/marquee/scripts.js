let marqueeImagesResizeTimer = null;
let marqueeImagesObserver = null;

function setupMarqueeObserver() {
  if (marqueeImagesObserver || !("IntersectionObserver" in window)) return;

  marqueeImagesObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const marquee = entry.target;
        const track = marquee.querySelector(".marquee-images__track");

        if (!track || !track._marqueeTween) return;

        if (entry.isIntersecting) {
          track._marqueeTween.play();
        } else {
          track._marqueeTween.pause();
        }
      });
    },
    {
      threshold: 0,
      rootMargin: "100px 0px",
    }
  );
}

async function initOneMarqueeImage(marquee) {
  if (!marquee || !window.gsap) return;

  const track = marquee.querySelector(".marquee-images__track");
  if (!track) return;

  if (track._marqueeTween) {
    track._marqueeTween.kill();
    track._marqueeTween = null;
  }

  track.querySelectorAll("[data-clone='true']").forEach((el) => el.remove());

  gsap.set(track, {
    x: 0,
    force3D: true,
  });

  const speed = Number(marquee.dataset.speed) || 60;

  const originalItems = Array.from(track.children).filter(
    (item) => item.dataset.clone !== "true"
  );

  const originalWidth = track.scrollWidth;

  if (!originalWidth) return;

  while (track.scrollWidth < marquee.offsetWidth + originalWidth * 2) {
    originalItems.forEach((item) => {
      const clone = item.cloneNode(true);
      clone.dataset.clone = "true";
      clone.setAttribute("aria-hidden", "true");
      track.appendChild(clone);
    });
  }

  const distance = originalWidth;
  const duration = distance / speed;

  track._marqueeTween = gsap.to(track, {
    x: -distance,
    duration,
    ease: "none",
    repeat: -1,
    force3D: true,
  });

  setupMarqueeObserver();

  if (marqueeImagesObserver) {
    marqueeImagesObserver.observe(marquee);
  }
}

function initMarqueeImages() {
  const marquees = document.querySelectorAll(
    ".marquee-component.marquee-images"
  );

  marquees.forEach((marquee) => {
    initOneMarqueeImage(marquee);
  });
}

function handleMarqueeImagesResize() {
  clearTimeout(marqueeImagesResizeTimer);

  marqueeImagesResizeTimer = setTimeout(() => {
    initMarqueeImages();
  }, 300);
}

// window.addEventListener("resize", handleMarqueeImagesResize);

document.addEventListener("DOMContentLoaded", () => {
  initMarqueeImages();
});
