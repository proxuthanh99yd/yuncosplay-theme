<?php
// template-parts/404/index.php — Trang 404 (không tìm thấy), concept kem sáng editorial.
?>
<section class="error-404">
    <div class="error-404__inner container">
        <p class="error-404__code" aria-hidden="true">404</p>

        <?php echo okhub_img('icons/line-1239-2', array('class' => 'error-404__divider', 'alt' => '')); ?>

        <h1 class="error-404__title">Oops! Không tìm thấy trang</h1>

        <p class="error-404__desc">
            Trang bạn tìm có thể đã đổi tên, được chuyển đi hoặc chưa từng tồn tại.
            Đừng lo — hãy quay về và khám phá tiếp bộ sưu tập cosplay của chúng tôi.
        </p>

        <div class="error-404__actions">
            <?php
            get_template_part('template-parts/components/animated-button/index', null, array(
                'text' => 'Về trang chủ',
                'href' => home_url('/'),
            ));
            ?>
        </div>

        <nav class="error-404__links" aria-label="Liên kết nhanh">
            <a class="error-404__link" href="<?php echo esc_url(okhub_page_url('shop')); ?>">Sản phẩm</a>
            <a class="error-404__link" href="<?php echo esc_url(okhub_page_url('blogs')); ?>">Blog</a>
            <a class="error-404__link" href="<?php echo esc_url(okhub_page_url('lien-he')); ?>">Liên hệ</a>
        </nav>
    </div>
</section>
