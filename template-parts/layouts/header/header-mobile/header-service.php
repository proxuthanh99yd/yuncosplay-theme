<?php 
$icon_list_disc_id = 71;
$offer_image_id = 9762;
$overlay_thumbnail_mobile_id = 9829;

$service_items_args = [
    'post_type' => 'service',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
];
$section_service_items = [];
$service_items_query = new WP_Query($service_items_args);

if ($service_items_query->have_posts()) {
    while ($service_items_query->have_posts()) {
        $service_items_query->the_post();
        $service_offer = function_exists('get_field') ? (get_field('service_offer') ?: []) : [];

        $section_service_items[] = [
            'service_title' => get_the_title() ?? '',
            'service_description' => get_the_excerpt() ?? '',
            'service_thumbnail' => get_post_thumbnail_id() ?? null,
            'service_link' => get_the_permalink() ?? '',
            'service_offer_title' => $service_offer['title'] ?? '',
            'service_offer_subtitle' => $service_offer['subtitle'] ?? null,
            'service_offer_items' =>  $service_offer['offer_items'] ?? [],
        ];
    }
    wp_reset_postdata();
}
?>

<div class="header-service">
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
            <?php foreach ($section_service_items as $index => $service_item): ?>
            <?php 
                $service_title = $service_item['service_title'] ?? '';
                $service_description = $service_item['service_description'] ?? '';
                $service_thumbnail = $service_item['service_thumbnail'] ?? null;
                $service_link = $service_item['service_link'] ?? '';
                $service_offer_title = $service_item['service_offer_title'] ?? '';
                $service_offer_subtitle = $service_item['service_offer_subtitle'] ?? null;
                $service_offer_items = $service_item['service_offer_items'] ?? [];
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
                    <p class="header-service__service-item__description">
                        <?= $service_description ?>
                    </p>
                    <div class="header-service__service-item__thumbnail">
                        <?php echo wp_get_attachment_image($overlay_thumbnail_mobile_id, 'full', false, array( 'class' => 'header-service__service-item__thumbnail-overlay')) ?>
                        <?php echo wp_get_attachment_image($offer_image_id, 'full', false, array( 'class' => 'header-service__service-item__thumbnail-image')) ?>
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
                                <?php echo wp_get_attachment_image($icon_list_disc_id, 'full', false, array( 'class' => '')) ?>
                            </span>
                            <p class="header-service__service-item__offer-item__text">
                                <?= $service_offer_item['offer_item'] ?? '' ?>
                            </p>
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