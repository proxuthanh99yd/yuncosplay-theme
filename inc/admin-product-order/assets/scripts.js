/**
 * Auto-save "Thứ tự hiển thị" (order_index) cho SP ngay trong danh sách wp-admin.
 * Gõ số -> debounce -> POST admin-ajax -> hiện trạng thái đã lưu / lỗi.
 */
(function () {
    'use strict';

    var cfg = window.OKHUB_ORDER_INDEX;
    if (!cfg || !cfg.ajaxurl) {
        return;
    }

    var timers = new WeakMap();

    function setState(input, state) {
        input.classList.remove('is-saving', 'is-saved', 'is-error');
        var status = input.nextElementSibling;
        var hasStatus = status && status.classList.contains('okhub-oi-status');
        if (hasStatus) {
            status.textContent = '';
            status.className = 'okhub-oi-status';
        }

        if (state === 'saving') {
            input.classList.add('is-saving');
        } else if (state === 'saved') {
            input.classList.add('is-saved');
            if (hasStatus) {
                status.textContent = '✓';
                status.classList.add('okhub-oi-status--saved');
            }
        } else if (state === 'error') {
            input.classList.add('is-error');
            if (hasStatus) {
                status.textContent = '!';
                status.classList.add('okhub-oi-status--error');
            }
        }
    }

    function save(input) {
        var id = input.getAttribute('data-id');
        if (!id) {
            return;
        }

        setState(input, 'saving');

        var body = new URLSearchParams();
        body.set('action', 'okhub_save_order_index');
        body.set('nonce', cfg.nonce);
        body.set('product_id', id);
        body.set('value', input.value.trim());

        fetch(cfg.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
            .then(function (res) { return res.json(); })
            .then(function (json) {
                if (json && json.success) {
                    // Đồng bộ lại ô nhập với giá trị server đã chuẩn hoá (vd: bỏ ghim -> '').
                    input.value = json.data && json.data.value ? json.data.value : '';
                    setState(input, 'saved');
                    window.setTimeout(function () {
                        input.classList.remove('is-saved');
                        var st = input.nextElementSibling;
                        if (st && st.classList.contains('okhub-oi-status')) {
                            st.textContent = '';
                            st.className = 'okhub-oi-status';
                        }
                    }, 1500);
                } else {
                    setState(input, 'error');
                }
            })
            .catch(function () {
                setState(input, 'error');
            });
    }

    function schedule(input) {
        var existing = timers.get(input);
        if (existing) {
            window.clearTimeout(existing);
        }
        timers.set(input, window.setTimeout(function () { save(input); }, 500));
    }

    document.addEventListener('input', function (e) {
        var input = e.target;
        if (input && input.classList && input.classList.contains('okhub-oi-input')) {
            schedule(input);
        }
    });

    // Enter = lưu ngay + rời focus; chặn submit form list-table.
    document.addEventListener('keydown', function (e) {
        var input = e.target;
        if (!input || !input.classList || !input.classList.contains('okhub-oi-input')) {
            return;
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            var existing = timers.get(input);
            if (existing) {
                window.clearTimeout(existing);
            }
            save(input);
            input.blur();
        }
    });
})();
