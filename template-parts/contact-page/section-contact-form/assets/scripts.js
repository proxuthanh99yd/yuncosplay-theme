/**
 * Contact Form - CF7 REST API Submission + Toast Notifications
 * ES Module
 */

// ==========================================
// TOAST NOTIFICATION
// ==========================================

const TOAST_ICONS = {
    success: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`,
    fail: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`,
    close: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`,
};

const TOAST_MESSAGES = {
    success: 'Gửi thông tin thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.',
    fail: 'Có lỗi xảy ra. Vui lòng thử lại sau.',
};

const TOAST_AUTO_DISMISS = 4000;

let activeToast = null;
let toastTimeout = null;

/**
 * Tạo toast element và inject vào DOM
 */
function createToastElement(type) {
    const toast = document.createElement('div');
    toast.className = `contact-toast contact-toast--${type}`;
    toast.setAttribute('role', 'alert');

    toast.innerHTML = `
        <span class="contact-toast__icon">${TOAST_ICONS[type]}</span>
        <div class="contact-toast__content">
            <p class="contact-toast__message">${TOAST_MESSAGES[type]}</p>
        </div>
        <button type="button" class="contact-toast__close" aria-label="Đóng">${TOAST_ICONS.close}</button>
    `;

    return toast;
}

/**
 * Hiển thị toast notification
 */
function showToast(type) {
    // Xóa toast cũ nếu có
    dismissToast();

    const toast = createToastElement(type);
    document.body.appendChild(toast);
    activeToast = toast;

    // Trigger reflow để animation hoạt động
    toast.offsetHeight;
    toast.classList.add('contact-toast--visible');

    // Đóng toast khi click nút close
    const closeBtn = toast.querySelector('.contact-toast__close');
    closeBtn.addEventListener('click', () => {
        dismissToast();
    });

    // Auto-dismiss sau 4 giây
    toastTimeout = setTimeout(() => {
        dismissToast();
    }, TOAST_AUTO_DISMISS);
}

/**
 * Ẩn và xóa toast
 */
function dismissToast() {
    if (toastTimeout) {
        clearTimeout(toastTimeout);
        toastTimeout = null;
    }

    if (!activeToast) return;

    const toast = activeToast;
    activeToast = null;

    toast.classList.remove('contact-toast--visible');
    toast.classList.add('contact-toast--exiting');

    toast.addEventListener('transitionend', () => {
        toast.remove();
    }, { once: true });

    // Fallback nếu transitionend không fire
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 500);
}

// ==========================================
// FORM VALIDATION
// ==========================================

const PHONE_REGEX = /^(0[0-9]{9}|(\+84)[0-9]{9})$/;

const VALIDATION_MESSAGES = {
    'your-name': 'Vui lòng nhập tên của bạn.',
    'your-phone': 'Vui lòng nhập số điện thoại.',
    'your-phone-format': 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng.',
    'your-message': 'Vui lòng nhập ghi chú.',
};

/**
 * Hiển thị lỗi cho field
 */
function showFieldError(fieldName, message) {
    const form = document.getElementById('contact-form');
    const input = form.querySelector(`[name="${fieldName}"]`);
    const errorEl = form.querySelector(`[data-error-for="${fieldName}"]`);

    if (input) {
        const errorClass = input.tagName === 'TEXTAREA'
            ? 'contact-form__textarea--error'
            : 'contact-form__input--error';
        input.classList.add(errorClass);
    }

    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add('contact-form__error--visible');
    }
}

/**
 * Xóa lỗi của 1 field
 */
function clearFieldError(fieldName) {
    const form = document.getElementById('contact-form');
    const input = form.querySelector(`[name="${fieldName}"]`);
    const errorEl = form.querySelector(`[data-error-for="${fieldName}"]`);

    if (input) {
        input.classList.remove('contact-form__input--error', 'contact-form__textarea--error');
    }

    if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.remove('contact-form__error--visible');
    }
}

/**
 * Xóa tất cả lỗi
 */
function clearAllErrors() {
    ['your-name', 'your-phone', 'your-message'].forEach(clearFieldError);
}

/**
 * Validate form phía client
 * Return true nếu valid, false nếu có lỗi
 */
function validateForm(form) {
    let isValid = true;
    clearAllErrors();

    const name = form.querySelector('[name="your-name"]').value.trim();
    const phone = form.querySelector('[name="your-phone"]').value.trim();
    const message = form.querySelector('[name="your-message"]').value.trim();

    // Validate tên
    if (!name) {
        showFieldError('your-name', VALIDATION_MESSAGES['your-name']);
        isValid = false;
    }

    // Validate số điện thoại
    if (!phone) {
        showFieldError('your-phone', VALIDATION_MESSAGES['your-phone']);
        isValid = false;
    } else if (!PHONE_REGEX.test(phone)) {
        showFieldError('your-phone', VALIDATION_MESSAGES['your-phone-format']);
        isValid = false;
    }

    // Validate ghi chú
    if (!message) {
        showFieldError('your-message', VALIDATION_MESSAGES['your-message']);
        isValid = false;
    }

    return isValid;
}

// ==========================================
// CF7 REST API SUBMISSION
// ==========================================

/**
 * Map CF7 invalid_fields response sang form fields
 * CF7 trả về dạng: { into: '.wpcf7-form-control-wrap[data-name="your-name"]', message: '...' }
 */
function handleCF7ValidationErrors(invalidFields) {
    if (!Array.isArray(invalidFields)) return;

    const fieldMap = {
        'your-name': 'your-name',
        'your-phone': 'your-phone',
        'your-message': 'your-message',
    };

    invalidFields.forEach((field) => {
        // Extract field name từ `into` string
        const match = field.into?.match(/data-name="([^"]+)"/);
        if (match) {
            const cfFieldName = match[1];
            const formFieldName = fieldMap[cfFieldName];
            if (formFieldName && field.message) {
                showFieldError(formFieldName, field.message);
            }
        }
    });
}

/**
 * Submit form qua CF7 REST API
 */
async function submitForm(form) {
    const wrapper = document.getElementById('contact-form-wrapper');
    const endpoint = wrapper.dataset.cf7Endpoint;
    const nonce = wrapper.dataset.nonce;

    if (!endpoint) {
        showToast('fail');
        return;
    }

    const formData = new FormData(form);

    const submitBtn = form.querySelector('.contact-form__submit-btn');
    submitBtn.classList.add('contact-form__submit-btn--loading');

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': nonce,
            },
            body: formData,
        });

        const data = await response.json();

        if (data.status === 'mail_sent') {
            // Thành công
            showToast('success');
            form.reset();
            clearAllErrors();
        } else if (data.status === 'validation_failed') {
            // CF7 validation errors — hiển thị inline
            clearAllErrors();
            handleCF7ValidationErrors(data.invalid_fields);
        } else {
            // Lỗi khác (spam, mail_failed, etc.)
            showToast('fail');
        }
    } catch (error) {
        console.error('Contact form submission error:', error);
        showToast('fail');
    } finally {
        submitBtn.classList.remove('contact-form__submit-btn--loading');
    }
}

// ==========================================
// INIT
// ==========================================

function init() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    // Xóa lỗi khi user nhập lại
    ['your-name', 'your-phone', 'your-message'].forEach((fieldName) => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        if (input) {
            input.addEventListener('input', () => {
                clearFieldError(fieldName);
            });
        }
    });

    // Submit handler
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Client-side validation trước
        if (!validateForm(form)) return;

        // Submit qua CF7 REST API
        submitForm(form);
    });
}

// Chạy khi DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
