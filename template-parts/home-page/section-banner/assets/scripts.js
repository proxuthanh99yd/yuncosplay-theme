function sectionBannerScripts() {
  const container = document.querySelector('#banner')
  if (!container) return

  const swiperEl = container.querySelector('.banner__swiper')
  if (!swiperEl) return

  const navPrev = container.querySelector('.banner__nav-prev')
  const navNext = container.querySelector('.banner__nav-next')

  const contentItems = Array.from(container.querySelectorAll('.banner__content-item'))
  const ctaItems = Array.from(container.querySelectorAll('.banner__content-second-item[data-banner-index]'))
  const setActiveContent = (activeIndex) => {
    contentItems.forEach((item) => {
      const indexAttr = item.getAttribute('data-banner-index')
      const index = indexAttr == null ? NaN : Number(indexAttr)
      const isActive = index === activeIndex

      item.classList.toggle('is-active', isActive)
      item.setAttribute('aria-hidden', isActive ? 'false' : 'true')
    })

    ctaItems.forEach((item) => {
      const indexAttr = item.getAttribute('data-banner-index')
      const index = indexAttr == null ? NaN : Number(indexAttr)
      const isActive = index === activeIndex

      item.classList.toggle('is-active', isActive)
      item.setAttribute('aria-hidden', isActive ? 'false' : 'true')
      item.setAttribute('tabindex', isActive ? '0' : '-1')
    })
  }

  const swiperOptions = {
    slidesPerView: 1,
    speed: 1500,
    loop: true,
    parallax: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: container.querySelector('.banner__pagination'),
      clickable: true,
    },
    on: {
      init(swiper) {
        setActiveContent(swiper.realIndex)
        // Ensure autoplay actually starts on first load (some browsers/pages can init in a paused state)
        queueMicrotask(() => {
          if (swiper?.autoplay?.running === false) swiper.autoplay.start()
        })
      },
      slideChange(swiper) {
        setActiveContent(swiper.realIndex)
      },
    },
  }

  if (navPrev && navNext) {
    swiperOptions.navigation = {
      prevEl: navPrev,
      nextEl: navNext,
    }
  }

  const swiper = new Swiper(swiperEl, swiperOptions)

  // If the page was hidden on load, resume autoplay when visible again
  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState !== 'visible') return
    if (swiper?.autoplay?.running === false) swiper.autoplay.start()
  })
}

document.addEventListener("DOMContentLoaded", () => {
  sectionBannerScripts();
});
