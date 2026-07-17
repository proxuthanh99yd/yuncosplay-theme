function faqSectionScripts() {
  const faqItems = document.querySelectorAll(".faq-section__item");

  if (!faqItems.length) return;

  const setAnswerHeight = (item, expand) => {
    const answer = item.querySelector(".faq-section__answer");
    if (!answer) return;

    answer.style.maxHeight = expand ? `${answer.scrollHeight}px` : "0px";
  };

  faqItems.forEach((item) => {
    setAnswerHeight(item, item.classList.contains("faq-section__item--active"));
  });

  faqItems.forEach((item) => {
    const button = item.querySelector(".faq-section__question");

    if (!button) return;

    button.addEventListener("click", () => {
      const isActive = item.classList.contains("faq-section__item--active");

      faqItems.forEach((faqItem) => {
        faqItem.classList.remove("faq-section__item--active");
        setAnswerHeight(faqItem, false);
      });

      if (!isActive) {
        item.classList.add("faq-section__item--active");
        setAnswerHeight(item, true);
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  faqSectionScripts();
});
