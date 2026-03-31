export function initProduct() {
	const products = document.querySelectorAll(".product");

	products.forEach((product) => {
	  const video = product.querySelector("video");

	  if (!video) return;

	  product.addEventListener("mouseenter", () => {
		if (window.innerWidth >= 1024 && video.src) {
		  video.play().catch(() => {});
		}
	  });

	  product.addEventListener("mouseleave", () => {
		if (window.innerWidth >= 1024 && video.src) {
		  video.pause();
		  video.currentTime = 0;
		}
	  });
	});
}