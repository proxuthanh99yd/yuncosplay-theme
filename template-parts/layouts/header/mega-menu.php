<?php
// Helper function to render journeys items
function render_mega_menu_journeys($terms, $taxonomy, $data_attr, $posts_per_page = 2, $post_type = 'any')
{
    if (empty($terms)) return;

    $first_term = $terms[0] ?? null;
    if (empty($first_term)) return;

    $term_obj = is_object($first_term) ? $first_term : get_term($first_term, $taxonomy);
    if (is_wp_error($term_obj) || !$term_obj) return;

    $tours = get_posts([
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'tax_query' => [
            [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_obj->term_id,
            ],
        ],
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    if (empty($tours)) return;

    foreach ($tours as $tour):
        $tour_id = is_object($tour) ? $tour->ID : $tour;
        $tour_title = get_the_title($tour_id);
        $tour_url = get_permalink($tour_id);
        $tour_thumb_id = get_post_thumbnail_id($tour_id) ?: 1916;
?>
        <div class="header-mega-menu__journeys-item-wrapper" <?= $data_attr ?>="<?= esc_attr($term_obj->slug) ?>">
            <a class="header-mega-menu__journeys-item" href="<?= esc_url($tour_url) ?>">
                    <?= wp_get_attachment_image($tour_thumb_id, 'full', false, array('class' => '')) ?>
                <div class="header-mega-menu__journeys-item-content">
                    <span class="header-mega-menu__journeys-item-title">
                        <?= esc_html($tour_title); ?>
                    </span>
                </div>
            </a>
        </div>
<?php
    endforeach;
}

function render_mega_menu_journeys_groups($terms, $taxonomy, $key_attr, $posts_per_page = 2, $post_type = 'any')
{
    if (empty($terms)) return;

    $first_term = $terms[0] ?? null;
    if (empty($first_term)) return;

    $first_term_obj = is_object($first_term) ? $first_term : get_term($first_term, $taxonomy);
    if (is_wp_error($first_term_obj) || !$first_term_obj) return;

    $first_slug = $first_term_obj->slug;

    foreach ($terms as $term) {
        $term_obj = is_object($term) ? $term : get_term($term, $taxonomy);
        if (is_wp_error($term_obj) || !$term_obj) {
            continue;
        }

        $tours = get_posts([
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $term_obj->term_id,
                ],
            ],
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        $is_active = ($term_obj->slug === $first_slug);
        ?>
        <div class="header-mega-menu__journeys-group<?= $is_active ? ' is-active' : '' ?>" <?= $key_attr ?>="<?= esc_attr($term_obj->slug) ?>">
            <?php if (!empty($tours)) : ?>
                <?php foreach ($tours as $tour):
                    $tour_id = is_object($tour) ? $tour->ID : $tour;
                    $tour_title = get_the_title($tour_id);
                    $tour_url = get_permalink($tour_id);
                    $tour_thumb_id = get_post_thumbnail_id($tour_id) ?: 1916;
                ?>
                    <div class="header-mega-menu__journeys-item-wrapper">
                        <a class="header-mega-menu__journeys-item" href="<?= esc_url($tour_url) ?>">
                            <?= wp_get_attachment_image($tour_thumb_id, 'full', false, array('class' => '')) ?>
                            <div class="header-mega-menu__journeys-item-content">
                                <span class="header-mega-menu__journeys-item-title">
                                    <?= esc_html($tour_title); ?>
                                </span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="header-mega-menu__journeys-no-results">No result</div>
            <?php endif; ?>
        </div>
        <?php
    }
}

// Get destinations from ACF
$header = get_field('header', 'option');
$navigation = !empty($header['navigation']) ? $header['navigation'] : [];
$destinations_data = !empty($navigation['destinations']) ? $navigation['destinations'] : [];
$destinations_title = !empty($destinations_data['title']) ? $destinations_data['title'] : '';
$destinations_title_item = !empty($destinations_data['title_item']) ? $destinations_data['title_item'] : '';
$destination_terms = !empty($destinations_data['items']) ? $destinations_data['items'] : [];

$destination_terms_valid = [];
if (!empty($destination_terms)) {
    foreach ($destination_terms as $term) {
        $term_obj = is_object($term) ? $term : get_term($term, 'destination');
        if (is_wp_error($term_obj) || !$term_obj) continue;

        $thumbnail_id = get_field('thumbnail', 'destination_' . $term_obj->term_id);
        if (empty($thumbnail_id)) continue;

        $destination_terms_valid[] = $term_obj;
    }
}

$destinations_view_all_url = '#';
if (!empty($destination_terms_valid)) {
    $first_destination_term_link = get_term_link($destination_terms_valid[0], 'destination');
    if (!is_wp_error($first_destination_term_link) && !empty($first_destination_term_link)) {
        $destinations_view_all_url = $first_destination_term_link;
    }
}

$holiday_types = !empty($navigation['holiday_types']) ? $navigation['holiday_types'] : [];
$holiday_types_title = !empty($holiday_types['title']) ? $holiday_types['title'] : '';
$holiday_types_title_item = !empty($holiday_types['title_item']) ? $holiday_types['title_item'] : '';
$holiday_types_items = !empty($holiday_types['items']) ? $holiday_types['items'] : [];

$holiday_types_view_all_url = '#';
if (!empty($holiday_types_items)) {
    $first_holiday_term = is_object($holiday_types_items[0]) ? $holiday_types_items[0] : get_term($holiday_types_items[0], 'holiday-type');
    if (!is_wp_error($first_holiday_term) && $first_holiday_term) {
        $first_holiday_term_link = get_term_link($first_holiday_term, 'holiday-type');
        if (!is_wp_error($first_holiday_term_link) && !empty($first_holiday_term_link)) {
            $holiday_types_view_all_url = $first_holiday_term_link;
        }
    }
}

$blog_categories = !empty($navigation['blog_categories']) ? $navigation['blog_categories'] : [];
$blog_categories_items = !empty($blog_categories['items']) ? $blog_categories['items'] : [];
$blog_categories_title_item = !empty($blog_categories['title_item']) ? $blog_categories['title_item'] : '';

$blog_categories_view_all_url = '#';
if (!empty($blog_categories_items)) {
    $first_category_term = is_object($blog_categories_items[0]) ? $blog_categories_items[0] : get_term($blog_categories_items[0], 'category');
    if (!is_wp_error($first_category_term) && $first_category_term) {
        $first_category_term_link = get_term_link($first_category_term, 'category');
        if (!is_wp_error($first_category_term_link) && !empty($first_category_term_link)) {
            $blog_categories_view_all_url = $first_category_term_link;
        }
    }
}

$about_us = !empty($navigation['about_us']) ? $navigation['about_us'] : [];
$about_us_title = !empty($about_us['title']) ? $about_us['title'] : '';
$about_us_items = !empty($about_us['items']) ? $about_us['items'] : [];

$popup_destinations = get_terms([
    'taxonomy'   => 'destination',
    'hide_empty' => false,
    'parent'     => 0,
]);

$popup_vietnam_destination = okhub_get_destination_data_for_country('vietnam', $popup_destinations);
$popup_cambodia_destination = okhub_get_destination_data_for_country('cambodia', $popup_destinations);
$popup_laos_destination = okhub_get_destination_data_for_country('laos', $popup_destinations);
?>

<script>
window.destinationData = window.destinationData || {};
window.destinationData = Object.assign(window.destinationData, {
    <?php if ($popup_vietnam_destination) : ?>
    vietnam: {
        id: <?= esc_js($popup_vietnam_destination['id']) ?>,
        name: <?= json_encode($popup_vietnam_destination['name'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        thumbnail: <?= json_encode($popup_vietnam_destination['thumbnail'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        description: <?= json_encode($popup_vietnam_destination['description'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        link: <?= json_encode($popup_vietnam_destination['link'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        tour_count: <?= esc_js($popup_vietnam_destination['tour_count']) ?>
    },
    <?php endif; ?>
    <?php if ($popup_cambodia_destination) : ?>
    cambodia: {
        id: <?= esc_js($popup_cambodia_destination['id']) ?>,
        name: <?= json_encode($popup_cambodia_destination['name'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        thumbnail: <?= json_encode($popup_cambodia_destination['thumbnail'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        description: <?= json_encode($popup_cambodia_destination['description'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        link: <?= json_encode($popup_cambodia_destination['link'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        tour_count: <?= esc_js($popup_cambodia_destination['tour_count']) ?>
    },
    <?php endif; ?>
    <?php if ($popup_laos_destination) : ?>
    laos: {
        id: <?= esc_js($popup_laos_destination['id']) ?>,
        name: <?= json_encode($popup_laos_destination['name'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        thumbnail: <?= json_encode($popup_laos_destination['thumbnail'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        description: <?= json_encode($popup_laos_destination['description'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        link: <?= json_encode($popup_laos_destination['link'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        tour_count: <?= esc_js($popup_laos_destination['tour_count']) ?>
    }
    <?php endif; ?>
});
</script>

<!-- mega menu -->
<div class="header-mega-menu header-mega-menu--hidden">
    <div class="header-container header-mega-menu__inner" data-panel="destination">
        <div class="header-mega-item header-mega-menu__country">
            <?php if (!empty($destination_terms_valid)):
                foreach ($destination_terms_valid as $term_obj):
                    $thumbnail_id = get_field('thumbnail', 'destination_' . $term_obj->term_id);
                    $term_link = get_term_link($term_obj, 'destination');
                    if (is_wp_error($term_link) || empty($term_link)) {
                        $term_link = '#';
                    }

                    $destination_country = get_field('country', 'destination_' . $term_obj->term_id);
                    $destination_country = !empty($destination_country) ? strtolower($destination_country) : '';
                    if (empty($destination_country)) {
                        $slug_parts = explode('-', $term_obj->slug);
                        $possible_countries = ['vietnam', 'cambodia', 'laos'];
                        foreach ($possible_countries as $pc) {
                            if (in_array($pc, $slug_parts, true)) {
                                $destination_country = $pc;
                                break;
                            }
                        }
                    }
            ?>
                    <a class="header-mega-menu__country-item" href="<?= esc_url($term_link) ?>" data-country="<?= esc_attr($destination_country) ?>" data-destination="<?= esc_attr($term_obj->slug) ?>">
                        <?= wp_get_attachment_image($thumbnail_id, 'full', false, array('class' => '')) ?>
                        <span class="header-mega-menu__country-item-text"><?= esc_html($term_obj->name) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="header-mega-item header-mega-menu__map">
            <?php get_template_part('template-parts/home-page/section-destinations/destinations-map/index', null, ['id_suffix' => 'header']); ?>
        </div>
        <div class="header-mega-item header-mega-menu__line"></div>
        <div class="header-mega-item header-mega-menu__journeys">
            <h3 class="header-mega-menu__journeys-title">
                <?= esc_html(!empty($destinations_title_item) ? $destinations_title_item : '') ?>
            </h3>
            <div class="header-mega-menu__journeys-list" data-taxonomy="destination" data-post-type="any" data-posts-per-page="2" data-key-attr="data-destination">
                <?php render_mega_menu_journeys_groups($destination_terms_valid, 'destination', 'data-destination'); ?>
            </div>
            <a class="compound-avian-button compound-avian-button--md" href="<?= esc_url($destinations_view_all_url) ?>">
                <span class="compound-avian-button__content">
                    <span class="compound-avian-button__content-text">View All</span>
                </span>
            </a>
        </div>
    </div>

    <!-- Holidays Types Panel -->
    <div class="header-container header-mega-menu__inner" data-panel="holidays-types">
        <div class="header-mega-item header-mega-menu__country">
            <?php if (!empty($holiday_types_items)):
                foreach ($holiday_types_items as $term):
                    $term_obj = is_object($term) ? $term : get_term($term, 'holiday-type');
                    if (is_wp_error($term_obj) || !$term_obj) continue;

                    $term_link = get_term_link($term_obj, 'holiday-type');
                    if (is_wp_error($term_link) || empty($term_link)) {
                        $term_link = '#';
                    }

                    $thumbnail_id = get_field('thumbnail_mobile', 'holiday-type_' . $term_obj->term_id);
                    if (empty($thumbnail_id)) {
                        $thumbnail_id = 1163; // default image
                    }
            ?>
                    <a class="header-mega-menu__country-item" href="<?= esc_url($term_link) ?>" data-holiday-type="<?= esc_attr($term_obj->slug) ?>">
                        <?= wp_get_attachment_image($thumbnail_id, 'full', false, array('class' => '')) ?>
                        <span class="header-mega-menu__country-item-text"><?= esc_html($term_obj->name) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="header-mega-item header-mega-menu__line"></div>
        <div class="header-mega-item header-mega-menu__journeys">
            <h3 class="header-mega-menu__journeys-title">
                <?= esc_html(!empty($holiday_types_title_item) ? $holiday_types_title_item : '') ?>
            </h3>
            <div class="header-mega-menu__journeys-list" data-taxonomy="holiday-type" data-post-type="any" data-posts-per-page="2" data-key-attr="data-holiday-type">
                <?php render_mega_menu_journeys_groups($holiday_types_items, 'holiday-type', 'data-holiday-type'); ?>
            </div>
            <a class="compound-avian-button compound-avian-button--md" href="<?= esc_url($holiday_types_view_all_url) ?>">
                <span class="compound-avian-button__content">
                    <span class="compound-avian-button__content-text">View All</span>
                </span>
            </a>
        </div>
    </div>

    <!-- Inspiration Panel (Blog Categories) -->
    <div class="header-container header-mega-menu__inner" data-panel="inspiration">
        <div class="header-mega-item header-mega-menu__country">
            <?php if (!empty($blog_categories_items)):
                foreach ($blog_categories_items as $category):
                    $term_obj = is_object($category) ? $category : get_term($category, 'category');
                    if (is_wp_error($term_obj) || !$term_obj) continue;

                    $term_link = get_term_link($term_obj, 'category');
                    if (is_wp_error($term_link) || empty($term_link)) {
                        $term_link = '#';
                    }

                    $thumbnail_id = get_field('category_thumbnail', 'category_' . $term_obj->term_id);
                    if (empty($thumbnail_id)) {
                        $thumbnail_id = 1201; // default image
                    }
            ?>
                    <a class="header-mega-menu__country-item" href="<?= esc_url($term_link) ?>" data-category="<?= esc_attr($term_obj->slug) ?>">
                        <?= wp_get_attachment_image($thumbnail_id, 'full', false, array('class' => '')) ?>
                        <span class="header-mega-menu__country-item-text"><?= esc_html($term_obj->name) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="header-mega-item header-mega-menu__line"></div>
        <div class="header-mega-item header-mega-menu__journeys">
            <h3 class="header-mega-menu__journeys-title">
                <?= esc_html(!empty($blog_categories_title_item) ? $blog_categories_title_item : '') ?>
            </h3>
            <div class="header-mega-menu__journeys-list" data-taxonomy="category" data-post-type="post" data-posts-per-page="2" data-key-attr="data-category">
                <?php render_mega_menu_journeys_groups($blog_categories_items, 'category', 'data-category', 2, 'post'); ?>
            </div>
            <a class="compound-avian-button compound-avian-button--md" href="<?= esc_url($blog_categories_view_all_url) ?>">
                <span class="compound-avian-button__content">
                    <span class="compound-avian-button__content-text">View All</span>
                </span>
            </a>
        </div>
    </div>

    <!-- About Panel -->
    <div class="header-container header-mega-menu__inner" data-panel="about">
        <div class="header-mega-item header-mega-menu__about">
            <h3 class="header-mega-menu__about-title"><?= esc_html($about_us_title) ?></h3>
            <ul class="header-mega-menu__about-list">
                <?php if (!empty($about_us_items)):
                    foreach ($about_us_items as $item):
                        $link = !empty($item['link']) ? $item['link'] : [];
                        $link_url = !empty($link['url']) ? $link['url'] : '#';
                        $link_title = !empty($link['title']) ? $link['title'] : '';
                        $link_target = !empty($link['target']) ? $link['target'] : '_self';
                ?>
                        <li class="header-mega-menu__about-item">
                            <a href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>" class="header-mega-menu__about-item-link">
                                <span class="header-mega-menu__about-item-text"><?= esc_html($link_title) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <?= wp_get_attachment_image(1265, 'full', false, array('class' => 'header-mega-menu__about-background')) ?>
</div>