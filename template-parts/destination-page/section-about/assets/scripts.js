const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
};

export function sectionAboutScripts() {
  gsap.registerPlugin(ScrollTrigger, CustomEase);

  const customEase = CustomEase.create("custom", "0.41, -0.02, 0, 1");

  const scrollTrigger = {
    trigger: ".destination-about_cards",
    start: "top+=10% bottom",
    end: "bottom top",
  };

  const boxShadow =
    "-101px 232px 71px 0 rgba(54, 8, 10, 0.00), -64px 148px 65px 0 rgba(54, 8, 10, 0.03), -36px 83px 55px 0 rgba(54, 8, 10, 0.10), -16px 37px 40px 0 rgba(54, 8, 10, 0.17), -4px 9px 22px 0 rgba(54, 8, 10, 0.20)";

  gsap.to(".destination-about_main-card", {
    y: "-13.75rem",
    ease: customEase,
    duration: 1.2,
    scrollTrigger,
  });

  gsap.to(".destination-about_card-1", {
    y: "-16rem",
    x: "3.5rem",
    rotate: "-2.863deg",
    boxShadow,
    ease: customEase,
    duration: 1.2,
    scrollTrigger,
  });
  
  gsap.to(".destination-about_card-2", {
    y: "-11.47rem",
    x: "-3.6rem",
    rotate: "4.335deg",
    boxShadow,
    ease: customEase,
    duration: 1.2,
    scrollTrigger,
  });

  gsap.to(".destination-about_card-3", {
    y: "-21.7475rem",
    x: "-0.24rem",
    rotate: "-1.801deg",
    boxShadow,
    ease: customEase,
    duration: 1.2,
    scrollTrigger,
  });
  
  new Swiper(".destination-about_card-slides.swiper", {
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });
}
