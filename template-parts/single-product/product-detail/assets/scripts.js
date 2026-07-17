/**
 * Product Detail - Sync desktop sticky offset with auto-hidden header
 */

(function () {
	const stickyInfo = document.querySelector('.product-detail__info-sticky');
	const header = document.querySelector('.header');
	const desktopQuery = window.matchMedia('(min-width: 640px)');
	const bodyClass = 'product-detail-header-hidden';

	if (!stickyInfo || !header) return;

	function syncStickyOffset() {
		const isHeaderHidden = header.classList.contains('header--auto-hidden');

		document.body.classList.toggle(
			bodyClass,
			desktopQuery.matches && isHeaderHidden
		);
	}

	const observer = new MutationObserver(syncStickyOffset);
	observer.observe(header, {
		attributes: true,
		attributeFilter: ['class'],
	});

	if (typeof desktopQuery.addEventListener === 'function') {
		desktopQuery.addEventListener('change', syncStickyOffset);
	} else if (typeof desktopQuery.addListener === 'function') {
		desktopQuery.addListener(syncStickyOffset);
	}

	syncStickyOffset();
})();

/**
 * Product Detail — Image Gallery Lightbox
 * Uses Fancybox if available, otherwise opens image in new tab
 */

(function () {
	const galleryLinks = document.querySelectorAll('.product-detail__gallery-link[data-lightbox]');

	if (!galleryLinks.length) return;

	if (typeof Fancybox !== 'undefined') {
		Fancybox.bind('.product-detail__gallery-link[data-lightbox]', {
			groupAll: true,
			animated: true,
			showClass: 'fancybox-fadeIn',
			hideClass: 'fancybox-fadeOut',
			Images: {
				zoom: true,
			},
			Toolbar: {
				display: {
					left: [],
					middle: [],
					right: ['close'],
				},
			},
		});
	}
})();

/**
 * Product Detail — Desktop Video Click-to-Play
 * Loads video src from data-src on first play click
 */

(function () {
	var wraps = document.querySelectorAll('.product-detail__gallery-video-wrap[data-video]');
	wraps.forEach(function (wrap) {
		var btn = wrap.querySelector('.product-detail__video-play');
		var video = wrap.querySelector('video');
		if (!btn || !video) return;

		btn.addEventListener('click', function () {
			var src = video.getAttribute('data-src');
			if (src && !video.getAttribute('src')) {
				video.src = src;
			}
			video.play().catch(function () {});
			wrap.classList.add('is-playing');
		});
	});
})();

/**
 * Product Detail — Mobile Story Gallery
 * Auto-play like FB/IG Stories: 3.5s per image, video advances on ended
 */

