const rootFontSize = parseFloat(
	getComputedStyle(document.documentElement).fontSize
);

function remToPixels(rem) {
	return rem * rootFontSize;
}

export const sectionReviewsScripts = () => {
	console.log("sectionReviewsScripts")
	const CONFIG = {
		BREAKPOINT_MOBILE: 639.98,
		SLIDES_PER_VIEW: 3,
	};

	const SELECTORS = {
		SWIPER: "#reviews-swiper",
		SLIDES: ".reviews__swiper .swiper-slide",
		BUTTON_PREV: ".reviews__button--prev",
		BUTTON_NEXT: ".reviews__button--next",
		REVIEW_ITEMS: ".reviews__item",
		POPUP: ".reviews-popup",
		POPUP_OVERLAY: ".reviews-popup-overlay",
		POPUP_CLOSE: ".reviews-popup-close",
		POPUP_AVATAR: ".reviews-popup-avatar-img",
		POPUP_NAME: ".reviews-popup-name",
		POPUP_DATE: ".reviews-popup-date",
		POPUP_SOCIAL: ".reviews-popup-social",
		POPUP_RATING: ".reviews-popup-rating",
		POPUP_REVIEW_TITLE: ".reviews-popup-review-title",
		POPUP_REVIEW_DESCRIPTION: ".reviews-popup-review-description",
		POPUP_GALLERY: ".reviews-popup-gallery",
		POPUP_GALLERY_MAIN: ".reviews-popup-gallery-main",
		POPUP_GALLERY_SWIPER: ".reviews-popup-gallery-swiper",
		POPUP_GALLERY_WRAPPER: ".reviews-popup-gallery-swiper .swiper-wrapper",
		POPUP_GALLERY_THUMBS: ".reviews-popup-gallery-thumbs",
		POPUP_GALLERY_THUMBS_SWIPER: ".reviews-popup-gallery-thumbs-swiper",
		POPUP_GALLERY_THUMBS_WRAPPER: ".reviews-popup-gallery-thumbs-swiper .swiper-wrapper",
		POPUP_GALLERY_BUTTON_PREV: ".reviews-popup-gallery-button-prev",
		POPUP_GALLERY_BUTTON_NEXT: ".reviews-popup-gallery-button-next",
	};

	let swiper = null;
	let gallerySwiper = null;
	let galleryThumbsSwiper = null;
	let isDesktop = null;
	let slidesCount = 0;

	function initSwiper() {
		if (typeof Swiper === "undefined") return;

		const swiperEl = document.querySelector(SELECTORS.SWIPER);
		if (!swiperEl) return;

		const currentIsDesktop = window.innerWidth > CONFIG.BREAKPOINT_MOBILE;

		// Không thay đổi breakpoint → bỏ qua
		if (currentIsDesktop === isDesktop && swiper) return;
		isDesktop = currentIsDesktop;

		// Destroy swiper cũ nếu có
		if (swiper) {
			swiper.destroy(true, true);
			swiper = null;
		}

		if (!slidesCount) {
			slidesCount = document.querySelectorAll(SELECTORS.SLIDES).length;
		}
		if (!slidesCount) return;

		swiper = new Swiper(SELECTORS.SWIPER, {
			slidesPerView: isDesktop ? CONFIG.SLIDES_PER_VIEW : "auto",
			spaceBetween: isDesktop ? remToPixels(0.875) : remToPixels(0.5),
			grabCursor: true,
			watchOverflow: true,
			navigation: {
				nextEl: SELECTORS.BUTTON_NEXT,
				prevEl: SELECTORS.BUTTON_PREV,
				disabledClass: "is-disabled",
			},
		});
	}

	function initPopup() {
		const popup = document.querySelector(SELECTORS.POPUP);
		const overlay = document.querySelector(SELECTORS.POPUP_OVERLAY);
		const closeBtn = document.querySelector(SELECTORS.POPUP_CLOSE);
		const popupContent = popup?.querySelector(".reviews-popup-content");
		const reviewItems = document.querySelectorAll(SELECTORS.REVIEW_ITEMS);

		if (!popup) return;

		// Swipe down to close on mobile
		let touchStartY = 0;
		let touchCurrentY = 0;
		let isDragging = false;

		function handleTouchStart(e) {
		if (window.innerWidth > CONFIG.BREAKPOINT_MOBILE) return;
		touchStartY = e.touches[0].clientY;
		isDragging = true;
		if (popupContent) {
		popupContent.style.transition = "none";
		}
		}

		function handleTouchMove(e) {
		if (!isDragging || window.innerWidth > CONFIG.BREAKPOINT_MOBILE) return;
		touchCurrentY = e.touches[0].clientY;
		const diff = touchCurrentY - touchStartY;

		// Only allow dragging down
		if (diff > 0 && popupContent) {
		popupContent.style.transform = `translateY(${diff}px)`;
}
}

function handleTouchEnd() {
if (!isDragging || window.innerWidth > CONFIG.BREAKPOINT_MOBILE) return;
isDragging = false;

const diff = touchCurrentY - touchStartY;
const threshold = 100; // Minimum drag distance to close

if (popupContent) {
popupContent.style.transition = "";

if (diff > threshold) {
// Close popup
closePopup();
} else {
// Snap back
popupContent.style.transform = "translateY(0)";
}
}

touchStartY = 0;
touchCurrentY = 0;
}

if (popupContent) {
popupContent.addEventListener("touchstart", handleTouchStart, { passive: true });
popupContent.addEventListener("touchmove", handleTouchMove, { passive: true });
popupContent.addEventListener("touchend", handleTouchEnd, { passive: true });
}

function openPopup(reviewItem) {
// Get data from the clicked review item
const reviewId = reviewItem.getAttribute("data-review-id");
const currentReviewId = popup.getAttribute("data-current-review-id");
const avatarEl = reviewItem.querySelector(".reviews__item-info-avatar");
const nameEl = reviewItem.querySelector(".reviews__item-info-name");
const dateEl = reviewItem.querySelector(".reviews__item-info-location");
const ratingEl = reviewItem.querySelector(".reviews__item-rating");
const reviewTitleEl = reviewItem.querySelector(".reviews__item-review-title");
const reviewDescEl = reviewItem.querySelector(".reviews__item-review-description");
const socialLinksEl = reviewItem.querySelector(".reviews__item-social");
const galleryData = reviewItem.getAttribute("data-gallery");
// Populate popup with data
const popupAvatar = popup.querySelector(SELECTORS.POPUP_AVATAR);
const popupGallery = popup.querySelector(SELECTORS.POPUP_GALLERY);
const popupGalleryWrapper = popup.querySelector(SELECTORS.POPUP_GALLERY_WRAPPER);
const popupName = popup.querySelector(SELECTORS.POPUP_NAME);
const popupDate = popup.querySelector(SELECTORS.POPUP_DATE);
const popupSocial = popup.querySelector(SELECTORS.POPUP_SOCIAL);
const popupRating = popup.querySelector(SELECTORS.POPUP_RATING);
const popupReviewTitle = popup.querySelector(SELECTORS.POPUP_REVIEW_TITLE);
const popupReviewDesc = popup.querySelector(SELECTORS.POPUP_REVIEW_DESCRIPTION);

// Avatar
if (popupAvatar && avatarEl) {
// avatarEl có thể là <img> tag hoặc container chứa <img>
const avatarImg = avatarEl.tagName === "IMG" ? avatarEl : avatarEl.querySelector("img");
if (avatarImg) {
popupAvatar.src = avatarImg.src;
popupAvatar.alt = avatarImg.alt || "";
popupAvatar.style.display = "block";
} else {
popupAvatar.style.display = "none";
}
} else if (popupAvatar) {
popupAvatar.style.display = "none";
}

// Name
if (popupName && nameEl) {
popupName.textContent = nameEl.textContent || "";
}

// Date
if (popupDate && dateEl) {
popupDate.textContent = dateEl.textContent || "";
}

// Social links
if (popupSocial && socialLinksEl) {
popupSocial.innerHTML = "";
const socialLinks = socialLinksEl.querySelectorAll(".reviews__item-link");
socialLinks.forEach((link) => {
const clonedLink = link.cloneNode(true);
clonedLink.onclick = (e) => e.stopPropagation();
popupSocial.appendChild(clonedLink);
});
} else if (popupSocial) {
popupSocial.innerHTML = "";
}

// Rating
if (popupRating && ratingEl) {
popupRating.innerHTML = "";
const ratingIcons = ratingEl.querySelectorAll(".reviews__item-rating-icon");
ratingIcons.forEach((icon) => {
const clonedIcon = icon.cloneNode(true);
clonedIcon.classList.add("reviews-popup-rating-icon");
popupRating.appendChild(clonedIcon);
});
}

// Review title
if (popupReviewTitle && reviewTitleEl) {
popupReviewTitle.textContent = reviewTitleEl.textContent || "";
}

// Review description
if (popupReviewDesc && reviewDescEl) {
popupReviewDesc.innerHTML = reviewDescEl.innerHTML || "";
}

// Gallery
const popupGalleryThumbsWrapper = popup.querySelector(SELECTORS.POPUP_GALLERY_THUMBS_WRAPPER);

if (popupGalleryWrapper && popupGalleryThumbsWrapper) {
// Only recreate slides if opening a different review
const isDifferentReview = currentReviewId !== reviewId;

if (isDifferentReview) {
popupGalleryWrapper.innerHTML = "";
popupGalleryThumbsWrapper.innerHTML = "";
}

if (galleryData) {
try {
const galleryImages = JSON.parse(galleryData);
if (Array.isArray(galleryImages) && galleryImages.length > 0) {
// Only create slides if opening a different review
if (isDifferentReview) {
// Create main gallery slides
galleryImages.forEach((imageUrl) => {
const slide = document.createElement("div");
slide.className = "swiper-slide";
const img = document.createElement("img");
img.src = imageUrl;
img.alt = "";
img.style.width = "100%";
img.style.height = "100%";
img.style.objectFit = "cover";
slide.appendChild(img);
popupGalleryWrapper.appendChild(slide);
});

// Create thumbs gallery slides
galleryImages.forEach((imageUrl) => {
const slide = document.createElement("div");
slide.className = "swiper-slide";
const img = document.createElement("img");
img.src = imageUrl;
img.alt = "";
img.style.width = "100%";
img.style.height = "100%";
img.style.objectFit = "cover";
slide.appendChild(img);
popupGalleryThumbsWrapper.appendChild(slide);
});
}

// Only destroy existing gallery swipers if opening a different review
if (isDifferentReview) {
if (galleryThumbsSwiper) {
galleryThumbsSwiper.destroy(true, true);
galleryThumbsSwiper = null;
}
if (gallerySwiper) {
gallerySwiper.destroy(true, true);
gallerySwiper = null;
}
}

// Initialize gallery swiper with thumbs (only if not already initialized or review changed)
if (typeof Swiper !== "undefined") {
const galleryThumbsSwiperEl = popup.querySelector(SELECTORS.POPUP_GALLERY_THUMBS_SWIPER);
const gallerySwiperEl = popup.querySelector(SELECTORS.POPUP_GALLERY_SWIPER);

if (galleryThumbsSwiperEl && gallerySwiperEl) {
// Only initialize if swipers don't exist or review changed
if (!galleryThumbsSwiper || isDifferentReview) {
// Initialize thumbs swiper first
galleryThumbsSwiper = new Swiper(galleryThumbsSwiperEl, {
spaceBetween: remToPixels(0.25),
slidesPerView: galleryImages.length > 4 ? 4 : galleryImages.length,
freeMode: true,
watchSlidesProgress: true,
direction: "vertical",
speed: 500,
loop: galleryImages.length > 1,
});

// Initialize main gallery swiper with thumbs
gallerySwiper = new Swiper(gallerySwiperEl, {
slidesPerView: 1,
spaceBetween: 0,
loop: galleryImages.length > 1,
thumbs: {
swiper: galleryThumbsSwiper,
},
direction: "vertical",
mousewheel: true,
speed: 500,
});
}
}
}
popupGallery.style.display = "block";
} else {
popupGallery.style.display = "none";
}
} catch (e) {
console.error("Error parsing gallery data:", e);
popupGallery.style.display = "none";
}
} else {
popupGallery.style.display = "none";
// Destroy gallery swipers if no images
if (galleryThumbsSwiper) {
galleryThumbsSwiper.destroy(true, true);
galleryThumbsSwiper = null;
}
if (gallerySwiper) {
gallerySwiper.destroy(true, true);
gallerySwiper = null;
}
}
}

// Stop Lenis smooth scroll khi mở popup
const lenisInstance = window.app?.lenis;
if (lenisInstance && typeof lenisInstance.stop === "function") {
lenisInstance.stop();
}

// Chặn scroll body
document.body.style.overflow = "hidden";
document.documentElement.style.overflow = "hidden";

// Store current review ID
popup.setAttribute("data-current-review-id", reviewId);

// Reset transform for mobile animation
if (popupContent && window.innerWidth <= CONFIG.BREAKPOINT_MOBILE) {
popupContent.style.transform = "translateY(100%)";
// Force reflow
popupContent.offsetHeight;
// Then animate to visible
requestAnimationFrame(() => {
popupContent.style.transition = "";
popupContent.style.transform = "translateY(0)";
});
}

// Show popup
popup.classList.add("reviews-popup--active");
}

function closePopup() {
popup.classList.remove("reviews-popup--active");

// Reset transform for mobile
if (popupContent && window.innerWidth <= CONFIG.BREAKPOINT_MOBILE) {
popupContent.style.transform = "translateY(100%)";
}

// Don't destroy gallery swipers when closing - keep them for next time
// They will be destroyed when opening a different review

// Restore scroll
document.body.style.overflow = "";
document.documentElement.style.overflow = "";

// Resume Lenis smooth scroll
const lenisInstance = window.app?.lenis;
if (lenisInstance && typeof lenisInstance.start === "function") {
lenisInstance.start();
}
}

// Add click handlers to review items
reviewItems.forEach((item) => {
item.addEventListener("click", (e) => {
// Don't open popup if clicking on social links
if (e.target.closest(".reviews__item-link")) {
return;
}
openPopup(item);
});
});

// Close popup handlers
if (closeBtn) {
closeBtn.addEventListener("click", closePopup);
}

if (overlay) {
overlay.addEventListener("click", closePopup);
}

// Close on Escape key
document.addEventListener("keydown", (e) => {
if (e.key === "Escape" && popup.classList.contains("reviews-popup--active")) {
closePopup();
}
});
}

function init() {
if (document.readyState === "loading") {
document.addEventListener("DOMContentLoaded", () => {
initSwiper();
initPopup();
});
} else {
initSwiper();
initPopup();
}

let resizeTimeout;
window.addEventListener("resize", () => {
clearTimeout(resizeTimeout);
resizeTimeout = setTimeout(initSwiper, 200);
});
}

init();
};
