(() => {
  const initCtaPopovers = () => {
    const OPEN_CLASS = 'is-open';
    const TRIGGER_ATTR = 'data-cta-popover-trigger';
    const CLOSE_ATTR = 'data-cta-popover-close';
    const POPOVER_ATTR = 'data-cta-popover';

    const popovers = document.querySelectorAll('.cta-popover');
    const triggers = document.querySelectorAll(`[${TRIGGER_ATTR}]`);

    if (!popovers.length || !triggers.length) return;

    let lastTriggerEl = null;

    const getPopoverByType = (type) => {
      if (!type) return null;
      return (
        document.querySelector(`.cta-popover[${POPOVER_ATTR}="${type}"]`) ||
        document.querySelector(`.cta-popover--${type}`)
      );
    };

    const focusFirstFocusable = (root) => {
      if (!root) return;

      const preferred = root.querySelector(
        'input:not([type="hidden"]):not([disabled]), textarea:not([disabled]), select:not([disabled])'
      );

      if (preferred && typeof preferred.focus === 'function') {
        preferred.focus();
        return;
      }

      const focusable = root.querySelector(
        'button:not([disabled]), a[href], [tabindex]:not([tabindex="-1"])'
      );

      if (focusable && typeof focusable.focus === 'function') {
        focusable.focus();
      }
    };

    const closeAll = ({ restoreFocus = true } = {}) => {
      popovers.forEach((p) => {
        p.classList.remove(OPEN_CLASS);
        p.setAttribute('aria-hidden', 'true');
      });

      if (restoreFocus && lastTriggerEl && typeof lastTriggerEl.focus === 'function') {
        lastTriggerEl.focus();
      }
    };

    const open = (type, triggerEl) => {
      const popover = getPopoverByType(type);
      if (!popover) return;

      lastTriggerEl = triggerEl || null;
      closeAll({ restoreFocus: false });
      popover.classList.add(OPEN_CLASS);
      popover.setAttribute('aria-hidden', 'false');

      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          focusFirstFocusable(popover);
        });
      });
    };

    const toggle = (type, triggerEl) => {
      const popover = getPopoverByType(type);
      if (!popover) return;

      const isOpen = popover.classList.contains(OPEN_CLASS);
      if (isOpen) {
        closeAll();
      } else {
        open(type, triggerEl);
      }
    };

    triggers.forEach((trigger) => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const type = trigger.getAttribute(TRIGGER_ATTR);
        if (!type) return;
        toggle(type, trigger);
      });
    });

    popovers.forEach((popover) => {
      popover.addEventListener('click', (e) => {
        const closeEl = e.target.closest(`[${CLOSE_ATTR}]`);
        if (!closeEl) return;
        e.preventDefault();
        e.stopPropagation();
        closeAll();
      });
    });

    document.addEventListener('click', (e) => {
      const clickedTrigger = e.target.closest(`[${TRIGGER_ATTR}]`);
      const clickedPopover = e.target.closest('.cta-popover');
      if (clickedTrigger || clickedPopover) return;
      closeAll();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      closeAll();
    });
  };

  const initScrollProgress = () => {
    const PROGRESS_SELECTOR = '#scroll-progress';
    const SCROLL_BTN_SELECTOR = '.cta__scroll';
    const RADIUS = 14;

    const progressEl = document.querySelector(PROGRESS_SELECTOR);
    const scrollBtn = document.querySelector(SCROLL_BTN_SELECTOR);

    if (!progressEl) return;

    const circumference = 2 * Math.PI * RADIUS;
    progressEl.style.strokeDasharray = circumference;
    progressEl.style.strokeDashoffset = circumference;

    const updateProgress = (scrollTop = window.scrollY) => {
      const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;

      if (scrollHeight <= 0) return;

      const percent = Math.min(Math.max(scrollTop / scrollHeight, 0), 1);
      progressEl.style.strokeDashoffset = circumference * (1 - percent);
    };

    function scrollToTop(e) {
      e?.preventDefault();
      e?.stopPropagation();

      const lenis = window.app?.lenis;
      if (lenis) {
        lenis.stop();
        lenis.start();
        lenis.scrollTo(0, {
          duration: 0.8,
          easing: (t) => 1 - Math.pow(1 - t, 3)
        });
        return;
      }

      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }

    const bindScroll = () => {
      const lenis = window.app?.lenis;

      if (lenis) {
        lenis.on('scroll', ({ scroll }) => {
          updateProgress(scroll);
        });

        updateProgress(lenis.scroll || 0);
      } else {
        window.addEventListener(
          'scroll',
          () => updateProgress(window.scrollY),
          { passive: true }
        );

        updateProgress();
      }
    };

    bindScroll();

    if (scrollBtn) {
      scrollBtn.addEventListener('pointerdown', scrollToTop);
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initCtaPopovers();
      initScrollProgress();
    });
  } else {
    initCtaPopovers();
    initScrollProgress();
  }
})();
