<?php
/**
 * Contact Page - Contact Form Section (right column)
 * Form liên hệ với CF7 REST API submission
 */

if (!defined('ABSPATH')) {
    exit;
}

$cf7_form_id = get_field('contact_cf7_form_id') ?: 0;
?>

<div class="contact-form"
     id="contact-form-wrapper"
     data-cf7-id="<?= esc_attr($cf7_form_id) ?>"
     data-cf7-endpoint="<?= esc_url(rest_url("contact-form-7/v1/contact-forms/{$cf7_form_id}/feedback")) ?>"
     data-nonce="<?= esc_attr(wp_create_nonce('wp_rest')) ?>">

    <form id="contact-form" class="contact-form__form" novalidate>
        <!-- Hidden field required by CF7 API -->
        <input type="hidden" name="_wpcf7" value="<?= esc_attr($cf7_form_id) ?>">
        <input type="hidden" name="_wpcf7_version" value="5.9">
        <input type="hidden" name="_wpcf7_locale" value="vi">
        <input type="hidden" name="_wpcf7_unit_tag" value="wpcf7-f<?= esc_attr($cf7_form_id) ?>-custom">
        <input type="hidden" name="_wpcf7_container_post" value="0">

        <!-- Row 1: Tên + Số điện thoại -->
        <div class="contact-form__row">
            <!-- Tên của bạn -->
            <div class="contact-form__field">
                <label class="contact-form__label" for="your-name">
                    Tên của bạn
                    <span class="contact-form__required">*</span>
                </label>
                <input
                    type="text"
                    id="your-name"
                    name="your-name"
                    class="contact-form__input"
                    placeholder="Nhập tên của bạn"
                    required
                >
                <span class="contact-form__error" data-error-for="your-name"></span>
            </div>

            <!-- Số điện thoại -->
            <div class="contact-form__field">
                <label class="contact-form__label" for="your-phone">
                    Số điện thoại
                    <span class="contact-form__required">*</span>
                </label>
                <input
                    type="tel"
                    id="your-phone"
                    name="your-phone"
                    class="contact-form__input"
                    placeholder="Nhập số điện thoại"
                    required
                >
                <span class="contact-form__error" data-error-for="your-phone"></span>
            </div>
        </div>

        <!-- Row 2: Ghi chú -->
        <div class="contact-form__field">
            <label class="contact-form__label" for="your-message">
                Ghi chú
                <span class="contact-form__required">*</span>
            </label>
            <textarea
                id="your-message"
                name="your-message"
                class="contact-form__textarea"
                placeholder="Nhập ghi chú"
                rows="5"
                required
            ></textarea>
            <span class="contact-form__error" data-error-for="your-message"></span>
        </div>

        <!-- Row 3: Submit button -->
        <div class="contact-form__submit">
            <button type="submit" class="contact-form__submit-btn">
                <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => 'Gửi thông tin']); ?>
            </button>
        </div>
    </form>
</div>
