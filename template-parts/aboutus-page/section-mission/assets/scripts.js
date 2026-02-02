export function sectionMissionScripts() {
  // Bật normalize scroll để giảm giật khi kéo trên mobile (nếu plugin hỗ trợ).
  if (typeof ScrollTrigger.normalizeScroll === "function") {
    ScrollTrigger.normalizeScroll(true);
  }
  // Delay scroll (px) trước khi timeline bắt đầu chạy.
  const scrollDelay = 500;
  // Cache DOM để tránh query nhiều lần trong onUpdate.
  const descriptionFirst = document.querySelector(
    ".section__mission__description__first"
  );
  const descriptionSecond = document.querySelector(
    ".section__mission__description__second"
  );
  const bird = document.querySelector(
    ".section__mission__bird"
  );
  const pictureNormal = document.querySelector(
    ".section__mission__picture__normal"
  );

  const ourMissionFirst = document.querySelector(
    ".our__mission__image__first"
  );
  const ourMissionFirstContent = document.querySelector(
    ".our__mission__content__first"
  );
  const ourMissionSecond = document.querySelector(
    ".our__mission__image__second"
  );
  const ourMissionSecondContent = document.querySelector(
    ".our__mission__content__second"
  );

  const isMobile = window.innerWidth < 1025;

  // Timeline tổng hợp tất cả animation, điều khiển bằng progress (0..1).
  const missionTl = gsap.timeline({
    paused: true,
    defaults: { ease: "expo.out" },
  });
  // Thời lượng chuẩn hoá để dễ chỉnh UX.
  const durationShort = 0.6;
  const durationMedium = 0.7;
  const durationLong = 0.8;
  // Tổng chiều dài pin cho phần mission (đoạn animation).
  const baseEndDistance = 8500;
  // Overlap theo chiều cao viewport để section dưới đè lên rõ ràng.
  const getPinOverlap = () => Math.max(0, Math.round(window.innerHeight || 0));
  let pinOverlap = getPinOverlap();
  // Đẩy biến overlap xuống CSS để section dưới có thể overlap đúng nhịp.
  document.documentElement.style.setProperty(
    "--mission-overlap",
    `${pinOverlap}px`
  );
  // Các mốc thời gian (giây) trong timeline để canh nhịp chuyển cảnh.
  const tSecondIn = 0.55;
  const tSecondOut = 1.35;
  const tPictureIn = 1.35;
  const tMissionFirstIn = 2.1;
  const tMissionFirstOut = 2.95;
  const tMissionSecondIn = 3.5;

  if (descriptionFirst) {
    // Trạng thái ban đầu theo CSS để không lệch layout.
    missionTl.set(descriptionFirst, {
      top: "41%",
      opacity: 1,
      transform: "translate(-50%, -50%)",
    }, 0);
    // Fade + dịch nhẹ text đầu tiên.
    missionTl.to(descriptionFirst, {
      top: "38%",
      opacity: 0,
      duration: durationMedium,
    }, 0);
  }

  if (bird) {
    // Chim mờ dần cùng đoạn text đầu.
    missionTl.set(bird, { opacity: 1 }, 0);
    missionTl.to(bird, { opacity: 0, duration: durationMedium }, 0);
  }

  if (descriptionSecond) {
    // Text thứ 2 xuất hiện sau đoạn đầu, rồi mờ đi để nhường chỗ cho ảnh.
    missionTl.set(descriptionSecond, {
      top: "45%",
      opacity: 0,
      transform: "translate(-50%, -50%)",
    }, 0);
    missionTl.to(descriptionSecond, {
      top: "40%",
      opacity: 1,
      duration: durationMedium,
    }, tSecondIn);
    missionTl.to(descriptionSecond, {
      top: "35%",
      opacity: 0,
      duration: durationLong,
    }, tSecondOut);
  }

  if (pictureNormal) {
    // Ảnh normal fade in sau khi text 2 đã hiện.
    missionTl.set(pictureNormal, { opacity: 0 }, 0);
    missionTl.to(pictureNormal, { opacity: 1, duration: durationMedium }, tPictureIn);
  }

  if (ourMissionFirst) {
    // Cặp hình + content 1: vào rồi ra.
    missionTl.set(ourMissionFirst, { top: "-20%", opacity: 0 }, 0);
    missionTl.to(
      ourMissionFirst,
      { top: isMobile ? "3rem" : "0%", opacity: 1, duration: durationLong },
      tMissionFirstIn
    );
    missionTl.to(
      ourMissionFirst,
      { top: "-20%", opacity: 0, duration: durationShort },
      tMissionFirstOut
    );
  }

  if (ourMissionFirstContent) {
    // Content 1 đi kèm hình 1.
    missionTl.set(ourMissionFirstContent, { top: isMobile ? "65%" : "60%", opacity: 0 }, 0);
    missionTl.to(
      ourMissionFirstContent,
      { top: isMobile ? "55%" : "50%", opacity: 1, duration: durationLong },
      tMissionFirstIn
    );
    missionTl.to(
      ourMissionFirstContent,
      { top: isMobile ? "65%" : "60%", opacity: 0, duration: durationShort },
      tMissionFirstOut
    );
  }

  if (ourMissionSecond) {
    // Cặp hình + content 2: xuất hiện ở cuối timeline.
    missionTl.set(ourMissionSecond, { top: "-20%", opacity: 0 }, 0);
    missionTl.to(
      ourMissionSecond,
      { top: isMobile ? "3rem" : "0%", opacity: 1, duration: durationMedium },
      tMissionSecondIn
    );
  }

  if (ourMissionSecondContent) {
    // Content 2 đi kèm hình 2.
    missionTl.set(ourMissionSecondContent, { top: isMobile ? "65%" : "60%", opacity: 0 }, 0);
    missionTl.to(
      ourMissionSecondContent,
      { top: isMobile ? "55%" : "50%", opacity: 1, duration: durationMedium },
      tMissionSecondIn
    );
  }

  // QuickTo để update progress mượt hơn, tránh tạo tween mới liên tục.
  const setTimelineProgress = gsap.quickTo(missionTl, "progress", {
    duration: 0.7,
    ease: "expo.out",
    overwrite: true,
  });

  ScrollTrigger.create({
    id: "missionPin",
    trigger: ".section__mission",
    start: "top top",
    end: () => {
      pinOverlap = getPinOverlap();
      return `+=${baseEndDistance + pinOverlap}px`;
    },
    pin: true,
    anticipatePin: 1,
    fastScrollEnd: true,
    invalidateOnRefresh: true,
    onRefresh: () => {
      pinOverlap = getPinOverlap();
      document.documentElement.style.setProperty(
        "--mission-overlap",
        `${pinOverlap}px`
      );
    },
    onLeaveBack: () => {
      const aboutMedia = document.querySelector(".about__media");
      if (aboutMedia && aboutMedia.classList.contains("active")) {
        aboutMedia.classList.remove("active");
      }
    },
    onUpdate: (self) => {
      const aboutMedia = document.querySelector(".about__media");
      if (aboutMedia) {
        aboutMedia.classList.add("active");
      }
      // Quy đổi progress ScrollTrigger -> progress timeline, có delay đầu.
      const pinDistance = Math.max(1, self.end - self.start);
      const baseDistance = Math.max(1, pinDistance - pinOverlap);
      const animationDistance = Math.max(1, baseDistance - scrollDelay);
      const rawScroll = self.progress * pinDistance;
      const clampedScroll = Math.min(
        scrollDelay + animationDistance,
        Math.max(0, rawScroll)
      );
      const adjustedProgress =
        clampedScroll <= scrollDelay
          ? 0
          : (clampedScroll - scrollDelay) / animationDistance;
      const safeProgress = Math.min(1, Math.max(0, adjustedProgress));

      // Mượt hóa cập nhật progress, tránh giật khi scroll mạnh.
      setTimelineProgress(safeProgress);
    }
  });

}