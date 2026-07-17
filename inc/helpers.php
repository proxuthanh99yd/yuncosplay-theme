<?php

/**
 * Generates a pagination array
 *
 * @param array $params
 *      - current: int, current page
 *      - max: int, max page
 *
 * @return array
 *      - current: int, current page
 *      - prev: int|null, previous page
 *      - next: int|null, next page
 *      - items: array, page items
 */
function paginate(array $params)
{
    extract($params);
    if (!isset($current) || !isset($max)) return null;

    $prev = $current === 1 ? null : $current - 1;
    $next = $current === $max ? null : $current + 1;
    $items = [1];

    // If only 1 page
    if ($max === 1) {
        return [
            "current" => $current,
            "prev" => $prev,
            "next" => $next,
            "items" => $items,
        ];
    }

    // If current = 1, add next and max
    if ($current === 1) {
        if ($max > 2) {
            array_push($items, 2);
            if ($max > 3) {
                array_push($items, "…");
            }
            array_push($items, $max);
        } elseif ($max === 2) {
            array_push($items, 2);
        }
        return [
            "current" => $current,
            "prev" => $prev,
            "next" => $next,
            "items" => $items,
        ];
    }

    // Add ellipsis before prev if there's a gap (when current >= 3 and prev > 1)
    if ($current > 3 || ($current === 3 && $current - 1 > 1)) {
        array_push($items, "…");
    }

    // Add prev page (skip if it's 1, already in items)
    $prev_page = $current - 1;
    if ($prev_page > 1) {
        array_push($items, $prev_page);
    }

    // Add current page
    array_push($items, $current);

    // Add next page and max if not last page
    if ($current < $max) {
        $next_page = $current + 1;

        // Add next page if it's different from max
        if ($next_page < $max) {
            array_push($items, $next_page);

            // Add ellipsis before max only if next is not adjacent to max
            if ($next_page < $max - 1) {
                array_push($items, "…");
            }
        }

        // Add max page
        array_push($items, $max);
    }

    return [
        "current" => $current,
        "prev" => $prev,
        "next" => $next,
        "items" => $items,
    ];
}

function my_theme_setup()
{
    add_filter('wpcf7_autop_or_not', '__return_false');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid' => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 10,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ],
    ]);
}
add_action('after_setup_theme', 'my_theme_setup', 20);

add_action('init', function () {
    remove_post_type_support('page', 'editor');
}, 99);

function okhub_get_destination_data_for_country($country, $destinations)
{
    if (empty($destinations) || is_wp_error($destinations) || !is_iterable($destinations)) {
        return null;
    }

    foreach ($destinations as $destination) {
        $country_field = get_field('country', 'destination_' . $destination->term_id);
        $destination_slug = $destination->slug;

        $destination_country = '';
        if ($country_field) {
            $destination_country = strtolower($country_field);
        } else {
            $slug_parts = explode('-', $destination_slug);
            $possible_countries = ['vietnam', 'cambodia', 'laos'];
            foreach ($possible_countries as $pc) {
                if (in_array($pc, $slug_parts, true)) {
                    $destination_country = $pc;
                    break;
                }
            }
        }

        if ($destination_country !== $country) {
            continue;
        }

        $thumbnail_id = get_field('thumbnail', 'destination_' . $destination->term_id);
        $custom_link = get_field('custom_permalink', 'destination_' . $destination->term_id);
        $slug_link = $custom_link ?: get_term_link($destination);

        $description = get_field('description', 'destination_' . $destination->term_id);
        if (empty($description)) {
            $description = $destination->description ?: '';
        }

        $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

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

    return null;
}

/**
 * Trả về mảng attr cho wp_get_attachment_image().
 *
 * QUAN TRỌNG — loading="eager" ở đây làm ĐỒNG THỜI 2 việc:
 *   1. Hint native cho browser (đừng hoãn ảnh LCP).
 *   2. Kích hoạt luật loại trừ của WP Rocket LazyLoad: chuỗi 'loading="eager"' nằm
 *      sẵn trong getExcludedAttributes() (wp-rocket/inc/Dependencies/RocketLazyload/
 *      Image.php:441) và được khớp bằng strpos() trên cả thẻ img. Không có nó,
 *      Rocket nuốt src thành SVG placeholder rỗng rồi đẩy URL thật sang
 *      data-lazy-src -> mọi tối ưu ở đây thành vô nghĩa. Đừng đổi 'eager' sang
 *      giá trị khác, và sau mỗi lần update WP Rocket nên kiểm lại list đó.
 *
 * LUÔN set 'loading' ở MỌI nhánh — không bao giờ bỏ trống. Các page template của
 * theme chỉ get_header() + get_template_part() chứ không chạy main loop, nên
 * $wp_query->before_loop luôn true; wp_get_loading_optimization_attributes()
 * rơi vào nhánh coi MỌI ảnh là "trong viewport" và KHÔNG tự thêm lazy. Bỏ trống
 * 'loading' = cả trang eager. Đó cũng là lý do ~129 chỗ trong theme hardcode
 * 'lazy' — chúng load-bearing, không phải thừa.
 *
 * Mỗi hero render cả 2 ảnh desktop + mobile rồi CSS ẩn 1 cái bằng display:none.
 * Ảnh display:none + lazy sẽ KHÔNG bao giờ tải -> truyền 'lazy' cho variant đang
 * bị ẩn để cắt double-download. Ngược lại eager + display:none vẫn tải, nên đừng
 * bao giờ eager cả 2 variant.
 *
 * 'eager' (không kèm high) dành cho ảnh trên fold nhưng KHÔNG phải LCP (logo).
 * fetchpriority='auto' ở đó là cố ý: wp_maybe_add_fetchpriority_high_attr() thấy
 * fetchpriority đã set sẵn thì return sớm mà không hạ wp_high_priority_element_flag(),
 * nhờ vậy hero render sau vẫn giành được suất 'high' duy nhất của trang.
 *
 * Cách dùng:
 *      // variant desktop, slide đầu của swiper
 *      wp_get_attachment_image($id, 'full', false, okhub_image_attrs(
 *          ['class' => 'banner-image'],
 *          $i === 0 && !IS_MOBILE ? 'lcp' : 'lazy'
 *      ));
 *
 * @param array  $attrs    Attr riêng của call site (class, alt, ...).
 * @param string $priority 'lcp' = ảnh LCP | 'eager' = trên fold, không LCP | 'lazy'.
 *
 * @return array
 */
function okhub_image_attrs(array $attrs = [], string $priority = 'lazy'): array
{
    $attrs['decoding'] = $attrs['decoding'] ?? 'async';

    if ($priority === 'lcp') {
        $attrs['loading'] = 'eager';
        $attrs['fetchpriority'] = 'high';

        return $attrs;
    }

    if ($priority === 'eager') {
        $attrs['loading'] = 'eager';
        $attrs['fetchpriority'] = 'auto';

        return $attrs;
    }

    $attrs['loading'] = 'lazy';
    unset($attrs['fetchpriority']);

    return $attrs;
}
