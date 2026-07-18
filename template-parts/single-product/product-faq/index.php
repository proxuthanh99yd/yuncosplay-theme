<?php
$faqs_group = get_field('faqs');
$custom_faqs = get_field('custom_faqs');

$relationship_faqs = [];
$faq_title         = '';

if (is_array($faqs_group)) {
    $relationship_faqs = $faqs_group['faq'] ?? [];
    $faq_title         = $faqs_group['faq_title'] ?? '';
}

$all_faqs = [];

/**
 * Relationship FAQ
 */
if (is_array($relationship_faqs)) {

    foreach ($relationship_faqs as $faq_id) {

        $faq_post = get_post($faq_id);

        if (!$faq_post) {
            continue;
        }

        $all_faqs[] = [
            'question' => $faq_post->post_title,
            'answer' => wp_kses_post($faq_post->post_content),
        ];
    }
}

/**
 * Custom FAQ
 */
if (is_array($custom_faqs)) {

    foreach ($custom_faqs as $faq) {

        $question = trim($faq['question'] ?? '');
        $answer   = trim($faq['answer'] ?? '');

        if (!$question || !$answer) {
            continue;
        }

        $all_faqs[] = [
            'question' => $question,
            'answer' => wp_kses_post($answer),
        ];
    }
}

/**
 * Fallback query FAQ post type
 */
if (empty($all_faqs)) {

    $fallback_query = new WP_Query([
        'post_type'      => 'faq',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    if ($fallback_query->have_posts()) {

        while ($fallback_query->have_posts()) {

            $fallback_query->the_post();

            $all_faqs[] = [
                'question' => get_the_title(),
                'answer' => wp_kses_post(get_the_content()),
            ];
        }
    }

    wp_reset_postdata();
}

/**
 * Không có FAQ nào thì return
 */
if (empty($all_faqs)) {
    return;
}

// Icon +/- accordion → file tĩnh theme (okhub_img).
$icon_plus_url  = okhub_img_url('faq/icon-plus');
$icon_minus_url = okhub_img_url('faq/icon-minus');

$initial_limit  = 5;
$load_more_step = 5;
?>

<section class="product-faq" data-initial-limit="<?= esc_attr($initial_limit) ?>" data-load-more="<?= esc_attr($load_more_step) ?>">
    <div class="product-faq__container">
        <div class="product-faq__header">
            <p class="product-faq__subtitle">Câu hỏi thường gặp</p>
            <h2 class="product-faq__title"><?= esc_html($faq_title) ?></h2>
        </div>

        <div class="product-faq__list">
            <?php foreach ($all_faqs as $index => $faq) : ?>
                <?php
                $is_first = $index === 0;
                $answer_id = 'product-faq-answer-' . $index;
                ?>
                <div class="product-faq__item<?= $is_first ? ' is-active' : '' ?><?= $index >= $initial_limit ? ' is-hidden' : '' ?>">
                    <button type="button"
                        class="product-faq__item-trigger"
                        aria-expanded="<?= $is_first ? 'true' : 'false' ?>"
                        aria-controls="<?= esc_attr($answer_id) ?>">
                        <h3 class="product-faq__item-question"><?= esc_html($faq['question']) ?></h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="39" height="39" viewBox="0 0 39 39" fill="none" aria-hidden="true" focusable="false">
                            <path d="M8.125 12.9998L20.6022 25.1877L32.5 12.9998" stroke="#680103" stroke-width="4" />
                        </svg>
                    </button>

                    <div class="product-faq__item-answer" id="<?= esc_attr($answer_id) ?>" role="region">
                        <div class="product-faq__item-answer-inner">
                            <?= $faq['answer']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="product-faq__button">
            <button type="button"
                class="product-faq__button-link"
                aria-label="Xem thêm câu hỏi"
                aria-expanded="false"
                data-icon-plus="<?= esc_url($icon_plus_url ?: '') ?>"
                data-icon-minus="<?= esc_url($icon_minus_url ?: '') ?>">
                <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => 'Xem thêm', 'icon_key' => 'faq/icon-plus']); ?>
            </button>
        </div>
    </div>
</section>