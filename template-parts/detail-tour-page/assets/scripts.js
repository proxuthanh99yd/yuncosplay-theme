import { sectionBannerScripts } from "../section-banner/assets/scripts.js";
import { sectionTourOverviewScripts } from "../section-tour-overview/assets/scripts.js";
import { initDetailedItinerary, initAccommodationOption } from "../section-detailed-itinerary/assets/scripts.js";
import { sectionReviewsScripts } from "../../components/section-reviews/assets/scripts.js";
import { sectionReasonScripts } from "../../home-page/section-reason/assets/scripts.js";

import { HotelDrawer } from "./custom-components/tour-hotel-drawer/scripts.js";
import { LocationDrawer } from "./custom-components/location-drawer/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
    const hotelDrawer = new HotelDrawer();
    const locationDrawer = new LocationDrawer();
    sectionBannerScripts();
    sectionTourOverviewScripts();
    initDetailedItinerary();
    initAccommodationOption();
    sectionReviewsScripts();
    sectionReasonScripts();
});
