<?php
$header      = function_exists( 'get_field' ) ? get_field( 'header', 'option' ) : null;
$header      = is_array( $header ) ? $header : [];
$header_logo = $header['logo_image'] ?? null;
$header_menu = isset( $header['menu'] ) && is_array( $header['menu'] ) ? $header['menu'] : [];
$header_outstanding_products = isset( $header['outstanding_products'] ) && is_array( $header['outstanding_products'] ) ? $header['outstanding_products'] : [];
$header_contact = isset( $header['contact'] ) && is_array( $header['contact'] ) ? $header['contact'] : [];
$contact_socials = $header_contact['contact_socials'] ?? [];
// Icon menu → file tĩnh theme (okhub_img). Logo/thumbnail vẫn từ CMS.
$icon_list_disc_id = 71;

if (! function_exists('okhub_header_get_first_related_post_id')) {
	function okhub_header_get_first_related_post_id($value) {
		if ($value instanceof WP_Post) {
			return (int) $value->ID;
		}

		if (is_numeric($value)) {
			return (int) $value;
		}

		if (is_array($value)) {
			$first_value = reset($value);
			return okhub_header_get_first_related_post_id($first_value);
		}

		return 0;
	}
}

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
                        $mega_links = isset( $item['menu_link_mega'] ) && is_array( $item['menu_link_mega'] ) ? array_values( array_filter( $item['menu_link_mega'], function ( $mega_link_item ) {
                            return ! empty( $mega_link_item['link']['url'] );
                        } ) ) : [];
                        $has_template_mega = $type === 'mega_menu' && in_array($mega, ['mega_service', 'mega_product'], true);
						$has_link_mega = ! empty($mega_links) && ($type !== 'mega_menu' || ! $has_template_mega);
                        $url   = ! empty( $link['url'] ) ? esc_url( $link['url'] ) : '#';
                        $target = ! empty( $link['target'] ) ? esc_attr( $link['target'] ) : '_self';
                        $label = $label !== '' ? $label : ( $link['title'] ?? '' );

                        if ( $type === 'mega_menu' || $type === 'menu_link_mega' ) :
                            if ( ! empty( $mega_links ) && !($mega === 'mega_service') ) :
                            ?>
                            <li class="header-menu__nav-item header-menu__nav-item--dropdown">
                                <details class="header-menu__nav-dropdown">
                                    <summary class="header-menu__nav-item__link header-menu__nav-item__link--dropdown">
                                        <span class="header-menu__nav-item__link-text"><?php echo esc_html( $label ); ?></span>
                                        <span class="header-menu__nav-item__link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3.375 6.75L9 12.3752L14.625 6.75" stroke="#1D1D1D" stroke-opacity="0.8" stroke-width="1.2"/></svg>
                                        </span>
                                    </summary>
                                    <ul class="header-menu__nav-dropdown-list">
                                        <?php foreach ( $mega_links as $mega_link_item ) : ?>
                                            <?php
                                            $dropdown_link = $mega_link_item['link'] ?? [];
                                            $dropdown_url = ! empty( $dropdown_link['url'] ) ? esc_url( $dropdown_link['url'] ) : '';
                                            $dropdown_target = ! empty( $dropdown_link['target'] ) ? esc_attr( $dropdown_link['target'] ) : '_self';
                                            $dropdown_label = $dropdown_link['title'] ?? '';
                                            if ( $dropdown_url === '' || $dropdown_label === '' ) {
                                                continue;
                                            }
                                            ?>
                                            <li class="header-menu__nav-dropdown-item">
                                                <a href="<?php echo $dropdown_url; ?>" target="<?php echo $dropdown_target; ?>" class="header-menu__nav-dropdown-link">
                                                    <?php echo esc_html( $dropdown_label ); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </details>
                            </li>
                            <?php
                            else :
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
                            <?php if ($has_link_mega && $mega === 'mega_service') :?>
                                <div class="header-service test">
                                    <div class="header-service__header">
                                        <div class="header-service__title-wrapper" data-close="header-service">
                                            <span class="header-service__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path d="M12.5 16.25L6.2498 9.85143L12.5 3.75" stroke="#1D1D1D" stroke-width="2"/>
                                                </svg>
                                            </span>
                                            <h2 class="header-service__title">Dịch vụ</h2>
                                        </div>
                                        <button class="header-service__close-btn" data-close="header-service">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M5.00098 5L19 18.9991" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M4.99996 18.9991L18.999 5" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="header-service__content" data-lenis-prevent>
                                        <ul class="header-service__service-list">
                                            <?php foreach ($mega_links as $index => $service_item): ?>
                                            <?php 
                                                $service_id = okhub_header_get_first_related_post_id($service_item['service'] ?? null);
                                                $service_title = $service_item['link']['title'] ?? '';
                                                $service_description = get_the_excerpt($service_id) ?? '';
                                                $service_thumbnail = $service_item['image']['ID'] ?? null;
                                                $service_link = $service_item['link']['url'] ?? '';
                                                $service_offer = $service_id && function_exists('get_field') ? (get_field('service_offer', $service_id) ?: []) : [];
                                                $service_offer_title = $service_offer['title'] ?? $service_title;
                                                $service_offer_subtitle = $service_item['service_offer_subtitle'] ?? null;
                                                $service_offer_items = isset($service_offer['offer_items']) && is_array($service_offer['offer_items']) ? $service_offer['offer_items'] : [];
                                                // $service_offer_items = $service_item['service_offer_items'] ?? [];
                                            ?>
                                            <li class="header-service__service-item <?= $index === 0 ? 'header-service__service-item--active' : '' ?>">
                                                <div class="header-service__service-item__header">
                                                    <h3 class="header-service__service-item__title">
                                                        <?= $service_title ?>
                                                    </h3>
                                                    <span class="header-service__service-item__icon-chevron">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                        <path d="M3.75 6L9.50871 11.6252L15 6" stroke="#680103" stroke-width="2"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="header-service__service-item__content">
                                                    <div class="header-service__service-item__description">
                                                        <?= $service_description ?? ""; ?>
                                                    </div>
                                                    <div class="header-service__service-item__thumbnail">
                                                        <?php echo wp_get_attachment_image($service_thumbnail, 'full', false, array( 'class' => 'header-service__service-item__thumbnail-image')) ?>
                                                        <div class="header-service__service-item__thumbnail-content">
                                                            <h4 class="header-service__service-item__subtitle">
                                                                <?= $service_offer_subtitle ?>
                                                            </h4>
                                                            <h4 class="header-service__service-item__offer-title">
                                                                <?= $service_offer_title ?>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <ul class="header-service__service-item__offer-list">
                                                        <?php if(!empty($service_offer_items)): ?>
                                                        <?php foreach ($service_offer_items as $service_offer_item): ?>
                                                            <li class="header-service__service-item__offer-item">
                                                            <span class="header-service__service-item__offer-item__icon">
                                                                <?php echo okhub_img('icons/icon') ?>
                                                            </span>
                                                            <div class="header-service__service-item__offer-item__text">
                                                                <?= $service_offer_item['offer_item'] ?? '' ?>
                                                            </div>
                                                        </li>
                                                        <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                    <a href="<?= $service_link ?>" class="header-service__service-item__link">
                                                        <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => 'Chi tiết dịch vụ')); ?>
                                                    </a>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php endif; ?>
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
