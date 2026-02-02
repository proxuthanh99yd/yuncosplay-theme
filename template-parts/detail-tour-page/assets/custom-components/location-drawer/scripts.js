export class LocationDrawer {
    constructor() {
        this.locationDrawerEl = document.getElementById("location-drawer");
        this.locationDrawerOverlayEl = document.getElementById("location-drawer-overlay");
        this.locationDrawerContentEl = document.getElementById("location-drawer-content");
        if (!this.locationDrawerContentEl) return;

        this.locationTitleEl = this.locationDrawerContentEl.querySelector(".location-drawer__title");
        this.locationAddressLabelEl = this.locationDrawerContentEl.querySelector(".location-drawer__meta__location-label");
        this.locationAddressLinkEl = this.locationDrawerContentEl.querySelector(".location-drawer__meta__location-link");
        this.locationMetaRating = this.locationDrawerContentEl.querySelector(".location-drawer__meta__review-rating");
        this.locationDescriptionEl = this.locationDrawerContentEl.querySelector(".location-drawer__desc");
        this.locationDetailLinkEl = this.locationDrawerContentEl.querySelector(".location-drawer__link");
        this.locationGalleryMainWrapperEl = this.locationDrawerContentEl.querySelector(".location-drawer__gallery-swiper-main-wrapper");
        this.locationGalleryThumbsWrapperEl = this.locationDrawerContentEl.querySelector(".location-drawer__gallery-swiper-thumbs-wrapper");

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
    bindHotelData({ locationTitle, locationDescription, locationGalleryImages = [], locationLink }) {
        // Bind dữ liệu khách sạn vào drawer ở đây
        this.locationTitleEl.textContent = locationTitle;
        this.locationDetailLinkEl.href = locationLink;
        this.locationDescriptionEl.innerHTML = locationDescription;

        this.locationGalleryMainWrapperEl.innerHTML = "";
        this.locationGalleryThumbsWrapperEl.innerHTML = "";

        const parsedGalleryImages = locationGalleryImages.length ? JSON.parse(locationGalleryImages) : [];
        for (const imageUrl of parsedGalleryImages) {
            // Thêm ảnh vào gallery main
            const mainSlideEl = document.createElement("div");
            mainSlideEl.classList.add("swiper-slide", "location-drawer__gallery-swiper-main-slide");
            const mainSlideImg = document.createElement("img");
            mainSlideImg.src = imageUrl;
            mainSlideEl.appendChild(mainSlideImg);
            this.locationGalleryMainWrapperEl.appendChild(mainSlideEl);

            // Thêm ảnh vào gallery thumbs
            const thumbSlideEl = document.createElement("div");
            thumbSlideEl.classList.add("swiper-slide", "location-drawer__gallery-swiper-thumbs-slide");
            const thumbSlideImg = document.createElement("img");
            thumbSlideImg.src = imageUrl;
            thumbSlideEl.appendChild(thumbSlideImg);
            this.locationGalleryThumbsWrapperEl.appendChild(thumbSlideEl);
        }
    }
    openCityDrawer() {
        this.openCityDrawerBtns = document.querySelectorAll("[data-open-location-drawer-trigger]");

        if (!this.openCityDrawerBtns.length || !this.locationDrawerEl) return;
        // Mở drawer khi nhấn nút mở
        this.openCityDrawerBtns.forEach((btnTrigger) => {
            btnTrigger.addEventListener("click", () => {
                const { locationTitle, locationDescription, locationGalleryImages = [], locationLink } = btnTrigger.dataset;

                this.locationDrawerEl.classList.add("location-drawer--open");
                this.bindHotelData({ locationTitle, locationDescription, locationGalleryImages, locationLink });
                this.toggleBodyScroll({ openDrawer: true });
                requestAnimationFrame(() => {
                    this.initializeGallerySwiper();
                });
            });
        });
    }
    closeCityDrawer() {
        this.closeCityDrawerBtns = document.querySelectorAll("[data-close-location-drawer-trigger]");
        if (!this.closeCityDrawerBtns.length || !this.locationDrawerEl || !this.locationDrawerOverlayEl) return;

        // Đóng drawer khi nhấn nút đóng
        this.closeCityDrawerBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                this.locationDrawerEl.classList.remove("location-drawer--open");
                this.toggleBodyScroll({ openDrawer: false });
            });
        });
        // Đóng drawer khi nhấn overlay
        this.locationDrawerOverlayEl.addEventListener("click", () => {
            this.locationDrawerEl.classList.remove("location-drawer--open");
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
        this.gallerySwiperThumbs = new Swiper(this.locationDrawerContentEl.querySelector(".location-drawer__gallery-swiper-thumbs"), {
            spaceBetween: remToPixels(0.25) || 4,
            slidesPerView: "auto",
            freeMode: true,
            watchSlidesProgress: true,
            grabCursor: true,
        });

        this.gallerySwiperMain = new Swiper(this.locationDrawerContentEl.querySelector(".location-drawer__gallery-swiper-main"), {
            effect: "fade",
            thumbs: { swiper: this.gallerySwiperThumbs },
        });
    }
    events() {
        this.openCityDrawer();
        this.closeCityDrawer();
    }
}
