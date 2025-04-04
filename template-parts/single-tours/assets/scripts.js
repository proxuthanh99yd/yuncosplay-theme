import FaqsAccordion from "../../front-page/section-faqs/assets/scripts.js";
import sectionOverview from "../section-overview/assets/scripts.js";
import sectionProgram from "../section-program/assets/scripts.js";
import sectionRelated from "../section-related/assets/scripts.js";

sectionOverview();
if (window.innerWidth > 640) {
    new FaqsAccordion("program");
}
new FaqsAccordion("good-know");
sectionProgram();
sectionRelated();
