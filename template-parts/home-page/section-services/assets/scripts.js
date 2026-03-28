export function sectionServicesScripts() {
  // Tìm section, không có thì bỏ
  const container = document.querySelector('.home-services')
  if (!container) return

  // Thu thập tab (list trái) và panel (media phải)
  const tabs = Array.from(container.querySelectorAll('.home-services__list-item'))
  const panels = Array.from(container.querySelectorAll('.home-services__media'))
  const accordions = Array.from(container.querySelectorAll('.home-services__accordion'))

  // Kiểm tra breakpoint mobile
  const isMobile = () => window.matchMedia('(max-width: 639px)').matches

  // Áp chiều cao/opacity accordion để animate mở/đóng
  const applyAccordionHeights = () => {
    if (!isMobile()) {
      accordions.forEach(acc => {
        acc.style.maxHeight = ''
        acc.style.opacity = ''
        acc.style.visibility = ''
      })
      return
    }

    accordions.forEach((acc, i) => {
      const isActive = acc.classList.contains('active')
      acc.style.maxHeight = isActive ? `${acc.scrollHeight}px` : '0px'
      acc.style.opacity = isActive ? '1' : '0'
      acc.style.visibility = isActive ? 'visible' : 'hidden'
    })
  }

  // Đồng bộ accordion: chỉ mở mục trùng index khi ở mobile
  const syncAccordion = targetIndex => {
    accordions.forEach((acc, i) => acc.classList.toggle('active', i === targetIndex && isMobile()))
    applyAccordionHeights()
  }

  // Khi đổi kích thước: tắt accordion trên desktop, giữ mục hiện tại trên mobile
  const handleResize = () => {
    if (!isMobile()) {
      accordions.forEach(acc => acc.classList.remove('active'))
    } else {
      syncAccordion(current)
    }
    applyAccordionHeights()
  }

  // Xác định tab/panel active ban đầu
  let current = Math.max(
    0,
    tabs.findIndex(t => t.classList.contains('active')),
    panels.findIndex(p => p.classList.contains('active')),
  )

  syncAccordion(current)

  // Khoá tránh click khi đang animate
  let isAnimating = false

  // Dọn class trạng thái sau khi animate xong
  const cleanup = (leavingPanel, enteringPanel) => {
    leavingPanel.classList.remove('is-leaving', 'is-up', 'is-down')
    enteringPanel.classList.remove('is-up', 'is-down')
    isAnimating = false
  }

  // Lắng nghe click trên danh sách dịch vụ
  container.addEventListener('click', e => {
    const tab = e.target.closest('.home-services__list-item')
    if (!tab) return

    if (isAnimating) return

    const index = tabs.indexOf(tab)
    if (index === -1) return

    // Mobile: cho phép click lại để đóng accordion (đóng tất cả)
    if (index === current) {
      if (isMobile()) {
        const acc = accordions[index]
        const willOpen = acc && !acc.classList.contains('active')
        tabs[current]?.classList.toggle('active', !!willOpen)
        syncAccordion(willOpen ? index : -1)
        if (!willOpen) current = index
      }
      return
    }

    // Xác định hướng di chuyển (xuống / lên)
    const direction = index > current ? 'is-down' : 'is-up'

    const currentPanel = panels[current]
    const nextPanel = panels[index]
    const nextDecor = nextPanel?.querySelector('.home-services__decor-text')
    const nextContent = nextPanel?.querySelector('.home-services__content')

    if (!currentPanel || !nextPanel) return

    isAnimating = true

    // Bỏ class hướng cũ trước khi set hướng mới
    currentPanel.classList.remove('is-up', 'is-down')
    nextPanel.classList.remove('is-up', 'is-down', 'is-leaving')

    // set direction
    currentPanel.classList.add('is-leaving', direction)
    nextPanel.classList.remove('active')
    nextPanel.classList.add(direction)

    // Force browser to apply directional start state before activating new panel
    if (nextDecor || nextContent) {
      const prevDecorTransition = nextDecor?.style.transition
      const prevContentTransition = nextContent?.style.transition

      // Đặt trạng thái xuất phát cho decor theo hướng
      if (nextDecor) {
        const startTransform = direction === 'is-down'
          ? 'rotate(-180deg) translateY(-100%)'
          : 'rotate(-180deg) translateY(100%)'

        nextDecor.style.transition = 'none'
        nextDecor.style.transform = startTransform
        nextDecor.style.opacity = '0'
      }

      // Đặt trạng thái xuất phát cho content: dưới + mờ
      if (nextContent) {
        nextContent.style.transition = 'none'
        nextContent.style.transform = 'translateY(100%)'
        nextContent.style.opacity = '0'
      }

      nextPanel.offsetWidth

      // Khôi phục transition rồi kích hoạt panel mới
      requestAnimationFrame(() => {
        if (nextDecor) {
          nextDecor.style.transition = prevDecorTransition
          nextDecor.style.transform = ''
          nextDecor.style.opacity = ''
        }

        if (nextContent) {
          nextContent.style.transition = prevContentTransition
          nextContent.style.transform = ''
          nextContent.style.opacity = ''
        }

        requestAnimationFrame(() => {
          nextPanel.classList.add('active')
        })
      })
    } else {
      nextPanel.offsetWidth
      requestAnimationFrame(() => {
        nextPanel.classList.add('active')
      })
    }

    // Cập nhật class active cho tab/panel
    currentPanel.classList.remove('active')
    tab.classList.add('active')
    tabs[current].classList.remove('active')

    syncAccordion(index)

    const onDone = ev => {
      if (ev && ev.target !== currentPanel) return
      cleanup(currentPanel, nextPanel)
    }

    // Kết thúc animation theo event hoặc fallback timeout
    currentPanel.addEventListener('transitionend', onDone, { once: true })
    currentPanel.addEventListener('animationend', onDone, { once: true })
    window.setTimeout(() => cleanup(currentPanel, nextPanel), 600)

    current = index
  })

  // Đồng bộ khi đổi kích thước (ẩn accordion ở desktop, mở đúng mục ở mobile)
  window.addEventListener('resize', handleResize)
  handleResize()
}