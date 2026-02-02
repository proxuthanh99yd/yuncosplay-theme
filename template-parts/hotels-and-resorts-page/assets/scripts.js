import { sectionBannerScripts } from "../section-banner/assets/scripts.js";
import { hotelsScripts } from "../section-hotels/assets/scripts.js";
import { hotelFilter } from "../section-hotels/assets/filter.js";
import { sectionReasonScripts } from "../../components/section-reason/assets/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
	sectionBannerScripts();
	hotelsScripts();
	hotelFilter();
	sectionReasonScripts();
});
