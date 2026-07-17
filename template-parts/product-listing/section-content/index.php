<?php

/**
 * Section Content — Product Listing
 * Content header (title + description + expand) + product grid with infinite scroll
 *
 * @param array $args {
 *   @type string $category_name        Category display name
 *   @type string $category_description Category description (may contain HTML)
 *   @type string $category_slug        Category slug for filtering
 * }
 */

$category_name        = !empty($args['category_name']) ? $args['category_name'] : '';
$category_description = !empty($args['category_description']) ? $args['category_description'] : '';
$category_slug        = !empty($args['category_slug']) ? $args['category_slug'] : '';

/**
 * Render no results HTML
 * @return string HTML string for no results message
 */
function render_no_results() {
	ob_start(); ?>
<div class="pl-content__no-results">
    <svg class="pl-content__no-results-icon" xmlns="http://www.w3.org/2000/svg" width="101" height="101"
        viewBox="0 0 101 101" fill="none">
        <path
            d="M50.5001 88.233C71.3395 88.233 88.2333 71.3393 88.2333 50.4998C88.2333 29.6603 71.3395 12.7666 50.5001 12.7666C29.6606 12.7666 12.7668 29.6603 12.7668 50.4998C12.7668 71.3393 29.6606 88.233 50.5001 88.233Z"
            stroke="#CB5140" stroke-width="6.3125" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M95.1006 95.1004L84.1667 84.1665" stroke="#CB5140" stroke-width="6.3125" stroke-linecap="round"
            stroke-linejoin="round" />
        <path
            d="M69.3277 49.2478L66.082 45.7434L62.836 49.2478L66.082 52.7522C66.5301 53.236 66.5301 54.0205 66.082 54.5043L62.836 58.0087C62.3878 58.4926 61.6612 58.4926 61.2131 58.0087L44.9835 40.4868C44.5354 40.003 44.5354 39.2185 44.9835 38.7347L48.2294 35.2302C48.6777 34.7464 49.4043 34.7464 49.8524 35.2302L53.0983 38.7347L56.3442 35.2302L53.0983 31.7258C52.202 30.7581 50.7488 30.7581 49.8524 31.7258L42.5491 39.6107C41.2046 41.0623 41.2046 43.4157 42.5491 44.8673L49.4467 52.3141L45.3893 56.6947C44.9411 57.1785 44.2145 57.1785 43.7664 56.6947L35.2459 47.4956L32 51L38.8975 58.4468C40.6901 60.3823 43.5966 60.3823 45.3893 58.4468L50.2581 53.1903L57.9671 61.5131C58.4153 61.997 58.4153 62.7815 57.9671 63.2655L54.7213 66.7698C54.2731 67.2537 53.5464 67.2537 53.0983 66.7698L49.8524 63.2655L46.6065 66.7698L49.8524 70.2741C50.7488 71.242 52.202 71.242 53.0983 70.2741L69.3277 52.7522C70.2241 51.7845 70.2241 50.2155 69.3277 49.2478Z"
            fill="#CB5140" />
    </svg>
    <p class="pl-content__no-results-text">Không tìm được kết quả dành cho bạn.</p>
</div>
<?php
	return ob_get_clean();
}

// WP_Query cho initial products
$paged    = 1;
$per_page = 12;
$icon_driver_id = 9908;

$query_args = [
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
];

if (!empty($category_slug)) {
	$query_args['tax_query'] = [[
		'taxonomy' => 'product_cat',
		'field'    => 'slug',
		'terms'    => $category_slug,
	]];
}

$order_clauses = okhub_product_order_clauses();
add_filter('posts_clauses', $order_clauses, 10, 2);
$products_query = new WP_Query($query_args);
remove_filter('posts_clauses', $order_clauses, 10);
$total_pages    = $products_query->max_num_pages;

$has_description = !empty(trim(wp_strip_all_tags($category_description)));
?>

