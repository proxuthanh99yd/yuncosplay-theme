import { headerDesktopInit } from "./header-desktop/scripts.js";
import { headerMobileInit } from "./header-mobile/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
    const isMobile = window.innerWidth < 640;
    if (isMobile) {
        headerMobileInit();
    } else {
        headerDesktopInit();
    }
});