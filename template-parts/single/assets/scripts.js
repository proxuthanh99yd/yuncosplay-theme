import { sectionBannerScripts } from "../section-banner/assets/scripts.js";
import { sectionRelatedTourScripts } from "../section-related-tour/assets/scripts.js";
import { sectionArticleScripts } from "../section-related-article/assets/scripts.js";
import { initShareButton } from "../component-socials/assets/scripts.js";

const initSinglePost = () => {
	sectionBannerScripts();
	sectionRelatedTourScripts();
	sectionArticleScripts();
	initShareButton();
};

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initSinglePost);
} else {
	initSinglePost();
}
