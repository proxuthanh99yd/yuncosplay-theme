<?php
get_header();
?>
<section class="error-page">
    <div class='error-page__container'>
        <?= wp_get_attachment_image(IS_MOBILE ? 574 : 573, 'full', false, ['class' => 'error-page__background']) ?>
        <div class='error-page__content'>
            <h1 class='error-page__title'>
                404
            </h1>
            <h2 class='error-page__subtitle'>
                Không tìm thấy trang
            </h2>
            <p class='error-page__description'>
                Chúng tôi xin lỗi. Trang bạn yêu cầu không thể tìm thấy.<br> Vui lòng quay lại trang chủ
            </p>
            <a href='<?= esc_url(home_url()) ?>' class='error-page__link'>
                <span>
                    Về trang chủ
                </span>
                <img src='/wp-content/uploads/2025/10/arrow-right.svg' alt="Arrow Right" class='error-page__icon' data-no-lazy="1" />
            </a>
        </div>
    </div>
</section>
<?php get_footer(); ?>