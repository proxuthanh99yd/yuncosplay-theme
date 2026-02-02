const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
};

export function tabBarScripts() {
  const tabs = document.querySelectorAll(".destination-tab");
  const tabBar = document.querySelector(".destination-tabs"); // wrapper tab

  const OFFSET = convertRemToPx(2.4375);

  const isMobile = window.matchMedia("(max-width: 639px)").matches;

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const dataId = tab.getAttribute("data-id") || "";
      const el = document.getElementById(dataId);

      if (el) {
        tabs.forEach((tab) => tab.classList.remove("active"));
        tab.classList.add("active");

        const y = el.getBoundingClientRect().top + window.pageYOffset - OFFSET;

        window.scrollTo({
          top: y,
          behavior: "smooth",
        });
      }
    });
  });

  const sections = [...tabs]
    .map((tab) => document.getElementById(tab.dataset.id))
    .filter(Boolean);

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const id = entry.target.id;

          tabs.forEach((tab) => {
            const isActive = tab.dataset.id === id;
            tab.classList.toggle("active", isActive);

            if (isActive && isMobile) {
              const tabLeft =
                tab.offsetLeft - (tabBar.clientWidth / 2 - tab.clientWidth / 2);

              tabBar.scrollTo({
                left: tabLeft,
                behavior: "smooth",
              });
            }
          });
        }
      });
    },
    {
      threshold: 0.5, // section chiếm 50% màn hình thì active
    },
  );

  sections.forEach((section) => observer.observe(section));
}