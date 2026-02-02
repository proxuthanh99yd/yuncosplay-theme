import { bannerScripts } from "../section-banner/assets/scripts.js";
import { tabBarScripts } from "./tab-bar.js";
import { finestBeachScripts } from "../section-finest-beach/assets/scripts.js";
import { beachGetawayScripts } from "../section-beach-getaway/assets/scripts.js";
import { resortScripts } from "../section-resort-collection/assets/scripts.js";
import { whenToGoScripts } from "../section-when-to-go/assets/scripts.js";
import { inspirationsScripts } from "../section-coastal-inspirations/assets/scripts.js";
import { sectionReasonScripts } from "../../components/section-reason/assets/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
  bannerScripts();
  tabBarScripts();
  finestBeachScripts();
  beachGetawayScripts();
  resortScripts();
  whenToGoScripts();
  inspirationsScripts();
  sectionReasonScripts();
});
