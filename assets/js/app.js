class App {
	constructor() {
		this.lenis = null;
		this.scrollDisabledCount = 0; // Track how many components disabled scroll
		this.init();
	}

	init() {
		this.handleInitializeLenis();
		// this.handleInitializeGSAP();
		this.handleInitializeAOS();
	}
	handleInitializeGSAP() {
		gsap.registerPlugin(ScrollTrigger, ScrollSmoother);
		const smoother = ScrollSmoother.create({
			wrapper: "#smooth-wrapper",
			content: "#smooth-content",
			smooth: 1.5,
			effects: true,
			smoothTouch: 0.1,
		});
	}
	handleInitializeAOS() {
		AOS.init({
			// Global settings:
			disable: false, // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
			startEvent: "DOMContentLoaded", // name of the event dispatched on the document, that AOS should initialize on
			initClassName: "aos-init", // class applied after initialization
			animatedClassName: "aos-animate", // class applied on animation
			useClassNames: false, // if true, will add content of `data-aos` as classes on scroll
			disableMutationObserver: false, // disables automatic mutations' detections (advanced)
			debounceDelay: 50, // the delay on debounce used while resizing window (advanced)
			throttleDelay: 99, // the delay on throttle used while scrolling the page (advanced)

			// Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
			offset: 200, // offset (in px) from the original trigger point
			delay: 0, // values from 0 to 3000, with step 50ms
			duration: 750, // values from 0 to 3000, with step 50ms
			easing: "ease", // default easing for AOS animations
			once: true, // whether animation should happen only once - while scrolling down
			mirror: false, // whether elements should animate out while scrolling past them
			anchorPlacement: "top-bottom", // defines which position of the element regarding to window should trigger the animation
		});
	}

	handleInitializeLenis() {
		// const isMobile = window.innerWidth < 640;
		// if (isMobile) return;
		if (typeof Lenis === "undefined") return;

		this.lenis = new Lenis({
			smoothWheel: true,
		});

		const raf = (time) => {
			this.lenis.raf(time);
			requestAnimationFrame(raf);
		};

		requestAnimationFrame(raf);
	}

	// Reset scroll state (useful for debugging or cleanup)
	resetScrollState() {
		this.scrollDisabledCount = 0;
		console.log('Scroll state reset');
	}

	// Scroll control methods with counter
	disableScroll() {
		if (this.lenis && typeof this.lenis.stop === 'function') {
			this.lenis.stop();
			console.log('Lenis scroll disabled');
		} else {
			// Fallback for mobile or no Lenis
			document.documentElement.style.overflow = 'hidden';
			console.log('CSS overflow disabled');
		}

	}

	enableScroll() {
		if (this.lenis && typeof this.lenis.start === 'function') {
			this.lenis.start();
			console.log('Lenis scroll enabled');
		} else {
			// Fallback for mobile or no Lenis
			document.documentElement.style.overflow = '';
			console.log('CSS overflow enabled');
		}
	}
}

document.addEventListener("DOMContentLoaded", () => {
	if (document.body.classList.contains("page-template-page-contact")) return;
	// if (document.body.classList.contains("single-tour")) return
	window.app = new App();
});
