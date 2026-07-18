<?php 
// Icon mega-menu → file tĩnh theme (okhub_img). Thumbnail dịch vụ vẫn từ CMS.

$service_items_args = [
    'post_type' => 'service',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
];
$section_service_items = [];
$service_items_query = new WP_Query($service_items_args);
// thumbnail fallback → okhub_img('common/thumb-fallback')
if ($service_items_query->have_posts()) {
    while ($service_items_query->have_posts()) {
        $service_items_query->the_post();
        $service_offer = function_exists('get_field') ? (get_field('service_offer') ?: []) : [];

        $section_service_items[] = [
            'service_title' => get_the_title() ?? '',
            'service_description' => get_the_excerpt() ?? '',
            'service_thumbnail' => get_post_thumbnail_id() ?: 0,
            'service_link' => get_the_permalink() ?? '',
            'service_offer_title' => $service_offer['title'] ?? '',
            'service_offer_subtitle' => $service_offer['subtitle'] ?? null,
            'service_offer_items' =>  $service_offer['offer_items'] ?? [],
        ];
    }
    wp_reset_postdata();
}
?>

<div data-mega-menu-content="mega-menu-service" class="header__mega-menu-service header__mega-menu-item">
    <div class="header__mega-menu-service-wrapper">
        <div class="header__mega-menu-service-left">
            <ul data-lenis-prevent class="header__mega-menu-service__service-list">
                <?php foreach ($section_service_items as $index => $service_item): ?>
                <?php 
                    $service_title = $service_item['service_title'] ?? '';
                    $service_link = $service_item['service_link'] ?? '';
                ?>
                <li data-service-trigger-index="<?= $index ?>" class="header__mega-menu-service__service-item <?= $index === 0 ? 'header__mega-menu-service__service-item--active' : '' ?>">
                    <a href="<?= $service_link ?>" class="header__mega-menu-service__service-link">
                        <span class="header__mega-menu-service__service-link-text">
                            <?= $service_title ?>
                        </span>
                        <span class="header__mega-menu-service__service-link-icon">
                            <?php echo okhub_img('icons/arrow') ?>
                        </span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="header__mega-menu-service-right">
            <?php foreach ($section_service_items as $index => $service_item): ?>
            <?php 
                $service_thumbnail = $service_item['service_thumbnail'] ?? null;
                $service_link = $service_item['service_link'] ?? '';
                $service_offer_title = $service_item['service_offer_title'] ?? '';
                $service_offer_subtitle = $service_item['service_offer_subtitle'] ?? null;
                $service_offer_items = $service_item['service_offer_items'] ?? [];
            ?>
            <article data-service-target-index="<?= $index ?>" class="header__mega-menu-service-item <?= $index === 0 ? 'header__mega-menu-service-item--active' : '' ?>">
                <div class="header__mega-menu-service__banner">
                    <div class="header__mega-menu-service__banner-overlay"></div>
                    <div class="header__mega-menu-service__banner-background">
                        <?php echo $service_thumbnail ? wp_get_attachment_image($service_thumbnail, 'full', false) : okhub_img('common/thumb-fallback'); ?>
                    </div>
                    <div class="header__mega-menu-service__banner-content">
                        <div class="header__mega-menu-service__banner-content-left">
                            <h3 class="header__mega-menu-service__banner-title">
                                <?= $service_offer_title ?>
                            </h3>
                            <ul class="header__mega-menu-service__banner-service-list">
                                <?php foreach ($service_offer_items as $service_offer_item): ?>
                                <li class="header__mega-menu-service__banner-service-item">
                                    <p class="header__mega-menu-service__banner-service-content">
                                        <span class="header__mega-menu-service__banner-service-item-icon">
                                            <?php echo okhub_img('icons/icon') ?>
                                        </span>
                                        <span class="header__mega-menu-service__banner-service-item-text">
                                            <?= $service_offer_item['offer_item'] ?? '' ?>
                                        </span>
                                    </p>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="header__mega-menu-service-banner__content-right">
                            <a href="<?= $service_link ?>" class="header__mega-menu-service-banner__btn-details">
                                <span class="header__mega-menu-service-banner__btn-details-icon">
                                    <?php echo okhub_img('icons/arrow-right-2') ?>
                                </span>
                                <span class="header__mega-menu-service-banner__btn-details-text">Xem chi tiết</span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</div>
