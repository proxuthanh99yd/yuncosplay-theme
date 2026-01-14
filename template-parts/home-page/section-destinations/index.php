<?php 
$section_destinations = get_field('section_destinations');
$section_title = $section_destinations['title'];
$section_description = $section_destinations['description'];

$image_decor_pc_id = 1109;
$image_overlay_pc_id = 1110;
$image_decor_mb_id = 1118;
$image_overlay_mb_id = 1117;

$destinations = get_terms([
    'taxonomy'   => 'destination',
    'hide_empty' => false,
]);

$destination_capital_location_icon = 1115;
$destination_capital_star_icon = 1116;

// Hàm lấy destination data cho một country
function get_destination_data_for_country($country, $destinations) {
    foreach ($destinations as $destination) {
        $country_field = get_field('country', 'destination_' . $destination->term_id);
        $destination_slug = $destination->slug;
        
        $destination_country = '';
        if ($country_field) {
            $destination_country = strtolower($country_field);
        } else {
            // Try to parse from slug
            $slug_parts = explode('-', $destination_slug);
            $possible_countries = ['vietnam', 'cambodia', 'laos'];
            foreach ($possible_countries as $pc) {
                if (in_array($pc, $slug_parts)) {
                    $destination_country = $pc;
                    break;
                }
            }
        }
        
        if ($destination_country === $country) {
            $thumbnail_id = get_field('thumbnail', 'destination_' . $destination->term_id);
            $custom_link = get_field('custom_permalink', 'destination_' . $destination->term_id);
            $slug_link = $custom_link ?: get_term_link($destination);
            
            // Lấy description: ưu tiên ACF field, nếu không có thì lấy từ term description mặc định
            $description = get_field('description', 'destination_' . $destination->term_id);
            if (empty($description)) {
                $description = $destination->description ?: '';
            }
            
            $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';
            
            // Count tours/posts for this destination
            $tour_count = get_posts([
                'post_type' => 'any',
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'destination',
                        'field' => 'term_id',
                        'terms' => $destination->term_id,
                    ],
                ],
                'fields' => 'ids',
            ]);
            $tour_quantity = count($tour_count);
            
            return [
                'id' => $destination->term_id,
                'name' => $destination->name,
                'thumbnail' => $thumbnail_url,
                'description' => $description,
                'link' => $slug_link,
                'tour_count' => $tour_quantity,
            ];
        }
    }
    return null;
}

// Lấy destination data cho mỗi country
$vietnam_destination = get_destination_data_for_country('vietnam', $destinations);
$cambodia_destination = get_destination_data_for_country('cambodia', $destinations);
$laos_destination = get_destination_data_for_country('laos', $destinations);
?>

