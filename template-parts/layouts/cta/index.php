<?php
$cta = function_exists( 'get_field' ) ? get_field( 'cta', 'option' ) : [];
if ( ! is_array( $cta ) ) {
	$cta = [];
}
$cta_zalo      = $cta['link_zalo'] ?? null;
$cta_messenger = $cta['link_messenger'] ?? null;
$cta_hotline   = $cta['link_hotline'] ?? null;

$cta_service_items = [];
$cta_service_query = new WP_Query(
	[
		'post_type'      => 'service',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	]
);

if ( $cta_service_query->have_posts() ) {
	while ( $cta_service_query->have_posts() ) {
		$cta_service_query->the_post();
		$cta_service_items[] = [
			'title' => get_the_title(),
		];
	}
	wp_reset_postdata();
}
?>

<div class="cta-right">
    <div class="cta-right-container">
        <div class="cta-right__btn-contact-list">
            <!-- Zalo -->
            <?php if(!empty($cta_zalo) && !empty($cta_zalo['url'])): ?>
            <?php 
                $cta_zalo_url = $cta_zalo['url'];
                $cta_zalo_target = $cta_zalo['target'] ? $cta_zalo['target'] : '_self';
            ?>
            <a href="<?= $cta_zalo_url; ?>" target="<?= $cta_zalo_target; ?>" class="cta-right__btn-contact-item cta-right__btn-contact-item--zalo">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="11" viewBox="0 0 28 11" fill="none">
                <path d="M6.18493 2.00552L2.015 8.84254V8.99448H6.18493V10.8177H0V8.99448L4.19792 2.15746V2.00552H0V0.18232H6.18493V2.00552Z" fill="#F26C59"/>
                <path d="M6.58035 10.8177V10.4834L8.73528 0.18232L11.9817 0.197513L14.1506 10.4834V10.8177H12.3595L11.8137 7.90055H8.91719L8.37146 10.8177H6.58035ZM9.16906 6.25967H11.5479L10.7503 1.94475H9.98066L9.16906 6.25967Z" fill="#F26C59"/>
                <path d="M15.0258 0.18232H16.747V9.11602H19.6295V10.8177H15.0258V0.18232Z" fill="#F26C59"/>
                <path d="M19.8001 5.5C19.8001 4.21363 19.9913 3.16529 20.3738 2.35497C20.7563 1.54466 21.2553 0.952118 21.871 0.577348C22.4961 0.192449 23.1724 0 23.9 0C24.6277 0 25.2993 0.192449 25.915 0.577348C26.5401 0.952118 27.0438 1.54466 27.4263 2.35497C27.8088 3.16529 28 4.21363 28 5.5C28 6.78637 27.8088 7.83471 27.4263 8.64503C27.0438 9.45534 26.5401 10.0529 25.915 10.4378C25.2993 10.8126 24.6277 11 23.9 11C23.1724 11 22.4961 10.8126 21.871 10.4378C21.2553 10.0529 20.7563 9.45534 20.3738 8.64503C19.9913 7.83471 19.8001 6.78637 19.8001 5.5ZM21.5772 5.5C21.5772 6.33057 21.6798 7.01934 21.885 7.5663C22.0903 8.11326 22.3655 8.52855 22.7106 8.81215C23.0651 9.08564 23.4569 9.22238 23.886 9.22238C24.3152 9.22238 24.707 9.08564 25.0615 8.81215C25.4253 8.52855 25.7098 8.11326 25.915 7.5663C26.1296 7.01934 26.2369 6.33057 26.2369 5.5C26.2369 4.6593 26.1296 3.96547 25.915 3.41851C25.7005 2.87155 25.4159 2.46133 25.0615 2.18785C24.707 1.91436 24.3198 1.77762 23.9 1.77762C23.4616 1.77762 23.0651 1.91943 22.7106 2.20304C22.3655 2.47652 22.0903 2.88674 21.885 3.4337C21.6798 3.98066 21.5772 4.66943 21.5772 5.5Z" fill="#F26C59"/>
                </svg>
            </a>
            <?php endif; ?>
            
            <!-- Messenger -->
            <?php if(!empty($cta_messenger) && !empty($cta_messenger['url'])): ?>
            <?php 
                $cta_messenger_url = $cta_messenger['url'];
                $cta_messenger_target = $cta_messenger['target'] ? $cta_messenger['target'] : '_self';
            ?>
            <a href="<?= $cta_messenger_url; ?>" target="<?= $cta_messenger_target; ?>" class="cta-right__btn-contact-item cta-right__btn-contact-item--messenger">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                <path d="M14 1.12012C6.88843 1.12012 1.12 6.49699 1.12 13.1601C1.12 16.6886 2.74312 19.9917 5.6 22.2886V27.217L10.4169 24.697C11.5916 25.0317 12.7684 25.1432 14 25.1432C21.1116 25.1432 26.88 19.7686 26.88 13.1032C26.88 6.49699 21.1116 1.12012 14 1.12012ZM15.2884 17.137L12.04 13.6632L5.99156 17.0801L12.7116 9.96855L16.0169 13.2717L21.8969 9.96855L15.2884 17.137Z" fill="#CB5140"/>
                </svg>
            </a>
            <?php endif; ?>

            <!-- Hotline -->
            <?php if(!empty($cta_hotline) && !empty($cta_hotline['url'])): ?>
            <?php 
                $cta_hotline_url = $cta_hotline['url'];
                $cta_hotline_target = $cta_hotline['target'] ? $cta_hotline['target'] : '_self';
            ?>
            <a href="<?= $cta_hotline_url; ?>" target="<?= $cta_hotline_target; ?>" class="cta-right__btn-contact-item cta-right__btn-contact-item--hotline">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M22.0448 17.52C21.3892 16.9703 17.5448 14.5358 16.9058 14.6475C16.6058 14.7008 16.3763 14.9565 15.762 15.6893C15.4779 16.0499 15.166 16.3877 14.829 16.6995C14.2116 16.5504 13.614 16.3288 13.0485 16.0395C10.831 14.9599 9.03947 13.1679 7.9605 10.95C7.67118 10.3846 7.44964 9.78691 7.3005 9.1695C7.6123 8.83252 7.95009 8.52056 8.31075 8.2365C9.04275 7.62225 9.29925 7.39425 9.3525 7.09275C9.46425 6.45225 7.0275 2.60925 6.48 1.95375C6.2505 1.68225 6.042 1.5 5.775 1.5C5.001 1.5 1.5 5.829 1.5 6.39C1.5 6.43575 1.575 10.9425 7.26675 16.7333C13.0575 22.425 17.5642 22.5 17.61 22.5C18.171 22.5 22.5 18.999 22.5 18.225C22.5 17.958 22.3177 17.7495 22.0448 17.52ZM17.25 11.25H18.75C18.7482 9.65925 18.1155 8.13416 16.9907 7.00933C15.8658 5.8845 14.3408 5.25179 12.75 5.25V6.75C13.9431 6.75119 15.087 7.22568 15.9307 8.06933C16.7743 8.91299 17.2488 10.0569 17.25 11.25Z" fill="#CB5140"/>
                <path d="M21 11.25H22.5C22.497 8.66505 21.4688 6.18683 19.641 4.359C17.8132 2.53116 15.3349 1.50298 12.75 1.5V3C14.9372 3.00258 17.0342 3.8726 18.5808 5.41922C20.1274 6.96584 20.9974 9.06276 21 11.25Z" fill="#CB5140"/>
                </svg>
            </a>
            <?php endif; ?>
        </div>
        <button class="cta-right__btn-scroll-top">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
            <foreignObject x="-20" y="-20" width="88" height="88"><div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(10px);clip-path:url(#bgblur_0_2088_6962_clip_path);height:100%;width:100%"></div></foreignObject><g data-figma-bg-blur-radius="20">
                <rect x="0.5" y="0.5" width="47" height="47" rx="23.5" fill="#F6F3EA" fill-opacity="0.1"/>
                <rect x="0.5" y="0.5" width="47" height="47" rx="23.5" stroke="#CB5140"/>
                <path d="M24 34L24 18" stroke="#CB5140" stroke-width="3"/>
                <path d="M16.9502 25.2461L24.1915 18.0048L31.4327 25.2461" stroke="#CB5140" stroke-width="3"/>
            </g>
            <defs>
                <clipPath id="bgblur_0_2088_6962_clip_path" transform="translate(20 20)"><rect x="0.5" y="0.5" width="47" height="47" rx="23.5"/>
            </clipPath></defs>
            </svg>
        </button>
    </div>
</div>
<div class="cta-bottom">
    <div class="cta-bottom-container">
        <!-- Hotline -->
        <?php if(!empty($cta_hotline) && !empty($cta_hotline['url'])): ?>
        <?php 
            $cta_hotline_url = $cta_hotline['url'];
            $cta_hotline_target = $cta_hotline['target'] ? $cta_hotline['target'] : '_self';
            $cta_hotline_title = $cta_hotline['title'];
        ?>
        <a href="<?= $cta_hotline_url; ?>" target="<?= $cta_hotline_target; ?>" class="cta-bottom__btn-contact-phone">
            <span class="cta-bottom__btn-contact-phone__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                <path d="M17.4521 13.87C16.9332 13.4348 13.8896 11.5075 13.3837 11.5959C13.1462 11.6381 12.9645 11.8406 12.4783 12.4207C12.2534 12.7062 12.0064 12.9736 11.7396 13.2204C11.2508 13.1024 10.7777 12.927 10.3301 12.6979C8.57451 11.8432 7.15625 10.4246 6.30206 8.66875C6.07302 8.2211 5.89763 7.74797 5.77956 7.25919C6.0264 6.99241 6.29382 6.74545 6.57934 6.52056C7.15884 6.03428 7.36191 5.85378 7.40406 5.61509C7.49253 5.10803 5.56344 2.06566 5.13 1.54672C4.94831 1.33178 4.78325 1.1875 4.57187 1.1875C3.95912 1.1875 1.1875 4.61463 1.1875 5.05875C1.1875 5.09497 1.24687 8.66281 5.75284 13.2472C10.3372 17.7531 13.905 17.8125 13.9412 17.8125C14.3854 17.8125 17.8125 15.0409 17.8125 14.4281C17.8125 14.2167 17.6682 14.0517 17.4521 13.87ZM13.6562 8.90625H14.8438C14.8423 7.64691 14.3414 6.43955 13.4509 5.54905C12.5605 4.65856 11.3531 4.15766 10.0938 4.15625V5.34375C11.0383 5.34469 11.9439 5.72033 12.6118 6.38822C13.2797 7.05612 13.6553 7.96171 13.6562 8.90625Z" fill="#F6F3EA"/>
                <path d="M16.625 8.90625H17.8125C17.8101 6.85983 16.9962 4.89791 15.5491 3.45087C14.1021 2.00384 12.1402 1.18986 10.0938 1.1875V2.375C11.8253 2.37704 13.4854 3.06581 14.7098 4.29022C15.9342 5.51462 16.623 7.17468 16.625 8.90625Z" fill="#F6F3EA"/>
                </svg>
            </span>
            <div class="cta-bottom__btn-contact-phone__content">
                <p class="cta-bottom__btn-contact-phone__title">Thuê đồ ngay</p>
                <span class="cta-bottom__btn-contact-phone__subtitle"><?= $cta_hotline_title; ?></span>
            </div>
        </a>
        <?php endif; ?>

        <button type="button" class="cta-bottom__btn-service">
            <span class="cta-bottom__btn-service__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M4.82667 1.33325H3.56C2.1 1.33325 1.33334 2.09992 1.33334 3.55325V4.81992C1.33334 6.27325 2.1 7.03992 3.55334 7.03992H4.82C6.27334 7.03992 7.04 6.27325 7.04 4.81992V3.55325C7.04667 2.09992 6.28 1.33325 4.82667 1.33325Z" fill="#CB5140"/>
                <path d="M12.4467 1.33325H11.18C9.72667 1.33325 8.96 2.09992 8.96 3.55325V4.81992C8.96 6.27325 9.72667 7.03992 11.18 7.03992H12.4467C13.9 7.03992 14.6667 6.27325 14.6667 4.81992V3.55325C14.6667 2.09992 13.9 1.33325 12.4467 1.33325Z" fill="#CB5140"/>
                <path d="M12.4467 8.95337H11.18C9.72667 8.95337 8.96 9.72004 8.96 11.1734V12.44C8.96 13.8934 9.72667 14.66 11.18 14.66H12.4467C13.9 14.66 14.6667 13.8934 14.6667 12.44V11.1734C14.6667 9.72004 13.9 8.95337 12.4467 8.95337Z" fill="#CB5140"/>
                <path d="M4.82667 8.95337H3.56C2.1 8.95337 1.33334 9.72004 1.33334 11.1734V12.44C1.33334 13.9 2.1 14.6667 3.55334 14.6667H4.82C6.27334 14.6667 7.04 13.9 7.04 12.4467V11.18C7.04667 9.72004 6.28 8.95337 4.82667 8.95337Z" fill="#CB5140"/>
                </svg>
            </span>
            <p class="cta-bottom__btn-service__text">Dịch vụ</p>
        </button>
        <a href="<?= okhub_page_url('shop') ?>" class="cta-bottom__btn-product">
            <span class="cta-bottom__btn-product__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M14.43 4.18492H14.13L11.595 1.64992C11.3925 1.44742 11.0625 1.44742 10.8525 1.64992C10.65 1.85242 10.65 2.18242 10.8525 2.39242L12.645 4.18492H5.355L7.1475 2.39242C7.35 2.18992 7.35 1.85992 7.1475 1.64992C6.945 1.44742 6.615 1.44742 6.405 1.64992L3.8775 4.18492H3.5775C2.9025 4.18492 1.5 4.18492 1.5 6.10492C1.5 6.83242 1.65 7.31242 1.965 7.62742C2.145 7.81492 2.3625 7.91242 2.595 7.96492C2.8125 8.01742 3.045 8.02492 3.27 8.02492H14.73C14.9625 8.02492 15.18 8.00992 15.39 7.96492C16.02 7.81492 16.5 7.36492 16.5 6.10492C16.5 4.18492 15.0975 4.18492 14.43 4.18492Z" fill="#CB5140"/>
                <path d="M14.2875 9H3.6525C3.1875 9 2.835 9.4125 2.91 9.87L3.54 13.725C3.75 15.015 4.3125 16.5 6.81 16.5H11.0175C13.545 16.5 13.995 15.2325 14.265 13.815L15.0225 9.8925C15.1125 9.4275 14.76 9 14.2875 9ZM7.9575 13.8375C7.9575 14.13 7.725 14.3625 7.44 14.3625C7.1475 14.3625 6.915 14.13 6.915 13.8375V11.3625C6.915 11.0775 7.1475 10.8375 7.44 10.8375C7.725 10.8375 7.9575 11.0775 7.9575 11.3625V13.8375ZM11.1675 13.8375C11.1675 14.13 10.935 14.3625 10.6425 14.3625C10.3575 14.3625 10.1175 14.13 10.1175 13.8375V11.3625C10.1175 11.0775 10.3575 10.8375 10.6425 10.8375C10.935 10.8375 11.1675 11.0775 11.1675 11.3625V13.8375Z" fill="#CB5140"/>
                </svg>
            </span>
            <p class="cta-bottom__btn-product__text">Sản phẩm</p>
        </a>
    </div>
</div>
<div class="service-drawer-mobile">
    <div class="service-drawer-mobile__overlay"></div>
    <div class="service-drawer-mobile__content">
        <div class="service-drawer-mobile__content-header">
            <p class="service-drawer-mobile__content-title">
                Dịch vụ của chúng tôi
            </p>
            <button type="button" class="service-drawer-mobile__button-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M5.00098 5L19 18.9991" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.99996 18.9991L18.999 5" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <div class="service-drawer-mobile__content-body">
            <ul class="service-drawer-mobile__service-list">
                <?php foreach ( $cta_service_items as $cta_service_item ) : ?>
                    <?php
                    $item_title = $cta_service_item['title'] ?? '';
                    if ( $item_title === '' ) {
                        continue;
                    }
                    ?>
                <li class="service-drawer-mobile__service-item">
                    <div class="service-drawer-mobile__service-item__header">
                        <p class="service-drawer-mobile__service-item__title"><?= $item_title; ?></p>
                        <span class="service-drawer-mobile__service-item__arrow-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M6.75 14.625L12.3752 8.86629L6.75 3.375" stroke="#1D1D1D" stroke-opacity="0.8" stroke-width="1.2"/>
                            </svg>
                        </span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>