<?php
$detail_shop = get_field('Detail_shop', get_the_ID());

$detail_shop_title = $detail_shop['title'] ?? '';
$detail_introduce  = $detail_shop['detail_introduce'] ?? [];
$contact           = $detail_shop['contact'] ?? [];
$map               = $detail_shop['map'] ?? [];

$bg_desktop_id     = 10250;
$bg_mobile_id      = 10256;
$store_overlay_id  = 10268;

$phone_number = $contact['phone'] ?? '';
$zalo_link    = $contact['zalo'] ?? '#';
$mess_link    = $contact['mess'] ?? '#';

$phone_tel = preg_replace('/[^0-9+]/', '', $phone_number);

$map_link = $map['link_map'] ?? '#';

/**
 * 4 ảnh trong group map
 */
$store_image_desktop = $map['image'] ?? null;
$store_image_mobile  = $map['image_mobile'] ?? null;

$image_map_desktop = $map['image_map_desktop'] ?? null;
$image_map_mobile  = $map['image_map_mobile'] ?? null;

/**
 * Store image desktop
 */
$store_image_desktop_id = is_array($store_image_desktop)
    ? ($store_image_desktop['ID'] ?? null)
    : $store_image_desktop;

$store_image_desktop_alt = is_array($store_image_desktop)
    ? ($store_image_desktop['alt'] ?: ($store_image_desktop['title'] ?? 'Yun Cosplay'))
    : 'Yun Cosplay';

/**
 * Store image mobile
 */
$store_image_mobile_id = is_array($store_image_mobile)
    ? ($store_image_mobile['ID'] ?? null)
    : $store_image_mobile;

$store_image_mobile_alt = is_array($store_image_mobile)
    ? ($store_image_mobile['alt'] ?: ($store_image_mobile['title'] ?? 'Yun Cosplay Mobile'))
    : 'Yun Cosplay Mobile';

/**
 * Map desktop
 */
$image_map_desktop_id = is_array($image_map_desktop)
    ? ($image_map_desktop['ID'] ?? null)
    : $image_map_desktop;

$image_map_desktop_alt = is_array($image_map_desktop)
    ? ($image_map_desktop['alt'] ?: ($image_map_desktop['title'] ?? 'Map Yun Cosplay'))
    : 'Map Yun Cosplay';

/**
 * Map mobile
 */
$image_map_mobile_id = is_array($image_map_mobile)
    ? ($image_map_mobile['ID'] ?? null)
    : $image_map_mobile;

$image_map_mobile_alt = is_array($image_map_mobile)
    ? ($image_map_mobile['alt'] ?: ($image_map_mobile['title'] ?? 'Map Yun Cosplay Mobile'))
    : 'Map Yun Cosplay Mobile';