<section class="destinations">
    <div class="destination-image-decor destination-image-decor--pc">
        <?= wp_get_attachment_image($image_decor_pc_id, 'full', false, array( 'class' => '')) ?>
    </div>
    <div class="destination-image-overlay destination-image-overlay--pc">
        <?= wp_get_attachment_image($image_overlay_pc_id, 'full', false, array( 'class' => '')) ?>
    </div>

    <div class="destination-image-decor destination-image-decor--mb">
        <?= wp_get_attachment_image($image_decor_mb_id, 'full', false, array( 'class' => '')) ?>
    </div>
    <div class="destination-image-overlay destination-image-overlay--mb">
        <?= wp_get_attachment_image($image_overlay_mb_id, 'full', false, array( 'class' => '')) ?>
    </div>
    
    <div class="destinations-container">
        <div class="destinations-content__header destinations-content__header--mobile">
            <h2 class="destinations-content__title">
                <?= $section_title ?>
            </h2>
            <p class="destinations-content__description">
                <?= $section_description ?>
            </p>
        </div>
        <div class="destinations-map-wrapper">
            <?php get_template_part('template-parts/home-page/section-destinations/destinations-map/index'); ?>
            <div data-country="vietnam" 
                 class="destination-map__capital destination-map__capital--vietnam destination-map__capital--active">
                <div class="destination-map__capital-icon destination-map__capital-icon--location">
                    <?= wp_get_attachment_image($destination_capital_location_icon, 'full', false, array( 'class' => '')) ?>
                </div>
                <div class="destination-map__capital-icon destination-map__capital-icon--star">
                    <?= wp_get_attachment_image($destination_capital_star_icon, 'full', false, array( 'class' => '')) ?>
                </div>
            </div>
            <div data-country="cambodia" 
                 class="destination-map__capital destination-map__capital--cambodia">
                <div class="destination-map__capital-icon destination-map__capital-icon--location">
                    <?= wp_get_attachment_image($destination_capital_location_icon, 'full', false, array( 'class' => '')) ?>
                </div>
                <div class="destination-map__capital-icon destination-map__capital-icon--star">
                    <?= wp_get_attachment_image($destination_capital_star_icon, 'full', false, array( 'class' => '')) ?>
                </div>
            </div>
            <div data-country="laos" 
                 class="destination-map__capital destination-map__capital--laos">
                <div class="destination-map__capital-icon destination-map__capital-icon--location">
                    <?= wp_get_attachment_image($destination_capital_location_icon, 'full', false, array( 'class' => '')) ?>
                </div>
                <div class="destination-map__capital-icon destination-map__capital-icon--star">
                    <?= wp_get_attachment_image($destination_capital_star_icon, 'full', false, array( 'class' => '')) ?>
                </div>
            </div>
        </div>
        <div class="destinations-content">
            <div class="destinations-content__header destinations-content__header--desktop">
                <h2 class="destinations-content__title">
                    <?= $section_title ?>
                </h2>
                <p class="destinations-content__description">
                    <?= $section_description ?>
                </p>
            </div>
            <div class="destination-content__destinations-list">
                <?php if (!is_wp_error($destinations) && !empty($destinations)) : ?>
                    <?php foreach ($destinations as $destination) : 
                        $thumbnail_id = get_field('thumbnail', 'destination_' . $destination->term_id);
                        $custom_link = get_field('custom_permalink', 'destination_' . $destination->term_id);
                        $slug_link = $custom_link ?: get_term_link($destination);
                    ?>
                        <article class="destination-content__destinations-item">
                            <a href="<?= esc_url($slug_link) ?>" class="destination-content__destinations-item__link"></a>
                            <div class="destination-content__destinations-item__thumbnail">
                                <?= wp_get_attachment_image($thumbnail_id, 'full', false, array( 'class' => '')) ?>
                            </div>
                            <div class="destination-content__destinations-item__content">
                                <h3 class="destination-content__destinations-item__title">
                                    <?= esc_html($destination->name) ?>
                                </h3>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php get_template_part('template-parts/components/popup-destination/index'); ?>

<script>
// Lưu destination data vào window object để an toàn hơn
window.destinationData = {
    <?php if ($vietnam_destination) : ?>
    vietnam: {
        id: <?= esc_js($vietnam_destination['id']) ?>,
        name: <?= json_encode($vietnam_destination['name'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        thumbnail: <?= json_encode($vietnam_destination['thumbnail'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        description: <?= json_encode($vietnam_destination['description'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        link: <?= json_encode($vietnam_destination['link'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        tour_count: <?= esc_js($vietnam_destination['tour_count']) ?>
    },
    <?php endif; ?>
    <?php if ($cambodia_destination) : ?>
    cambodia: {
        id: <?= esc_js($cambodia_destination['id']) ?>,
        name: <?= json_encode($cambodia_destination['name'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        thumbnail: <?= json_encode($cambodia_destination['thumbnail'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        description: <?= json_encode($cambodia_destination['description'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        link: <?= json_encode($cambodia_destination['link'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        tour_count: <?= esc_js($cambodia_destination['tour_count']) ?>
    },
    <?php endif; ?>
    <?php if ($laos_destination) : ?>
    laos: {
        id: <?= esc_js($laos_destination['id']) ?>,
        name: <?= json_encode($laos_destination['name'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        thumbnail: <?= json_encode($laos_destination['thumbnail'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        description: <?= json_encode($laos_destination['description'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        link: <?= json_encode($laos_destination['link'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        tour_count: <?= esc_js($laos_destination['tour_count']) ?>
    }
    <?php endif; ?>
};
</script>