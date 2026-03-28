class Footer {
    constructor() {
        this.footerElement = document.querySelector(".footer");
        if (!this.footerElement) return;

        this.isMobileViewport = window.innerWidth < 640;

        // Các phần tử click để toggle accordion
        this.accordionTriggers = this.footerElement.querySelectorAll("[data-accordion-trigger]");

        this.initializeAccordionState();
        this.bindEvents();
    }

    // Lấy phần content tương ứng với trigger (nằm ngay sau trigger)
    getAccordionContentElement(triggerElement) {
        let sibling = triggerElement.nextElementSibling;

        while (sibling && !sibling.hasAttribute("data-accordion-content")) {
            sibling = sibling.nextElementSibling;
        }

        return sibling;
    }

    // Mặc định mở hết
    initializeAccordionState() {
        this.accordionTriggers.forEach((triggerElement) => {
            const contentElement = this.getAccordionContentElement(triggerElement);
            if (!contentElement) return;

            triggerElement.setAttribute("aria-expanded", "true");
            contentElement.style.height = "auto";
        });
    }

    openAccordionItem(triggerElement, contentElement) {
        triggerElement.setAttribute("aria-expanded", "true");

        contentElement.style.height = "0px";
        contentElement.offsetHeight;
        contentElement.style.height = `${contentElement.scrollHeight}px`;

        const handleTransitionEnd = (event) => {
            if (event.propertyName !== "height") return;

            contentElement.style.height = "auto";
            contentElement.removeEventListener("transitionend", handleTransitionEnd);
        };

        contentElement.addEventListener("transitionend", handleTransitionEnd);
    }

    closeAccordionItem(triggerElement, contentElement) {
        triggerElement.setAttribute("aria-expanded", "false");

        const currentContentHeight = contentElement.scrollHeight;

        contentElement.style.height = `${currentContentHeight}px`;
        contentElement.offsetHeight; // force reflow
        contentElement.style.height = "0px";
    }

    toggleAccordionItem(triggerElement) {
        const contentElement = this.getAccordionContentElement(triggerElement);
        if (!contentElement) return;

        const isExpanded = triggerElement.getAttribute("aria-expanded") === "true";

        if (isExpanded) this.closeAccordionItem(triggerElement, contentElement);
        else this.openAccordionItem(triggerElement, contentElement);
    }

    bindAccordionToggleOnMobile() {
        if (!this.isMobileViewport) return;

        this.accordionTriggers.forEach((triggerElement) => {
            triggerElement.addEventListener("click", () => this.toggleAccordionItem(triggerElement));
        });
    }

    bindEvents() {
        this.bindAccordionToggleOnMobile();
    }
}

window.addEventListener("DOMContentLoaded", () => new Footer());
