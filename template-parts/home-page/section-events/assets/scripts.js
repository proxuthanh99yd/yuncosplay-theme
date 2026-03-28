const rootFontSize = parseFloat(
	getComputedStyle(document.documentElement).fontSize
);

function remToPixels(rem) {
	return rem * rootFontSize;
}

export function sectionEventsScripts() {
	const SELECTORS = {
		SWIPER: ".home_events-swiper",
		SLIDES: ".home_events-swiper .swiper-slide",
		BUTTON_PREV: ".home_events-swiper-button--prev",
		BUTTON_NEXT: ".home_events-swiper-button--next",
		// PAGINATION: ".home_events-swiper-pagination",
	};

	let swiper = null;
	let lastMode = null;

	function initSwiper() {
		if (typeof Swiper === "undefined") return;

		const swiperEl = document.querySelector(SELECTORS.SWIPER);
		if (!swiperEl) return;

		const slidesCount = document.querySelectorAll(SELECTORS.SLIDES).length;
		if (!slidesCount) return;

		const isDesktop = window.innerWidth >= 1024;
		const mode = isDesktop ? "desktop" : "mobile";
		if (mode === lastMode && swiper) {
			swiper.update();
			return;
		}
		lastMode = mode;

		if (swiper) {
			swiper.destroy(true, true);
			swiper = null;
		}

		swiper = new Swiper(swiperEl, {
			watchOverflow: true,
			grabCursor: true,
			speed: 600,
			// loop: slidesCount > 1,
			navigation: {
				nextEl: SELECTORS.BUTTON_NEXT,
				prevEl: SELECTORS.BUTTON_PREV,
				disabledClass: "is-disabled",
			},
			// pagination: {
			// 	el: SELECTORS.PAGINATION,
			// 	clickable: true,
			// },
			breakpoints: {
				0: {
					slidesPerView: 1,
					spaceBetween: remToPixels(0),
				},
				640: {
					slidesPerView: "auto",
					spaceBetween: remToPixels(1.25),
				},
			},
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initSwiper);
	} else {
		initSwiper();
	}

	let resizeTimeout;
	window.addEventListener("resize", () => {
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(initSwiper, 200);
	});
}