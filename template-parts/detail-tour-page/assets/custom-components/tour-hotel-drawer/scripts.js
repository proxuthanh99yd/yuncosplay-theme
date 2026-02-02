export class HotelDrawer {
    constructor() {
        this.hotelDrawerEl = document.getElementById("hotel-info-drawer");
        this.hotelDrawerOverlayEl = document.getElementById("hotel-info-drawer-overlay");
        this.hotelDrawerContentEl = document.getElementById("hotel-info-drawer-content");
        if (!this.hotelDrawerContentEl) return;

        this.hotelTitleEl = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__title");
        this.hotelAddressLabelEl = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__meta__location-label");
        this.hotelAddressLinkEl = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__meta__location-link");
        this.hotelMetaRating = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__meta__review-rating");
        this.hotelDescriptionEl = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__desc");
        this.hotelDetailLinkEl = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__link");
        this.hotelGalleryMainWrapperEl = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__gallery-swiper-main-wrapper");
        this.hotelGalleryThumbsWrapperEl = this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__gallery-swiper-thumbs-wrapper");

        this.init();
        this.events();
    }
    init() {}
    toggleBodyScroll({ openDrawer = false }) {
        const appInstance = window.app;
        if (openDrawer) {
            if (appInstance && typeof appInstance?.disableScroll === "function") {
                appInstance.disableScroll();
            }
        } else {
            if (appInstance && typeof appInstance.enableScroll === "function") {
                appInstance.enableScroll();
            }
        }
    }
    bindHotelData({ hotelTitle, hotelAddressTitle, hotelAddressLink, hotelRating = 5, hotelDescription, hotelGalleryImages = [], hotelLink }) {
        // Bind dữ liệu khách sạn vào drawer ở đây
        this.hotelTitleEl.textContent = hotelTitle;
        this.hotelAddressLabelEl.textContent = hotelAddressTitle;
        this.hotelAddressLinkEl.href = hotelAddressLink;
        this.hotelMetaRating.innerHTML = "";

        for (let i = 0; i < Number(hotelRating); i++) {
            const starIconEl = document.createElement("img");
            starIconEl.src = "/wp-content/uploads/star.svg";
            starIconEl.alt = "";
            this.hotelMetaRating.appendChild(starIconEl);
        }

        this.hotelDescriptionEl.textContent = hotelDescription;
        this.hotelDetailLinkEl.href = hotelLink;

        this.hotelGalleryMainWrapperEl.innerHTML = "";
        this.hotelGalleryThumbsWrapperEl.innerHTML = "";

        const parsedGalleryImages = hotelGalleryImages.length ? JSON.parse(hotelGalleryImages) : [];
        for (const imageUrl of parsedGalleryImages) {
            // Thêm ảnh vào gallery main
            const mainSlideEl = document.createElement("div");
            mainSlideEl.classList.add("swiper-slide", "hotel-info-drawer__gallery-swiper-main-slide");
            const mainSlideImg = document.createElement("img");
            mainSlideImg.src = imageUrl;
            mainSlideEl.appendChild(mainSlideImg);
            this.hotelGalleryMainWrapperEl.appendChild(mainSlideEl);

            // Thêm ảnh vào gallery thumbs
            const thumbSlideEl = document.createElement("div");
            thumbSlideEl.classList.add("swiper-slide", "hotel-info-drawer__gallery-swiper-thumbs-slide");
            const thumbSlideImg = document.createElement("img");
            thumbSlideImg.src = imageUrl;
            thumbSlideEl.appendChild(thumbSlideImg);
            this.hotelGalleryThumbsWrapperEl.appendChild(thumbSlideEl);
        }
    }
    openHotelDrawer() {
        this.openHotelDrawerBtns = document.querySelectorAll("[data-open-hotel-drawer-trigger]");

        if (!this.openHotelDrawerBtns.length || !this.hotelDrawerEl) return;
        // Mở drawer khi nhấn nút mở
        this.openHotelDrawerBtns.forEach((btnTrigger) => {
            btnTrigger.addEventListener("click", () => {
                const {
                    hotelTitle,
                    hotelAddressTitle,
                    hotelAddressLink,
                    hotelRating,
                    hotelDescription,
                    hotelGalleryImages = [],
                    hotelLink,
                } = btnTrigger.dataset;

                this.hotelDrawerEl.classList.add("hotel-info-drawer--open");
                this.bindHotelData({
                    hotelTitle,
                    hotelAddressTitle,
                    hotelAddressLink,
                    hotelRating,
                    hotelDescription,
                    hotelGalleryImages,
                    hotelLink,
                });
                this.toggleBodyScroll({ openDrawer: true });
                requestAnimationFrame(() => {
                    this.initializeGallerySwiper();
                });
            });
        });
    }
    closeHotelDrawer() {
        this.closeHotelDrawerBtns = document.querySelectorAll("[data-close-hotel-drawer-trigger]");
        if (!this.closeHotelDrawerBtns.length || !this.hotelDrawerEl || !this.hotelDrawerOverlayEl) return;

        // Đóng drawer khi nhấn nút đóng
        this.closeHotelDrawerBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                this.hotelDrawerEl.classList.remove("hotel-info-drawer--open");
                this.toggleBodyScroll({ openDrawer: false });
            });
        });
        // Đóng drawer khi nhấn overlay
        this.hotelDrawerOverlayEl.addEventListener("click", () => {
            this.hotelDrawerEl.classList.remove("hotel-info-drawer--open");
            this.toggleBodyScroll({ openDrawer: false });
        });
    }

    initializeGallerySwiper() {
        // Nếu đã khởi tạo rồi thì hủy trước
        if (this.gallerySwiperMain) {
            this.gallerySwiperMain.destroy(true, true);
            this.gallerySwiperMain = null;
        }

        if (this.gallerySwiperThumbs) {
            this.gallerySwiperThumbs.destroy(true, true);
            this.gallerySwiperThumbs = null;
        }
        // Khởi tạo Swiper cho gallery
        this.gallerySwiperThumbs = new Swiper(this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__gallery-swiper-thumbs"), {
            spaceBetween: remToPixels(0.25) || 4,
            slidesPerView: "auto",
            freeMode: true,
            watchSlidesProgress: true,
            grabCursor: true,
        });

        this.gallerySwiperMain = new Swiper(this.hotelDrawerContentEl.querySelector(".hotel-info-drawer__gallery-swiper-main"), {
            effect: "fade",
            thumbs: { swiper: this.gallerySwiperThumbs },
        });
    }
    events() {
        this.openHotelDrawer();
        this.closeHotelDrawer();
    }
}
