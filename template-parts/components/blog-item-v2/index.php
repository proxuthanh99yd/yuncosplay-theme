<?php 
$blog_item_id = get_the_ID();
$blog_title = get_the_title($blog_item_id);
$blog_link = get_permalink($blog_item_id);
$blog_date = get_the_date('d/m/Y', $blog_item_id);
$blog_thumbnail_image_id = get_post_thumbnail_id($blog_item_id);
$blog_item_category = get_the_category($blog_item_id);
$blog_item_category_name = '';
if (!empty($blog_item_category) && isset($blog_item_category[0]) && isset($blog_item_category[0]->name)) {
  $blog_item_category_name = $blog_item_category[0]->name;
}
?>

<article class="blog-item-v2">
    <a href="<?= esc_url($blog_link); ?>" class="blog-item-v2__link">
        <div class="blog-item-v2__thumbnail">
            <?= wp_get_attachment_image($blog_thumbnail_image_id, 'full', false, ['loading' => 'lazy', 'decoding' => 'async', 'class' => 'blog-item-v2__thumbnail-image']); ?>
        </div>
        <div class="blog-item-v2__content">
            <div class="blog-item-v2__meta">
                <p class="blog-item-v2__category">
                    <span class="blog-item-v2__category-text"><?= $blog_item_category_name ?></span>
                </p>
                <p class="blog-item-v2__date"><?= $blog_date ?></p>
            </div>
            <h3 class="blog-item-v2__title"><?= $blog_title ?></h3>
        </div>
    </a>
</article>