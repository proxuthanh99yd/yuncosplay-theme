const productFeedback = () => {
  const isMobile = window.innerWidth < 640;
  const TIKTOK_PLAYER_V1 = (videoId, autoplay = 1, muted = 0) =>
    `https://www.tiktok.com/player/v1/${videoId}?music_info=0&description=0&controls=1&progress_bar=1&play_button=1&volume_control=1&fullscreen_button=0&timestamp=1&loop=1&autoplay=${autoplay}&muted=${muted}`;

  const ACTIVE_W = isMobile ? 21.9375 : 22.5;
  const INACTIVE_W = isMobile ? 15.625 : 12.5;
  const ACTIVE_H = isMobile ? 37.78125 : 38.75;
  const INACTIVE_H = isMobile ? 26.90938 : 21.5275;
  const GAP = isMobile ? 0.75 : 1.25;
  const SPEED = 500;
  const INIT_REAL = 0;
  const SX = INACTIVE_W / ACTIVE_W;
  const SY = INACTIVE_H / ACTIVE_H;

  const contentSwiper = new Swiper(".product-fb__content-swiper", {
    slidesPerView: 1,
    effect: "fade",
    fadeEffect: { crossFade: true },
    speed: SPEED,
    loop: true,
    allowTouchMove: false,
    initialSlide: INIT_REAL,
  });

  const carousel = document.querySelector(".product-fb__media-swiper");
  if (!carousel) return;
  const track = carousel.querySelector(".swiper-wrapper");
  if (!track) return;

  const originals = Array.from(
    track.querySelectorAll(".product-fb__media-slide"),
  );
  const N = originals.length;
  if (N === 0) return;

  originals.forEach((s, i) => s.setAttribute("data-real-index", String(i)));

  const beforeFrag = document.createDocumentFragment();
  originals.forEach((s) => beforeFrag.appendChild(s.cloneNode(true)));
  track.insertBefore(beforeFrag, track.firstChild);
  originals.forEach((s) => track.appendChild(s.cloneNode(true)));

  const slides = Array.from(track.querySelectorAll(".product-fb__media-slide"));
  let activeIdx = N + INIT_REAL;
  let isAnimating = false;
  let playGen = 0;
  let isInView = false;

  const rem = () =>
    parseFloat(getComputedStyle(document.documentElement).fontSize);

  function render(animate) {
    const r = rem();
    const activeW = ACTIVE_W * r;
    const inactiveW = INACTIVE_W * r;
    const activeH = ACTIVE_H * r;
    const inactiveH = INACTIVE_H * r;
    const gapPx = GAP * r;

    const xPos = [];
    let x = 0;
    for (let i = 0; i < slides.length; i++) {
      xPos[i] = x;
      x += (i === activeIdx ? activeW : inactiveW) + gapPx;
    }

    // const anchor = isMobile ? (carousel.offsetWidth - activeW) / 2 : 13.75 * r;
    // const shift = anchor - xPos[activeIdx];
    const shift = -xPos[activeIdx];

    if (!animate) {
      slides.forEach((s) => s.classList.add("no-transition"));
    }

    slides.forEach((s, i) => {
      const isActive = i === activeIdx;
      const sx = isActive ? 1 : SX;
      const sy = isActive ? 1 : SY;
      const tx = xPos[i] + shift;
      const ty = isMobile && !isActive ? (activeH - inactiveH) / 2 : 0;

      s.style.transform = `translate(${tx}px,${ty}px) scale(${sx},${sy})`;
      s.classList.toggle("swiper-slide-active", isActive);

      const btn = s.querySelector(".product-fb__media-play-button");
      if (btn) {
        const invX = isActive ? 1 : 1 / sx;
        const invY = isActive ? 1 : 1 / sy;
        const REDUCE = 0.85;
        btn.style.setProperty("--btn-scale-x", String(invX * REDUCE));
        btn.style.setProperty("--btn-scale-y", String(invY * REDUCE));
      }
    });

    if (!animate) {
      void carousel.offsetHeight;
      slides.forEach((s) => s.classList.remove("no-transition"));
    }
  }

  function navigateTo(idx) {
    if (isAnimating) return;
    isAnimating = true;
    activeIdx = idx;
    stopAllVideos();

    contentSwiper.slideToLoop(
      parseInt(slides[activeIdx].getAttribute("data-real-index")),
      SPEED,
    );
    render(true);

    setTimeout(() => {
      if (activeIdx < N) activeIdx += N;
      else if (activeIdx >= 2 * N) activeIdx -= N;
      render(false);
      if (isInView && !isMobile) playActiveVideo();
      isAnimating = false;
    }, SPEED + 30);
  }

  render(false);

  const section = carousel.closest(".product-fb");
  const observer = new IntersectionObserver(
    ([entry]) => {
      isInView = entry.isIntersecting;
      if (isInView && !isMobile) playActiveVideo();
      else stopAllVideos();
    },
    { threshold: 0.3 },
  );
  if (section) observer.observe(section);

  const navWrap = carousel.closest(".product-fb__container");
  navWrap
    ?.querySelector(".product-fb__pagination-button--prev")
    ?.addEventListener("click", () => navigateTo(activeIdx - 1));
  navWrap
    ?.querySelector(".product-fb__pagination-button--next")
    ?.addEventListener("click", () => navigateTo(activeIdx + 1));

  slides.forEach((s, i) => {
    s.addEventListener("click", (e) => {
      if (e.target.closest(".product-fb__media-play-button")) return;
      if (i !== activeIdx && !isAnimating) navigateTo(i);
    });
  });

  const SWIPE_THRESHOLD = 50;
  const AXIS_LOCK_THRESHOLD = 10;

  let dragStartX = 0;
  let dragStartY = 0;
  let isDragging = false;
  let dragAxis = null;

  function onDragStart(clientX, clientY) {
    dragStartX = clientX;
    dragStartY = clientY;
    isDragging = true;
    dragAxis = null;
  }

  function resetDrag() {
    isDragging = false;
    dragAxis = null;
  }

  function onDragEnd(clientX, clientY) {
    if (!isDragging) return;
    const axis = dragAxis;
    const dx = dragStartX - clientX;
    const dy = dragStartY - clientY;
    resetDrag();

    if (axis === "y") return;
    if (Math.abs(dx) > SWIPE_THRESHOLD && Math.abs(dx) > Math.abs(dy)) {
      navigateTo(activeIdx + (dx > 0 ? 1 : -1));
    }
  }

  function onTouchMove(e) {
    if (!isDragging || dragAxis) return;
    const touch = e.touches[0];
    const dx = Math.abs(touch.clientX - dragStartX);
    const dy = Math.abs(touch.clientY - dragStartY);
    if (dx <= AXIS_LOCK_THRESHOLD && dy <= AXIS_LOCK_THRESHOLD) return;
    dragAxis = dx > dy ? "x" : "y";
  }

  carousel.addEventListener(
    "touchstart",
    (e) => {
      onDragStart(e.touches[0].clientX, e.touches[0].clientY);
    },
    { passive: true },
  );
  if (isMobile) {
    carousel.addEventListener("touchmove", onTouchMove, { passive: true });
  }
  carousel.addEventListener(
    "touchend",
    (e) => {
      onDragEnd(e.changedTouches[0].clientX, e.changedTouches[0].clientY);
    },
    { passive: true },
  );
  carousel.addEventListener("touchcancel", resetDrag, { passive: true });

  carousel.addEventListener("mousedown", (e) => {
    if (e.button !== 0) return;
    onDragStart(e.clientX, e.clientY);
  });
  window.addEventListener("mouseup", (e) => {
    if (!isDragging) return;
    onDragEnd(e.clientX, e.clientY);
  });

  function stopAllVideos() {
    playGen++;
    slides.forEach((s) => {
      const article = s.querySelector(".product-fb__media-video");
      const iframe = s.querySelector(".product-fb__media-embed iframe");
      if (iframe) iframe.src = "";
      if (article) {
        article.classList.remove("product-fb__media-video--playing");
        article.classList.remove("product-fb__media-video--loading");
      }
    });
  }

  function playActiveVideo(autoplay = 1, muted = 0) {
    const gen = ++playGen;
    const slide = slides[activeIdx];
    if (!slide) return;
    const article = slide.querySelector(".product-fb__media-video");
    const embed = slide.querySelector(".product-fb__media-embed");
    const iframe = embed?.querySelector("iframe");
    if (!article || !embed || !iframe) return;
    const videoId = embed.getAttribute("data-video-id");
    if (!videoId) return;

    function revealPlayer() {
      if (gen !== playGen) return;
      article.classList.remove("product-fb__media-video--loading");
      article.classList.add("product-fb__media-video--playing");
    }

    article.classList.add("product-fb__media-video--loading");
    iframe.addEventListener("load", revealPlayer, { once: true });
    iframe.src = TIKTOK_PLAYER_V1(videoId, autoplay, muted);

    window.setTimeout(revealPlayer, isMobile ? 1200 : 3500);
  }

  slides.forEach((s, i) => {
    const btn = s.querySelector(".product-fb__media-play-button");
    if (!btn) return;

    btn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (i !== activeIdx) {
        navigateTo(i);
        return;
      }
      stopAllVideos();
      playActiveVideo(isMobile ? 0 : 1);
    });
  });
};

productFeedback();
