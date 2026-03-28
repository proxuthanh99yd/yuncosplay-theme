<?php
$header      = function_exists( 'get_field' ) ? get_field( 'header', 'option' ) : null;
$header      = is_array( $header ) ? $header : [];
$header_logo = $header['logo_image'] ?? null;
$header_menu = isset( $header['menu'] ) && is_array( $header['menu'] ) ? $header['menu'] : [];
$header_outstanding_products = isset( $header['outstanding_products'] ) && is_array( $header['outstanding_products'] ) ? $header['outstanding_products'] : [];
$header_contact = isset( $header['contact'] ) && is_array( $header['contact'] ) ? $header['contact'] : [];
$contact_socials = $header_contact['contact_socials'] ?? [];
?>
<div class="header-menu" data-lenis-prevent>
    <div class="header-menu__header">
        <a href="/" class="header-menu__header-logo">
            <?php if (!empty($header_logo)) : ?>
                <?php echo wp_get_attachment_image( $header_logo, 'full', false, array( 'class' => 'header-menu__header-logo-image' ) ); ?>
            <?php endif; ?>
        </a>
        <button class="header-menu__header-close-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M5.00098 5L19 18.9991" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M4.99996 18.9991L18.999 5" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    <div class="header-menu__content">
        <nav class="header-menu__nav-list-wrapper">
            <ul class="header-menu__nav-list">
                <?php
                if ( ! empty( $header_menu ) ) :
                    foreach ( $header_menu as $item ) :
                        $type  = $item['menu_item_type'] ?? '';
                        $label = $item['menu_item_label'] ?? '';
                        $link  = $item['menu_item_link'] ?? [];
                        $mega  = $item['menu_item_mega'] ?? '';
                        $url   = ! empty( $link['url'] ) ? esc_url( $link['url'] ) : '#';
                        $target = ! empty( $link['target'] ) ? esc_attr( $link['target'] ) : '_self';
                        $label = $label !== '' ? $label : ( $link['title'] ?? '' );

                        if ( $type === 'mega_menu' ) :
                            $trigger = ( $mega === 'mega_service' ) ? 'header-service' : 'header-product';
                            ?>
                            <li class="header-menu__nav-item">
                                <p class="header-menu__nav-item__link" data-trigger="<?php echo esc_attr( $trigger ); ?>">
                                    <span class="header-menu__nav-item__link-text"><?php echo esc_html( $label ); ?></span>
                                    <span class="header-menu__nav-item__link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M6.75 14.625L12.3752 8.86629L6.75 3.375" stroke="#1D1D1D" stroke-opacity="0.8" stroke-width="1.2"/></svg>
                                    </span>
                                </p>
                            </li>
                        <?php else : ?>
                            <li class="header-menu__nav-item">
                                <a href="<?php echo $url; ?>" target="<?php echo $target; ?>" class="header-menu__nav-item__link">
                                    <span class="header-menu__nav-item__link-text"><?php echo esc_html( $label ); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <div class="header-menu__footer">
        <?php if(!empty($header_outstanding_products['product_items'])): ?>
        <div class="header-menu__outstanding-product">
            <div class="header-menu__outstanding-product-top">
                <p class="header-menu__outstanding-product-title">
                    <?php echo $header_outstanding_products['title'] ?? ''; ?>
                </p>
                <div class="header-menu__outstanding-product-swiper-pagination"></div>
            </div>
            <div class="swiper header-menu__outstanding-product-swiper">
                <div class="swiper-wrapper header-menu__outstanding-product-swiper-wrapper">
                    <?php foreach ( $header_outstanding_products['product_items'] as $product_id ) : ?>
                        <?php
                        $post = $product_id;
                        setup_postdata( $post );
                        ?>
                        <div class="swiper-slide header-menu__outstanding-product-swiper-slide">
                            <?php get_template_part( 'template-parts/components/product/index' ); ?>
                        </div>
                    <?php endforeach; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <a href="/" class="header-menu__direction-btn">
            <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => 'Chỉ đường đến cửa hàng')); ?>
        </a>
        <div class="header-menu__social-list-wrapper">
            <ul class="header-menu__social-list">
                <?php foreach($contact_socials as $social): ?>
                <?php 
                $social_link = $social['social_link'];
                $social_link_url = $social_link['url'];
                $social_link_target = ! empty( $social_link['target'] ) ? $social_link['target'] : '_self';
                $social_icon = $social['social_icon_mobile'] ?? null;
                if(!empty($social_link_url) && !empty($social_icon)):
                ?>
                    <li class="header-menu__social-item">
                        <a href="<?php echo esc_url( $social_link_url ); ?>" target="<?php echo esc_attr( $social_link_target ); ?>" class="header-menu__social-link">
                            <?php echo wp_get_attachment_image( $social_icon, 'full', false, array( 'class' => '' ) ); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>