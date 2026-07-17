<?php

/**
 * Section: Product Detail — Main Content
 * Figma node: 514:7407
 */

global $product;

if (!$product) {
    $product = wc_get_product(get_the_ID());
}

if (!$product) return;

$product_id    = $product->get_id();
$product_title = get_the_title($product_id);

// Category
$categories    = get_the_terms($product_id, 'product_cat');
$category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';

// Stock
$stock_qty = $product->get_stock_quantity();

// Prices
// Rent price: ONLY from regular_price (no fallback)
$rent_price_raw = $product->get_regular_price();
$rent_price = number_format((float) ($rent_price_raw ?: 0), 0, '.', '.');

// Sale price: ONLY from _sale_price_custom (no fallback)
$sale_price_raw = get_post_meta($product_id, '_sale_price_custom', true);
$sale_price = number_format((float) ($sale_price_raw ?: 0), 0, '.', '.');

// Short description (sale price card)
$short_description = $product->get_short_description();

// Rental price card — ACF
$rental_price_description = function_exists('get_field') ? get_field('rental_price_description', $product_id) : '';

// Full description
$description = $product->get_description();

// Gallery images
$gallery_ids   = $product->get_gallery_image_ids();
$thumbnail_id  = get_post_thumbnail_id($product_id);

// Video from ACF field (inserted as 2nd item in gallery)
$video_url = function_exists('get_field') ? get_field('video', $product_id) : '';

// Video poster: use 2nd gallery image (first item in $gallery_ids)
$video_poster_url = '';
$video_thumb_url  = '';
if ($video_url && !empty($gallery_ids)) {
    $video_poster_url = wp_get_attachment_image_url($gallery_ids[0], 'large') ?: '';
    $video_thumb_url  = wp_get_attachment_image_url($gallery_ids[0], 'thumbnail') ?: '';
}

// Build media list: featured image + video (2nd) + remaining gallery images
$media_items = [];
if ($thumbnail_id) {
    $media_items[] = ['type' => 'image', 'id' => $thumbnail_id];
}
if ($video_url) {
    $media_items[] = ['type' => 'video', 'url' => $video_url, 'poster' => $video_poster_url, 'thumb' => $video_thumb_url];
}
if (!empty($gallery_ids)) {
    foreach ($gallery_ids as $gid) {
        $media_items[] = ['type' => 'image', 'id' => $gid];
    }
}

// Also keep flat image IDs for desktop gallery (skips video)
$all_image_ids = [];
if ($thumbnail_id) {
    $all_image_ids[] = $thumbnail_id;
}
if (!empty($gallery_ids)) {
    $all_image_ids = array_merge($all_image_ids, $gallery_ids);
}

// Contact info
$phone_number   = '(+84) 79 409 888';
$zalo_link      = 'https://zalo.me/0794098888';
$messenger_link = 'https://m.me/yuncosplay';

$icon_path = '/assets/images/single-product';
?>

