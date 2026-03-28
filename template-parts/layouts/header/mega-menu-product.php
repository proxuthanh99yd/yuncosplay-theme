<?php 
$category_image_id = 82;
$icon_arrow_right_id = 69;

$mock_parent_categories = array(
    array('title' => 'Lễ hội - Sự kiện'),
    array('title' => 'Nhân vật Fantasy'),
    array('title' => 'Giải trí - Hài hước'),
    array('title' => 'Thần thoại, Cổ tích'),
    array('title' => 'Lễ hội - Sự kiện'),
    array('title' => 'Nhân vật Fantasy'),
    array('title' => 'Giải trí - Hài hước'),
    array('title' => 'Thần thoại, Cổ tích'),
);

$mock_child_categories = array(
    array('title' => 'Siêu anh hùng', 'image' => $category_image_id),
    array('title' => 'Đồng phục học sinh', 'image' => $category_image_id),
    array('title' => 'Cao bồi, Cướp biển hải tặc', 'image' => $category_image_id),
    array('title' => 'Trung thu', 'image' => $category_image_id),
    array('title' => 'Siêu anh hùng', 'image' => $category_image_id),
    array('title' => 'Đồng phục học sinh', 'image' => $category_image_id),
    array('title' => 'Cao bồi, Cướp biển hải tặc', 'image' => $category_image_id),
    array('title' => 'Trung thu', 'image' => $category_image_id),
)
?>

<div data-mega-menu-content="mega-menu-product" class="header__mega-menu-product header__mega-menu-item">
    <div class="header__mega-menu-product-wrapper">
        <button class="header__mega-menu-product__parent-categories-swiper-prev">
            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => '')) ?>
        </button>
        <button class="header__mega-menu-product__parent-categories-swiper-next">
            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => '')) ?>
        </button>
        <div class="swiper header__mega-menu-product__parent-categories-swiper">
            <div class="swiper-wrapper header__mega-menu-product__parent-categories-swiper-wrapper">
                <?php foreach($mock_parent_categories as $parent_category) : ?>
                    <div class="swiper-slide header__mega-menu-product__parent-categories-swiper-slide">
                        <p class="header__mega-menu-product__parent-category-title">
                            <?php echo $parent_category['title'] ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div data-lenis-prevent class="swiper header__mega-menu-product__child-categories-swiper">
            <div class="swiper-wrapper header__mega-menu-product__child-categories-swiper-wrapper">
                <?php foreach($mock_parent_categories as $parent_category) : ?>
                <div class="swiper-slide header__mega-menu-product__child-categories-swiper-slide">
                    <div class="header__mega-menu-product__child-category-list">
                        <?php foreach($mock_child_categories as $child_category) : ?>
                        <div class="header__mega-menu-product__child-category-item">
                            <div class="header__mega-menu-product__child-category-item__thumbnail">
                                <?php echo wp_get_attachment_image($child_category['image'], 'full', false, array( 'class' => '')) ?>
                            </div>
                            <div class="header__mega-menu-product__child-category-item__content">
                                <p class="header__mega-menu-product__child-category-item__title">
                                    <?php echo $child_category['title'] ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="header__mega-menu-product__show-all">
            <a href="/" class="animated-btn header__mega-menu-product__show-all-btn">
                <div class="animated-btn-wrapper">
                    <div class="animated-btn__content-hidden">
                        <div class="animated-btn__content-hidden-text">Xem tất cả sản phẩm</div>
                        <span class="animated-btn__content-hidden-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => 'animated-btn__icon')) ?>
                        </span>
                    </div>
                    <div class="animated-btn__content-visible">
                        <div class="animated-btn__content-visible-text">Xem tất cả sản phẩm</div>
                        <span class="animated-btn__content-visible-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => 'animated-btn__icon')) ?>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>