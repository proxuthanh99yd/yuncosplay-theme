class App {
  constructor() {
    this.lenis = null;
    this.scrollDisabledCount = 0; // Track how many components disabled scroll
    this.init();
  }

  init() {
    this.handleInitializeLenis();
  }

  handleInitializeLenis() {
    // const isMobile = window.innerWidth < 640;
    // if (isMobile) return;
    if (typeof Lenis === "undefined") return;

    this.lenis = new Lenis({
      smoothWheel: true,
    });

    const raf = (time) => {
      this.lenis.raf(time);
      requestAnimationFrame(raf);
    };

    requestAnimationFrame(raf);
  }

  // Reset scroll state (useful for debugging or cleanup)
  resetScrollState() {
    this.scrollDisabledCount = 0;
    console.log("Scroll state reset");
  }

  // Scroll control methods with counter
  disableScroll() {
    if (this.lenis && typeof this.lenis.stop === "function") {
      this.lenis.stop();
      console.log("Lenis scroll disabled");
    } else {
      // Fallback for mobile or no Lenis
      document.documentElement.style.overflow = "hidden";
      console.log("CSS overflow disabled");
    }
  }

  enableScroll() {
    if (this.lenis && typeof this.lenis.start === "function") {
      this.lenis.start();
      console.log("Lenis scroll enabled");
    } else {
      // Fallback for mobile or no Lenis
      document.documentElement.style.overflow = "";
      console.log("CSS overflow enabled");
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  if (document.body.classList.contains("page-template-page-contact")) return;
  // if (document.body.classList.contains("single-tour")) return
  window.app = new App();
});
