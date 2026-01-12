class Header {
  constructor() {
    this.headerSearchEl = document.querySelector(".header-search");
    this.headerSearchInputEl = document.getElementById("header-search-input");

    this.init();
    this.events();
  }
  init() {}
  handleFocusSearchInput() {
    if (!this.headerSearchInputEl) return;
  }
  events() {
    this.handleFocusSearchInput();
  }
}