<section class="pl-content">
    <!-- Page Title (visible on mobile outside header, desktop inside header) -->
    <h1 class="pl-content__title">Danh sách sản phẩm</h1>

    <!-- Mobile Filter Button -->
    <div class="pl-filter-btn-wrap" id="pl-filter-btn-wrap">
        <button class="pl-filter-btn" type="button" data-action="open-drawer">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path
                    d="M2.79154 1.66675H10.2082C10.8249 1.66675 11.3332 2.17509 11.3332 2.79175V4.02507C11.3332 4.47507 11.0499 5.03342 10.7749 5.31675L8.35826 7.45008C8.02492 7.73342 7.79989 8.29174 7.79989 8.74174V11.1584C7.79989 11.4918 7.5749 11.9418 7.29156 12.1168L6.50823 12.6251C5.7749 13.0751 4.76654 12.5667 4.76654 11.6667V8.69174C4.76654 8.30008 4.54156 7.79176 4.31656 7.50842L2.18323 5.25841C1.89989 4.97508 1.67491 4.47507 1.67491 4.13341V2.84175C1.66657 2.17508 2.17488 1.66675 2.79154 1.66675Z"
                    stroke="#F6F3EA" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path
                    d="M1.66675 10V12.5C1.66675 16.6667 3.33341 18.3334 7.50008 18.3334H12.5001C16.6667 18.3334 18.3334 16.6667 18.3334 12.5V7.50004C18.3334 4.90004 17.6834 3.2667 16.1751 2.4167C15.7501 2.17504 14.9001 1.9917 14.1251 1.8667"
                    stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10.8333 10.8333H14.9999" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M9.16675 14.1667H15.0001" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span class="pl-filter-btn__text">Lọc sản phẩm</span>
        </button>
    </div>

    <!-- Content Header (desktop: full header with title; mobile: description only) -->
    <div class="pl-content__header">
        <h1 class="pl-content__title pl-content__title--desktop">Danh sách sản phẩm</h1>

        <?= wp_get_attachment_image($icon_driver_id, 'full', false, array('class' => 'pl-content__divider')); ?>

        <?php if (!empty($category_name)) : ?>
        <h2 class="pl-content__subtitle"><?php echo esc_html($category_name); ?></h2>
        <?php endif; ?>

        <?php if ($has_description) : ?>
        <div class="pl-content__desc" id="pl-desc">
            <?php echo wp_kses_post($category_description); ?>
        </div>

        <button class="pl-content__expand-btn" id="pl-expand-btn" type="button" aria-expanded="false"
            aria-controls="pl-desc">
            <svg class="pl-content__expand-btn-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                viewBox="0 0 18 18" fill="none">
                <g clip-path="url(#clip0_599_10031)">
                    <path
                        d="M5.89275 0.9375C6.04193 0.9375 6.18501 0.996763 6.2905 1.10225C6.39599 1.20774 6.45525 1.35082 6.45525 1.5C6.45525 1.64918 6.39599 1.79226 6.2905 1.89775C6.18501 2.00324 6.04193 2.0625 5.89275 2.0625H2.85825L7.14825 6.3525C7.25066 6.45864 7.3073 6.60075 7.30595 6.74824C7.30459 6.89572 7.24537 7.03678 7.14103 7.14102C7.03669 7.24527 6.89558 7.30436 6.74809 7.30557C6.6006 7.30678 6.45854 7.25001 6.3525 7.1475L2.0625 2.8575V5.89275C2.0625 6.04193 2.00324 6.18501 1.89775 6.2905C1.79226 6.39599 1.64918 6.45525 1.5 6.45525C1.35082 6.45525 1.20774 6.39599 1.10225 6.2905C0.996763 6.18501 0.9375 6.04193 0.9375 5.89275V1.5C0.9375 1.35082 0.996763 1.20774 1.10225 1.10225C1.20774 0.996763 1.35082 0.9375 1.5 0.9375H5.89275ZM12.1073 17.0625C11.9581 17.0625 11.815 17.0032 11.7095 16.8977C11.604 16.7923 11.5447 16.6492 11.5447 16.5C11.5447 16.3508 11.604 16.2077 11.7095 16.1023C11.815 15.9968 11.9581 15.9375 12.1073 15.9375H15.1417L10.8517 11.6475C10.7981 11.5956 10.7552 11.5335 10.7258 11.4649C10.6963 11.3962 10.6809 11.3224 10.6802 11.2477C10.6796 11.173 10.6939 11.099 10.7222 11.0298C10.7505 10.9607 10.7923 10.8979 10.8452 10.8452C10.898 10.7924 10.9608 10.7506 11.03 10.7224C11.0991 10.6941 11.1732 10.6799 11.2479 10.6806C11.3226 10.6813 11.3964 10.6968 11.465 10.7264C11.5336 10.7559 11.5956 10.7988 11.6475 10.8525L15.9375 15.1425V12.1073C15.9375 12.0334 15.952 11.9602 15.9803 11.892C16.0086 11.8237 16.05 11.7617 16.1023 11.7095C16.1545 11.6573 16.2165 11.6158 16.2847 11.5876C16.353 11.5593 16.4261 11.5448 16.5 11.5448C16.5739 11.5448 16.647 11.5593 16.7153 11.5876C16.7835 11.6158 16.8455 11.6573 16.8977 11.7095C16.95 11.7617 16.9914 11.8237 17.0197 11.892C17.048 11.9602 17.0625 12.0334 17.0625 12.1073V16.5C17.0625 16.8105 16.8105 17.0625 16.5 17.0625H12.1073Z"
                        fill="#CB5140" />
                </g>
                <defs>
                    <clipPath id="clip0_599_10031">
                        <rect width="18" height="18" fill="white" transform="matrix(-1 0 0 1 18 0)" />
                    </clipPath>
                </defs>
            </svg>
        </button>
        <?php endif; ?>
    </div>

    <!-- Selected Filters (mobile only) -->
    <div class="pl-selected-filters" id="pl-selected-filters">
        <!-- JS will populate filter chips here -->
    </div>

    <!-- Product Grid -->
    <div class="pl-content__grid" id="pl-grid" data-total-pages="<?php echo esc_attr($total_pages); ?>"
        data-current-page="<?php echo esc_attr($paged); ?>" data-category="<?php echo esc_attr($category_slug); ?>">
        <?php if ($products_query->have_posts()) : ?>
        <?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
        <?php get_template_part('template-parts/components/product/index'); ?>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php else : ?>
        <?= render_no_results(); ?>
        <?php endif; ?>
    </div>

    <!-- Hidden no-results template for JavaScript -->
    <template id="pl-no-results-template">
        <?= render_no_results(); ?>
    </template>

    <!-- Infinite Scroll Sentinel -->
    <div class="pl-content__sentinel" id="pl-sentinel"></div>

    <!-- Loading Spinner -->
    <div class="pl-content__loader" id="pl-loader">
        <div class="pl-content__spinner"></div>
        <span class="pl-content__loader-text">Đang tải sản phẩm</span>
    </div>
</section>