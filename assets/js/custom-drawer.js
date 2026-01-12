// Định nghĩa các hằng số CSS dùng cho hiệu ứng chuyển động
const CONSTANTS_CSS = {
  toggleDrawerTransition: "all 0.5s ease",
  draggingDrawerTransition: "all 0.5s ease",
};

// CSS cơ bản áp dụng cho toàn bộ component
const BASE_CSS = `
  * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
  }
`;

// Định nghĩa custom element cho Drawer (ngăn kéo)
class Drawer extends HTMLElement {
  // Theo dõi thuộc tính direction để xác định hướng mở của drawer
  static observedAttributes = ["data-direction"];
  constructor() {
    super();
    // Sử dụng shadow DOM để cô lập style và markup
    this.attachShadow({
      mode: "open",
    });
  }
  connectedCallback() {
    this.init(); // Khởi tạo thuộc tính direction và trạng thái
    this.render(); // Render HTML
    this.loadStyle(); // Thêm style
    this.addEvents(); // Thêm các sự kiện (nếu có)
  }
  init() {
    // Lấy hướng mở từ thuộc tính, mặc định là bottom
    const direction = this.getAttribute("data-direction") || "bottom";
    this.direction = direction;
    this.setAttribute("data-state", "closed"); // Mặc định đóng
  }
  addEvents() {}
  render() {
    // Tạo khung chứa slot cho nội dung drawer
    this.shadowRoot.innerHTML = `
        <div class="drawer">
            <slot></slot>
        </div>
      `;
  }
  loadStyle() {
    // Thêm style cho drawer
    const style = document.createElement("style");
    style.textContent = `
        ${BASE_CSS}
        .drawer, slot {
          display: block;
          width: 100%;
          height: 100%;
        }
        .drawer[data-state="closed"] .drawer-content {
          visibility: hidden;
        }
        .drawer[data-state="open"] .drawer-content {
          visibility: visible;
        }
      `;
    this.shadowRoot.append(style);
  }
}

// Định nghĩa custom element cho nút trigger mở drawer
class DrawerTrigger extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({
      mode: "open",
    });
  }
  connectedCallback() {
    this.render();
    this.loadStyle();
    this.addEvents();
  }

  addEvents() {
    // Lắng nghe sự kiện click để mở drawer
    const drawerTriggerEl = this.shadowRoot;
    if (drawerTriggerEl) {
      drawerTriggerEl.addEventListener("click", this.handleClickEvent.bind(this));
    }
  }

  handleClickEvent() {
    // Khi click trigger, tìm phần tử cha là drawer và mở nó
    const drawerEl = this.closest("custom-drawer");
    if (!drawerEl) return;
    drawerEl.setAttribute("data-state", "open");

    // Truyền trạng thái vào custom-drawer-content
    const drawerContent = drawerEl.querySelector("custom-drawer-content");
    if (drawerContent) {
      drawerContent.setAttribute("data-state", "open");
      drawerContent.setAttribute("data-direction", drawerEl.getAttribute("data-direction") || "bottom");
    }

    if (drawerContent.shadowRoot.querySelector(".drawer-content__inner")) {
      drawerContent.shadowRoot.querySelector(".drawer-content__inner").style.transform = "";
    }

    // Khi mở drawer, ẩn scroll body
    document.body.style.overflow = "hidden";
  }

  render() {
    // Tạo slot cho nút trigger
    this.shadowRoot.innerHTML = `
        <div class="drawer-trigger">
            <slot></slot>
        </div>
      `;
  }

  loadStyle() {
    // Style cho trigger
    const style = document.createElement("style");
    style.textContent = `
        ${BASE_CSS}
        .drawer-trigger, slot {
          display: block;
          width: 100%;
          height: 100%;
        }
      `;
    this.shadowRoot.append(style);
  }
}

