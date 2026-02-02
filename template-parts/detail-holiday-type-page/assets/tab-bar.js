const convertRemToPx = (rem) => {
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize,
  );
  return rem * rootFontSize;
};

export function tabBarScripts() {
  const tabs = document.querySelectorAll(".tab");
  const tabBar = document.querySelector(".tabs"); // wrapper tab
  const tabContainer = document.querySelector(".tabs-container");
  const headerTop = document.querySelector(".header-top");
  const headerBottom = document.querySelector(".header-bottom");

  const headerTopHeight = headerTop?.clientHeight || 0;
  const tabsHeight = tabs?.clientHeight || 0;

  const lenisInstance = window.app?.lenis;

  let isMobile = window.matchMedia("(max-width: 639px)").matches;

  window.addEventListener("resize", () => {
    isMobile = window.matchMedia("(max-width: 639px)").matches;
  });
  
  tabContainer.style.top = isMobile ? 0 : `${headerTopHeight}px`;

  let lastScrollY = window.scrollY;
  const getScrollThreshold = () => (isMobile ? 0 : headerTopHeight);
  
  const toggleActiveTabContainer = (isActive) => {
      if (isActive) {
        tabContainer.classList.add("active");
      } else {
        tabContainer.classList.remove("active");  
      }
  }

  // Detect scroll direction
  window.addEventListener("scroll", () => {
    const currentScrollY = window.scrollY;
    const diff = currentScrollY - lastScrollY;

    const containerTop = tabContainer.getBoundingClientRect().top;

    if (containerTop > getScrollThreshold()) {
      tabContainer.classList.remove("in-viewport");
      toggleActiveTabContainer(true);
      lastScrollY = currentScrollY;
      return;
    } else {
      tabContainer.classList.add("in-viewport");
    }

    if (Math.abs(diff) > 0) {
      if (diff > 0) {
        // ⬇ Scroll xuống
        toggleActiveTabContainer(true);
      } else {
        // ⬆ Scroll lên
        toggleActiveTabContainer(false);
      }
      lastScrollY = currentScrollY;
    }
  });

  tabs.forEach((tab, i) => {
    tab.addEventListener("click", () => {
      const dataId = tab.getAttribute("data-id") || "";
      const el = document.getElementById(dataId);

      if (el) {
        tabs.forEach((tab) => tab.classList.remove("active"));
        tab.classList.add("active");
        

        const y = el.getBoundingClientRect().top + window.scrollY - headerTopHeight;
        
        const handleScrollStop = () => {
            if (Math.abs(window.scrollY - y) < 2) {
              const header = document.querySelector(".header");
              header.classList.add("header--mobile-hidden");
              toggleActiveTabContainer(true);
              window.removeEventListener("scroll", handleScrollStop);
            }
        };
        
        if (isMobile) {
            window.addEventListener("scroll", handleScrollStop);
        }

        if (lenisInstance?.scrollTo) {
          lenisInstance.scrollTo(y, {
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
          });
        } else {
          window.scrollTo({
            top: y,
            behavior: 'smooth',
          });
        }
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
