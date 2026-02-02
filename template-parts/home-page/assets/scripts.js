import { sectionBannerScripts } from "../section-banner/assets/scripts.js";
import { sectionHighlightsScripts } from "../section-highlights/assets/scripts.js";
import { sectionDestinationsScripts } from "../section-destinations/assets/scripts.js";
import { sectionInspiringScripts } from "../section-inspiring/assets/scripts.js";
import { sectionReasonScripts } from "../../components/section-reason/assets/scripts.js";
import { sectionReviewsScripts } from "../../components/section-reviews/assets/scripts.js";
import { sectionTravelguideScripts } from "../section-travelguide/assets/scripts.js";
import { popupDestinationScripts } from "../../components/popup-destination/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
  popupDestinationScripts();
  sectionBannerScripts();
  sectionHighlightsScripts();
  sectionDestinationsScripts();
  sectionInspiringScripts();
  sectionReasonScripts();
  sectionReviewsScripts();
  sectionTravelguideScripts();
});
