function sectionBannerScripts() {
  const faqSearchForm = document.querySelector('.faq-hero__search');
  const faqSection = document.querySelector('#faq-section');

  if (!faqSearchForm || !faqSection) return;

  const scrollToFaq = () => {
    const headerOffset = 5 * 16; // 5rem
    const lenis = window.app && window.app.lenis;

    if (lenis && typeof lenis.scrollTo === 'function') {
      lenis.scrollTo(faqSection, { offset: -headerOffset });
      return;
    }

    const faqTop = faqSection.getBoundingClientRect().top + window.scrollY;
    window.scrollTo({ top: faqTop - headerOffset, behavior: 'smooth' });
  };

  const handleScrollTrigger = (e) => {
    e.preventDefault();
    scrollToFaq();
  };

  faqSearchForm.addEventListener('submit', handleScrollTrigger);
  faqSearchForm.addEventListener('click', handleScrollTrigger);
}

document.addEventListener("DOMContentLoaded", () => {
  sectionBannerScripts();
});
