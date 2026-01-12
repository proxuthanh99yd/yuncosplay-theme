<?php
$header = get_field('header', 'option');
if(!empty($header)) {
    $header_logo = $header['logo'];
    $header_whatsapp_link = $header['whatsapp_contact'];
    $header_phone_link = $header['phone_contact'];
    $header_contact_link = $header['button_contact'];
}

$icon_language_en_id = 1057;
$icon_language_vi_id = 1058;
$icon_arrow_down_id = 1060;
$icon_search_id = 1061;
$icon_close_id = 1062;
?>

<header class="header">
    <div class="header-top">
        <div class="header-container header-top__inner">
            <a href="/" class="header-logo">
                <?= wp_get_attachment_image($header_logo, 'full', false, array( 'class' => '')) ?>
            </a>
            <div class="header-info">
                 <div class="header-info__item">
                    <custom-dropdown class="header-info__item-language" value="en" name="language">
                        <custom-option class="header-info__item-language-option" value="en">
                            <?= wp_get_attachment_image($icon_language_en_id, 'full', false, array( 'class' => 'header-info__item-language-option__text__icon')) ?>
                            <span class="header-info__item-language-option__text">English</span>
                        </custom-option>
                        <custom-option class="header-info__item-language-option" value="vi">
                            <?= wp_get_attachment_image($icon_language_vi_id, 'full', false, array( 'class' => 'header-info__item-language-option__text__icon')) ?>
                            <span class="header-info__item-language-option__text">Vietnamese</span>
                        </custom-option>
                    </custom-dropdown>
                 </div>
                <div class="header-info__item">
                    <?php if(!empty($header_whatsapp_link) && !empty($header_whatsapp_link['url'])): ?>
                        <a class="header-info__item-link header-info__item-link--whatsapp" href="<?= $header_whatsapp_link['url']; ?>" target="<?= $header_whatsapp_link['target']; ?>">
                            <span class="header-info__item-link--whatsapp-text"><?= $header_whatsapp_link['title']; ?></span>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="header-info__item">
                    <?php if(!empty($header_phone_link) && !empty($header_phone_link['url'])): ?>
                        <a class="header-info__item-link header-info__item-link--phone" href="<?= $header_phone_link['url']; ?>" target="<?= $header_phone_link['target']; ?>">
                            <?= wp_get_attachment_image(1056, 'full', false, array( 'class' => 'header-info__item-link--phone-icon')) ?>
                            <span class="header-info__item-link--phone-text"><?= $header_phone_link['title']; ?></span>
                        </a>
                    <?php endif; ?>

                    <span class="header-info__item-or-text">or</span>
                </div>

                <div class="header-info__item">
                    <?php if(!empty($header_contact_link) && !empty($header_contact_link['url'])): ?>
                        <a class="header-info__item-link header-info__item-link--contact" href="<?= $header_contact_link['url']; ?>" target="<?= $header_contact_link['target']; ?>">
                            <span class="header-info__item-link--contact-text"><?= $header_contact_link['title']; ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="header-container header-bottom__inner">
            <nav class="header-navigation">
                <ul class="header-navigation__list">
                    <li class="header-navigation__item">
                        <div class="header-navigation__item-link">
                            <span class="header-navigation__item-link__text">Destination</span>
                            <?= wp_get_attachment_image($icon_arrow_down_id, 'full', false, array( 'class' => 'header-navigation__item-link__icon')) ?>
                        </div>
                    </li>
                    <li class="header-navigation__item">
                        <div class="header-navigation__item-link">
                            <span class="header-navigation__item-link__text">Holidays types</span>
                            <?= wp_get_attachment_image($icon_arrow_down_id, 'full', false, array( 'class' => 'header-navigation__item-link__icon')) ?>
                        </div>
                    </li>
                    <li class="header-navigation__item">
                        <div class="header-navigation__item-link">
                            <span class="header-navigation__item-link__text">Inspiration</span>
                            <?= wp_get_attachment_image($icon_arrow_down_id, 'full', false, array( 'class' => 'header-navigation__item-link__icon')) ?>
                        </div>
                    </li>
                    <li class="header-navigation__item">
                        <div class="header-navigation__item-link">
                            <span class="header-navigation__item-link__text">Hotel & resorts</span>
                            <?= wp_get_attachment_image($icon_arrow_down_id, 'full', false, array( 'class' => 'header-navigation__item-link__icon')) ?>
                        </div>
                    </li>
                    <li class="header-navigation__item">
                        <div class="header-navigation__item-link">
                            <span class="header-navigation__item-link__text">About </span>
                            <?= wp_get_attachment_image($icon_arrow_down_id, 'full', false, array( 'class' => 'header-navigation__item-link__icon')) ?>
                        </div>
                    </li>
                </ul>
            </nav>

            <div id="header-search" class="header-search">
                <div class="header-search__input-wrapper">
                    <label class="header-search__input">
                        <span class="header-search__button-close">
                            <?= wp_get_attachment_image($icon_close_id, 'full', false, array( 'class' => 'header-search__button-close__icon')) ?>
                        </span>
                        <input type="text" id="header-search-input" placeholder="Search destinations, experiences or hotels...">
                    </label>
                </div>
                <label for="header-search-input" class="header-search__button">
                    <?= wp_get_attachment_image($icon_search_id, 'full', false, array( 'class' => 'header-search__button-icon')) ?>
                </label>
            </div>
        </div>
    </div>
</header>