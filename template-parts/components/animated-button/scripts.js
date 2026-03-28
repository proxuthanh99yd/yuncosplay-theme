function initAnimatedBtnSwap() {
    document.querySelectorAll(".animated-btn").forEach((btn) => {
        const text = btn.querySelector(".animated-btn__content-visible-text");
        const icon = btn.querySelector(".animated-btn__content-visible-icon");
        if (!text || !icon) return;

        btn.style.setProperty("--text-w", `${text.offsetWidth}px`);
        btn.style.setProperty("--icon-w", `${icon.offsetWidth}px`);
    });
}
window.addEventListener("load", initAnimatedBtnSwap);
window.addEventListener("resize", initAnimatedBtnSwap);
