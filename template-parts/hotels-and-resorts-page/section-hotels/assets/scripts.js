export function hotelsScripts() {
  const dropdownItems = document.querySelectorAll('.hotels__list-filter-item--dropdown');
  const filterPopup = document.getElementById('hotelsFilterPopup');
  const filterPopupOverlay = filterPopup?.querySelector('.hotels__filter-popup-overlay');
  const filterPopupClose = filterPopup?.querySelector('.hotels__filter-popup-close');
  const filterPopupTitle = filterPopup?.querySelector('.hotels__filter-popup-title');
  const filterPopupBody = filterPopup?.querySelector('.hotels__filter-popup-body');
  
  // Check if mobile
  const isMobile = () => window.innerWidth <= 639.98;
  
  // Lock/unlock scroll functions
  const lockScroll = () => {
    const lenisInstance = window.app?.lenis;
    if (lenisInstance && typeof lenisInstance.stop === 'function') {
      lenisInstance.stop();
    }
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
    document.body.addEventListener('touchmove', preventScroll, { passive: false });
    document.documentElement.addEventListener('touchmove', preventScroll, { passive: false });
  };

  const unlockScroll = () => {
    const lenisInstance = window.app?.lenis;
    if (lenisInstance && typeof lenisInstance.start === 'function') {
      lenisInstance.start();
    }
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
    document.body.removeEventListener('touchmove', preventScroll);
    document.documentElement.removeEventListener('touchmove', preventScroll);
  };

  const preventScroll = (e) => {
    const popupContent = filterPopup?.querySelector('.hotels__filter-popup-body');
    if (popupContent && popupContent.contains(e.target)) {
      return;
    }
    e.preventDefault();
  };

  // Store reference to current open dropdown and item
  let currentOpenDropdown = null;
  let currentOpenItem = null;

  // Sync checkbox state between popup and original dropdown
  const syncCheckboxState = (popupCheckbox, originalCheckbox) => {
    if (originalCheckbox) {
      originalCheckbox.checked = popupCheckbox.checked;
      // Trigger change event on original checkbox to trigger filter.js
      originalCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
    }
  };

  // Open mobile popup
  const openMobilePopup = (item) => {
    if (!filterPopup || !filterPopupTitle || !filterPopupBody) return;
    
    const header = item.querySelector('.hotels__list-filter-item-header');
    const titleText = header?.querySelector('p')?.textContent || '';
    const dropdown = item.querySelector('.hotels__list-filter-dropdown');
    
    if (!dropdown) return;
    
    // Set title
    filterPopupTitle.textContent = titleText;
    
    // Store references
    currentOpenDropdown = dropdown;
    currentOpenItem = item;
    
    // Copy dropdown content to popup using innerHTML to avoid CSS issues
    filterPopupBody.innerHTML = dropdown.innerHTML;
    
    // Ensure the wrapper (if any) is visible
    const wrapper = filterPopupBody.querySelector('.hotels__list-filter-dropdown');
    if (wrapper) {
      wrapper.style.cssText = 'display: block !important; position: static !important; opacity: 1 !important; visibility: visible !important; transform: none !important; padding: 0 !important; margin: 0 !important; box-shadow: none !important;';
    }
    
    // Check if this is Cities popup - apply visibility logic
    const isCitiesPopup = item.hasAttribute('data-filter-cities');
    if (isCitiesPopup) {
      // For Cities popup, apply visibility logic immediately (don't clear styles first)
      // This will properly hide/show cities based on selected countries
      applyCitiesVisibilityInPopup(filterPopupBody);
      
      // Add search functionality for Cities popup
      const popupSearchInput = filterPopupBody.querySelector('.hotels__list-filter-search-input');
      if (popupSearchInput) {
        // Sync search value from original input
        const originalSearchInput = dropdown.querySelector('.hotels__list-filter-search-input');
        if (originalSearchInput) {
          popupSearchInput.value = originalSearchInput.value;
        }
        
        // Add search event listener
        const searchHandler = function(e) {
          // Sync to original search input first
          if (originalSearchInput) {
            originalSearchInput.value = e.target.value;
          }
          // Re-apply visibility logic which includes search filter
          applyCitiesVisibilityInPopup(filterPopupBody);
        };
        
        popupSearchInput.removeEventListener('input', searchHandler);
        popupSearchInput.addEventListener('input', searchHandler);
      }
    } else {
      // For other popups (Country, Rating), ensure all elements are visible
      const allClonedElements = filterPopupBody.querySelectorAll('*');
      allClonedElements.forEach(el => {
        // Don't override display if it's already set by inline style
        if (!el.hasAttribute('style') || !el.style.display) {
          el.style.display = '';
        }
        el.style.visibility = '';
        el.style.opacity = '';
        el.style.position = '';
        el.style.transform = '';
      });
    }
    
    // Sync initial checkbox states
    const popupCheckboxes = filterPopupBody.querySelectorAll('input[type="checkbox"]');
    const originalCheckboxes = dropdown.querySelectorAll('input[type="checkbox"]');
    
    popupCheckboxes.forEach(popupCheckbox => {
      const originalCheckbox = Array.from(originalCheckboxes).find(
        cb => cb.value === popupCheckbox.value && cb.name === popupCheckbox.name
      );
      if (originalCheckbox) {
        popupCheckbox.checked = originalCheckbox.checked;
      }
    });
    
    // Add event listener to sync checkbox changes
    const changeHandler = function(e) {
      if (e.target.type === 'checkbox' && currentOpenDropdown) {
        const originalCheckbox = currentOpenDropdown.querySelector(`input[type="checkbox"][value="${e.target.value}"][name="${e.target.name}"]`);
        syncCheckboxState(e.target, originalCheckbox);
        
        // If country checkbox changed, update cities visibility in popup
        if (e.target.name === 'indochina' && filterPopup.classList.contains('is-open')) {
          const citiesItem = document.querySelector('[data-filter-cities]');
          if (citiesItem && currentOpenItem === citiesItem) {
            applyCitiesVisibilityInPopup(filterPopupBody);
          }
        }
        
        // Close popup after checkbox is checked (with small delay to ensure sync completes)
        setTimeout(() => {
          if (filterPopup.classList.contains('is-open')) {
            closeMobilePopup();
          }
        }, 100);
      }
    };
    
    // Remove previous listener if exists and add new one
    filterPopupBody.removeEventListener('change', changeHandler);
    filterPopupBody.addEventListener('change', changeHandler);
    
    // Open popup
    filterPopup.classList.add('is-open');
    lockScroll();
  };

  // Function to apply cities visibility logic in popup
  const applyCitiesVisibilityInPopup = (popupBody) => {
    // Get selected country parent IDs from the original country box
    const countryBox = document.querySelector('[data-filter-country]');
    if (!countryBox) return;
    
    const selectedCountryCheckboxes = countryBox.querySelectorAll('input[name="indochina"]:checked');
    const selectedParentIds = Array.from(selectedCountryCheckboxes).map(cb => {
      const parentId = cb.getAttribute('data-parent-term-id');
      return parentId ? String(parentId) : null;
    }).filter(Boolean);
    
    // Get cities in popup
    const cityLabels = popupBody.querySelectorAll('.hotels__list-filter-dropdown-item[data-parent-id]');
    const emptyMessage = popupBody.querySelector('.hotels__list-filter-empty-message');
    
    // Get search query from popup search input
    const popupSearchInput = popupBody.querySelector('.hotels__list-filter-search-input');
    const searchQuery = popupSearchInput ? popupSearchInput.value.trim().toLowerCase() : '';
    
    if (!selectedParentIds.length) {
      // No country selected - hide all cities and show empty message
      cityLabels.forEach(lb => {
        // Use !important to override any existing styles
        lb.style.setProperty('display', 'none', 'important');
        const inp = lb.querySelector('input[name="cities"]');
        if (inp) inp.checked = false;
      });
      if (emptyMessage) {
        emptyMessage.style.setProperty('display', 'block', 'important');
        emptyMessage.classList.add('is-visible');
      }
      return;
    }
    
    // Country selected - show matching cities (also apply search filter if any)
    if (emptyMessage) {
      emptyMessage.style.setProperty('display', 'none', 'important');
      emptyMessage.classList.remove('is-visible');
    }
    
    let visibleCount = 0;
    cityLabels.forEach(lb => {
      const parentId = lb.getAttribute('data-parent-id');
      const isCountryMatch = selectedParentIds.includes(String(parentId));
      
      if (!isCountryMatch) {
        lb.style.setProperty('display', 'none', 'important');
        const inp = lb.querySelector('input[name="cities"]');
        if (inp) inp.checked = false;
        return;
      }
      
      // Apply search filter if there's a search query
      if (searchQuery) {
        const text = (lb.textContent || "").trim().toLowerCase();
        const matchesSearch = text.includes(searchQuery);
        
        if (matchesSearch) {
          lb.style.setProperty('display', 'flex', 'important');
          visibleCount++;
        } else {
          lb.style.setProperty('display', 'none', 'important');
        }
      } else {
        // No search query, show all cities of selected country
        lb.style.setProperty('display', 'flex', 'important');
        visibleCount++;
      }
    });
    
    // If no cities visible, show empty message
    if (visibleCount === 0 && emptyMessage) {
      emptyMessage.style.setProperty('display', 'block', 'important');
      emptyMessage.classList.add('is-visible');
    }
  };

  // Function to apply city search filter in popup
  const applyCitySearchInPopup = (popupBody, searchQuery) => {
    const q = (searchQuery || "").trim().toLowerCase();
    const cityLabels = popupBody.querySelectorAll('.hotels__list-filter-dropdown-item[data-parent-id]');
    const emptyMessage = popupBody.querySelector('.hotels__list-filter-empty-message');
    
    // Get selected country parent IDs
    const countryBox = document.querySelector('[data-filter-country]');
    if (!countryBox) return;
    
    const selectedCountryCheckboxes = countryBox.querySelectorAll('input[name="indochina"]:checked');
    const selectedParentIds = Array.from(selectedCountryCheckboxes).map(cb => {
      const parentId = cb.getAttribute('data-parent-term-id');
      return parentId ? String(parentId) : null;
    }).filter(Boolean);
    
    let visibleCount = 0;
    
    cityLabels.forEach(lb => {
      const parentId = lb.getAttribute('data-parent-id');
      const isCountryMatch = selectedParentIds.length === 0 || selectedParentIds.includes(String(parentId));
      
      // Only search in cities that match selected country
      if (!isCountryMatch) {
        lb.style.setProperty('display', 'none', 'important');
        return;
      }
      
      // Apply search filter
      const text = (lb.textContent || "").trim().toLowerCase();
      const matchesSearch = q === '' || text.includes(q);
      
      if (matchesSearch) {
        lb.style.setProperty('display', 'flex', 'important');
        visibleCount++;
      } else {
        lb.style.setProperty('display', 'none', 'important');
      }
    });
    
    // Show/hide empty message
    if (emptyMessage) {
      if (visibleCount === 0) {
        emptyMessage.style.setProperty('display', 'block', 'important');
        emptyMessage.classList.add('is-visible');
      } else {
        emptyMessage.style.setProperty('display', 'none', 'important');
        emptyMessage.classList.remove('is-visible');
      }
    }
  };

  // Close mobile popup
  const closeMobilePopup = () => {
    if (!filterPopup || !filterPopupBody) return;
    
    // Sync final checkbox states back to original
    if (currentOpenDropdown) {
      const popupCheckboxes = filterPopupBody.querySelectorAll('input[type="checkbox"]');
      popupCheckboxes.forEach(popupCheckbox => {
        const originalCheckbox = currentOpenDropdown.querySelector(`input[type="checkbox"][value="${popupCheckbox.value}"]`);
        if (originalCheckbox && originalCheckbox.checked !== popupCheckbox.checked) {
          syncCheckboxState(popupCheckbox, originalCheckbox);
        }
      });
    }
    
    // Clear references
    currentOpenDropdown = null;
    currentOpenItem = null;
    
    // Clear popup content
    filterPopupBody.innerHTML = '';
    
    filterPopup.classList.remove('is-open');
    unlockScroll();
  };
  
  dropdownItems.forEach(item => {
    const header = item.querySelector('.hotels__list-filter-item-header');
    
    if (header) {
      header.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // On mobile, open popup instead of dropdown
        if (isMobile() && filterPopup) {
          openMobilePopup(item);
        } else {
          // Desktop: toggle dropdown
          item.classList.toggle('active');
          
          // Close other dropdowns
          dropdownItems.forEach(otherItem => {
            if (otherItem !== item) {
              otherItem.classList.remove('active');
            }
          });
        }
      });
    }
  });
  
  // Close dropdowns when clicking outside (desktop only)
  document.addEventListener('click', function(e) {
    if (isMobile()) return;
    
    if (!e.target.closest('.hotels__list-filter-item--dropdown')) {
      dropdownItems.forEach(item => {
        item.classList.remove('active');
      });
    }
  });
  
  // Prevent dropdown from closing when clicking inside it (desktop only)
  const dropdowns = document.querySelectorAll('.hotels__list-filter-dropdown');
  dropdowns.forEach(dropdown => {
    dropdown.addEventListener('click', function(e) {
      if (isMobile()) return;
      e.stopPropagation();
    });
  });

  // Mobile popup close handlers
  if (filterPopupOverlay) {
    filterPopupOverlay.addEventListener('click', closeMobilePopup);
  }

  if (filterPopupClose) {
    filterPopupClose.addEventListener('click', closeMobilePopup);
  }

  // Close popup on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && filterPopup?.classList.contains('is-open')) {
      closeMobilePopup();
    }
  });

  // Listen for country changes to update cities in popup if Cities popup is open
  const countryBox = document.querySelector('[data-filter-country]');
  if (countryBox) {
    countryBox.addEventListener('change', function(e) {
      if (e.target.type === 'checkbox' && e.target.name === 'indochina') {
        // If Cities popup is currently open, update cities visibility
        if (filterPopup?.classList.contains('is-open') && currentOpenItem?.hasAttribute('data-filter-cities')) {
          applyCitiesVisibilityInPopup(filterPopupBody);
        }
      }
    });
  }

  // Handle window resize - close popup if switching to desktop
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      if (!isMobile() && filterPopup?.classList.contains('is-open')) {
        closeMobilePopup();
      }
    }, 100);
  });

  // Filter child countries based on selected parent countries
  const parentCountryCheckboxes = document.querySelectorAll('input[name="indochina"][data-parent-term-id]');
  const childCountryItems = document.querySelectorAll('.hotels__list-filter-dropdown-item[data-parent-id]');
  const citiesSearchInput = document.querySelector('.hotels__list-filter-search-input');
  const citiesEmptyMessage = document.getElementById('citiesEmptyMessage');
  
  function updateChildCountriesVisibility() {
    const selectedParentIds = [];
    
    // Lấy danh sách các parent term_id đã được chọn
    parentCountryCheckboxes.forEach(checkbox => {
      if (checkbox.checked) {
        const parentTermId = parseInt(checkbox.getAttribute('data-parent-term-id'));
        selectedParentIds.push(parentTermId);
      }
    });
    
    // Lấy search term hiện tại (nếu có)
    const searchTerm = citiesSearchInput ? citiesSearchInput.value.toLowerCase().trim() : '';
    
    let visibleCount = 0;
    const visibleItems = [];
    
    // Hiển thị/ẩn child countries dựa trên parent đã chọn và search term
    childCountryItems.forEach(item => {
      const parentId = parseInt(item.getAttribute('data-parent-id'));
      const childCheckbox = item.querySelector('input[name="cities"]');
      const label = item.querySelector('.hotels__list-filter-label');
      const labelText = label ? label.textContent.toLowerCase() : '';
      
      // Xóa class is-last-visible trước
      item.classList.remove('is-last-visible');
      
      if (selectedParentIds.includes(parentId)) {
        // Nếu parent được chọn, kiểm tra search term
        if (searchTerm === '' || labelText.includes(searchTerm)) {
          item.style.display = 'flex';
          visibleCount++;
          visibleItems.push(item);
        } else {
          item.style.display = 'none';
        }
      } else {
        // Ẩn child country và bỏ chọn nếu đã chọn
        item.style.display = 'none';
        if (childCheckbox && childCheckbox.checked) {
          childCheckbox.checked = false;
        }
      }
    });
    
    // Thêm class is-last-visible cho item cuối cùng đang hiển thị
    if (visibleItems.length > 0) {
      visibleItems[visibleItems.length - 1].classList.add('is-last-visible');
    }
    
    // Hiển thị/ẩn empty message
    if (citiesEmptyMessage) {
      if (visibleCount === 0) {
        citiesEmptyMessage.classList.add('is-visible');
      } else {
        citiesEmptyMessage.classList.remove('is-visible');
      }
    }
  }
  
  // Lắng nghe sự kiện khi checkbox parent countries thay đổi
  parentCountryCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateChildCountriesVisibility);
  });
  
  // Khởi tạo trạng thái ban đầu
  updateChildCountriesVisibility();

  // Search functionality for Cities & Places dropdown
  // Sử dụng lại hàm updateChildCountriesVisibility để đảm bảo tính nhất quán
  if (citiesSearchInput) {
    citiesSearchInput.addEventListener('input', updateChildCountriesVisibility);
  }

  // Fix scroll trong dropdown khi có Lenis
  const dropdownContents = document.querySelectorAll('.hotels__list-filter-dropdown-content');
  dropdownContents.forEach(content => {
    // Stop Lenis từ việc xử lý wheel events trong dropdown
    content.addEventListener('wheel', function(e) {
      // Cho phép scroll native trong dropdown
      const isScrolling = content.scrollHeight > content.clientHeight;
      const isAtTop = content.scrollTop === 0;
      const isAtBottom = content.scrollTop + content.clientHeight >= content.scrollHeight - 1;
      
      // Nếu đang scroll trong dropdown và chưa đến giới hạn, stop propagation để Lenis không xử lý
      if (isScrolling && !(isAtTop && e.deltaY < 0) && !(isAtBottom && e.deltaY > 0)) {
        e.stopPropagation();
        e.stopImmediatePropagation();
      }
    }, { passive: false, capture: true });

    // Đảm bảo scroll hoạt động bình thường trên mobile
    content.addEventListener('touchmove', function(e) {
      e.stopPropagation();
    }, { passive: false });
  });

  // Rating Popup functionality
  const ratingPopup = document.getElementById('hotelsRatingPopup');
  const ratingPopupIcon = document.querySelector('.hotels__list-filter-item-icon');
  const ratingPopupClose = document.querySelector('.hotels__rating-popup-close');
  const ratingPopupOverlay = document.querySelector('.hotels__rating-popup-overlay');

  if (ratingPopup && ratingPopupIcon) {
    // Lock scroll function
    const lockScroll = () => {
      // Stop Lenis smooth scroll nếu có
      const lenisInstance = window.app?.lenis;
      if (lenisInstance && typeof lenisInstance.stop === 'function') {
        lenisInstance.stop();
      }

      // Chặn scroll body
      document.body.style.overflow = 'hidden';
      document.documentElement.style.overflow = 'hidden';

      // Prevent scroll on touch devices
      document.body.addEventListener('touchmove', preventScroll, { passive: false });
      document.documentElement.addEventListener('touchmove', preventScroll, { passive: false });
    };

    // Unlock scroll function
    const unlockScroll = () => {
      // Start lại Lenis smooth scroll nếu có
      const lenisInstance = window.app?.lenis;
      if (lenisInstance && typeof lenisInstance.start === 'function') {
        lenisInstance.start();
      }

      // Khôi phục scroll body
      document.body.style.overflow = '';
      document.documentElement.style.overflow = '';

      // Remove touchmove prevention
      document.body.removeEventListener('touchmove', preventScroll);
      document.documentElement.removeEventListener('touchmove', preventScroll);
    };

    // Prevent scroll function for touch devices
    const preventScroll = (e) => {
      // Allow scrolling inside popup content
      const popupContent = ratingPopup.querySelector('.hotels__rating-popup-content');
      if (popupContent && popupContent.contains(e.target)) {
        return;
      }
      e.preventDefault();
    };

    // Open popup when clicking on info icon
    ratingPopupIcon.addEventListener('click', function(e) {
      e.stopPropagation(); // Prevent dropdown from opening
      ratingPopup.classList.add('is-open');
      lockScroll();
    });

    // Close popup function
    const closeRatingPopup = () => {
      ratingPopup.classList.remove('is-open');
      unlockScroll();
    };

    // Close popup when clicking close button
    if (ratingPopupClose) {
      ratingPopupClose.addEventListener('click', closeRatingPopup);
    }

    // Close popup when clicking overlay
    if (ratingPopupOverlay) {
      ratingPopupOverlay.addEventListener('click', closeRatingPopup);
    }

    // Close popup when pressing ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && ratingPopup.classList.contains('is-open')) {
        closeRatingPopup();
      }
    });
  }
}
