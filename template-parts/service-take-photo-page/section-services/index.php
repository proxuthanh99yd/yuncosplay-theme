<?php
// Ảnh trang trí → file tĩnh theme (okhub_img); thumbnail dịch vụ vẫn từ post.
$line_key = IS_MOBILE ? 'icons/line-1243' : 'icons/line-1239-3';

$section_service = get_field('service');
$section_service_title = $section_service['title'] ?? 'dịch vụ chụp ảnh nghệ thuật';

$sub_service = $section_service['services'] ?? [];

$isMobileDevice = isMobileDevice() || wp_is_mobile();

foreach ($sub_service as $item) :
    $item_link = $item['link'] ?? null;
    $section_service_items[] = [
      'service_title' => $item['name'] ?? '',
      'service_description' =>  '',
      'service_thumbnail' =>  $item['image'] ?? null,
      'service_link' =>  !empty($item_link['url']) ? $item_link['url'] : '#contact-form-wrapper',
      'service_link_text' => !empty($item_link['title']) ? $item_link['title'] : 'Liên hệ ngay',
      'service_link_target' => !empty($item_link['target']) ? $item_link['target'] : '_self',
      'service_offer_title' => $item['price'] ?? '',
      'service_offer_items' =>  $item['details'] ?? [],
    ];
endforeach;

?>


<section class="home-services">
    <div class="home-services__container">
        <?= okhub_img('services/service-list-container', array('class' => 'home-services__bg')) ?>

        <!-- left -->
        <div class="home-services__list-container">
            <h2 class="home-services__title">
                <?= wp_kses_post($section_service_title) ?>
            </h2>
            <?= okhub_img($line_key, array('class' => 'home-services__line')) ?>
            <div class="home-services__list" <?= $isMobileDevice  ? '' : 'data-lenis-prevent'; ?>>
                <?php
        foreach ($section_service_items as $index => $service_item): ?>
                <?php
                $service_title = $service_item['service_title'] ?? '';
                $service_description = $service_item['service_description'] ?? '';
                $service_thumbnail = $service_item['service_thumbnail'] ?? null;
                $service_link = $service_item['service_link'] ?? '';
                $service_link_text = $service_item['service_link_text'] ?? 'Liên hệ ngay';
                $service_link_target = $service_item['service_link_target'] ?? '_self';
                $service_offer_title = $service_item['service_offer_title'] ?? '';
                $service_offer_items = $service_item['service_offer_items'] ?? [];
                ?>
                <div class="home-services__list-item <?= $index === 0 ? 'active' : '' ?>"
                    data-index="<?= esc_attr($index) ?>">
                    <h3 class="home-services__list-item-title">
                        <?= wp_kses_post($service_title) ?>
                    </h3>

                    <svg class="home-services__list-item-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 18 18" fill="none">
                        <path d="M3.75009 5.99991L9.5088 11.6251L15.0001 5.99991" stroke="#680103" stroke-width="2" />
                    </svg>
                </div>

                <div class="home-services__accordion">
                    <div class="home-services__accordion-inner">
                        <p class="home-services__accordion-description">
                            <?= wp_kses_post($service_description) ?>
                        </p>
                        <div class="home-services__accordion-media">
                            <div class="home-services__media-gradient"></div>
                            <div class="home-services__media-gradient-1"></div>
                            <?php if ($service_thumbnail): ?>
                            <?= wp_get_attachment_image($service_thumbnail, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-image')) ?>
                            <?php endif; ?>
                            <?php // overlay (id 10238) đã bị xoá khỏi media library ?>
                            <div class="home-services__accordion-media-content">
                                <h4 class="home-services__content-title">
                                    <?= wp_kses_post($service_offer_title) ?>
                                </h4>
                            </div>
                        </div>
                        <ul class="home-services__content-list">
                            <?php foreach ($service_offer_items as $service_offer_item): ?>
                            <li class="home-services__content-list-item">
                                <?= okhub_img('icons/star', array('class' => 'home-services__media-icon')) ?>
                                <span class="home-services__content-list-item-text">
                                    <?= wp_kses_post($service_offer_item['desc'] ?? '') ?>
                                </span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?= esc_url($service_link) ?>" class="home-services__button"<?= $service_link_target !== '_self' ? ' target="' . esc_attr($service_link_target) . '"' : '' ?>>
                            <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => $service_link_text)); ?>
                        </a>
                    </div>
                </div>
                <?= okhub_img($line_key, array('class' => 'home-services__line')) ?>
                <?php
        endforeach;
        ?>
            </div>
        </div>

        <!-- right -->
        <div class="home-services__panels">

            <?php
      foreach ($section_service_items as $index => $service_item):
        $service_title = $service_item['service_title'] ?? '';
        $service_description = $service_item['service_description'] ?? '';
        $service_thumbnail = $service_item['service_thumbnail'] ?? null;
        $service_link = $service_item['service_link'] ?? '';
        $service_link_text = $service_item['service_link_text'] ?? 'Liên hệ ngay';
        $service_link_target = $service_item['service_link_target'] ?? '_self';
        $service_offer_title = $service_item['service_offer_title'] ?? '';
        $service_offer_items = $service_item['service_offer_items'] ?? [];
      ?>
            <div class="home-services__media <?= $index === 0 ? 'active' : '' ?>" data-index="<?= esc_attr($index) ?>">
                <div class="services__panels--overlay">
                    <?= okhub_img('service/offer-deco', array('class' => 'home-services__media-image')) ?>

                </div>
                <div class="home-services__media-gradient"></div>
                <div class="home-services__media-gradient-1"></div>
                <?php if ($service_thumbnail): ?>
                <?= wp_get_attachment_image($service_thumbnail, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-image')) ?>
                <?php endif; ?>
                <?php // overlay (id 10238) đã bị xoá khỏi media library ?>
                <div class="home-services__content">
                    <h4 class="home-services__content-title">
                        <?= wp_kses_post($service_offer_title) ?>
                    </h4>
                    <ul class="home-services__content-list">
                        <?php foreach ($service_offer_items as $service_offer_item): ?>
                        <li class="home-services__content-list-item">
                            <?= okhub_img('icons/star', array('class' => 'home-services__media-icon')) ?>
                            <span class="home-services__content-list-item-text">
                                <?= wp_kses_post($service_offer_item['desc'] ?? '') ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?= esc_url($service_link) ?>" class="home-services__button"<?= $service_link_target !== '_self' ? ' target="' . esc_attr($service_link_target) . '"' : '' ?>>
                        <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => $service_link_text)); ?>
                    </a>
                </div>
            </div>
            <?php
      endforeach;
      ?>
        </div>
    </div>
</section>