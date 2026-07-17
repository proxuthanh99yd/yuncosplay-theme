
document.addEventListener("DOMContentLoaded", () => {
    const isMobile = window.innerWidth < 640;
    if (isMobile) {
        headerMobileInit();
    } else {
        headerDesktopInit();
    }
});