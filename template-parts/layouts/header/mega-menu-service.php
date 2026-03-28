<?php 
$icon_list_disc_id = 71;
$icon_arrow_right_id = 69;
$image_service_banner_id = 72;
$icon_arrow_right_orange_id = 73;
?>

<div data-mega-menu-content="mega-menu-service" class="header__mega-menu-service header__mega-menu-item">
    <div class="header__mega-menu-service-wrapper">
        <div class="header__mega-menu-service-left">
            <ul data-lenis-prevent class="header__mega-menu-service__service-list">
                <li class="header__mega-menu-service__service-item">
                    <a href="/" class="header__mega-menu-service__service-link">
                        <span class="header__mega-menu-service__service-link-text">Chụp ảnh</span>
                        <span class="header__mega-menu-service__service-link-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false) ?>
                        </span>
                    </a>
                </li>
                <li class="header__mega-menu-service__service-item header__mega-menu-service__service-item--active">
                    <a href="/" class="header__mega-menu-service__service-link">
                        <span class="header__mega-menu-service__service-link-text">Trang điểm</span>
                        <span class="header__mega-menu-service__service-link-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false) ?>
                        </span>
                    </a>
                </li>
                <li class="header__mega-menu-service__service-item">
                    <a href="/" class="header__mega-menu-service__service-link">
                        <span class="header__mega-menu-service__service-link-text">May đồ</span>
                        <span class="header__mega-menu-service__service-link-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false) ?>
                        </span>
                    </a>
                </li>
                <li class="header__mega-menu-service__service-item">
                    <a href="/" class="header__mega-menu-service__service-link">
                        <span class="header__mega-menu-service__service-link-text">Thuê đồ</span>
                        <span class="header__mega-menu-service__service-link-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false) ?>
                        </span>
                    </a>
                </li>
                <li class="header__mega-menu-service__service-item">
                    <a href="/" class="header__mega-menu-service__service-link">
                        <span class="header__mega-menu-service__service-link-text">Người mẫu</span>
                        <span class="header__mega-menu-service__service-link-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false) ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="header__mega-menu-service-right">
            <div class="header__mega-menu-service__banner">
                <div class="header__mega-menu-service__banner-background">
                    <?php echo wp_get_attachment_image($image_service_banner_id, 'full', false) ?>
                </div>
                <div class="header__mega-menu-service__banner-content">
                    <div class="header__mega-menu-service__banner-content-left">
                        <div class="header__mega-menu-service__banner-title">
                            <p>Makeup artist <strong>10 năm</strong> kinh nghiệm</p>
                        </div>
                        <ul class="header__mega-menu-service__banner-service-list">
                            <li class="header__mega-menu-service__banner-service-item">
                                <a href="/" class="header__mega-menu-service__banner-service-link">
                                    <span class="header__mega-menu-service__banner-service-item-icon">
                                        <?php echo wp_get_attachment_image($icon_list_disc_id, 'full', false, array( 'class' => '')) ?>
                                    </span>
                                    <span class="header__mega-menu-service__banner-service-item-text"> Mỹ phẩm chính hãng. </span>
                                </a>
                            </li>
                            <li class="header__mega-menu-service__banner-service-item">
                                <a href="/" class="header__mega-menu-service__banner-service-link">
                                    <span class="header__mega-menu-service__banner-service-item-icon">
                                        <?php echo wp_get_attachment_image($icon_list_disc_id, 'full', false, array( 'class' => '')) ?>
                                    </span>
                                    <span class="header__mega-menu-service__banner-service-item-text">
                                        Make up theo mọi layout (dự tiệc, cosplay, kỷ yếu, hóa trang halloween, giả vết thương,..)
                                    </span>
                                </a>
                            </li>
                            <li class="header__mega-menu-service__banner-service-item">
                                <a href="/" class="header__mega-menu-service__banner-service-link">
                                    <span class="header__mega-menu-service__banner-service-item-icon">
                                        <?php echo wp_get_attachment_image($icon_list_disc_id, 'full', false, array( 'class' => '')) ?>
                                    </span>
                                    <span class="header__mega-menu-service__banner-service-item-text"> Trọn gói có đi kèm làm tóc miễn phí </span>
                                </a>
                            </li>
                            <li class="header__mega-menu-service__banner-service-item">
                                <a href="/" class="header__mega-menu-service__banner-service-link">
                                    <span class="header__mega-menu-service__banner-service-item-icon">
                                        <?php echo wp_get_attachment_image($icon_list_disc_id, 'full', false, array( 'class' => '')) ?>
                                    </span>
                                    <span class="header__mega-menu-service__banner-service-item-text">
                                        Tư vấn trang điểm tận tình phù hợp gương mặt, tôn lên ưu điểm, lấp đi khuyết điểm.
                                    </span>
                                </a>
                            </li>
                            <li class="header__mega-menu-service__banner-service-item">
                                <a href="/" class="header__mega-menu-service__banner-service-link">
                                    <span class="header__mega-menu-service__banner-service-item-icon">
                                        <?php echo wp_get_attachment_image($icon_list_disc_id, 'full', false, array( 'class' => '')) ?>
                                    </span>
                                    <span class="header__mega-menu-service__banner-service-item-text">
                                        Tư vấn trang điểm tận tình phù hợp gương mặt, tôn lên ưu điểm, lấp đi khuyết điểm.
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="header__mega-menu-service-banner__content-right">
                        <a href="/" class="header__mega-menu-service-banner__btn-details">
                            <span class="header__mega-menu-service-banner__btn-details-icon">
                                <?php echo wp_get_attachment_image($icon_arrow_right_orange_id, 'full', false) ?>
                            </span>
                            <span class="header__mega-menu-service-banner__btn-details-text">Xem chi tiết</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
