<?php
$blog_item_id = get_the_ID();
$blog_item_title = get_the_title($blog_item_id);
$blog_item_link = get_permalink($blog_item_id);
$blog_item_thumbnail_id = get_post_thumbnail_id($blog_item_id);

$blog_item_category = get_the_category($blog_item_id);
$blog_item_category_name = '';
if (!empty($blog_item_category) && isset($blog_item_category[0]) && isset($blog_item_category[0]->name)) {
  $blog_item_category_name = $blog_item_category[0]->name;
}
?>

<article class="blog-item">
  <a
    href="<?= $blog_item_link ?>"
    class="blog-item__link"
  ></a>

  <div class="blog-item__thumbnail">
      <?= wp_get_attachment_image($blog_item_thumbnail_id, 'full', false, ['loading' => 'lazy', 'decoding' => 'async']); ?>
  </div>

  <div class="blog-item__content">
    <p class="blog-item__category">
        <?= $blog_item_category_name ?>
    </p>
    <h3 class="blog-item__title">
        <?= $blog_item_title ?>
    </h3>
  </div>
</article>