export function sectionOurWayScripts() {
  const listDescriptions = Array.from(
    document.querySelectorAll(".our__way__content__description")
  );

  if (!listDescriptions.length) {
    return;
  }

  // Khoảng cách px cho mỗi lần chuyển description (tùy chỉnh UX ở đây).
  const scrollPerItem = 1200;
  const endDistance = Math.max(1, listDescriptions.length - 1) * scrollPerItem;
  // Độ trễ px sau khi pin trước khi animation bắt đầu.
  const scrollDelay = 500;
  // Tổng khoảng pin = delay + khoảng animation.
  const totalEndDistance = endDistance + scrollDelay;

  ScrollTrigger.create({
    trigger: ".our__way",
    start: "top top",
    end: `+=${totalEndDistance}`,
    anticipatePin: 1,
    fastScrollEnd: true,
    invalidateOnRefresh: true,
    pin: true,
    onUpdate: (self) => {
      const total = listDescriptions.length;

      if (total === 1) {
        listDescriptions[0].style.transform = "translate(-50%, 0rem)";
        listDescriptions[0].style.opacity = "1";
        return;
      }

      // Chia progress 0..1 thành các đoạn bằng nhau cho mỗi lần chuyển item.
      const segment = 1 / (total - 1);
      // Quy đổi progress của ScrollTrigger sang px đã scroll trong vùng pin.
      const rawScroll = self.progress * totalEndDistance;
      // Giữ ở 0 cho tới khi qua delay, sau đó chuẩn hóa về 0..1 cho animation.
      const adjustedProgress =
        rawScroll <= scrollDelay ? 0 : (rawScroll - scrollDelay) / endDistance;
      const safeProgress = Math.min(1, Math.max(0, adjustedProgress));
      const currentIndex = Math.min(
        total - 2,
        Math.floor(safeProgress / segment)
      );
      // Progress cục bộ cho lần chuyển hiện tại (0..1).
      const localProgress =
        (safeProgress - currentIndex * segment) / segment;
      // Nửa đầu ẩn item hiện tại, nửa sau hiện item tiếp theo để tránh chồng.
      const hidePhase = Math.min(1, localProgress * 2);
      const showPhase = Math.max(0, (localProgress - 0.5) * 2);

      listDescriptions.forEach((description, index) => {
        let y = 10;
        let opacity = 0;

        if (index === currentIndex) {
          if (localProgress < 0.5) {
            y = gsap.utils.interpolate(0, -1, hidePhase);
            opacity = gsap.utils.interpolate(1, 0, hidePhase);
          } else {
            y = -1;
            opacity = 0;
          }
        } else if (index === currentIndex + 1) {
          if (localProgress >= 0.5) {
            y = gsap.utils.interpolate(1, 0, showPhase);
            opacity = gsap.utils.interpolate(0, 1, showPhase);
          } else {
            y = 10;
            opacity = 0;
          }
        }

        description.style.transform = `translate(-50%, ${y}rem)`;
        description.style.opacity = opacity.toString();
      });
    },
  });
}
