export function sectionBlogScripts() {
	const AUTOPLAY_DURATION = 5000;

	const SELECTORS = {
		SWIPER: ".related-blog__swiper",
		BUTTON_PREV: ".related-blog__nav-prev",
		BUTTON_NEXT: ".related-blog__nav-next",
		PROGRESS_BARS: ".related-blog__progress-bar",
	};

	const swiperEl = document.querySelector(SELECTORS.SWIPER);
	if (!swiperEl) return;
	if (typeof Swiper === "undefined") return;

	const bars = document.querySelectorAll(SELECTORS.PROGRESS_BARS);
	if (!bars.length) return;

	function resetBars() {
		bars.forEach((bar) => {
			bar.classList.remove("is-active", "is-done");
			const fill = bar.querySelector(".related-blog__progress-fill");
			if (fill) {
				fill.style.transition = "none";
				fill.style.width = "0%";
			}
		});
	}

	function activateBar(index) {
		resetBars();

		bars.forEach((bar, i) => {
			const fill = bar.querySelector(".related-blog__progress-fill");
			if (!fill) return;

			if (i < index) {
				bar.classList.add("is-done");
				fill.style.transition = "none";
				fill.style.width = "100%";
			} else if (i === index) {
				bar.classList.add("is-active");
				fill.style.transition = "none";
				fill.style.width = "0%";
				fill.offsetHeight; // force reflow
				fill.style.transition = `width ${AUTOPLAY_DURATION}ms linear`;
				fill.style.width = "100%";
			}
		});
	}

	const swiper = new Swiper(swiperEl, {
		slidesPerView: 1,
		speed: 600,
		loop: true,
		grabCursor: true,
		effect: "fade",
		fadeEffect: {
			crossFade: false,
		},
		autoplay: {
			delay: AUTOPLAY_DURATION,
			disableOnInteraction: false,
		},
		navigation: {
			nextEl: SELECTORS.BUTTON_NEXT,
			prevEl: SELECTORS.BUTTON_PREV,
			disabledClass: "is-disabled",
		},
		on: {
			init(s) {
				activateBar(s.realIndex);
			},
			slideChange(s) {
				activateBar(s.realIndex);
			},
		},
	});

	bars.forEach((bar, i) => {
		bar.addEventListener("click", () => {
			swiper.slideToLoop(i);
		});
	});

	document.addEventListener("visibilitychange", () => {
		if (document.visibilityState === "visible") {
			if (swiper?.autoplay?.running === false) swiper.autoplay.start();
			activateBar(swiper.realIndex);
		}
	});
}
