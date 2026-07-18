<?php
$search_quick = get_field('search_quick', get_the_ID());

$search_quick_title = $search_quick['title'] ?? '';
$search_quick_desc  = $search_quick['desc'] ?? ($search_quick['lable'] ?? '');
$search_quick_items = $search_quick['list_item'] ?? [];
?>

<section class="faq-category">
  <?= okhub_img('common/mermaid-bg', array('class' => 'faq-category__bg', 'extra' => 'aria-hidden="true"')) ?>

  <div class="faq-category__inner">
    <p class="faq-category__subtitle">
      <?= esc_html($search_quick_title); ?>
    </p>

    <div class="faq-category__title">
      <?= wp_kses_post($search_quick_desc); ?>
    </div>

    <div class="faq-category__list">
      <?php foreach ($search_quick_items as $search_quick_item) : ?>
        <?php
        $item_image = $search_quick_item['image'] ?? null;
        $item_title = $search_quick_item['title'] ?? '';

        $item_image_id = is_array($item_image) ? ($item_image['ID'] ?? null) : $item_image;

        $item_image_alt = is_array($item_image)
          ? ($item_image['alt'] ?: ($item_image['title'] ?? $item_title))
          : $item_title;
        ?>

        <a href="#" class="faq-category__item">
          <?= wp_get_attachment_image($item_image_id, 'full', false, [
            'class' => 'faq-category__icon',
            'alt' => esc_attr($item_image_alt),
            'loading' => 'lazy',
          ]) ?>

          <span class="faq-category__name">
            <?= nl2br(esc_html($item_title)); ?>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>