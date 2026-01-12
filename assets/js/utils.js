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
        this.onSuccess = onSuccess;

        if (!this.form) {
            console.error(`Form với selector '${formSelector}' không tồn tại!`);
            return;
        }

        this.attachEventListeners();
    }

    attachEventListeners() {
        this.form.addEventListener("submit", (event) => {
            event.preventDefault();
            this.validateForm();
        });

        const inputs = this.form.querySelectorAll("input, textarea, select");
        inputs.forEach((input) => {
            if (input.hasAttribute("datepicker")) {
                input.addEventListener("changeDate", () =>
                    this.validateInput(input)
                );
            } else {
                input.addEventListener("input", () =>
                    this.validateInput(input)
                );
            }
        });
    }

    // Lấy dữ liệu form thành object để truyền vào validate.js
    getFormData() {
        const data = {};
        const elements = this.form.querySelectorAll("[name]");
        elements.forEach((el) => {
            if (el.type === "checkbox") {
                data[el.name] = el.checked ? el.value : "";
            } else if (el.type === "radio") {
                if (el.checked) data[el.name] = el.value;
            } else {
                data[el.name] = el.value;
            }
        });
        return data;
    }

    validateForm(cb) {
        const formData = this.getFormData();
        const errors = validate(formData, this.constraints);
        this.showErrors(errors || {});
        if (!errors) this.handleSuccess();
        if (typeof cb === "function") {
            cb({
                status: !errors, // true nếu không có lỗi
                errors: errors || null,
                formData: formData,
            });
        }
    }

    validateInput(input) {
        const formGroup = this.closestParent(input, "form-group");
        if (!formGroup) return;

        this.resetFormGroup(formGroup);

        const data = this.getFormData();
        const fieldConstraint = { [input.name]: this.constraints[input.name] };
        const errors = validate(data, fieldConstraint);

        if (errors && errors[input.name]) {
            const messages = errors[input.name].map((msg) =>
                this.convertAndRemove(input.name, msg)
            );
            this.showErrorsForInput(input, messages);
        }
    }

    showErrors(errors) {
        const inputs = this.form.querySelectorAll(
            "input[name], select[name], textarea[name]"
        );
        inputs.forEach((input) => {
            const messages = errors[input.name];
            if (messages) {
                const msgList = messages.map((msg) =>
                    this.convertAndRemove(input.name, msg)
                );
                this.showErrorsForInput(input, msgList);
            } else {
                this.resetFormGroup(this.closestParent(input, "form-group"));
            }
        });
    }

    showErrorsForInput(input, errors) {
        const formGroup = this.closestParent(input, "form-group");
        if (!formGroup) return;

        // Tìm hoặc tạo thẻ .messages
        let messages = formGroup.querySelector(".messages");
        if (!messages) {
            messages = document.createElement("div");
            messages.classList.add("messages");
            formGroup.appendChild(messages);
        }

        this.resetFormGroup(formGroup);

        if (errors.length > 0) {
            formGroup.classList.add("has-error");
            errors.forEach((error) => this.addError(messages, error));
        } else {
            formGroup.classList.add("has-success");
        }
    }

    resetFormGroup(formGroup) {
        formGroup.classList.remove("has-error", "has-success");

        const messages = formGroup.querySelector(".messages");
        if (messages) {
            messages.innerHTML = "";
        }
    }

    resetAll() {
        const groups = this.form.querySelectorAll(".form-group");
        groups.forEach((group) => this.resetFormGroup(group));
    }

    addError(messages, error) {
        const block = document.createElement("p");
        block.classList.add("help-block", "error");
        block.innerText = error;
        messages.appendChild(block);
    }

    convertAndRemove(input, text) {
        const formatted = input
            .split("-")
            .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
            .join(" ");
        const regex = new RegExp(formatted, "gi");
        return text.replace(regex, "").replace(/\s+/g, " ").trim();
    }

    closestParent(element, className) {
        while (element && element !== document) {
            if (element.classList.contains(className)) return element;
            element = element.parentNode;
        }
        return null;
    }

    handleSuccess() {
        console.log("Form validated successfully!");
        const formData = new FormData(this.form);
        if (typeof this.onSuccess === "function") {
            this.onSuccess(formData);
        }
    }
}
