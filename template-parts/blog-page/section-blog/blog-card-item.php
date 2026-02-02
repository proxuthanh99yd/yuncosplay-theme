<?php

/**
 * Blog Card Item (Reusable)
 * $args['layout'] : xl | lg | base
 */

$post_id = $args['post_id'] ?? 0;
$layout = $args['layout'] ?? 'normal';
$layout_class = match ($layout) {
  'lg'  => 'blog-card--lg',
  'xl' => 'blog-card--xl',
  default => '',
};

$title = $post_id ? get_the_title($post_id) : '';

$image_id = get_post_thumbnail_id($post_id) ?: 1916;
$permalink = $post_id ? get_permalink($post_id) : '#';
$category = '';
if ($post_id) {
  $categories = get_the_category($post_id);
  if (!empty($categories)) {
    $category = $categories[0]->name;
  }
}
$reading_time = '';
if ($post_id) {
  $content = get_post_field('post_content', $post_id);
  $word_count = str_word_count(wp_strip_all_tags($content));
  $reading_time = max(1, ceil($word_count / 200));
}
?>

<article class=" <?php echo esc_attr($layout_class); ?>">
  <a href="<?php echo esc_url($permalink); ?>" class="blog-card <?php echo esc_attr($layout_class); ?>">

    <div class="blog-card__image">
      <?php
      if ($image_id) {
        echo wp_get_attachment_image(
          $image_id,
          'large',
          false,
          ['alt' => esc_attr($title)]
        );
      }
      ?>
    </div>

    <div class="blog-card__content">
      <h3 class="blog-card__title"><?php echo esc_html($title); ?></h3>

      <div class="blog-card__meta">
        <span><?php echo esc_html($category); ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none">
          <path d="M0 3.5C1.55555 3.11111 3.11111 1.55556 3.5 0C3.88889 1.55556 5.44445 3.11111 7 3.5C5.44445 3.88889 3.88889 5.44444 3.5 7.00002C3.11111 5.44444 1.55555 3.88889 0 3.5Z" fill="#630F3F" />
        </svg>
        <span><?php echo esc_html($reading_time); ?> min read</span>
      </div>
    </div>

  </a>
</article>