<section class="product-detail">
    <!-- Mobile Story Gallery -->
    <?php if (!empty($media_items)) : ?>
        <div class="product-detail__story">
            <div class="product-detail__story-viewer">
                <!-- Gradient overlay -->
                <div class="product-detail__story-gradient" aria-hidden="true"></div>
                <!-- Progress bars -->
                <!--<div class="product-detail__story-progress">-->
                <!--    <?php foreach ($media_items as $idx => $item) : ?>-->
                <!--        <div class="product-detail__story-bar<?php echo $idx === 0 ? ' is-active' : ''; ?>">-->
                <!--            <div class="product-detail__story-bar-fill"></div>-->
                <!--        </div>-->
                <!--    <?php endforeach; ?>-->
                <!--</div>-->
                <!-- Media slides -->
                <?php foreach ($media_items as $idx => $item) :
                    if ($item['type'] === 'video') :
                ?>
                        <div class="product-detail__story-slide<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                            data-index="<?php echo esc_attr($idx); ?>" data-type="video">
                            <video class="product-detail__story-video" data-src="<?php echo esc_url($item['url']); ?>"
                                <?php if (!empty($item['poster'])) : ?>poster="<?php echo esc_url($item['poster']); ?>"
                                <?php endif; ?> playsinline muted preload="none"></video>
                        </div>
                    <?php else : ?>
                        <div class="product-detail__story-slide<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                            data-index="<?php echo esc_attr($idx); ?>" data-type="image">
                            <?php echo wp_get_attachment_image($item['id'], 'large', false, okhub_image_attrs([
                                'class'    => 'product-detail__story-img',
                            ], $idx === 0 && IS_MOBILE ? 'lcp' : 'lazy')); ?>
                        </div>
                <?php endif;
                endforeach; ?>
            </div>
            <!-- Thumbnail strip -->
            <div class="product-detail__story-thumbs">
                <?php foreach ($media_items as $idx => $item) :
                    if ($item['type'] === 'video') :
                        $video_thumb = !empty($item['thumb']) ? $item['thumb'] : '';
                ?>
                        <button class="product-detail__story-thumb<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                            data-index="<?php echo esc_attr($idx); ?>" type="button"
                            aria-label="<?php echo esc_attr(sprintf('Xem video %d', $idx + 1)); ?>">
                            <?php if ($video_thumb) : ?>
                                <img src="<?php echo esc_url($video_thumb); ?>" alt="Video" loading="lazy" decoding="async" />
                            <?php endif; ?>
                        </button>
                    <?php else : ?>
                        <button class="product-detail__story-thumb<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                            data-index="<?php echo esc_attr($idx); ?>" type="button"
                            aria-label="<?php echo esc_attr(sprintf('Xem ảnh %d', $idx + 1)); ?>">
                            <?php echo wp_get_attachment_image($item['id'], 'thumbnail', false, okhub_image_attrs()); ?>
                        </button>
                <?php endif;
                endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="product-detail__wrapper">
        <!-- Left Column: Image Gallery (Desktop) -->
        <div class="product-detail__gallery">
            <?php
            $total_media  = count($media_items);
            $media_index  = 0;
            // Ảnh ĐẦU TIÊN của gallery desktop là LCP. Dùng cờ chứ không so index === 0
            // vì item đầu có thể là video — lúc đó ưu tiên phải rơi vào ảnh kế tiếp.
            $hero_img_done = false;

            while ($media_index < $total_media) :
                // Gallery row: 2 items side by side
                if ($media_index + 1 < $total_media) :
            ?>
                    <div class="product-detail__gallery-row">
                        <?php for ($i = 0; $i < 2 && ($media_index + $i) < $total_media; $i++) :
                            $m = $media_items[$media_index + $i];
                        ?>
                            <div class="product-detail__gallery-item">
                                <?php if ($m['type'] === 'video') : ?>
                                    <div class="product-detail__gallery-link product-detail__gallery-video-wrap" data-video>
                                        <video class="product-detail__gallery-img" data-src="<?php echo esc_url($m['url']); ?>"
                                            <?php if (!empty($m['poster'])) : ?>poster="<?php echo esc_url($m['poster']); ?>"
                                            <?php endif; ?> playsinline muted loop preload="none"></video>
                                        <button class="product-detail__video-play" type="button" aria-label="Play video">
                                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="24" cy="24" r="24" fill="rgba(0,0,0,0.45)" />
                                                <polygon points="19,14 36,24 19,34" fill="#fff" />
                                            </svg>
                                        </button>
                                    </div>
                                <?php else :
                                    $img_full = wp_get_attachment_image_url($m['id'], 'full');
                                    $is_hero  = !$hero_img_done && !IS_MOBILE;
                                    $hero_img_done = true;
                                ?>
                                    <a href="<?php echo esc_url($img_full); ?>" class="product-detail__gallery-link"
                                        data-lightbox="product-gallery">
                                        <?php echo wp_get_attachment_image($m['id'], 'large', false, okhub_image_attrs([
                                            'class'    => 'product-detail__gallery-img',
                                        ], $is_hero ? 'lcp' : 'lazy')); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php
                    $media_index += 2;
                else :
                    $media_index++;
                    continue;
                endif;

                // Large item (single, full-width)
                if ($media_index < $total_media) :
                    $m = $media_items[$media_index];
                ?>
                    <div class="product-detail__large-image">
                        <?php if ($m['type'] === 'video') : ?>
                            <div class="product-detail__gallery-link product-detail__gallery-video-wrap" data-video>
                                <video class="product-detail__large-img" data-src="<?php echo esc_url($m['url']); ?>"
                                    <?php if (!empty($m['poster'])) : ?>poster="<?php echo esc_url($m['poster']); ?>"
                                    <?php endif; ?> playsinline muted loop preload="none"></video>
                                <button class="product-detail__video-play" type="button" aria-label="Play video">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <path d="M1.28564 0L16.7142 9L1.28564 18V0Z" fill="white" />
                                    </svg>
                                </button>
                            </div>
                        <?php else :
                            $img_full    = wp_get_attachment_image_url($m['id'], 'full');
                            $img_caption = wp_get_attachment_caption($m['id']);
                            $is_hero     = !$hero_img_done && !IS_MOBILE;
                            $hero_img_done = true;
                        ?>
                            <a href="<?php echo esc_url($img_full); ?>" class="product-detail__gallery-link"
                                data-lightbox="product-gallery">
                                <?php echo wp_get_attachment_image($m['id'], 'large', false, okhub_image_attrs([
                                    'class'    => 'product-detail__large-img',
                                ], $is_hero ? 'lcp' : 'lazy')); ?>
                                <?php if ($img_caption) : ?>
                                    <span class="product-detail__large-caption"><?php echo esc_html($img_caption); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
            <?php
                    $media_index++;
                endif;
            endwhile;
            ?>
        </div>

        <!-- Right Column: Product Info -->
        <div class="product-detail__info">
            <div class="product-detail__info-sticky">
                <!-- Header -->
                <div class="product-detail__header">
                    <div class="product-detail__category-row">
                        <?php if ($category_name) : ?>
                            <span class="product-detail__category"><?php echo esc_html($category_name); ?></span>
                        <?php endif; ?>
                        <?php if ($stock_qty !== null) : ?>
                            <span class="product-detail__stock-badge">
                                <?php echo esc_html(sprintf('Còn %d sản phẩm', $stock_qty)); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h1 class="product-detail__title"><?php echo esc_html($product_title); ?></h1>
                </div>

                <!-- Price Section -->
                <div class="product-detail__prices">
                    <!-- Rental Price Card -->
                    <div class="product-detail__price-card">
                        <div class="product-detail__price-row">
                            <span class="product-detail__price-label">Giá thuê (1 ngày):</span>
                            <span class="product-detail__price-value"><?php echo esc_html($rent_price); ?>đ</span>
                        </div>
                        <hr class="product-detail__price-divider" />
                        <?php if ($rental_price_description) : ?>
                            <div class="product-detail__price-desc"><?php echo wp_kses_post($rental_price_description); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Sale Price Card -->
                    <div class="product-detail__price-card">
                        <div class="product-detail__price-row">
                            <span class="product-detail__price-label">Giá bán:</span>
                            <span class="product-detail__price-value"><?php echo esc_html($sale_price); ?>đ</span>
                        </div>
                        <hr class="product-detail__price-divider" />
                        <?php if ($short_description) : ?>
                            <div class="product-detail__price-desc"><?php echo wp_kses_post($short_description); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CTA -->
                <div class="product-detail__cta">
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_number)); ?>"
                        class="product-detail__cta-btn">
                        <span class="product-detail__cta-btn-top">
                            <span class="product-detail__cta-btn-text">Thuê đồ ngay</span>
                        </span>
                        <span class="product-detail__cta-btn-phone">Gọi: <?php echo esc_html($phone_number); ?></span>
                    </a>
                    <div class="product-detail__contact">
                        <span class="product-detail__contact-label">Hoặc liên hệ</span>
                        <div class="product-detail__contact-icons">
                            <a href="<?php echo esc_url($zalo_link); ?>" target="_blank" rel="noopener noreferrer"
                                class="product-detail__contact-icon" aria-label="Zalo">
                                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="13" viewBox="0 0 33 13"
                                    fill="none">
                                    <path
                                        d="M7.21575 2.33978L2.35083 10.3163V10.4936H7.21575V12.6206H0V10.4936L4.89757 2.51703V2.33978H0V0.212707H7.21575V2.33978Z"
                                        fill="#F6F3EA" />
                                    <path
                                        d="M7.67707 12.6206V12.2307L10.1912 0.212707L13.9786 0.230432L16.509 12.2307V12.6206H14.4194L13.7827 9.21731H10.4034L9.7667 12.6206H7.67707ZM10.6972 7.30295H13.4725L12.542 2.26888H11.6441L10.6972 7.30295Z"
                                        fill="#F6F3EA" />
                                    <path d="M17.5301 0.212707H19.5381V10.6354H22.9011V12.6206H17.5301V0.212707Z"
                                        fill="#F6F3EA" />
                                    <path
                                        d="M23.1001 6.41667C23.1001 4.9159 23.3232 3.69283 23.7694 2.74747C24.2156 1.8021 24.7979 1.1108 25.5162 0.673573C26.2454 0.224524 27.0345 0 27.8834 0C28.7323 0 29.5159 0.224524 30.2342 0.673573C30.9634 1.1108 31.5511 1.8021 31.9973 2.74747C32.4436 3.69283 32.6667 4.9159 32.6667 6.41667C32.6667 7.91743 32.4436 9.1405 31.9973 10.0859C31.5511 11.0312 30.9634 11.7284 30.2342 12.1775C29.5159 12.6147 28.7323 12.8333 27.8834 12.8333C27.0345 12.8333 26.2454 12.6147 25.5162 12.1775C24.7979 11.7284 24.2156 11.0312 23.7694 10.0859C23.3232 9.1405 23.1001 7.91743 23.1001 6.41667ZM25.1734 6.41667C25.1734 7.38567 25.2931 8.18923 25.5325 8.82735C25.772 9.46547 26.093 9.94997 26.4957 10.2808C26.9093 10.5999 27.3664 10.7594 27.867 10.7594C28.3677 10.7594 28.8248 10.5999 29.2384 10.2808C29.6628 9.94997 29.9948 9.46547 30.2342 8.82735C30.4845 8.18923 30.6097 7.38567 30.6097 6.41667C30.6097 5.43585 30.4845 4.62638 30.2342 3.98826C29.9839 3.35014 29.6519 2.87155 29.2384 2.55249C28.8248 2.23343 28.3731 2.07389 27.8834 2.07389C27.3718 2.07389 26.9093 2.23933 26.4957 2.57021C26.093 2.88927 25.772 3.36786 25.5325 4.00599C25.2931 4.64411 25.1734 5.44767 25.1734 6.41667Z"
                                        fill="#F6F3EA" />
                                </svg>
                            </a>
                            <a href="<?php echo esc_url($messenger_link); ?>" target="_blank" rel="noopener noreferrer"
                                class="product-detail__contact-icon" aria-label="Messenger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31"
                                    fill="none">
                                    <path
                                        d="M15.0267 0C6.72984 0 0 6.27302 0 14.0467C0 18.1632 1.89365 22.0168 5.22667 24.6965V30.4464L10.8464 27.5064C12.2168 27.8968 13.5898 28.027 15.0267 28.027C23.3235 28.027 30.0533 21.7565 30.0533 13.9803C30.0533 6.27302 23.3235 0 15.0267 0ZM16.5298 18.6864L12.74 14.6336L5.68349 18.62L13.5235 10.3232L17.3797 14.1768L24.2397 10.3232L16.5298 18.6864Z"
                                        fill="#F6F3EA" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Separator -->
                <hr class="product-detail__separator" />

                <!-- Description Section -->
                <?php if ($description) : ?>
                    <div class="product-detail__description">
                        <h2 class="product-detail__description-title">Mô tả sản phẩm</h2>
                        <div class="product-detail__description-content wp-block-editor">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sticky Bottom Bar (Mobile) -->
    <div class="product-detail__sticky-bar">
        <div class="product-detail__sticky-col">
            <span class="product-detail__sticky-label">Giá thuê</span>
            <span class="product-detail__sticky-price"><?php echo esc_html($rent_price); ?>đ</span>
        </div>
        <div class="product-detail__sticky-col">
            <span class="product-detail__sticky-label">Giá bán</span>
            <span class="product-detail__sticky-price"><?php echo esc_html($sale_price); ?>đ</span>
        </div>
        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_number)); ?>"
            class="product-detail__sticky-cta">
            <span class="product-detail__sticky-cta-text">Thuê đồ ngay</span>
            <span class="product-detail__sticky-cta-phone">Gọi: <?php echo esc_html($phone_number); ?></span>
        </a>
    </div>

    <!-- Floating Side Icons (Mobile) -->
    <div class="product-detail__side-icons">
        <a href="<?php echo esc_url($zalo_link); ?>" target="_blank" rel="noopener noreferrer"
            class="product-detail__side-icon" aria-label="Zalo">
            <img src="<?php echo esc_url(get_theme_file_uri($icon_path . '/ic-zalo.svg')); ?>" alt="" aria-hidden="true"
                width="20" height="20" />
        </a>
        <a href="<?php echo esc_url($messenger_link); ?>" target="_blank" rel="noopener noreferrer"
            class="product-detail__side-icon" aria-label="Messenger">
            <img src="<?php echo esc_url(get_theme_file_uri($icon_path . '/ic-messenger.svg')); ?>" alt=""
                aria-hidden="true" width="20" height="20" />
        </a>
        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_number)); ?>"
            class="product-detail__side-icon" aria-label="Gọi điện">
            <img src="<?php echo esc_url(get_theme_file_uri($icon_path . '/ic-phone.svg')); ?>" alt=""
                aria-hidden="true" width="18" height="18" />
        </a>
    </div>
</section>