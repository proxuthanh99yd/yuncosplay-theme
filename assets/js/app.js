class App {
  constructor() {
    this.lenis = null;
    this.init();
  }

  init() {
    this.handleInitializeLenis();
  }

  handleInitializeLenis() {
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
}

document.addEventListener("DOMContentLoaded", () => {
  new App();
});