(function () {
	const story = document.querySelector('.product-detail__story');
	if (!story) return;

	const viewer = story.querySelector('.product-detail__story-viewer');
	const slides = story.querySelectorAll('.product-detail__story-slide');
	const bars = story.querySelectorAll('.product-detail__story-bar');
	const thumbs = story.querySelectorAll('.product-detail__story-thumb');
	const totalSlides = slides.length;

	if (totalSlides === 0) return;

	let currentIndex = 0;
	let timer = null;
	let isPaused = false;
	var activeVideo = null;
	var IMAGE_DURATION = 3500; // 3.5 seconds per image

	function scrollThumbIntoCenter(container, item) {
		if (!container || !item) return;

		const left =
			item.offsetLeft
			- container.offsetWidth / 2
			+ item.offsetWidth / 2;

		container.scrollTo({
			left,
			behavior: 'smooth'
		});
	}

	function getActiveVideo() {
		var slide = slides[currentIndex];
		if (slide && slide.dataset.type === 'video') {
			return slide.querySelector('video');
		}
		return null;
	}

	function stopAllVideos() {
		story.querySelectorAll('video').forEach(function (v) {
			v.pause();
			v.currentTime = 0;
			v.onended = null;
		});
		activeVideo = null;
	}

	function goTo(index) {
		if (index < 0 || index >= totalSlides) return;

		stopTimer();
		stopAllVideos();

		// Update slides
		slides.forEach(function (slide, i) {
			slide.classList.toggle('is-active', i === index);
		});

		// Update progress bars
		bars.forEach(function (bar, i) {
			bar.classList.remove('is-active', 'is-playing', 'is-done');
			var fill = bar.querySelector('.product-detail__story-bar-fill');
			if (fill) {
				fill.style.transition = 'none';
				fill.style.width = '';
			}

			if (i < index) {
				bar.classList.add('is-done');
			} else if (i === index) {
				bar.classList.add('is-active');
			}
		});

		// Update thumbnails
		thumbs.forEach(function (thumb, i) {
			thumb.classList.toggle('is-active', i === index);
		});

		// Auto-scroll active thumbnail into view
		var activeThumb = thumbs[index];
		if (activeThumb) {
			// activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
			scrollThumbIntoCenter(
				story.querySelector('.product-detail__story-thumbs'),
				activeThumb
			);
		}

		currentIndex = index;

		// Start playing
		var video = getActiveVideo();
		if (video) {
			// Lazy-load video src from data-src
			var dataSrc = video.getAttribute('data-src');
			if (dataSrc && !video.getAttribute('src')) {
				video.src = dataSrc;
			}
			video.currentTime = 0;
			activeVideo = video;
			video.onended = function () {
				if (activeVideo === video && !isPaused) {
					next();
				}
			};
			video.play().catch(function () {
				startSlideTimer(IMAGE_DURATION);
			});
			startBarAnimation();
		} else {
			startSlideTimer(IMAGE_DURATION);
			startBarAnimation(IMAGE_DURATION);
		}
	}

	function startSlideTimer(duration) {
		stopTimer();
		timer = setTimeout(function () {
			next();
		}, duration);
	}

	function startBarAnimation(duration) {
		var bar = bars[currentIndex];
		if (!bar) return;
		var fill = bar.querySelector('.product-detail__story-bar-fill');
		if (!fill) return;

		var animationDuration = duration || IMAGE_DURATION;

		// Force reflow
		fill.style.transition = 'none';
		fill.style.width = '0';
		void fill.offsetWidth;

		fill.style.transition = 'width ' + animationDuration + 'ms linear';
		fill.style.width = '100%';
		bar.classList.add('is-playing');
	}

	function next() {
		goTo((currentIndex + 1) % totalSlides);
	}

	function prev() {
		goTo((currentIndex - 1 + totalSlides) % totalSlides);
	}

	function stopTimer() {
		if (timer) {
			clearTimeout(timer);
			timer = null;
		}
	}

	function pause() {
		if (isPaused) return;
		isPaused = true;
		stopTimer();
		var activeBar = bars[currentIndex];
		if (activeBar) {
			activeBar.classList.remove('is-playing');
		}
		var video = getActiveVideo();
		if (video) {
			video.pause();
		}
	}

	// Tap navigation: left half = prev, right half = next
	viewer.addEventListener('click', function (e) {
		var rect = viewer.getBoundingClientRect();
		var x = e.clientX - rect.left;
		if (x < rect.width / 2) {
			prev();
		} else {
			next();
		}
	});

	// Touch: pause on hold, resume on release
	var touchStartX = 0;
	var touchStartY = 0;
	var isSwiping = false;

	viewer.addEventListener('touchstart', function (e) {
		touchStartX = e.touches[0].clientX;
		touchStartY = e.touches[0].clientY;
		isSwiping = false;
		pause();
	}, { passive: true });

	viewer.addEventListener('touchmove', function (e) {
		var dx = e.touches[0].clientX - touchStartX;
		var dy = e.touches[0].clientY - touchStartY;
		if (Math.abs(dx) > 10 && Math.abs(dx) > Math.abs(dy)) {
			isSwiping = true;
		}
	}, { passive: true });

	viewer.addEventListener('touchend', function (e) {
		if (isSwiping) {
			var dx = e.changedTouches[0].clientX - touchStartX;
			if (dx < -30) {
				next();
			} else if (dx > 30) {
				prev();
			}
		} else {
			// Resumed from hold — restart current slide
			isPaused = false;
			goTo(currentIndex);
		}
		isPaused = false;
	});

	// Thumbnail click
	thumbs.forEach(function (thumb) {
		thumb.addEventListener('click', function () {
			var index = parseInt(this.dataset.index, 10);
			isPaused = false;
			goTo(index);
		});
	});

	// Initialize
	goTo(0);

	// Pause when page is hidden
	document.addEventListener('visibilitychange', function () {
		if (document.hidden) {
			pause();
		} else {
			isPaused = false;
			goTo(currentIndex);
		}
	});
})();
