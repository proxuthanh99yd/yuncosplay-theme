// Define the custom-option element
class CustomOption extends HTMLElement {
    constructor() {
        super();
        this._value = this.getAttribute("value") || "";
        this._text = this.textContent || "";

        // Create shadow DOM
        this.attachShadow({ mode: "open" });

        // Create styles
        const style = document.createElement("style");
        style.textContent = `
      :host {
        display: block;
        padding: var(--option-padding, 8px 12px);
        cursor: pointer;
        user-select: none;
        border-radius: 4px;
        transition: background-color 0.2s;

        color: var(--option-content-color, #333);
        font-family: var(--font-family);
        font-size: var(--option-content-font-size, 1rem);
        font-style: var(--option-content-font-style, normal);
        font-weight: var(--option-content-font-weight, 400);
        line-height: var(--option-content-line-height, 1.5);
      }
      
      :host(:hover) {
        background-color: rgba(0, 0, 0, 0.05);
      }
      
      :host([selected]) {
        background-color: rgba(0, 0, 0, 0.08);
        font-weight: 500;
      }
      
      .option-content {
        display: flex;
        align-items: center;
        padding: var(--option-content-padding);
        background: var(--option-content-background);
        border: var(--option-content-border);
        border-radius: var(--option-content-border-radius);
        box-shadow: var(--option-content-box-shadow);

      }
      
      ::slotted(*) {
        margin: 0;
      }
    `;

        // Create the option content
        const optionContent = document.createElement("div");
        optionContent.className = "option-content";

        // Add a slot for the content
        const slot = document.createElement("slot");
        optionContent.appendChild(slot);

        // Add everything to the shadow DOM
        this.shadowRoot.appendChild(style);
        this.shadowRoot.appendChild(optionContent);
    }

    get value() {
        return this._value;
    }

    get text() {
        return this._text;
    }

    // Get the HTML content
    get htmlContent() {
        return this.innerHTML;
    }

    set selected(isSelected) {
        if (isSelected) {
            this.setAttribute("selected", "");
        } else {
            this.removeAttribute("selected");
        }
    }

    get selected() {
        return this.hasAttribute("selected");
    }

    connectedCallback() {
        // Store the text content when connected
        this._text = this.textContent.trim();

        // Add click event listener
        this.addEventListener("click", this._handleClick.bind(this));
    }

    _handleClick(event) {
        // Dispatch a custom event to the parent dropdown
        const selectEvent = new CustomEvent("option-selected", {
            bubbles: true,
            composed: true,
            detail: {
                value: this.value,
                text: this.text,
                htmlContent: this.htmlContent,
            },
        });

        this.dispatchEvent(selectEvent);
    }
}