?>
<section class="store-info">
    <?php if ($bg_desktop_id) : ?>
        <?= wp_get_attachment_image($bg_desktop_id, 'full', false, [
            'class' => 'store-info__bg store-info__bg--desktop',
            'alt' => '',
            'aria-hidden' => 'true',
            'loading' => 'lazy',
        ]) ?>
    <?php endif; ?>

    <?php if ($bg_mobile_id) : ?>
        <?= wp_get_attachment_image($bg_mobile_id, 'full', false, [
            'class' => 'store-info__bg store-info__bg--mobile',
            'alt' => '',
            'aria-hidden' => 'true',
            'loading' => 'lazy',
        ]) ?>
    <?php endif; ?>

    <div class="store-info__inner">
        <div class="store-info__content">
            <h2 class="store-info__title">
                <?= esc_html($detail_shop_title); ?>
            </h2>

            <div class="store-info__list">
                <?php foreach ($detail_introduce as $detail_item) : ?>
                    <?php
                    $item_title = $detail_item['title'] ?? '';
                    $item_desc  = $detail_item['desc'] ?? '';

                    $answer_lines = preg_split('/<\/p>|<br\s*\/?>|\r\n|\r|\n/i', $item_desc);
                    $answer_lines = array_values(array_filter(array_map(function ($line) {
                        $line = trim($line);
                        $line = preg_replace('/^<p[^>]*>/i', '', $line);
                        return trim($line);
                    }, $answer_lines)));
                    ?>

                    <div class="store-info__item">
                        <div class="store-info__question">


                            <svg class="store-info__spark" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M6.26918 0.199326L7.17133 3.15802C7.29149 3.55211 7.50672 3.91061 7.79806 4.20195C8.08939 4.49328 8.44789 4.70851 8.84198 4.82867L11.8007 5.73082C12.0664 5.81192 12.0664 6.18808 11.8007 6.26918L8.84198 7.17133C8.44789 7.29149 8.08939 7.50672 7.79806 7.79806C7.50672 8.08939 7.29149 8.44789 7.17133 8.84198L6.26905 11.8007C6.18796 12.0664 5.81179 12.0664 5.73069 11.8007L4.82855 8.84198C4.70838 8.44789 4.49315 8.08939 4.20182 7.79806C3.91049 7.50672 3.55199 7.29149 3.1579 7.17133L0.199326 6.26905C-0.0664421 6.18796 -0.0664421 5.81179 0.199326 5.73069L3.15802 4.82855C3.55211 4.70838 3.91061 4.49315 4.20195 4.20182C4.49328 3.91049 4.70851 3.55199 4.82867 3.1579L5.73095 0.199326C5.81204 -0.0664421 6.18808 -0.0664421 6.26918 0.199326Z" fill="#CB5140" />
                            </svg>
                            <span><?= esc_html($item_title); ?></span>
                        </div>

                        <div class="store-info__answer">
                            <?php if (count($answer_lines) >= 2) : ?>
                                <?php foreach ($answer_lines as $line_index => $line) : ?>
                                    <div class="store-info__answer-row">
                                        <?php if ($line_index === 0 || $line_index === 1) : ?>
                                            <span class="store-info__answer-icon" aria-hidden="true">
                                                <?php if ($line_index === 0) : ?>
                                                    <!-- Phone SVG -->
                                                    <svg class="icon_phone_info" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                        <path d="M14.0633 11.6367C14.0633 11.8767 14.01 12.1233 13.8967 12.3633C13.7833 12.6033 13.6367 12.83 13.4433 13.0433C13.1167 13.4033 12.7567 13.6633 12.35 13.83C11.95 13.9967 11.5167 14.0833 11.05 14.0833C10.37 14.0833 9.64333 13.9233 8.87667 13.5967C8.11 13.27 7.34333 12.83 6.58333 12.2767C5.81667 11.7167 5.09 11.0967 4.39667 10.41C3.71 9.71667 3.09 8.99 2.53667 8.23C1.99 7.47 1.55 6.71 1.23 5.95667C0.91 5.19667 0.75 4.47 0.75 3.77667C0.75 3.32333 0.83 2.89 0.99 2.49C1.15 2.08333 1.40333 1.71 1.75667 1.37667C2.18333 0.956667 2.65 0.75 3.14333 0.75C3.33 0.75 3.51667 0.79 3.68333 0.87C3.85667 0.95 4.01 1.07 4.13 1.24333L5.67667 3.42333C5.79667 3.59 5.88333 3.74333 5.94333 3.89C6.00333 4.03 6.03667 4.17 6.03667 4.29667C6.03667 4.45667 5.99 4.61667 5.89667 4.77C5.81 4.92333 5.68333 5.08333 5.52333 5.24333L5.01667 5.77C4.94333 5.84333 4.91 5.93 4.91 6.03667C4.91 6.09 4.91667 6.13667 4.93 6.19C4.95 6.24333 4.97 6.28333 4.98333 6.32333C5.10333 6.54333 5.31 6.83 5.60333 7.17667C5.90333 7.52333 6.22333 7.87667 6.57 8.23C6.93 8.58333 7.27667 8.91 7.63 9.21C7.97667 9.50333 8.26333 9.70333 8.49 9.82333C8.52333 9.83667 8.56333 9.85667 8.61 9.87667C8.66333 9.89667 8.71667 9.90333 8.77667 9.90333C8.89 9.90333 8.97667 9.86333 9.05 9.79L9.55667 9.29C9.72333 9.12333 9.88333 8.99667 10.0367 8.91667C10.19 8.82333 10.3433 8.77667 10.51 8.77667C10.6367 8.77667 10.77 8.80333 10.9167 8.86333C11.0633 8.92333 11.2167 9.01 11.3833 9.12333L13.59 10.69C13.7633 10.81 13.8833 10.95 13.9567 11.1167C14.0233 11.2833 14.0633 11.45 14.0633 11.6367Z" stroke="#F6F3EA" stroke-width="1.5" stroke-miterlimit="10" />
                                                    </svg>
                                                <?php elseif ($line_index === 1) : ?>
                                                    <!-- Email SVG -->
                                                    <svg class="icon_phone_info" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <g opacity="0.8">
                                                            <path d="M11.334 13.6667H4.66732C2.66732 13.6667 1.33398 12.6667 1.33398 10.3334V5.66671C1.33398 3.33337 2.66732 2.33337 4.66732 2.33337H11.334C13.334 2.33337 14.6673 3.33337 14.6673 5.66671V10.3334C14.6673 12.6667 13.334 13.6667 11.334 13.6667Z" stroke="#F6F3EA" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M11.3327 6L9.24601 7.66667C8.55935 8.21333 7.43268 8.21333 6.74601 7.66667L4.66602 6" stroke="#F6F3EA" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                        </g>
                                                    </svg>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>

                                        <span class="store-info__answer-text">
                                            <?= wp_kses_post($line); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?= wp_kses_post($item_desc); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


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
                            <svg xmlns="http://www.w3.org/2000/svg" width="33" height="13" viewBox="0 0 33 13" fill="none">
                                <path d="M7.21575 2.33978L2.35083 10.3163V10.4936H7.21575V12.6206H0V10.4936L4.89757 2.51703V2.33978H0V0.212707H7.21575V2.33978Z" fill="#F6F3EA" />
                                <path d="M7.67707 12.6206V12.2307L10.1912 0.212707L13.9786 0.230432L16.509 12.2307V12.6206H14.4194L13.7827 9.21731H10.4034L9.7667 12.6206H7.67707ZM10.6972 7.30295H13.4725L12.542 2.26888H11.6441L10.6972 7.30295Z" fill="#F6F3EA" />
                                <path d="M17.5301 0.212707H19.5381V10.6354H22.9011V12.6206H17.5301V0.212707Z" fill="#F6F3EA" />
                                <path d="M23.1001 6.41667C23.1001 4.9159 23.3232 3.69283 23.7694 2.74747C24.2156 1.8021 24.7979 1.1108 25.5162 0.673573C26.2454 0.224524 27.0345 0 27.8834 0C28.7323 0 29.5159 0.224524 30.2342 0.673573C30.9634 1.1108 31.5511 1.8021 31.9973 2.74747C32.4436 3.69283 32.6667 4.9159 32.6667 6.41667C32.6667 7.91743 32.4436 9.1405 31.9973 10.0859C31.5511 11.0312 30.9634 11.7284 30.2342 12.1775C29.5159 12.6147 28.7323 12.8333 27.8834 12.8333C27.0345 12.8333 26.2454 12.6147 25.5162 12.1775C24.7979 11.7284 24.2156 11.0312 23.7694 10.0859C23.3232 9.1405 23.1001 7.91743 23.1001 6.41667ZM25.1734 6.41667C25.1734 7.38567 25.2931 8.18923 25.5325 8.82735C25.772 9.46547 26.093 9.94997 26.4957 10.2808C26.9093 10.5999 27.3664 10.7594 27.867 10.7594C28.3677 10.7594 28.8248 10.5999 29.2384 10.2808C29.6628 9.94997 29.9948 9.46547 30.2342 8.82735C30.4845 8.18923 30.6097 7.38567 30.6097 6.41667C30.6097 5.43585 30.4845 4.62638 30.2342 3.98826C29.9839 3.35014 29.6519 2.87155 29.2384 2.55249C28.8248 2.23343 28.3731 2.07389 27.8834 2.07389C27.3718 2.07389 26.9093 2.23933 26.4957 2.57021C26.093 2.88927 25.772 3.36786 25.5325 4.00599C25.2931 4.64411 25.1734 5.44767 25.1734 6.41667Z" fill="#F6F3EA" />
                            </svg>
                        </a>
                        <a href="<?php echo esc_url($messenger_link); ?>" target="_blank" rel="noopener noreferrer"
                            class="product-detail__contact-icon" aria-label="Messenger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none">
                                <path d="M15.0267 0C6.72984 0 0 6.27302 0 14.0467C0 18.1632 1.89365 22.0168 5.22667 24.6965V30.4464L10.8464 27.5064C12.2168 27.8968 13.5898 28.027 15.0267 28.027C23.3235 28.027 30.0533 21.7565 30.0533 13.9803C30.0533 6.27302 23.3235 0 15.0267 0ZM16.5298 18.6864L12.74 14.6336L5.68349 18.62L13.5235 10.3232L17.3797 14.1768L24.2397 10.3232L16.5298 18.6864Z" fill="#F6F3EA" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="store-info__image-div">
            <div class="store-info__image">
                <?php if (!empty($store_image_desktop_id)) : ?>
                    <?= wp_get_attachment_image($store_image_desktop_id, 'full', false, [
                        'class' => 'store-info__image-img store-info__image-img--desktop',
                        'alt' => esc_attr($store_image_desktop_alt),
                        'loading' => 'lazy',
                    ]) ?>
                <?php endif; ?>

                <?php if (!empty($store_image_mobile_id)) : ?>
                    <?= wp_get_attachment_image($store_image_mobile_id, 'full', false, [
                        'class' => 'store-info__image-img store-info__image-img--mobile',
                        'alt' => esc_attr($store_image_mobile_alt),
                        'loading' => 'lazy',
                    ]) ?>
                <?php endif; ?>

                <?php if (!empty($store_overlay_id)) : ?>
                    <?= wp_get_attachment_image($store_overlay_id, 'full', false, [
                        'class' => 'store-info__image-overlay',
                        'alt' => '',
                        'aria-hidden' => 'true',
                        'loading' => 'lazy',
                    ]) ?>
                <?php endif; ?>
            </div>

            <div class="store-info__map">
                <?php if (!empty($image_map_desktop_id)) : ?>
                    <?= wp_get_attachment_image($image_map_desktop_id, 'full', false, [
                        'class' => 'store-info__map-img store-info__map-img--desktop',
                        'alt' => esc_attr($image_map_desktop_alt),
                        'loading' => 'lazy',
                    ]) ?>
                <?php endif; ?>

                <?php if (!empty($image_map_mobile_id)) : ?>
                    <?= wp_get_attachment_image($image_map_mobile_id, 'full', false, [
                        'class' => 'store-info__map-img store-info__map-img--mobile',
                        'alt' => esc_attr($image_map_mobile_alt),
                        'loading' => 'lazy',
                    ]) ?>
                <?php endif; ?>


                <a class="store-info__map-link" href="<?= esc_url($map_link); ?>" target="_blank" rel="noopener">
                    <span>Đường đến cửa hàng</span>

                    <svg class="store-info__map-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="18" viewBox="0 0 15 18" fill="none">
                        <path d="M7.5 12.6372C7.93093 12.6372 8.32235 12.4171 8.54716 12.0484C10.1224 9.46495 12 6.0602 12 4.51091C12 2.02358 9.98132 0 7.5 0C5.01867 0 3 2.02358 3 4.51091C3 6.0602 4.87768 9.46495 6.45284 12.0484C6.67765 12.4171 7.0691 12.6372 7.5 12.6372ZM5.69125 4.20118C5.69125 3.20146 6.50267 2.38811 7.5 2.38811C8.49733 2.38811 9.30874 3.20146 9.30874 4.20118C9.30874 5.20094 8.49733 6.01429 7.5 6.01429C6.50267 6.01429 5.69125 5.20097 5.69125 4.20118Z" fill="#F6F3EA" />
                    </svg>
                </a>

            </div>
        </div>
    </div>
</section>