// Định nghĩa custom element cho nội dung drawer (có thể kéo/thả, vuốt)
class DrawerContent extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({
      mode: "open",
    });

    // Biến trạng thái kéo/thả
    this.isDragging = false;
    this.startY = 0;
    this.currentY = 0;
  }
  connectedCallback() {
    this.init(); // Lấy direction
    this.render(); // Render HTML
    this.loadStyle(); // Thêm style
    this.addEvents(); // Thêm các sự kiện kéo/thả, overlay
  }

  init() {
    // Lấy hướng mở từ custom-drawer cha
    const drawerEl = this.closest("custom-drawer");
    this.direction = drawerEl.getAttribute("data-direction") || "bottom";
    this.setAttribute("data-direction", this.direction);
  }

  addEvents() {
    // Lấy các phần tử modal (overlay) và content bên trong
    const modal = this.shadowRoot.querySelector(".drawer-content__modal");
    const content = this.shadowRoot.querySelector(".drawer-content__inner");
    if (modal) {
      // Click overlay để đóng drawer
      modal.addEventListener("click", this.handleClickModal.bind(this));
    }
    if (content) {
      // Sự kiện kéo trên máy tính
      content.addEventListener("mousedown", (event) => this.handleMouseDown(event, content));
      content.addEventListener("mousemove", (event) => this.handleMouseMove(event, content));
      content.addEventListener("mouseup", (event) => this.handleMouseUp(event, content));

      // Sự kiện vuốt trên thiết bị di động
      content.addEventListener("touchstart", (event) => this.handleTouchStart(event, content));
      content.addEventListener("touchmove", (event) => this.handleTouchMove(event, content));
      content.addEventListener("touchend", (event) => this.handleTouchEnd(event, content));

      // Để xử lý khi chuột nhả ngoài element
      document.addEventListener("mouseup", () => (this.isDragging = false));
    }
  }
  // Xử lý bắt đầu kéo bằng chuột
  handleMouseDown(event, element = null) {
    this.isDragging = true;
    // Xác định trục kéo dựa vào direction
    if (this.direction === "left" || this.direction === "right") {
      this.startCoord = event.clientX;
    } else {
      this.startCoord = event.clientY;
    }
    element.style.transition = "none";
  }

  // Xử lý kéo chuột
  handleMouseMove(event, element = null) {
    if (!this.isDragging || !element) return;
    let currentCoord;
    if (this.direction === "left" || this.direction === "right") {
      currentCoord = event.clientX;
    } else {
      currentCoord = event.clientY;
    }
    this.currentCoord = currentCoord;
    const diff = currentCoord - this.startCoord;

    // Chỉ cho phép kéo đúng hướng
    if (
      (this.direction === "bottom" && diff > 0) ||
      (this.direction === "top" && diff < 0) ||
      (this.direction === "left" && diff < 0) ||
      (this.direction === "right" && diff > 0)
    ) {
      if (this.direction === "bottom" || this.direction === "top") {
        element.style.transform = `translateY(${diff}px)`;
      } else {
        element.style.transform = `translateX(${diff}px)`;
      }
    }
  }

  // Xử lý thả chuột
  handleMouseUp(event, element = null) {
    if (!this.isDragging || !element) return;
    this.isDragging = false;
    let currentCoord;
    if (this.direction === "left" || this.direction === "right") {
      currentCoord = event.clientX;
    } else {
      currentCoord = event.clientY;
    }
    const diff = currentCoord - this.startCoord;
    element.style.transition = "";

    // Tính ngưỡng để đóng drawer (25% chiều dài hoặc tối đa 100px)
    let thresholdCloseDrawer;
    if (this.direction === "bottom" || this.direction === "top") {
      const drawerContentInnerHeight = element.clientHeight;
      thresholdCloseDrawer = Math.min(100, drawerContentInnerHeight * 0.25);
    } else {
      const drawerContentInnerWidth = element.clientWidth;
      thresholdCloseDrawer = Math.min(100, drawerContentInnerWidth * 0.25);
    }

    let shouldClose = false;
    if (
      (this.direction === "bottom" && diff > thresholdCloseDrawer) ||
      (this.direction === "top" && diff < -thresholdCloseDrawer) ||
      (this.direction === "left" && diff < -thresholdCloseDrawer) ||
      (this.direction === "right" && diff > thresholdCloseDrawer)
    ) {
      shouldClose = true;
    }

    if (shouldClose) {
      // Đóng drawer nếu kéo vượt ngưỡng
      const drawerEl = this.closest("custom-drawer");
      if (drawerEl) {
        drawerEl.setAttribute("data-state", "closed");
      }
      this.setAttribute("data-state", "closed");
      if (this.direction === "bottom") {
        element.style.transform = `translateY(100%)`;
      } else if (this.direction === "top") {
        element.style.transform = `translateY(-100%)`;
      } else if (this.direction === "left") {
        element.style.transform = `translateX(-100%)`;
      } else if (this.direction === "right") {
        element.style.transform = `translateX(100%)`;
      }
      // Khi đóng drawer, trả lại scroll body
      document.body.style.overflow = "";
    } else {
      // Nếu không vượt ngưỡng, trả lại vị trí ban đầu
      if (this.direction === "bottom" || this.direction === "top") {
        element.style.transform = `translateY(0)`;
      } else {
        element.style.transform = `translateX(0)`;
      }
    }
  }

  // Xử lý bắt đầu vuốt trên mobile
  handleTouchStart(event, element = null) {
    if (!element) return;
    if (this.direction === "left" || this.direction === "right") {
      this.startCoord = event.touches[0].clientX;
    } else {
      this.startCoord = event.touches[0].clientY;
    }
    this.isDragging = true;
    element.style.transition = "none";
  }
  // Xử lý vuốt trên mobile
  handleTouchMove(event, element) {
    if (!element || !this.isDragging) return;
    let currentCoord;
    if (this.direction === "left" || this.direction === "right") {
      currentCoord = event.touches[0].clientX;
    } else {
      currentCoord = event.touches[0].clientY;
    }
    this.currentCoord = currentCoord;
    const diff = currentCoord - this.startCoord;

    // Chỉ cho phép vuốt đúng hướng
    if (
      (this.direction === "bottom" && diff > 0) ||
      (this.direction === "top" && diff < 0) ||
      (this.direction === "left" && diff < 0) ||
      (this.direction === "right" && diff > 0)
    ) {
      if (this.direction === "bottom" || this.direction === "top") {
        element.style.transform = `translateY(${diff}px)`;
      } else {
        element.style.transform = `translateX(${diff}px)`;
      }
    }
  }
  // Xử lý kết thúc vuốt trên mobile
  handleTouchEnd(event, element) {
    let currentCoord;
    if (this.direction === "left" || this.direction === "right") {
      currentCoord = this.currentCoord;
    } else {
      currentCoord = this.currentCoord;
    }
    const diff = currentCoord - this.startCoord;
    element.style.transition = "";

    if (!this.isDragging || !element) return;
    this.isDragging = false;

    // Tính ngưỡng để đóng drawer (25% chiều dài hoặc tối đa 100px)
    let thresholdCloseDrawer;
    if (this.direction === "bottom" || this.direction === "top") {
      const drawerContentInnerHeight = element.clientHeight;
      thresholdCloseDrawer = Math.min(100, drawerContentInnerHeight * 0.25);
    } else {
      const drawerContentInnerWidth = element.clientWidth;
      thresholdCloseDrawer = Math.min(100, drawerContentInnerWidth * 0.25);
    }

    let shouldClose = false;
    if (
      (this.direction === "bottom" && diff > thresholdCloseDrawer) ||
      (this.direction === "top" && diff < -thresholdCloseDrawer) ||
      (this.direction === "left" && diff < -thresholdCloseDrawer) ||
      (this.direction === "right" && diff > thresholdCloseDrawer)
    ) {
      shouldClose = true;
    }

    if (shouldClose) {
      // Đóng drawer nếu vuốt vượt ngưỡng
      const drawerEl = this.closest("custom-drawer");
      if (drawerEl) {
        drawerEl.setAttribute("data-state", "closed");
      }
      this.setAttribute("data-state", "closed");
      if (this.direction === "bottom") {
        element.style.transform = `translateY(100%)`;
      } else if (this.direction === "top") {
        element.style.transform = `translateY(-100%)`;
      } else if (this.direction === "left") {
        element.style.transform = `translateX(-100%)`;
      } else if (this.direction === "right") {
        element.style.transform = `translateX(100%)`;
      }
      // Khi đóng drawer, trả lại scroll body
      document.body.style.overflow = "";
    } else {
      // Nếu không vượt ngưỡng, trả lại vị trí ban đầu
      if (this.direction === "bottom" || this.direction === "top") {
        element.style.transform = `translateY(0)`;
      } else {
        element.style.transform = `translateX(0)`;
      }
    }
  }

  // Xử lý click vào overlay để đóng drawer
  handleClickModal() {
    // Tìm phần tử cha là custom-drawer
    const drawerEl = this.closest("custom-drawer");
    const content = drawerEl.querySelector("custom-drawer-content");
    const contentInner = content.shadowRoot.querySelector(".drawer-content__inner");
    if (!drawerEl || !contentInner) return;
    contentInner.style.transform = "";
    // Đóng drawer
    drawerEl.setAttribute("data-state", "closed");
    // Đồng thời cập nhật trạng thái trên chính DrawerContent
    this.setAttribute("data-state", "closed");
    // Khi đóng drawer, trả lại scroll body
    document.body.style.overflow = "";
  }

  handleSwiperTouchBar() {}

  render() {
    // Render HTML cho nội dung drawer, gồm overlay, touch-bar và slot nội dung
    this.shadowRoot.innerHTML = `
        <div class="drawer-content">
          <div class="drawer-content__modal"></div>
          <div class="drawer-content__inner">
            <div class="drawer-content__touch-bar"></div>
            <slot></slot>
          </div>
        </div>
      `;
  }
  loadStyle() {
    // Thêm style cho drawer content, overlay, hiệu ứng chuyển động và các hướng
    const style = document.createElement("style");
    style.textContent = `
        ${BASE_CSS}
        :host {
          visibility: hidden;
        }
        :host([data-state="open"]) .drawer-content {
          visibility: visible;
        }
        .drawer-content__touch-bar {
          display: none;
          justify-content: center;
          padding: 0.5625rem 0 1.375rem;
        }
        :host([data-direction="bottom"]) .drawer-content__touch-bar {
          display: flex;
        }
        .drawer-content__touch-bar::before {
          content: "";
          display: block;
          width: 7.74188rem;
          height: 0.40969rem;
          border-radius: 0.25rem;
          background: #cdd6f9;
        }
        .drawer-content {
          position: fixed;
          bottom: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: 50;
        }
        .drawer-content__modal {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.25);
          z-index: 0;
          opacity: 0;
          visibility: hidden;
          transition: ${CONSTANTS_CSS.toggleDrawerTransition};
        }
        :host([data-state="open"]) .drawer-content__modal {
          opacity: 1;
          visibility: visible;
        }
        .drawer-content__inner {
          position: absolute;
          background: #fff;
          z-index: 1;
          border-radius: 1.5rem 1.5rem 0rem 0rem;
          box-shadow: 0px -2px 47.3px 0px rgba(0, 0, 0, 0.14);
          transition: ${CONSTANTS_CSS.toggleDrawerTransition};
        }

        .drawer-content__inner {
          position: absolute;
          width: 100%;
          height: auto;
          background: #fff;
          z-index: 1;
          transition: ${CONSTANTS_CSS.toggleDrawerTransition};
        }

        /* Các hướng */
        :host([data-direction="bottom"]) .drawer-content__inner {
          bottom: 0;
          right: 0;
          left: 0;
          max-height: 80vh;
          transform: translateY(100%);
        }
        :host([data-direction="top"]) .drawer-content__inner {
          top: 0;
          right: 0;
          left: 0;
          max-height: 80vh;
          transform: translateY(-100%);
        }
        :host([data-direction="left"]) .drawer-content__inner {
          top: 0;
          bottom: 0;
          left: 0;
          max-width: 25vw;
          transform: translateX(-100%);
        }
        :host([data-direction="right"]) .drawer-content__inner {
          top: 0;
          bottom: 0;
          right: 0;
          max-width: 25vw;
          transform: translateX(100%);
        }

        /* Khi mở */
        :host([data-state="open"][data-direction="bottom"]) .drawer-content__inner {
          transform: translateY(0%);
        }
        :host([data-state="open"][data-direction="top"]) .drawer-content__inner {
          transform: translateY(0%);
        }
        :host([data-state="open"][data-direction="left"]) .drawer-content__inner {
          transform: translateX(0%);
        }
        :host([data-state="open"][data-direction="right"]) .drawer-content__inner {
          transform: translateX(0%);
        }
        @media (max-width: 639.98px) {
          :host([data-direction="left"]) .drawer-content__inner {
            max-width: 100%;
          }
          :host([data-direction="right"]) .drawer-content__inner {
            max-width: 100%;
          }
        }
      `;
    this.shadowRoot.append(style);
  }
}
// Định nghĩa custom element cho nút đóng drawer
class DrawerClose extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({
      mode: "open",
    });
  }
  connectedCallback() {
    this.render();
    this.loadStyle();
    this.addEvents();
  }

  addEvents() {
    // Lắng nghe click để đóng drawer
    const closeEl = this.shadowRoot.querySelector(".drawer-close");
    if (closeEl) {
      closeEl.addEventListener("click", this.handleClickClose.bind(this));
    }
  }

  handleClickClose(event) {
	  event.preventDefault();
	  event.stopPropagation();
    // Tìm drawer cha và đóng nó
    const drawerEl = this.closest("custom-drawer");
    if (!drawerEl) return;

    // Đóng drawer
    drawerEl.setAttribute("data-state", "closed");

    const drawerContent = drawerEl.querySelector("custom-drawer-content");
    if (drawerContent) {
      drawerContent.setAttribute("data-state", "closed");

      // ✅ Reset transform để animation hoạt động
      const inner = drawerContent.shadowRoot.querySelector(".drawer-content__inner");
      if (inner) {
        inner.style.transition = CONSTANTS_CSS.toggleDrawerTransition;
        inner.style.transform = ""; // <-- RESET transform
      }
    }
    // Khi đóng drawer, trả lại scroll body
    document.body.style.overflow = "";
  }

  render() {
    // Tạo slot cho nút đóng
    this.shadowRoot.innerHTML = `
        <div class="drawer-close">
            <slot></slot>
        </div>
      `;
  }
  loadStyle() {
    // Style cho nút đóng
    const style = document.createElement("style");
    style.textContent = `
        ${BASE_CSS}
        :host {
          display: block;
        }
        .drawer-close {
          cursor: pointer;
        }
      `;
    this.shadowRoot.append(style);
  }
}

// Đăng ký các custom element
customElements.define("custom-drawer", Drawer);
customElements.define("custom-drawer-trigger", DrawerTrigger);
customElements.define("custom-drawer-content", DrawerContent);
customElements.define("custom-drawer-close", DrawerClose);
