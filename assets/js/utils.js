function remToPixels(rem) {
    return (
        rem * parseFloat(getComputedStyle(document.documentElement).fontSize)
    );
}

class CF7Request {
    #formData;
    constructor(formData) {
        if (formData instanceof FormData) {
            this.#formData = formData;
        } else {
            this.#formData = new FormData();
            Object.entries(formData).forEach(([key, value]) => {
                this.#formData.append(key, value);
            });
        }
    }

    #getEndpoint(id) {
        const baseUrl = "/wp-json/contact-form-7/v1/contact-forms";
        if (!baseUrl) {
            throw new Error(
                "API base URL is not defined in environment variables."
            );
        }
        return `${baseUrl}/${id}/feedback`;
    }

    async send({ id, unitTag }) {
        if (!id || !unitTag) {
            throw new Error("Both 'id' and 'unitTag' are required.");
        }

        try {
            this.#formData.append("_wpcf7_unit_tag", unitTag); // Đảm bảo `_wpcf7_unit_tag` được thêm vào
            const response = await fetch(this.#getEndpoint(id), {
                method: "POST",
                body: this.#formData,
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error("Error sending CF7 request:", error);
            throw error;
        }
    }
}

class FormValidator {
    constructor(formSelector, constraints, onSuccess) {
        this.form = document.querySelector(formSelector);
        this.constraints = constraints;
        this.onSuccess = onSuccess; // Callback khi form hợp lệ

        if (!this.form) {
            console.error(`Form với selector '${formSelector}' không tồn tại!`);
            return;
        }

        this.attachEventListeners();
    }

    // Gắn sự kiện cho các input, select
    attachEventListeners() {
        this.form.addEventListener("submit", (event) => {
            event.preventDefault();
            this.validateForm();
        });

        const inputs = this.form.querySelectorAll("input, textarea, select");
        inputs.forEach((input) => {
            input.addEventListener("input", () => this.validateInput(input));
        });
    }

    // Validate toàn bộ form
    validateForm() {
        const errors = validate(this.form, this.constraints);
        this.showErrors(errors || {});
        if (!errors) this.handleSuccess();
    }

    // Validate một input riêng lẻ
    validateInput(input) {
        const formGroup = this.closestParent(input, "form-group");
        if (!formGroup) return;

        // Reset lỗi trước khi kiểm tra lại
        this.resetFormGroup(formGroup);

        const errors = validate(this.form, this.constraints) || {};
        const prevError = errors[input.name];

        if (prevError) {
            const inputErrs = prevError.map((prevEr) => {
                return this.convertAndRemove(input.name, prevEr);
            });
            this.showErrorsForInput(input, inputErrs);
        }
    }

    // Hiển thị lỗi trên toàn bộ form
    showErrors(errors) {
        console.log(errors);
        const inputs = this.form.querySelectorAll("input[name], select[name]");
        inputs.forEach((input) => {
            const prevError = errors[input.name];
            if (prevError) {
                const inputErrs = prevError.map((prevEr) => {
                    return this.convertAndRemove(input.name, prevEr);
                });
                this.showErrorsForInput(input, inputErrs);
            }
        });
    }

    convertAndRemove(input, text) {
        // Chuyển "name-name" thành "Name name"
        let formattedName = input
            .split("-")
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(" ");

        // Tạo regex để xóa cụm "Name name" không phân biệt hoa/thường
        let regex = new RegExp(formattedName, "gi");

        // Xóa cụm từ và chuẩn hóa khoảng trắng
        return text.replace(regex, "").replace(/\s+/g, " ").trim();
    }

    // Hiển thị lỗi cho một input
    showErrorsForInput(input, errors) {
        input.focus();
        const formGroup = this.closestParent(input, "form-group");
        if (!formGroup) return;

        const messages = formGroup.querySelector(".messages");
        this.resetFormGroup(formGroup);
        console.log("this.resetFormGroup");
        if (errors) {
            formGroup.classList.add("has-error");
            errors.forEach((error) => this.addError(messages, error));
        } else {
            formGroup.classList.add("has-success");
        }
    }

