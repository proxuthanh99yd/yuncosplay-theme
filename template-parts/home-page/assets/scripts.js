import { sectionBannerScripts } from "../section-banner/assets/scripts.js";
import { sectionAboutScripts } from "../section-about/assets/scripts.js";
import { sectionCategoryScripts } from "../section-category/assets/scripts.js";
import { sectionServicesScripts } from "../section-services/assets/scripts.js";
import { sectionEventsScripts } from "../section-events/assets/scripts.js";
import { sectionBlogScripts } from "../section-blog/assets/scripts.js";

document.addEventListener("DOMContentLoaded", () => {
  sectionBannerScripts();
  sectionAboutScripts();
  sectionCategoryScripts();
  sectionServicesScripts();
  sectionEventsScripts();
  sectionBlogScripts();
});
