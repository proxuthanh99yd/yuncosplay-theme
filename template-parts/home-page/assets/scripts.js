import { sectionBannerScripts } from "../section-banner/assets/scripts.js";
import { sectionHighlightsScripts } from "../section-highlights/assets/scripts.js";
import { sectionDestinationsScripts } from "../section-destinations/assets/scripts.js";
import { popupDestinationScripts } from "../../components/popup-destination/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
  popupDestinationScripts();
  sectionBannerScripts();
  sectionHighlightsScripts();
  sectionDestinationsScripts();
});
