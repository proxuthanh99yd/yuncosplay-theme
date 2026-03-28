export function initProduct() {
	const products = document.querySelectorAll(".product");

	products.forEach((product) => {
	  const video = product.querySelector("video");

	  product.addEventListener("mouseenter", () => {
		if (window.innerWidth >= 1024) {
		  video.play();
		}
	  });

	  product.addEventListener("mouseleave", () => {
		if (window.innerWidth >= 1024) {
		  video.pause();
		  video.currentTime = 0;
		}
	  });
	});
}