export function sectionAboutScripts() {
  if (!window.gsap || !window.ScrollTrigger) return;

  gsap.registerPlugin(ScrollTrigger, CustomEase);

  if (window.CustomEase && typeof CustomEase.get === "function") {
    if (!CustomEase.get("explorersEase")) {
      CustomEase.create("explorersEase", "0.41, 0.02, 0.1, 0.85");
    }
  } else {
    CustomEase.create("explorersEase", "0.41, 0.02, 0.1, 0.85");
  }

  // Chỉ chạy từ 640px trở lên
  const mm = gsap.matchMedia();

  mm.add("(min-width: 640px)", () => {
    const container = document.querySelector("#section-about") || document.querySelector(".section-about");
    if (!container) return;

    // Query trong phạm vi section để tránh đụng các section khác
    const selectors = {
      building: ".section-about__building",
      birds: ".section-about__birds",
      astronaut: ".section-about__astronaut",
      wave: ".section-about__wave",
      tower: ".section-about__tower",
      photo: ".section-about__photo",
      tree: ".section-about__tree",
      mountainLeft: ".section-about__mountain-left",
      zoro: ".section-about__zoro",
      mountainRight: ".section-about__mountain-right",
      mermaid: ".section-about__mermaid",
      bgBottom: ".section-about__bg-bottom",
      nezuko: ".section-about__nezuko",
      title: ".section-about__title",
      description: ".section-about__description",
      cosplay: ".section-about__cosplay",
      spiderman: ".section-about__spiderman",
      balloon: ".section-about__balloon",
      sun: ".section-about__sun",
    };

    const els = {};
    for (const [key, selector] of Object.entries(selectors)) {
      els[key] = container.querySelector(selector);
      if (!els[key]) return;
    }

    const onLenisScroll = () => ScrollTrigger.update();
    if (window.app?.lenis && typeof window.app.lenis.on === "function") {
      window.app.lenis.on("scroll", onLenisScroll);
    }

    const tl = gsap.timeline({
      defaults: {
        duration: 2,
        ease: "explorersEase",
      },
      scrollTrigger: {
        trigger: container,
        start: "top 50%",
        toggleActions: "play none none none",
        once: true,
        invalidateOnRefresh: true,
      },
    });

    // Animation data (grouped)
    const verticalMoveAnimations = {
      targets: [
        els.zoro,
        els.building,
        els.birds,
        els.tree,
        els.mountainLeft,
        els.mountainRight,
        els.mermaid,
        els.bgBottom,
        els.sun,
      ],
      yValues: [
        "11.875rem",
        "11.25rem",
        "16.875rem",
        "12.5rem",
        "3.7501rem",
        "3.75rem",
        "14.375rem",
        "1.25rem",
        "11.875rem",
      ],
    };

    const moveXYAnimations = {
      targets: [els.astronaut, els.wave, els.tower, els.photo, els.nezuko, els.cosplay],
      xValues: ["7.5rem", "5.625rem", "3.125rem", "-5.625rem", "7.5rem", "-5.625rem"],
      yValues: ["6.25rem", "5.625rem", "8.75rem", "7.5rem", "6.875rem", "5.625rem"],
    };

    const fadeVerticalAnimations = {
      targets: [els.title, els.description],
      yValues: ["9.375rem", "11.875rem"],
    };

    const horizontalMoveAnimations = {
      targets: [els.spiderman, els.balloon],
      xValues: ["8.125rem", "-5rem"],
    };

    // Vertical movement
    tl.fromTo(
      verticalMoveAnimations.targets,
      { y: (i) => verticalMoveAnimations.yValues[i] },
      { y: "0rem" },
      0
    );

    // Move X + Y
    tl.fromTo(
      moveXYAnimations.targets,
      {
        x: (i) => moveXYAnimations.xValues[i],
        y: (i) => moveXYAnimations.yValues[i],
      },
      { x: "0rem", y: "0rem" },
      "<"
    );

    // Fade in + vertical
    tl.fromTo(
      fadeVerticalAnimations.targets,
      {
        y: (i) => fadeVerticalAnimations.yValues[i],
        opacity: 0,
      },
      { y: "0rem", opacity: 1 },
      "<"
    );

    // Horizontal movement
    tl.fromTo(
      horizontalMoveAnimations.targets,
      { x: (i) => horizontalMoveAnimations.xValues[i] },
      { x: "0rem" },
      "<"
    );

    ScrollTrigger.refresh();

    // Cleanup khi matchMedia đổi điều kiện (responsive)
    return () => {
      if (window.app?.lenis && typeof window.app.lenis.off === "function") {
        window.app.lenis.off("scroll", onLenisScroll);
      }
      tl.scrollTrigger && tl.scrollTrigger.kill();
      tl.kill();
    };
  });
}