/**
 * Contact Form - CF7 REST API Submission + Toast Notifications
 * ES Module
 */

// ==========================================
// TOAST NOTIFICATION (Giữ nguyên)
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

function showToast(type) {
    dismissToast();
    const toast = createToastElement(type);
    document.body.appendChild(toast);
    activeToast = toast;
    toast.offsetHeight;
    toast.classList.add('contact-toast--visible');
    const closeBtn = toast.querySelector('.contact-toast__close');
    closeBtn.addEventListener('click', () => dismissToast());
    toastTimeout = setTimeout(() => dismissToast(), TOAST_AUTO_DISMISS);
}

function dismissToast() {
    if (toastTimeout) { clearTimeout(toastTimeout); toastTimeout = null; }
    if (!activeToast) return;
    const toast = activeToast;
    activeToast = null;
    toast.classList.remove('contact-toast--visible');
    toast.classList.add('contact-toast--exiting');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
}

// ==========================================
// FORM VALIDATION (ĐÃ CẬP NHẬT)
// ==========================================

const PHONE_REGEX = /^(0[0-9]{9}|(\+84)[0-9]{9})$/;

const VALIDATION_MESSAGES = {
    'your-name': 'Vui lòng nhập tên của bạn.',
    'your-phone': 'Vui lòng nhập số điện thoại.',
    'your-phone-format': 'Số điện thoại không hợp lệ.',
};

/**
 * Hàm kiểm tra lỗi cho từng Field (MỚI THÊM)
 * Giúp dùng chung cho cả sự kiện Blur và Submit
 */
function validateSingleField(fieldName, value) {
    const val = value.trim();
    if (fieldName === 'your-name') {
        if (!val) return VALIDATION_MESSAGES['your-name'];
    }
    if (fieldName === 'your-phone') {
        if (!val) return VALIDATION_MESSAGES['your-phone'];
        if (!PHONE_REGEX.test(val)) return VALIDATION_MESSAGES['your-phone-format'];
    }
    return null; // Không có lỗi
}

function showFieldError(fieldName, message) {
    const form = document.getElementById('contact-form');
    const input = form.querySelector(`[name="${fieldName}"]`);
    const errorEl = form.querySelector(`[data-error-for="${fieldName}"]`);

    if (input) {
        input.classList.add('contact-form__input--error');
    }
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add('contact-form__error--visible');
    }
}

function clearFieldError(fieldName) {
    const form = document.getElementById('contact-form');
    const input = form.querySelector(`[name="${fieldName}"]`);
    const errorEl = form.querySelector(`[data-error-for="${fieldName}"]`);

    if (input) {
        input.classList.remove('contact-form__input--error');
    }
    if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.remove('contact-form__error--visible');
    }
}

function clearAllErrors() {
    ['your-name', 'your-phone'].forEach(clearFieldError);
}

/**
 * Validate form khi nhấn Submit
 */
function validateForm(form) {
    let isValid = true;
    const fieldsToValidate = ['your-name', 'your-phone'];

    fieldsToValidate.forEach(fieldName => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        const errorMessage = validateSingleField(fieldName, input.value);
        
        if (errorMessage) {
            showFieldError(fieldName, errorMessage);
            isValid = false;
        } else {
            clearFieldError(fieldName);
        }
    });

    return isValid;
}

// ==========================================
// CF7 REST API SUBMISSION
// ==========================================

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
            headers: { 'X-WP-Nonce': nonce },
            body: formData,
        });

        const data = await response.json();

        if (data.status === 'mail_sent') {
            showToast('success');
            form.reset();
            clearAllErrors();
        } else if (data.status === 'validation_failed') {
            clearAllErrors();
            data.invalid_fields?.forEach((field) => {
                const match = field.into?.match(/data-name="([^"]+)"/);
                if (match) showFieldError(match[1], field.message);
            });
        } else {
            showToast('fail');
        }
    } catch (error) {
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

    // Chỉ theo dõi Name và Phone
    const fieldsToWatch = ['your-name', 'your-phone'];

    fieldsToWatch.forEach((fieldName) => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        if (input) {
            // Khi người dùng đang nhập -> Xóa thông báo lỗi
            input.addEventListener('input', () => {
                clearFieldError(fieldName);
            });
            
            // Khi click ra ngoài (Blur) -> Kiểm tra lỗi ngay
            input.addEventListener('blur', () => {
                const errorMessage = validateSingleField(fieldName, input.value);
                if (errorMessage) {
                    showFieldError(fieldName, errorMessage);
                } else {
                    clearFieldError(fieldName);
                }
            });
        }
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!validateForm(form)) return;
        submitForm(form);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}