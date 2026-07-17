<?php
$banner_acf = get_field('banner') ?: [];
$banner_img_pc = $banner_acf['banner_pc'] ?? null;
$banner_img_mb = $banner_acf['banner_mb'] ?? null;
$banner_title = $banner_acf['title'] ?? '';
$banner_description = $banner_acf['description'] ?? '';
$features = $banner_acf['features'] ?? [];
?>

<section class="section-banner">
    <?php echo wp_get_attachment_image($banner_img_pc, 'full', false, okhub_image_attrs(array(
        'class'    => 'banner-img banner-img--pc',
    ), !IS_MOBILE ? 'lcp' : 'lazy')); ?>
    <?php echo wp_get_attachment_image($banner_img_mb, 'full', false, okhub_image_attrs(array(
        'class'    => 'banner-img banner-img--mb',
    ), IS_MOBILE ? 'lcp' : 'lazy')); ?>
    <div class="banner-content">
        <div class="banner-content__left">
            <h1 class="banner-title"><?php echo esc_html($banner_title); ?></h1>
            <p class="banner-description"><?php echo esc_html($banner_description); ?></p>
            <a href="/lien-he" class="banner__button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M19.8564 12L13.3311 18.5244L11.4375 18.4668L14.1641 15.7402C15.0033 14.9022 15.7713 14.1511 16.4688 13.4883L17.2695 12.7275L16.1641 12.7217L5.4082 12.6709L5.47363 11.2197L16.2646 11.2725L17.3877 11.2773L16.5723 10.5049C15.8907 9.85915 15.136 9.12137 14.3076 8.29297L11.4883 5.47559L13.2705 5.41699L19.8564 12Z"
                        fill="#F26C59" stroke="#F26C59" stroke-width="0.8888" />
                </svg>
                <p>
                    Đặt lịch chụp ngay
                </p>
            </a>
        </div>
        <div class="banner-content__right">
            <div class="swiper swiper-banner">
                <div class="swiper-wrapper">
                    <?php foreach($features as $item):
                        $feature = $item['feature'];
                        $title = $feature['title'];
                        $icon = $feature['icon'];
                    ?>
                    <div class="feature__item swiper-slide">
                        <?php echo wp_get_attachment_image($icon, 'full', false, array(
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                            'class'    => 'feature__item--icon',
                        )); ?>
                        <p><?php echo $title?></p>
                    </div>
                    <?php endforeach;?>
                    <!-- <div class="feature__item swiper-slide">
                        <?php echo wp_get_attachment_image($banner_img_mb, 'full', false, array(
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                            'class'    => 'feature__item--icon',
                        )); ?>
                        <p>Makeup artist , chuyên viên chuyên concept</p>
                    </div> -->
                </div>
            </div>
            <div class="feature__item--pagination"></div>
        </div>
    </div>
</section>