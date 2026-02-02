const MOBILE_MAX = 639;
const btns = document.querySelectorAll(".footer_group-btn");

btns.forEach((btn) => {
  const list = btn.nextElementSibling;

  let isOpen = false;
  let isAnimating = false;

  const open = () => {
    isAnimating = true;
    list.style.height = list.scrollHeight + "px";

    list.addEventListener(
      "transitionend",
      () => {
        list.style.height = "auto";
        isOpen = true;
        isAnimating = false;
      },
      { once: true },
    );
  };

  const close = () => {
    isAnimating = true;
    list.style.height = list.scrollHeight + "px";

    requestAnimationFrame(() => {
      list.style.height = "0px";
    });

    list.addEventListener(
      "transitionend",
      () => {
        isOpen = false;
        isAnimating = false;
      },
      { once: true },
    );
  };

  btn.addEventListener("click", () => {
    if (window.innerWidth > MOBILE_MAX) return;
    if (isAnimating) return;

    isOpen ? close() : open();
  });

  const resetDesktop = () => {
    list.style.height = "auto";
    list.style.overflow = "visible";
    isOpen = true;
    isAnimating = false;
  };

  const initMobile = () => {
    list.style.height = "0px";
    list.style.overflow = "hidden";
    isOpen = false;
  };

  const handleResize = () => {
    if (window.innerWidth <= MOBILE_MAX) {
      initMobile();
    } else {
      resetDesktop();
    }
  };

  // init lần đầu
  handleResize();

  window.addEventListener("resize", handleResize);
});
