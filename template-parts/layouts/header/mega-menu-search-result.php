<?php 
$icon_arrow_right_id = 69;
?>

<div class="header__mega-menu-search">
    <div data-lenis-prevent class="header__mega-menu-search__result">
        <div class="header__mega-menu-search__result-top">
            <p class="header__mega-menu-search__result-title">
               Kết quả tìm kiếm 
            </p>
            <a href="/" class="header__mega-menu-search__result-contact">
                <span class="header__mega-menu-search__result-contact-icon">
                    <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false) ?>
                </span>
                <span class="header__mega-menu-search__result-contact-text">
                    Liên hệ ngay
                </span>
            </a>
        </div>
        <div class="header__mega-menu-search__result-bottom">
            <div class="header__mega-menu-search__result-list">

            </div>
        </div>
    </div>
    <div data-lenis-prevent class="header__mega-menu-search__related-blog">
        <div class="header__mega-menu-search__related-blog-top">
            <p class="header__mega-menu-search__related-blog__title">
                Tin liên quan
            </p>
            <a href="/" class="header__mega-menu-search__related-blog__contact-link">
                <span class="header__mega-menu-search__related-blog__contact-link-icon">
                    <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false) ?>
                </span>
                <span class="header__mega-menu-search__related-blog__contact-link-text">
                    Liên hệ ngay
                </span>
            </a>
        </div>
        <div class="header__mega-menu-search__related-blog-bottom">
            <div class="header__mega-menu-search__related-blog-list">
                <?php for($i = 0; $i < 10; $i++) : ?>
                    <?php get_template_part('template-parts/components/blog-item/index'); ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
