class HandleHeaderToggleOnScroll {
    constructor() {
        this.header = document.querySelector(".header");
        this.lastScrollTop = 0;
    }

    init() {
        this.handleScroll();
    }

    handleScroll() {
        window.addEventListener("scroll", () => {
            const vh = Math.max(
                document.documentElement.clientHeight || 0,
                window.innerHeight || 0
            );
            const scrollTop =
                window.pageYOffset || document.documentElement.scrollTop;
            const headerHeight = this.header.offsetHeight;
            const headerTop = this.header.getBoundingClientRect().top;
            // Kiểm tra nếu header còn trong viewport => remove "header--color", ngược lại thì add
            if (scrollTop <= vh) {
                this.header.classList.remove("header--color");
            } else {
                this.header.classList.add("header--color");
            }

            // Ẩn header khi scroll xuống và đã vượt quá viewport
            if (scrollTop > this.lastScrollTop && scrollTop > vh) {
                this.header.classList.add("hide");
            } else if (
                scrollTop < this.lastScrollTop ||
                scrollTop < headerHeight
            ) {
                this.header.classList.remove("hide");
            }

            this.lastScrollTop = scrollTop;
        });
    }
}

const handleHeaderToggleOnScroll = new HandleHeaderToggleOnScroll();
handleHeaderToggleOnScroll.init();

class HandleMenuHover {
    constructor() {
        this.headerItems = document.getElementsByClassName("header__menu-link");
        this.headerSubmenus =
            document.getElementsByClassName("header__submenu");
        this.subMenuEl = [];
    }
    init() {
        this.handleMouseover();
        this.handleMouseleave();
    }
    handleMouseover() {
        Array.from(this.headerItems).forEach((item) => {
            const subMenu = item.nextElementSibling;
            if (!subMenu) {
                return;
            }
            this.subMenuEl.push(subMenu);
            item.addEventListener("mouseover", () => {
                if (subMenu) {
                    this.subMenuEl.forEach((el) => {
                        el.classList.remove("active");
                    });
                    Array.from(this.headerItems).forEach((el) => {
                        el.classList.remove("active");
                    });

                    item.classList.add("active");
                    subMenu.classList.add("active");
                }
            });
            const closeBtn = subMenu.querySelector(".header__submenu-close");
            if (closeBtn) {
                closeBtn.addEventListener("click", () => {
                    subMenu.classList.remove("active");
                    item.classList.remove("active");
                });
            }
        });
    }
    handleMouseleave() {
        Array.from(this.headerSubmenus).forEach((submenu) => {
            submenu.addEventListener("mouseleave", () => {
                submenu.classList.remove("active");
                Array.from(this.headerItems).forEach((el) => {
                    el.classList.remove("active");
                });
            });
        });
    }
}

const handleMenuHover = new HandleMenuHover();
handleMenuHover.init();

class HandleMenuTabs {
    constructor() {
        this.megaMenuTabs = document.getElementsByClassName(
            "header__submenu-left-link"
        );
        this.megaMenuTabContent = document.querySelector(
            ".header__submenu-right"
        );
    }
    megaMenuReset() {
        Array.from(this.megaMenuTabs).forEach((tab) => {
            tab.classList.remove("active");
        });
    }
    init() {
        Array.from(this.megaMenuTabs).forEach((tab) => {
            tab.addEventListener("click", (e) => {
                e.preventDefault();
                this.megaMenuReset();
                tab.classList.add("active");
            });
        });
    }
    async fetchData() {}
}
const handleMenuTabs = new HandleMenuTabs();
handleMenuTabs.init();

class HeaderMobileAccordion {
    constructor() {}

    init() {
        this.headerMobile = document.querySelector(".header__menu-nav");
        if (!this.headerMobile) return;

        this.headerMobileBtns = Array.from(
            this.headerMobile.getElementsByClassName("header__menu-item")
        );

        this.handleAccordion();
    }

    handleAccordion() {
        const btnDomMapping = {};

        this.headerMobileBtns.forEach((btn, index) => {
            const headerMobileLink = btn.querySelector(".header__menu-link");
            const headerMobileSubmenu = btn.querySelector(".header__sub-menu");
            const headerMobileContent = btn.querySelector(
                ".header__sub-menu-list"
            );

            // Chỉ xử lý nếu có submenu
            if (headerMobileSubmenu && headerMobileContent) {
                btnDomMapping[index] = {
                    btn,
                    headerMobileLink,
                    headerMobileSubmenu,
                    headerMobileContent,
                };

                btn.addEventListener("click", function (e) {
                    e.preventDefault();

                    const isActive = btn.classList.contains("active");

                    // Đóng tất cả các menu trước khi mở menu mới
                    Object.keys(btnDomMapping).forEach((key) => {
                        const {
                            btn,
                            headerMobileLink,
                            headerMobileSubmenu,
                            headerMobileContent,
                        } = btnDomMapping[key];

                        btn.classList.remove("active");

                        if (headerMobileLink) {
                            headerMobileLink.classList.remove("active");
                        }

                        if (headerMobileSubmenu && headerMobileContent) {
                            headerMobileSubmenu.style.maxHeight = null;
                        }
                    });

                    // Nếu menu chưa mở, thì mở nó
                    if (!isActive) {
                        btn.classList.add("active");
                        headerMobileLink.classList.add("active");
                        headerMobileSubmenu.style.maxHeight =
                            headerMobileContent.scrollHeight + "px";
                    }
                });
            }
        });
    }
}

const headerMobileAccordion = new HeaderMobileAccordion();
headerMobileAccordion.init();
const toggleMenu = document.querySelector(".header__nav-toggle");
const headerMainNav = document.querySelector(".header__nav");
const headerNav = document.querySelector(".header__menu-nav");
const headerMenuClose = document.querySelector(".header__nav-toggle--close");
toggleMenu &&
    toggleMenu.addEventListener("click", () => {
        document.documentElement.style.overflow = "hidden";
        headerNav.classList.toggle("open");
        headerMainNav.classList.toggle("open");
    });

headerMenuClose &&
    headerMenuClose.addEventListener("click", () => {
        document.documentElement.style.overflow = null;
        headerNav.classList.remove("open");
        headerMainNav.classList.remove("open");
    });
