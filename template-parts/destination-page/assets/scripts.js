import { sectionBannerScripts } from "../section-banner/assets/scripts.js";
import { sectionAboutScripts } from "../section-about/assets/scripts.js";
import { tabBarScripts } from "./tab-bar.js";
import { sectionSuggestScripts } from "../section-suggest/assets/scripts.js";
import { sectionCulturesScripts } from "../section-cultures/assets/scripts.js";
import { sectionAttractionsScripts } from "../section-attractions/assets/scripts.js";
import { sectionYearScripts } from "../section-year/assets/scripts.js";
import { sectionInsiderScripts } from "../section-insider/assets/scripts.js";
import { sectionReasonScripts } from "../../components/section-reason/assets/scripts.js";
import { sectionStaysScripts } from "../section-stays/assets/scripts.js"; 

document.addEventListener("DOMContentLoaded", () => {
    sectionBannerScripts();
    sectionAboutScripts();
    tabBarScripts();
    sectionSuggestScripts();
    sectionCulturesScripts();
    sectionAttractionsScripts();
    sectionYearScripts();
    sectionInsiderScripts();
    sectionReasonScripts();
    sectionStaysScripts();
});