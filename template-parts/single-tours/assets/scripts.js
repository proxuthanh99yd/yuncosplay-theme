import FaqsAccordion from "../../front-page/section-faqs/assets/scripts.js";
import sectionOverview from "../section-overview/assets/scripts.js";
import sectionProgram from "../section-program/assets/scripts.js";
import sectionRelated from "../section-related/assets/scripts.js";

sectionOverview();
if (window.innerWidth > 640) {
    new FaqsAccordion("program");
}
new FaqsAccordion("good-know");
new FaqsAccordion("faqs");
sectionProgram();
sectionRelated();

const industrialNavs = document.querySelectorAll(".tours-navigation a");

function observeElementWithBoundingRect(elements, callback) {
    function checkActiveElement() {
        let closestElement = null;
        let minDistance = Infinity;
        const viewportMiddle = window.innerHeight / 2;
        const viewportTop20 = window.innerHeight * 0.2; // 20% viewport từ trên xuống
        const navContainer = document.querySelector(".tours-navigation"); // Chọn nav container

        elements.forEach((el) => {
            const rect = el.getBoundingClientRect();

            // Nếu là section overview và nó cách top 20% thì hiện nav container
            if (el.id === "overview") {
                if (rect.top <= viewportTop20) {
                    navContainer.classList.add("visible"); // Hiện nav container
                } else {
                    navContainer.classList.remove("visible"); // Ẩn nav container
                }
            }

            // Kiểm tra section gần nhất với 50% viewport
            const distance = Math.abs(rect.top - viewportMiddle);
            if (distance < minDistance) {
                minDistance = distance;
                closestElement = el;
            }
        });

        if (closestElement) {
            callback(closestElement);
        }
    }

    window.addEventListener("scroll", checkActiveElement);
    checkActiveElement(); // Gọi ngay khi load trang

    return () => window.removeEventListener("scroll", checkActiveElement);
}

// Áp dụng observer
const targetElements = document.querySelectorAll("section");

observeElementWithBoundingRect(targetElements, (activeElement) => {
    const activeNav = document.querySelector(
        `.tours-navigation a[href="#${activeElement.id}"]`
    );

    if (activeNav) {
        industrialNavs.forEach((nav) => nav.classList.remove("active"));
        activeNav.classList.add("active");
    }
});
