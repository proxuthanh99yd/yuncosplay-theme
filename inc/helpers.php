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
    //     add_filter('use_block_editor_for_post', '__return_false');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'my_theme_setup');

add_action('init', function () {
    remove_post_type_support('page', 'editor');
}, 99);