// Define the custom-dropdown element
class CustomDropdown extends HTMLElement {
    constructor() {
        super();
        this._open = false;
        this._selectedOption = null;
        this._hasSelection = false;

        // Create shadow DOM
        this.attachShadow({ mode: "open" });

        // Create styles
        const style = document.createElement("style");
        style.textContent = `
      :host {
        --font-family:  -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        --dropdown-trigger-padding: 8px 12px;
        --arrow-icon-size: 16px;
        --dropdown-menu-margin: 4px 0 0;

        --dropdown-menu-margin: 0; /* Set the default margin */
        --dropdown-menu-background-color: #fff;
        --dropdown-menu-border: 1px solid #e2e8f0;
        --dropdown-menu-border-radius: 6px;
        --dropdown-menu-box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --dropdown-menu-z-index: 10;
        --dropdown-menu-max-height: 250px;
        --dropdown-menu-padding: 4px;

        display: block;
        position: relative;
        font-family: var(--font-family);
        width: 100%;
      }
      
      .dropdown {
        position: relative;
      }
      
      .dropdown-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: var(--dropdown-trigger-padding);
        background: var(--dropdown-trigger-background, #fff);
        border: var(--dropdown-trigger-border, 1px solid #e2e8f0);
        border-radius: var(--dropdown-trigger-radius, 6px);
        cursor: pointer;
        user-select: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: var(--dropdown-trigger-shadow, 0px -24px 75.4px 3px rgba(251, 251, 251, 0.25));
      }
      
      .dropdown-trigger:hover {
        border-color: #cbd5e0;
      }
      
      .dropdown-trigger:focus {
        // outline: none;
        // border-color: #cce8c6;
        // box-shadow: 0 0 0 1px rgba(49, 130, 206, 0.2);
      }
      
      .dropdown-trigger.open {
        border-color: #cce8c6;
        box-shadow: var(--dropdown-trigger-shadow, var(--dropdown-trigger-open-shadow));
      }
      
      .placeholder-content {
        flex: 1;
        display: flex;
        align-items: center;
      }
      
      .selected-content,
      span[slot="placeholder"] {
        flex: 1;
        display: flex;
        align-items: center;
        color: var(--selected-content-color, #2E2E2E);
        font-family: var(--font-family);
        font-size: var(--selected-content-font-size, 1rem);
        font-style: var(--selected-content-font-style, normal);
        font-weight: var(--selected-content-font-weight, 400);
        line-height: var(--selected-content-line-height, 1.5);
      }
      
      .selected-content [hidden]{
        display: block;
      }

      .dropdown-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: var(--arrow-icon-width,var(--arrow-icon-size));
        height: var(--arrow-icon-height,var(--arrow-icon-size));
        margin-left: 8px;
        transition: transform 0.2s;
      }
      
      .dropdown-arrow svg {
        width: 100%;
        height: 100%;
      }

      .dropdown-arrow.open {
        transform: rotate(180deg);
      }
      
      .dropdown-menu {
          position: absolute;
          top: 100%;
          left: 0;
          right: 0;
          margin: var(--dropdown-menu-margin);
          background-color: var(--dropdown-menu-background-color);
          border: var(--dropdown-menu-border);
          border-radius: var(--dropdown-menu-border-radius);
          box-shadow: var(--dropdown-menu-box-shadow);
          z-index: var(--dropdown-menu-z-index);
          max-height: var(--dropdown-menu-max-height);
          overflow-y: auto;
          display: none;
          padding: var(--dropdown-menu-padding);
      }
      
      .dropdown-menu.open {
        display: block;
      }
      
      ::slotted(custom-option) {
        display: block;
      }
      
      .hidden {
        display: none !important;
      }
    `;

        // Create the dropdown container
        const dropdown = document.createElement("div");
        dropdown.className = "dropdown";

        // Create the dropdown trigger
        const trigger = document.createElement("div");
        trigger.className = "dropdown-trigger";
        trigger.tabIndex = 0;

        // Create placeholder slot
        const placeholderContent = document.createElement("div");
        placeholderContent.className = "placeholder-content";
        const placeholderSlot = document.createElement("slot");
        placeholderSlot.name = "placeholder";
        placeholderContent.appendChild(placeholderSlot);

        // Create selected content container (initially hidden)
        const selectedContent = document.createElement("div");
        selectedContent.className = "selected-content hidden";

        // Create dropdown arrow
        const arrow = document.createElement("div");
        arrow.className = "dropdown-arrow";
        arrow.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      `;

        // Create dropdown menu
        const menu = document.createElement("div");
        menu.className = "dropdown-menu";

        // Create options slot
        const optionsSlot = document.createElement("slot");

        // Assemble the elements
        trigger.appendChild(placeholderContent);
        trigger.appendChild(selectedContent);
        trigger.appendChild(arrow);
        menu.appendChild(optionsSlot);
        dropdown.appendChild(trigger);
        dropdown.appendChild(menu);

        // Add everything to the shadow DOM
        this.shadowRoot.appendChild(style);
        this.shadowRoot.appendChild(dropdown);

        // Store references to elements
        this._trigger = trigger;
        this._menu = menu;
        this._arrow = arrow;
        this._placeholderContent = placeholderContent;
        this._selectedContent = selectedContent;

        // Bind event handlers
        this._handleTriggerClick = this._handleTriggerClick.bind(this);
        this._handleOptionSelected = this._handleOptionSelected.bind(this);
        this._handleClickOutside = this._handleClickOutside.bind(this);
        this._handleKeyDown = this._handleKeyDown.bind(this);
    }

    connectedCallback() {
        // Add event listeners
        this._trigger.addEventListener("click", this._handleTriggerClick);
        this.addEventListener("option-selected", this._handleOptionSelected);
        this._trigger.addEventListener("keydown", this._handleKeyDown);
        document.addEventListener("click", this._handleClickOutside);

        // Initialize options
        this._initializeOptions();
    }

    disconnectedCallback() {
        // Remove event listeners
        this._trigger.removeEventListener("click", this._handleTriggerClick);
        this.removeEventListener("option-selected", this._handleOptionSelected);
        this._trigger.removeEventListener("keydown", this._handleKeyDown);
        document.removeEventListener("click", this._handleClickOutside);
    }

    _initializeOptions() {
        // Get all option elements
        this._options = Array.from(this.querySelectorAll("custom-option"));

        // Check for default selected option
        const defaultSelected = this._options.find(
            (option) =>
                option.hasAttribute("selected") ||
                option.getAttribute("default-selected") === ""
        );

        if (defaultSelected) {
            this._selectOption(defaultSelected);
        }
    }

    _handleTriggerClick(event) {
        this.toggleDropdown();
    }

    _handleOptionSelected(event) {
        const { value, text, htmlContent } = event.detail;
        const option = this._options.find((opt) => opt.value === value);

        if (option) {
            this._selectOption(option);
            this.closeDropdown();

            // Update the selected content
            this._updateSelectedContent(htmlContent);

            // Dispatch change event
            this._dispatchChangeEvent(value, text);
        }
    }

    _updateSelectedContent(htmlContent) {
        // Show selected content and hide placeholder
        this._selectedContent.innerHTML = htmlContent;
        this._selectedContent.classList.remove("hidden");
        this._placeholderContent.classList.add("hidden");
        this._hasSelection = true;
    }

    _resetSelectedContent() {
        // Show placeholder and hide selected content
        this._selectedContent.innerHTML = "";
        this._selectedContent.classList.add("hidden");
        this._placeholderContent.classList.remove("hidden");
        this._hasSelection = false;
    }

    _selectOption(option) {
        // Deselect previously selected option
        if (this._selectedOption) {
            this._selectedOption.selected = false;
        }

        // Select the new option
        option.selected = true;
        this._selectedOption = option;

        // Update the value
        this._value = option.value;

        // Update the selected content
        this._updateSelectedContent(option.htmlContent);
    }

    _dispatchChangeEvent(value, text) {
        const changeEvent = new CustomEvent("change", {
            bubbles: true,
            composed: true,
            detail: {
                value,
                text,
            },
        });

        this.dispatchEvent(changeEvent);
    }

    _handleClickOutside(event) {
        if (this._open && !this.contains(event.target)) {
            this.closeDropdown();
        }
    }

    _handleKeyDown(event) {
        switch (event.key) {
            case "Enter":
            case " ":
                event.preventDefault();
                this.toggleDropdown();
                break;
            case "Escape":
                if (this._open) {
                    event.preventDefault();
                    this.closeDropdown();
                }
                break;
            case "ArrowDown":
                if (this._open) {
                    event.preventDefault();
                    this._focusNextOption();
                } else {
                    this.openDropdown();
                }
                break;
            case "ArrowUp":
                if (this._open) {
                    event.preventDefault();
                    this._focusPreviousOption();
                }
                break;
        }
    }

    _focusNextOption() {
        if (!this._options.length) return;

        const currentIndex = this._selectedOption
            ? this._options.indexOf(this._selectedOption)
            : -1;

        const nextIndex = (currentIndex + 1) % this._options.length;
        const nextOption = this._options[nextIndex];

        if (nextOption) {
            nextOption.focus();
            nextOption.scrollIntoView({ block: "nearest" });
        }
    }

    _focusPreviousOption() {
        if (!this._options.length) return;

        const currentIndex = this._selectedOption
            ? this._options.indexOf(this._selectedOption)
            : 0;

        const prevIndex =
            (currentIndex - 1 + this._options.length) % this._options.length;
        const prevOption = this._options[prevIndex];

        if (prevOption) {
            prevOption.focus();
            prevOption.scrollIntoView({ block: "nearest" });
        }
    }

    toggleDropdown() {
        this._open ? this.closeDropdown() : this.openDropdown();
    }

    openDropdown() {
        if (this._open) return;

        this._open = true;
        this._menu.classList.add("open");
        this._trigger.classList.add("open");
        this._arrow.classList.add("open");

        // Focus the selected option if exists
        if (this._selectedOption) {
            this._selectedOption.focus();
        }
    }

    closeDropdown() {
        if (!this._open) return;

        this._open = false;
        this._menu.classList.remove("open");
        this._trigger.classList.remove("open");
        this._arrow.classList.remove("open");

        // Return focus to trigger
        this._trigger.focus();
    }

    get value() {
        return this._value;
    }

    set value(newValue) {
        const option = this._options.find((opt) => opt.value === newValue);
        if (option) {
            this._selectOption(option);
            this._dispatchChangeEvent(option.value, option.text);
        } else {
            // If value is null/undefined or not found, reset selection
            this._value = null;
            if (this._selectedOption) {
                this._selectedOption.selected = false;
                this._selectedOption = null;
            }
            this._resetSelectedContent();
        }
    }

    // Method to clear selection
    clearSelection() {
        this.value = null;
    }
}

// Register the custom elements
customElements.define("custom-option", CustomOption);
customElements.define("custom-dropdown", CustomDropdown);