    // Tìm phần tử cha gần nhất có class chỉ định
    closestParent(element, className) {
        while (element && element !== document) {
            if (element.classList.contains(className)) return element;
            element = element.parentNode;
        }
        return null;
    }

    // Xóa lỗi và reset trạng thái input
    resetFormGroup(formGroup) {
        formGroup.classList.remove("has-error", "has-success");
        const oldMessages = formGroup.querySelectorAll(".help-block.error");
        oldMessages.forEach((el) => el.remove());
    }

    // Thêm lỗi vào phần hiển thị
    addError(messages, error) {
        const block = document.createElement("p");
        block.classList.add("help-block", "error");
        block.innerText = error;
        messages.appendChild(block);
    }

    // Xử lý khi form hợp lệ
    handleSuccess() {
        console.log("Form validated successfully!");
        const formData = new FormData(this.form);
        if (typeof this.onSuccess === "function") {
            this.onSuccess(formData); // Gọi callback với dữ liệu form
        }
    }
}

// class CustomDropdown extends HTMLElement {
//     constructor() {
//         super();
//         this.attachShadow({ mode: "open" });
//         this.render();
//     }

//     connectedCallback() {
//         this.init();
//     }

//     render() {
//         this.shadowRoot.innerHTML = `
//             <style>
//                 :host { display: block; position: relative; }
//                 .dropdown-toggle { cursor: pointer; padding: 10px; border: 1px solid #ccc; display: flex; align-items: center; gap: 5px; }
//                 .dropdown-menu { display: none; position: absolute; background: white; border: 1px solid #ccc; list-style: none; padding: 0; margin: 0; width: 100%; }
//                 .dropdown-menu li { padding: 10px; cursor: pointer; display: flex; align-items: center; gap: 5px; }
//                 .dropdown-menu li:hover { background: #f0f0f0; }
//                 .open .dropdown-menu { display: block; }
//                 ::slotted(custom-option) { display: none; }
//             </style>
//             <div class="dropdown">
//                 <div class="dropdown-toggle">
//                     <slot name="placeholder">Select</slot>
//                 </div>
//                 <ul class="dropdown-menu"></ul>
//                 <input type="hidden" class="dropdown-input" />
//                 <slot></slot>
//             </div>
//         `;
//     }

//     init() {
//         this.toggle = this.shadowRoot.querySelector(".dropdown-toggle");
//         this.menu = this.shadowRoot.querySelector(".dropdown-menu");
//         this.input = this.shadowRoot.querySelector(".dropdown-input");

//         this.renderOptions();

//         this.toggle.addEventListener("click", () => this.toggleDropdown());
//         document.addEventListener("click", (e) => this.closeOnOutsideClick(e));
//     }

//     toggleDropdown() {
//         this.shadowRoot.querySelector(".dropdown").classList.toggle("open");
//     }

//     closeOnOutsideClick(e) {
//         if (!this.contains(e.target)) {
//             this.shadowRoot.querySelector(".dropdown").classList.remove("open");
//         }
//     }

//     renderOptions() {
//         const slot = this.querySelectorAll("custom-option");
//         this.menu.innerHTML = Array.from(slot)
//             .map(
//                 (opt) =>
//                     `<li data-value="${opt.getAttribute("value")}">
//                 ${opt.innerHTML}
//             </li>`
//             )
//             .join("");

//         this.menu.addEventListener("click", (e) => this.selectOption(e));
//     }

//     selectOption(e) {
//         if (e.target.tagName !== "LI") return;

//         this.toggle.innerHTML = e.target.innerHTML;
//         this.input.value = e.target.getAttribute("data-value");
//         this.shadowRoot.querySelector(".dropdown").classList.remove("open");

//         this.dispatchEvent(
//             new CustomEvent("change", {
//                 detail: { value: this.input.value },
//                 bubbles: true,
//                 composed: true,
//             })
//         );
//     }
// }

// customElements.define("custom-dropdown", CustomDropdown);

// class CustomOption extends HTMLElement {
//     constructor() {
//         super();
//     }
// }

// customElements.define("custom-option", CustomOption);

// document
//     .querySelector("custom-dropdown")
//     .addEventListener("change", (event) => {
//         console.log("Giá trị được chọn:", event.detail.value);
//     });
